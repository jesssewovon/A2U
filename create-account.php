<?php
	require __DIR__ . '/vendor/autoload.php';

    use Soneso\StellarSDK\Crypto\KeyPair;
    use Soneso\StellarSDK\Util\FriendBot;
    use Soneso\StellarSDK\StellarSDK;

	// create a completely new and unique pair of keys.
	$keyPair = KeyPair::random();

	print($keyPair->getAccountId());
	// GCFXHS4GXL6BVUCXBWXGTITROWLVYXQKQLF4YH5O5JT3YZXCYPAFBJZB
    echo nl2br("\n");

	print($keyPair->getSecretSeed());
	// SAV76USXIJOBMEQXPANUOQM6F5LIOTLPDIDVRJBFFE2MDJXG24TAPUU7

	$funded = FriendBot::fundTestAccount($keyPair->getAccountId());
    print ($funded ? "account funded" : "account not funded");
    echo nl2br("\n");

    $sdk = StellarSDK::getTestNetInstance();
    //$account = $sdk->requestAccount($keyPair->getAccountId());
    $account = $sdk->requestAccount("GCXMPLIIYF264A3Q6KU4ZY2XBEPNUUHXVFC3YBVJ3FRF46R7KHAKIS6P");

?>