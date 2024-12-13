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

    include 'PiNetwork.php';
    //include 'pi.php';
    include 'Types.php';
    include 'Utils.php';

    /////////////////////////PI NETWORK////////////////////////
    /////////////ACCOUNT A SENDER////////////////////////////////////
    //GCXMPLIIYF264A3Q6KU4ZY2XBEPNUUHXVFC3YBVJ3FRF46R7KHAKIS6P
    //SDORQ45BZ4626TJTQ2DDGDIWQDVK56IO6V6ZJWOEIMOG4C3RNZHF3ZUG

    $api_key = "r3sw4kk0z0e3aowvpmckqnvuvyiyeuqtfraoubyngjqqrytjdzjudlk4ad5va2ve";
    $seed = "SDORQ45BZ4626TJTQ2DDGDIWQDVK56IO6V6ZJWOEIMOG4C3RNZHF3ZUG";

    $pi = new PiNetwork\PiNetwork($api_key, $seed);
    $paymentData = [
        "payment" => [
            "amount" => 1,
            "memo" => "Refund for apple pie", // this is just an example
            "metadata" => ["productId" => "apple-pie-1"],
            "uid" => "7b568abd-31fc-49c8-bd29-ec0cebfff52e"//Filano uid
        ],
    ];
    $identifier = "wcYQr8iuxsLISyvXalhS7VYocpDV";
    //$paymentData = $pi->createPayment($paymentData);
    $payment = $pi->getPayment($identifier);
    echo "success";echo nl2br("\n");
    var_dump($payment);echo nl2br("\n");echo nl2br("\n");

    $url = "https://api.testnet.minepi.com";
    $sdk = new StellarSDK($url);
    $response = $sdk->requestFeeStats();
    //$feeCharged = $response->getFeeCharged();
    $feeCharged = $response->getLastLedgerBaseFee();
    var_dump($feeCharged);echo nl2br("\n");echo nl2br("\n");

    try {
        $submitResponse = $pi->submitPayment($identifier);
        var_dump($submitResponse);
    } catch (\Exception $e) {
        var_dump($e);
        //echo "errrr : ".$e->getMessage();
    }
    
    
    

?>