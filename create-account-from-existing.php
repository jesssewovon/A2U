<?php

	require __DIR__ . '/vendor/autoload.php';

    use Soneso\StellarSDK\Crypto\KeyPair;
    use Soneso\StellarSDK\Util\FriendBot;
    use Soneso\StellarSDK\StellarSDK;
    use Soneso\StellarSDK\CreateAccountOperationBuilder;
    use Soneso\StellarSDK\TransactionBuilder;
    use Soneso\StellarSDK\Network;

	/// Init sdk for public net
	$sdk = StellarSDK::getPublicNetInstance();
	 
	/// Create a key pair for your existing account.
	$keyA = KeyPair::fromSeed("GAIMLZBSO6C5CDRJUY2RRUNW64W7BGJ5R43HMJBCOC6PN4XKHRSQF5SF");

	/// Load the data of your account from the stellar network.
	$accA = $sdk->requestAccount($keyA->getAccountId());

	/// Create a keypair for a new account.
	$keyB = KeyPair::random();

	/// Create the operation builder.
	$createAccBuilder = new CreateAccountOperationBuilder($keyB->getAccountId(), "3"); // send 3 XLM (lumen)

	// Create the transaction.
	$transaction = (new TransactionBuilder($accA))
	    ->addOperation($createAccBuilder->build())
	    ->build();

	/// Sign the transaction with the key pair of your existing account.
	$transaction->sign($keyA, Network::public());

	/// Submit the transaction to the stellar network.
	$response = $sdk->submitTransaction($transaction);

	if ($response->isSuccessful()) {
	    printf (PHP_EOL."account %s created", $keyB->getAccountId());
	}

?>