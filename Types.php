<?php

namespace PiNetwork;

class NetworkPassphrase
{
    public const MAINNET = 'Pi Network';
    public const TESTNET = 'Pi Network Test';
}

class PaymentDTO
{
    public string $identifier;
    public float $amount;
    public string $memo;
    public array $metadata;
    public string $from_address;
    public string $to_address;
    public string $network;
    public ?array $transaction;

    public function __construct(array $data)
    {
        $this->identifier = $data['identifier'];
        $this->amount = $data['amount'];
        $this->memo = $data['memo'];
        $this->metadata = $data['metadata'];
        $this->from_address = $data['from_address'];
        $this->to_address = $data['to_address'];
        $this->network = $data['network'];
        $this->transaction = $data['transaction'] ?? null;
    }
}

class TransactionData
{
    public string $amount;
    public string $paymentIdentifier;
    public string $fromAddress;
    public string $toAddress;
    public string $memo;
    public string $network;
    public string $txid;

    public function __construct(array $data)
    {
        $this->amount = $data['amount'];
        $this->paymentIdentifier = $data['paymentIdentifier'];
        $this->fromAddress = $data['fromAddress'];
        $this->toAddress = $data['toAddress'];
        $this->memo = $data['memo'];
        $this->network = $data['network'];
        $this->txid = $data['txid'] ?? '';
    }
}
