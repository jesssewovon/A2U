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
    
    /////////////ACCOUNT A SENDER////////////////////////////////////
    //GAIMLZBSO6C5CDRJUY2RRUNW64W7BGJ5R43HMJBCOC6PN4XKHRSQF5SF
    //SCHVLLKZYOQBQZI2IGKT6VWMJ2GLSVUJZRAOWZ2JUGIKH2VAXFUIPW3E

    /////////////ACCOUNT B RECEIVER////////////////////////////////////
    //GAHCOAISSHVZ4KK5L4GKWGAEZ2G6MOI2JOMQRMO5SSCDJDZBFF7YMEZS
    //SD33TNJGE4WJ6LJS4D6B33HM64EMUZ2KIUIHER2Q3O4R33ANDQXWKHXI

    /// Init sdk for public net
	//$sdk = StellarSDK::getPublicNetInstance();
	$sdk = StellarSDK::getTestNetInstance();

	$senderKeyPair = KeyPair::fromSeed("SCHVLLKZYOQBQZI2IGKT6VWMJ2GLSVUJZRAOWZ2JUGIKH2VAXFUIPW3E");
	$destination = "GAHCOAISSHVZ4KK5L4GKWGAEZ2G6MOI2JOMQRMO5SSCDJDZBFF7YMEZS";

	// Load sender account data from the stellar network.
	$sender = $sdk->requestAccount($senderKeyPair->getAccountId());

	// Build the transaction to send 100 XLM native payment from sender to destination
	$paymentOperation = (new PaymentOperationBuilder($destination,Asset::native(), "100"))->build();
	$transaction = (new TransactionBuilder($sender))->addOperation($paymentOperation)->build();

	// Sign the transaction with the sender's key pair.
	$transaction->sign($senderKeyPair, Network::testnet());

	// Submit the transaction to the stellar network.
	$response = $sdk->submitTransaction($transaction);
	if ($response->isSuccessful()) {
	    print(PHP_EOL."Payment sent");
	}

?>