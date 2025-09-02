<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class OrderPay extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper('encryption'); 
        require_once(APPPATH.'config/ndps_config.php');
    }

    // =============================
    // 1. Payment Initiation (AUTH API)
    // =============================
    public function pay() {
        $merchId     = MERCHANT_ID;
        $merchTxnId  = uniqid("TXN"); // unique transaction id
        $totalAmount = "100.00";

        $authRequest = [
            "payInstrument" => [
                "headDetails" => [
                    "version"   => "OTSv1.1",
                    "api"       => "AUTH",
                    "platform"  => "FLASH"
                ],
                "merchDetails" => [
                    "merchId"     => $merchId,
                    "password"    => PASSWORD,
                    "merchTxnId"  => $merchTxnId,
                    "merchTxnDate"=> date('Y-m-d H:i:s')
                ],
                "payDetails" => [
                    "amount"      => $totalAmount,
                    "product"     => "NSE",
                    "custAccNo"   => "5464567453453435",
                    "txnCurrency" => "INR"
                ],
                "custDetails" => [
                   "custEmail"=> "sagar.gopale@atomtech.in",
                   "custMobile"=> "8976286911"
                ]
            ]
        ];

        // Encrypt Request
        $encryptedRequest = encryptAES512(json_encode($authRequest), ENC_REQ_KEY);

        // CURL to AUTH API
        $ch = curl_init(GATEWAY_URL_AUTH);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
            'merchId' => $merchId,
            'encData' => $encryptedRequest
        ]));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);

        if ($response === false) {
            die('Curl error: ' . curl_error($ch));
        }

        // Response JSON
        $respArr = json_decode($response, true);

       if (isset($respArr['encData'])) {
    $decrypted = decryptAES512($respArr['encData'], ENC_RES_KEY);
    $finalData = json_decode($decrypted, true);

    echo "<h3>Full Decrypted Response</h3><pre>";
    print_r($finalData);
    echo "</pre>";

    $atomTokenId = $finalData['payInstrument']['atomTokenId'] ?? null;

    if ($atomTokenId) {
        echo "✅ Token ID: " . $atomTokenId;
    } else {
        echo "❌ Token ID not found, check error details above.";
    }
} else {
    echo "Error: encData not found in response!<br>";
    echo "Raw Response: <pre>" . htmlspecialchars($response) . "</pre>";
}


if ($atomTokenId) {
    $data['tokenId'] = $atomTokenId;
    $data['amount'] = $totalAmount;
    $this->load->view('payment_form', $data);
} else {
    echo "❌ Token ID not found!";
}

    }

    // =============================
    // 2. Callback after Payment
    // =============================
    public function callback() {
        $response = $this->input->post();
        echo "<h3>Callback Response</h3><pre>";
        print_r($response);
        echo "</pre>";
    }
}