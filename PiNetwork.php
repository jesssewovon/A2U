<?php

require __DIR__ . '/vendor/autoload.php';

use Soneso\StellarSDK\Crypto\KeyPair;
use Soneso\StellarSDK\Util\FriendBot;
use Soneso\StellarSDK\StellarSDK;
use Soneso\StellarSDK\Asset;

use GuzzleHttp\Client;

class PiNetwork{
	private $api_key;
	private $walletPrivateSeed;

	private $httpClient;

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
            $paymentData
        ]);
        $body = $rep->getBody();
        Log::info("[body] $body");
        $body_obj = json_decode($body, false, 512, JSON_UNESCAPED_UNICODE);
        return $body_obj;
	}
}

?>