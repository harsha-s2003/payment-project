<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class OrderPay extends CI_Controller {

   public function __construct() {
    parent::__construct();
    require_once(APPPATH.'views/lib/config.php');
}



   public function pay()
{
    // 1️⃣ Unique Transaction ID
    $txnRefNo = "TXN".time();

    // 2️⃣ Plain Request Array
    // $request = [
    //     "payInstrument" => [
    //         "headDetails" => [
    //             "version"  => "OTSv1.1",
    //             "api"      => "TOKEN",   // Token generate karne ke liye
    //             "platform" => "FLASH"
    //         ],
    //         "merchDetails" => [
    //             "merchId"      => MERCHANT_ID,
    //             "userId"       => "",
    //             "password"     => PASSWORD,
    //             "merchTxnId"   => $txnRefNo,
    //             "merchTxnDate" => date("Y-m-d H:i:s")
    //         ],
    //         "payDetails" => [
    //             "amount" => "100.00",         // Hardcoded example, aap POST se le sakte ho
    //             "product"     => "NSE",
    //             "custAccNo"   => "5464567453453435",
    //             "txnCurrency" => "INR"
    //         ],
    //         "custDetails" => [
    //             "custEmail"   => "sagar.gopale@atomtech.in",
    //             "custMobile"  => "8976286911"
    //         ]
    //     ]
    // ];

    $request = [
    "payInstrument" => [
        "headDetails" => [
            "version"  => "OTSv1.1",
            "api"      => "TOKEN",
            "platform" => "FLASH"
        ],
        "merchDetails" => [
          "merchId" => MERCHANT_ID,
            "userId"       => "",
            "password" => PASSWORD,
            "merchTxnId"   => "TXN12345", // unique txn id
            "merchTxnDate" => date("Y-m-d H:i:s")
        ],
        "payDetails" => [
            "amount"      => "100.00",
            "product"     => "NSE",
            "custAccNo"   => "5464567453453435",
            "txnCurrency" => "INR"
        ],
        "custDetails" => [
            "custEmail"   => "test@atomtech.in",
            "custMobile"  => "9876543210"
        ]
    ]
];


    // 3️⃣ JSON Encode
    $jsonRequest = json_encode($request);

    // 4️⃣ Encrypt JSON with ENC_REQ_KEY
    $encrypted = base64_encode(
        openssl_encrypt($jsonRequest, "AES-256-ECB", ENC_REQ_KEY, OPENSSL_RAW_DATA)
    );

    // 5️⃣ Generate SHA256 Secure Hash
    $secureHash = hash('sha256', REQ_HASH_KEY . $encrypted);

    // 6️⃣ Prepare POST Data
    $postData = [
        "msg" => $encrypted,
        "secureHash" => $secureHash
    ];

    // 7️⃣ CURL POST to NDPS UAT Token Endpoint
    $ch = curl_init(GATEWAY_URL);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    // 8️⃣ Decode Response
    $respArr = json_decode($response, true);

    // Debugging
    echo "<h4>HTTP Code: $httpCode</h4>";
    echo "<pre>Raw Response:\n$response</pre>";

    // 9️⃣ Check Response and Decrypt if available
    if ($respArr && isset($respArr['encData'])) {
        $checkHash = hash('sha256', RESP_HASH_KEY . $respArr['encData']);
        if ($checkHash === $respArr['secureHash']) {
            $decrypted = openssl_decrypt(
                base64_decode($respArr['encData']),
                "AES-256-ECB",
                ENC_RES_KEY,
                OPENSSL_RAW_DATA
            );

            $result = json_decode($decrypted, true);

            echo "<pre>Decrypted Response:\n";
            print_r($result);
            echo "</pre>";

            // ✅ Token nikalna
            $tokenId = $result['payInstrument']['responseDetails']['tokenId'] ?? null;

            if ($tokenId) {
                // Pass tokenId to view for AtomPaynetz JS
                $data['tokenId'] = $tokenId;
                $this->load->view('payment_form', $data);
                return;
            } else {
                echo "❌ Token ID not found in response.";
                return;
            }
        } else {
            echo "❌ Hash mismatch! Response tampered.";
            return;
        }
    } else {
        echo "❌ Invalid response from NDPS.";
        echo "<pre>".$response."</pre>";
        return;
    }
}




    public function callback()
    {
        $response = $this->input->post();
        echo "<h3>NDPS Payment Callback Response</h3>";
        echo "<pre>"; print_r($response); echo "</pre>";
    }
}