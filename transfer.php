<?php

	require __DIR__ . '/vendor/autoload.php';

    use Soneso\StellarSDK\Crypto\KeyPair;
    use Soneso\StellarSDK\Util\FriendBot;
    use Soneso\StellarSDK\StellarSDK;
    use Soneso\StellarSDK\CreateAccountOperationBuilder;
    use Soneso\StellarSDK\TransactionBuilder;
    use Soneso\StellarSDK\PaymentOperationBuilder;
    use Soneso\StellarSDK\Network;
    use Soneso\StellarSDK\Asset;

    /////////////////////////STELLAR////////////////////////
    /////////////ACCOUNT A SENDER////////////////////////////////////
    //GAIMLZBSO6C5CDRJUY2RRUNW64W7BGJ5R43HMJBCOC6PN4XKHRSQF5SF
    //SCHVLLKZYOQBQZI2IGKT6VWMJ2GLSVUJZRAOWZ2JUGIKH2VAXFUIPW3E

    /////////////ACCOUNT B RECEIVER////////////////////////////////////
    //GAHCOAISSHVZ4KK5L4GKWGAEZ2G6MOI2JOMQRMO5SSCDJDZBFF7YMEZS
    //SD33TNJGE4WJ6LJS4D6B33HM64EMUZ2KIUIHER2Q3O4R33ANDQXWKHXI

    /////////////////////////PI NETWORK////////////////////////
    /////////////ACCOUNT A SENDER////////////////////////////////////
    //GCXMPLIIYF264A3Q6KU4ZY2XBEPNUUHXVFC3YBVJ3FRF46R7KHAKIS6P
    //SDORQ45BZ4626TJTQ2DDGDIWQDVK56IO6V6ZJWOEIMOG4C3RNZHF3ZUG

    /////////////ACCOUNT B RECEIVER////////////////////////////////////
    //GAHL4UEJWOL4GRRQLPSAMJCGWGOVSZJS5LGSDNUPO72Z6SM37MFB4V6N

    /// Init sdk for public net
	//$sdk = StellarSDK::getPublicNetInstance();

    //STELLAR
	//$sdk = StellarSDK::getTestNetInstance();

	//PI NETWORK
	$url = "https://api.testnet.minepi.com";
	$sdk = new StellarSDK($url);

	$senderKeyPair = KeyPair::fromSeed("SCHVLLKZYOQBQZI2IGKT6VWMJ2GLSVUJZRAOWZ2JUGIKH2VAXFUIPW3E");
	$destination = "GAHL4UEJWOL4GRRQLPSAMJCGWGOVSZJS5LGSDNUPO72Z6SM37MFB4V6N";

	// Load sender account data from the stellar network.
	$sender = $sdk->requestAccount($senderKeyPair->getAccountId());

	// Build the transaction to send 100 XLM native payment from sender to destination
	$paymentOperation = (new PaymentOperationBuilder($destination,Asset::native(), "1"))->build();
	$transaction = (new TransactionBuilder($sender))->addOperation($paymentOperation)->build();

	// Sign the transaction with the sender's key pair.
	$transaction->sign($senderKeyPair, Network::testnet());

	// Submit the transaction to the stellar network.
	$response = $sdk->submitTransaction($transaction);
	if ($response->isSuccessful()) {
	    print(PHP_EOL."Payment sent");
	}

?>