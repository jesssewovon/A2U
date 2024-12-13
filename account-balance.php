<?php

	require __DIR__ . '/vendor/autoload.php';

    use Soneso\StellarSDK\Crypto\KeyPair;
    use Soneso\StellarSDK\Util\FriendBot;
    use Soneso\StellarSDK\StellarSDK;
    use Soneso\StellarSDK\Asset;

	$accountId = "GAIMLZBSO6C5CDRJUY2RRUNW64W7BGJ5R43HMJBCOC6PN4XKHRSQF5SF";
	$accountId = "GCXMPLIIYF264A3Q6KU4ZY2XBEPNUUHXVFC3YBVJ3FRF46R7KHAKIS6P";//PI

	//$sdk = StellarSDK::getTestNetInstance();
	$url = "https://api.testnet.minepi.com";
    $sdk = new StellarSDK($url);
	// Request the account data.
	$account = $sdk->requestAccount($accountId);

	// You can check the `balance`, `sequence`, `flags`, `signers`, `data` etc.
	foreach ($account->getBalances() as $balance) {
	    switch ($balance->getAssetType()) {
	        case Asset::TYPE_NATIVE:
	            printf (PHP_EOL."Balance: %s XLM, %s ", $balance->getBalance(), $balance->getAssetIssuer() );
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