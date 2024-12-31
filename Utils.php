<?php

namespace PiNetwork;

use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;

class Utils
{
    public static function getHttpClient(string $apiKey, ?array $options = null): Client
    {
        $defaultOptions = [
            'base_uri' => 'https://api.minepi.com',
            'headers' => [
                'Authorization' => "Key {$apiKey}",
                'Content-Type' => 'application/json',
            ],
            RequestOptions::TIMEOUT => 30,
            RequestOptions::CONNECT_TIMEOUT => 5,
        ];

        if ($options) {
            $defaultOptions = array_merge($defaultOptions, $options);
        }

        return new Client($defaultOptions);
    }

    public static function buildHorizonUrl(string $network): string
    {
        return $network === NetworkPassphrase::MAINNET
            ? 'https://api.mainnet.minepi.com'
            : 'https://api.testnet.minepi.com';
    }
}
