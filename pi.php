<?php

require __DIR__ . '/vendor/autoload.php';

namespace PiNetwork;

use GuzzleHttp\Client;
use Soneso\StellarSDK\Keypair;
use Soneso\StellarSDK\Server;
use Soneso\StellarSDK\Transaction;
use Soneso\StellarSDK\TransactionBuilder;
use Soneso\StellarSDK\Xdr\XdrOperation;
use Soneso\StellarSDK\PaymentOperation;

class PiNetwork
{
    private string $apiKey;
    private Keypair $myKeypair;
    private ?string $networkPassphrase;
    private ?PaymentDTO $currentPayment;
    private ?array $axiosOptions;

    public function __construct(string $apiKey, string $walletPrivateSeed, ?array $options = null)
    {
        $this->validateSeedFormat($walletPrivateSeed);
        $this->apiKey = $apiKey;
        $this->myKeypair = Keypair::fromSeed($walletPrivateSeed);
        $this->axiosOptions = $options;
    }

    public function createPayment(array $paymentData): string
    {
        $this->validatePaymentData($paymentData);

        $client = Utils::getHttpClient($this->apiKey, $this->axiosOptions);
        $response = $client->post('/v2/payments', [
            'json' => ['payment' => $paymentData]
        ]);

        $data = json_decode($response->getBody(), true);
        $this->currentPayment = new PaymentDTO($data);
        return $this->currentPayment->identifier;
    }

    public function submitPayment(string $paymentId): string
    {
        if (!$this->currentPayment || $this->currentPayment->identifier !== $paymentId) {
            $this->currentPayment = $this->getPayment($paymentId);
            $txid = $this->currentPayment->transaction['txid'] ?? null;
            if ($txid) {
                throw new \Exception(json_encode([
                    'message' => 'This payment already has a linked txid',
                    'paymentId' => $paymentId,
                    'txid' => $txid
                ]));
            }
        }

        $server = $this->getHorizonClient($this->currentPayment->network);
        $sourceAccount = $server->loadAccount($this->myKeypair->getAccountId());

        // Build the transaction
        $transaction = (new TransactionBuilder($sourceAccount))
            ->addOperation(
                (new PaymentOperation())
                    ->setDestination($this->currentPayment->to_address)
                    ->setAsset(Asset::native())
                    ->setAmount((string) $this->currentPayment->amount)
            )
            ->addMemo(Memo::text($this->currentPayment->memo))
            ->setNetworkPassphrase($this->currentPayment->network)
            ->setTimeout(180)
            ->build();

        // Sign and submit the transaction
        $transaction->sign($this->myKeypair);
        $response = $server->submitTransaction($transaction);

        if (!$response->isSuccessful()) {
            throw new \Exception('Transaction submission failed: ' . json_encode($response->getExtras()));
        }

        // Update payment with transaction ID
        $client = Utils::getHttpClient($this->apiKey, $this->axiosOptions);
        $client->post("/v2/payments/{$paymentId}/transaction", [
            'json' => [
                'txid' => $response->getHash(),
                'network' => $this->currentPayment->network
            ]
        ]);

        return $response->getHash();
    }

    private function validateSeedFormat(string $seed): void
    {
        if (empty($seed)) {
            throw new \InvalidArgumentException('Wallet private seed cannot be empty');
        }
        try {
            Keypair::fromSeed($seed);
        } catch (\Exception $e) {
            throw new \InvalidArgumentException('Invalid wallet private seed format');
        }
    }

    private function validatePaymentData(array $paymentData): void
    {
        $requiredFields = ['amount', 'memo', 'metadata', 'uid'];
        foreach ($requiredFields as $field) {
            if (!isset($paymentData[$field])) {
                throw new \InvalidArgumentException("Missing required field: {$field}");
            }
        }

        if (!is_numeric($paymentData['amount']) || $paymentData['amount'] <= 0) {
            throw new \InvalidArgumentException('Amount must be a positive number');
        }
    }

    private function getPayment(string $paymentId): PaymentDTO
    {
        $client = Utils::getHttpClient($this->apiKey, $this->axiosOptions);
        $response = $client->get("/v2/payments/{$paymentId}");
        $data = json_decode($response->getBody(), true);
        return new PaymentDTO($data);
    }

    private function getHorizonClient(string $network): Server
    {
        $horizonUrl = Utils::buildHorizonUrl($network);
        return new Server($horizonUrl);
    }
}
