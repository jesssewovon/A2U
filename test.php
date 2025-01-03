<?php

	require __DIR__ . '/vendor/autoload.php';

    use Get2\A2uphp\PiNetwork;

    /////////////////////////PI NETWORK////////////////////////
    /////////////ACCOUNT A SENDER////////////////////////////////////
    //GCXMPLIIYF264A3Q6KU4ZY2XBEPNUUHXVFC3YBVJ3FRF46R7KHAKIS6P
    //SDORQ45BZ4626TJTQ2DDGDIWQDVK56IO6V6ZJWOEIMOG4C3RNZHF3ZUG

    $api_key = "r3sw4kk0z0e3aowvpmckqnvuvyiyeuqtfraoubyngjqqrytjdzjudlk4ad5va2ve";
    $seed = "SDORQ45BZ4626TJTQ2DDGDIWQDVK56IO6V6ZJWOEIMOG4C3RNZHF3ZUG";
    $uid = "7b568abd-31fc-49c8-bd29-ec0cebfff52e";//Filano uid
    $uid = "0e2a389b-8086-45a4-9ac0-c783e0fac907";//Mon uid
    $uid = "e6231dd6-8d8b-4aab-a068-1e3d60b95c13";//Emkins uid: TOGO
    $uid = "dd905fac-7cef-40d6-af20-2d03a2f0407e";//Raymond uid: BENIN
    $uid = "cde5441b-52ec-4ff2-aa65-784a2153ad39";//Raymond team member uid: BENIN
    $uid = "31344320-7d5a-41cc-a28a-11604962e486";//Akoele
    $uid = "ee088931-0d34-455f-a9e0-985f2fc30eb8";//Akoss
    $uid = "4f9c7924-2df6-4035-a5a0-ff945078cbf1";//Raymond team member uid: BENIN
    $uid = "30e98014-9702-46dd-a26f-c5a7dbfa247c";//Clinton

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
                "memo" => "Refund for apple pie", // this is just an example
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