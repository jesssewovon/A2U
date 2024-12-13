<?php

require __DIR__ . '/vendor/autoload.php';

use Soneso\StellarSDK\Crypto\KeyPair;
use Soneso\StellarSDK\Util\FriendBot;
use Soneso\StellarSDK\StellarSDK;
use Soneso\StellarSDK\Asset;

use Soneso\StellarSDK\CreateAccountOperationBuilder;
use Soneso\StellarSDK\TransactionBuilder;
use Soneso\StellarSDK\PaymentOperationBuilder;
use Soneso\StellarSDK\Network;
use Soneso\StellarSDK\TimeBounds;

use GuzzleHttp\Client;

class PiNetwork{
	private $api_key;
	private $walletPrivateSeed;

	private $httpClient;
    private $currentPayment;

	public function __construct($api_key, $walletPrivateSeed)
	{
		$this->api_key = $api_key;
		$this->walletPrivateSeed = $walletPrivateSeed;

		$this->httpClient = new Client([
            'base_uri' => "https://api.minepi.com",
            'exceptions' => false,
            'verify' => false
        ]);
	}

	public function createPayment($paymentData)
	{
		$rep = $this->httpClient->request('POST', '/v2/payments', [
            'headers' => [
                'Accept' => 'application/json',
                'Authorization' => 'Key '.$this->api_key
            ],
            'json' => $paymentData
        ]);
        $body = $rep->getBody();
        $body_obj = json_decode($body, false, 512, JSON_UNESCAPED_UNICODE);
        return $body_obj;
	}

    public function getPayment($paymentId)
    {
        $rep = $this->httpClient->request('GET', '/v2/payments/'.$paymentId, [
            'headers' => [
                'Accept' => 'application/json',
                'Authorization' => 'Key '.$this->api_key
            ],
        ]);
        $body = $rep->getBody();
        $body_obj = json_decode($body, false, 512, JSON_UNESCAPED_UNICODE);
        return $body_obj;
    }

    public function submitPayment_old($paymentId)
    {
        $currentPayment = $this->getPayment($paymentId);
        $amount = $currentPayment->amount;
        $from_address = $currentPayment->from_address;
        $to_address = $currentPayment->to_address;
        
        $url = "https://api.testnet.minepi.com";
        $sdk = new StellarSDK($url);

        $senderKeyPair = KeyPair::fromSeed($this->walletPrivateSeed);
        $destination = $to_address;

        // Load sender account data from the stellar network.
        $sender = $sdk->requestAccount($senderKeyPair->getAccountId());

        // Build the transaction to send 100 XLM native payment from sender to destination
        $minTime = 1;
        $maxTime = 90080;
        $timeBounds = new TimeBounds((new \DateTime)->setTimestamp($minTime), (new \DateTime)->setTimestamp($maxTime));

        $paymentOperation = (new PaymentOperationBuilder($destination,Asset::native(), $amount))->build();
        $transaction = (new TransactionBuilder($sender))
        //->setTimeBounds($timeBounds)
        ->setMaxOperationFee(0.01)
        ->addOperation($paymentOperation)->build();

        // Sign the transaction with the sender's key pair.
        $transaction->sign($senderKeyPair, Network::testnet());

        $feeBump = (new FeeBumpTransactionBuilder($innerTx))->setBaseFee(200)->setFeeAccount($payerId)->build();

        // Sign the fee bump transaction with the payer keypair
        $feeBump->sign($payerKeyPair, Network::testnet());

        // Submit the transaction to the stellar network.
        $response = $sdk->submitTransaction($transaction);
        if ($response->isSuccessful()) {
            print(PHP_EOL."Payment sent");
        }
        return $response;
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

        $url = "https://api.testnet.minepi.com";
        $sdk = new StellarSDK($url);

        $senderKeyPair = KeyPair::fromSeed($this->walletPrivateSeed);

        // Load sender account data from the stellar network.
        $sender = $sdk->requestAccount($senderKeyPair->getAccountId());

        // Build the transaction
        $transaction = (new TransactionBuilder($sender))
            ->addOperation(
                (new PaymentOperationBuilder())
                    ->setDestination($this->currentPayment->to_address)
                    ->setAsset(Asset::native())
                    ->setAmount((string) $this->currentPayment->amount)
            )
            ->addMemo(Memo::text($this->currentPayment->memo))
            ->setNetworkPassphrase($this->currentPayment->network)
            ->setTimeout(180)
            ->build();

        // Sign and submit the transaction
        $transaction->sign($senderKeyPair);
        $response = $sdk->submitTransaction($transaction);

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

    public function completePayment($paymentId, $txid)
    {
        $rep = $this->httpClient->request('POST', '/v2/payments/'.$paymentId.'/complete', [
            'headers' => [
                'Accept' => 'application/json',
                'Authorization' => 'Key '.$this->api_key
            ],
            'query' => ['txid' => $txid],
        ]);
        $body = $rep->getBody();
        $body_obj = json_decode($body, false, 512, JSON_UNESCAPED_UNICODE);
        return $body_obj;
    }
}

?>