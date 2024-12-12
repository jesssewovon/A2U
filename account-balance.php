<?php

	require __DIR__ . '/vendor/autoload.php';

    use Soneso\StellarSDK\Crypto\KeyPair;
    use Soneso\StellarSDK\Util\FriendBot;
    use Soneso\StellarSDK\StellarSDK;
    use Soneso\StellarSDK\Asset;

	$accountId = "GCQHNQR2VM5OPXSTWZSF7ISDLE5XZRF73LNU6EOZXFQG2IJFU4WB7VFY";

	$sdk = StellarSDK::getTestNetInstance();
	// Request the account data.
	$account = $sdk->requestAccount($accountId);

	// You can check the `balance`, `sequence`, `flags`, `signers`, `data` etc.
	foreach ($account->getBalances() as $balance) {
	    switch ($balance->getAssetType()) {
	        case Asset::TYPE_NATIVE:
	            printf (PHP_EOL."Balance: %s XLM", $balance->getBalance() );
	            break;
	        default:
	            printf(PHP_EOL."Balance: %s %s Issuer: %s",
	                $balance->getBalance(), $balance->getAssetCode(),
	                $balance->getAssetIssuer());
	    }
	}

	print(PHP_EOL."Sequence number: ".$account->getSequenceNumber());

	foreach ($account->getSigners() as $signer) {
	    print(PHP_EOL."Signer public key: ".$signer->getKey());
	}

?>