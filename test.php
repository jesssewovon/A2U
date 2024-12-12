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
    //use App\PiNetwork;

    /////////////////////////PI NETWORK////////////////////////
    /////////////ACCOUNT A SENDER////////////////////////////////////
    //GCXMPLIIYF264A3Q6KU4ZY2XBEPNUUHXVFC3YBVJ3FRF46R7KHAKIS6P
    //SDORQ45BZ4626TJTQ2DDGDIWQDVK56IO6V6ZJWOEIMOG4C3RNZHF3ZUG

    $api_key = "r3sw4kk0z0e3aowvpmckqnvuvyiyeuqtfraoubyngjqqrytjdzjudlk4ad5va2ve";
    $seed = "SDORQ45BZ4626TJTQ2DDGDIWQDVK56IO6V6ZJWOEIMOG4C3RNZHF3ZUG";

    $pi = new PiNetwork($api_key, $seed);
    $paymentData = [
        "amount" => 1,
        "memo" => "Refund for apple pie", // this is just an example
        "metadata" => ["productId" => "apple-pie-1"],
        "uid" => "7b568abd-31fc-49c8-bd29-ec0cebfff52e"//Filano uid
    ];
    $res = $pi->createPayment($paymentData);
    echo "success";echo nl2br("\n");
    var_dump($res);

?>