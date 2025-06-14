<?php

	require __DIR__ . '/vendor/autoload.php';

    use Get2\A2uphp\PiNetwork;

    $api_key = "your_app_api_key";
    $seed = "your_app_wallet_seed";
    $uid = "an_user_uid_from_your_app";

    $pi = new PiNetwork($api_key, $seed);
    $incompletePayments = $pi->incompletePayments();
    $identifier = "";
    $cancelAllIncompletePayments = true;
    if ($cancelAllIncompletePayments) {
        $pi->cancelAllIncompletePayments();
    }elseif(is_array($incompletePayments) && isset($incompletePayments[0])){
        $identifier = $incompletePayments[0]->identifier;
    }
    if($cancelAllIncompletePayments){
        $paymentData = [
            "payment" => [
                "amount" => 0.42,
                "memo" => "Refund for apple pie, A2U for Piketplace", // this is just an example
                "metadata" => ["productId" => "apple-pie-1"],
                "uid" => $uid
            ],
        ];
        $identifier = $pi->createPayment($paymentData);
    }
    if (isset($identifier['status']) && $identifier['status']===false) {
        die($identifier['message']);
    }
    $payment = $pi->getPayment($identifier);
    echo "success";echo nl2br("\n");
    var_dump($payment);echo nl2br("\n");echo nl2br("\n");

    $txid = "";
    try {
        $submitResponse = $pi->submitPayment($identifier);
        echo nl2br("\n");
        var_dump($submitResponse);
        $txid = $submitResponse;
    } catch (\Exception $e) {
        $data = json_decode($e->getMessage());
        $txid = $data->txid;
    }
    if (isset($txid['status']) && $txid['status']===false) {
        die($txid['message']);
    }
    echo nl2br("\n");echo nl2br("\n");
    echo "Payment completion";echo nl2br("\n");
    $paymentComplete = $pi->completePayment($identifier, $txid);
    var_dump($paymentComplete);

?>