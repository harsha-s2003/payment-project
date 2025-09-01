<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class OrderPay extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper('encryption_helper'); // Load AES helper
    }

    // Function to generate HMAC signature
    private function generateSignature($hashKey, $params = []) {
        $data = implode('', $params); // concat all required params
        return hash_hmac('sha512', $data, $hashKey);
    }

    public function pay() {
        // Example merchant and transaction data
        $merchId = "317159";
        $merchTxnId = "test000123";
        $totalAmount = "100";
        $respHashKey = "KEYRESP123657234"; // Replace with actual hash key
        $atomTxnId = ""; // Initially empty
        $txnStatusCode = ""; // Initially empty
        $subChannel = "DC";
        $bankTxnId = "";

        // Generate signature (optional, if required for response verification)
        $params = [$merchId, $atomTxnId, $merchTxnId, $totalAmount, $txnStatusCode, $subChannel, $bankTxnId];
        $signature = $this->generateSignature($respHashKey, $params);

        // Prepare request array
        $authRequest = [
            "payInstrument" => [
                "headDetails" => [
                    "version" => "OTSv1.1",
                    "api" => "AUTH",
                    "platform" => "FLASH"
                ],
                "merchDetails" => [
                    "merchId" => $merchId,
                    "userId" => "",
                    "password" => "Test@123",
                    "merchTxnId" => $merchTxnId,
                    "merchTxnDate" => date('Y-m-d H:i:s')
                ],
                "payDetails" => [
                    "amount" => $totalAmount,
                    "product" => "NSE",
                    "custAccNo" => "5464567453453435",
                    "txnCurrency" => "INR"
                ],
                "custDetails" => [
                    "custEmail" => "sagar.gopale@atomtech.in",
                    "custMobile" => "8976286911"
                ],
                "extras" => [
                    "udf1" => "One",
                    "udf2" => "Two"
                ]
            ],
            "payModeSpecificData" => [
                "subChannel" => $subChannel
            ]
        ];

        // Encrypt request
        $encReqKey = "7813E3E5E93548B096675AC27FE2C850"; // Replace with actual ENC key
        $encryptedRequest = encryptAES512(json_encode($authRequest), $encReqKey);

        // Send request via cURL
        $ch = curl_init("https://uatapi.atomtech.in/payment/initiate");
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

// 
        // Parse response
        parse_str($response, $respArr);
        $decrypted = decryptAES512($respArr['encData'], $encReqKey);
        $responseData = json_decode($decrypted, true);

        $atomTokenId = isset($responseData['atomTokenId']) ? $responseData['atomTokenId'] : null;

        echo "<pre>";
        print_r($responseData);
        echo "</pre>";
        echo "Atom Token ID: " . $atomTokenId;

// parse_str($response, $respArr);

// if(isset($respArr['encData'])) {
//     $decrypted = decryptAES512($respArr['encData'], $encReqKey);
//     $responseData = json_decode($decrypted, true);
//     $atomTokenId = isset($responseData['atomTokenId']) ? $responseData['atomTokenId'] : null;
//     echo "Atom Token ID: " . $atomTokenId;
// } else {
//     echo "Error: encData not found in response!";
//     echo "<pre>";
//     print_r($respArr);
//     echo "</pre>";
// }


    }
}