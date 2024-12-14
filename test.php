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

    /////////////////////////PI NETWORK////////////////////////
    /////////////ACCOUNT A SENDER////////////////////////////////////
    //GCXMPLIIYF264A3Q6KU4ZY2XBEPNUUHXVFC3YBVJ3FRF46R7KHAKIS6P
    //SDORQ45BZ4626TJTQ2DDGDIWQDVK56IO6V6ZJWOEIMOG4C3RNZHF3ZUG

    $api_key = "r3sw4kk0z0e3aowvpmckqnvuvyiyeuqtfraoubyngjqqrytjdzjudlk4ad5va2ve";
    $seed = "SDORQ45BZ4626TJTQ2DDGDIWQDVK56IO6V6ZJWOEIMOG4C3RNZHF3ZUG";
    $uid = "7b568abd-31fc-49c8-bd29-ec0cebfff52e";//Filano uid

    //marketplace-a52471361fb46943
    //$api_key = "awnvszy2pwdfey6jcnj7qye6yamaz5shtvqeaomsjhbazaqjadd2qeibvvaog8re";
    //$seed = "SCDEDMODCIMJ3ISYYAWW3MXQGXCZDL4MUKYKLUADHQDHSLZPK6NK43EA";
    //$uid = "ae69e3dc-ab1d-444e-81e5-89c140a8ba56";//Moi
    //$uid = "6ec29c65-351b-4ad9-9bcf-9760187c0242";//Filano

    $pi = new PiNetwork($api_key, $seed);
    $paymentData = [
        "payment" => [
            "amount" => 0.42,
            "memo" => "Refund for apple pie", // this is just an example
            "metadata" => ["productId" => "apple-pie-1"],
            "uid" => $uid
        ],
    ];
    //$identifier = "wcYQr8iuxsLISyvXalhS7VYocpDV";
    $paymentData = $pi->createPayment($paymentData);
    $identifier = $paymentData->identifier;
    $payment = $pi->getPayment($identifier);
    $identifier = $payment->identifier;
    echo "success";echo nl2br("\n");
    var_dump($payment);echo nl2br("\n");echo nl2br("\n");

    $url = "https://api.testnet.minepi.com";
    $sdk = new StellarSDK($url);
    $response = $sdk->requestFeeStats();
    //$feeCharged = $response->getFeeCharged();
    $feeCharged = $response->getLastLedgerBaseFee();
    var_dump($feeCharged);echo nl2br("\n");echo nl2br("\n");

    $txid = "";
    try {
        $submitResponse = $pi->submitPayment($identifier);
        var_dump($submitResponse);
        $txid = $submitResponse;
        //3dd38f3c66ad66a2171eea7c862ea1bd51e6edee61435e4faf8ea32a1bbb0a33
    } catch (\Exception $e) {
        var_dump($e);
        //echo "errrr : ".$e->getMessage();
    }

    echo nl2br("\n");echo nl2br("\n");
    echo "Payment completion";
    $paymentComplete = $pi->completePayment($identifier, $txid);
    var_dump($paymentComplete);

?>