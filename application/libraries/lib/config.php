<?php
/*
* PHP Kit for Icici Merchant Solutions
* Version: 1.0.5
*/

// /*Enter UAT kit parameters */
// define('ENC_KEY', '1FF49170C8CCECFF1345B38F971CABBD'); 
// define('SECURE_SECRET', '5FF1003BD85EC13EDDE106AC235F58AD'); 
// define('VERSION', '1'); 
// define('PASSCODE', 'ABCD1234'); //Must be 8 digit only. No special characters allowed
// define('', '100000000005859'); //This is not being used in kit but for your reference in case of use it as global
// define('TERMINALID', 'EG000488'); //This is not being used in kit but for your reference in case of use it as global
// define('BANKID', '24520'); //Must be 6 digit only. No special characters allowed
// define('MCC', '8641'); //Must be 4 digit only. No special characters allowed
// define('GATEWAYURL', 'https://payuatrbac.icicibank.com/accesspoint/angularBackEnd/requestproxypass'); 
// define('REFUNDURL', 'https://payuatrbac.icicibank.com/accesspoint/v1/24520/createRefundFromMerchantKit'); 
// define('STATUSURL', 'https://payuatrbac.icicibank.com/accesspoint/v1/24520/checkStatusMerchantKit');
// define('RETURNURL', 'http://localhost/epay/response'); // define('RETURNURL', 'YOUR_DOMAIN/ICICI_MS/responseSale.
// // Return url's length must not be more then 500 character




// ✅ Encryption / Security Keys (Testing Docs me diye gaye hote hain)
define('ENC_KEY', '58BE879B7DD635698764745511C704AB');  // 32-char Encryption Key
define('SECURE_SECRET', '7813E3E593548B096675AC27FE2C850'); // SHA-256 Secret



// ✅ Version
define('VERSION', '1');

// ✅ Merchant Details
define('MERCHANT_ID', '317159');       // NDPS Test Merchant ID
define('TERMINAL_ID', 'TEST_TERM');    // NDPS Test Terminal ID
define('PASSWORD', 'Test@123');        // NDPS Test Password
define('CURRENCY', '356');             // INR numeric ISO code

// ✅ NDPS UAT URLs
define('GATEWAY_URL', 'https://pgtest.atomtech.in/paynetz/epi/fts');   // Transaction Initiation
define('REFUND_URL',  'https://pgtest.atomtech.in/paynetz/epi/refund'); // Refund
define('STATUS_URL',  'https://pgtest.atomtech.in/paynetz/epi/status'); // Status Check
define('CALLBACK_URL','http://localhost/payment/orderpay/callback');   // Callback / Return URL




?>