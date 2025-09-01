<?php
/*
* PHP Kit for Icici Merchant Solutions
* Version: 1.0.5
*/

// ===============================
// NDPS Testing Configuration
// ===============================

defined('ENC_REQ_KEY')   OR define('ENC_REQ_KEY', 'A4476C2062FFA58980DC8F79EB6A799E');
defined('ENC_RES_KEY')   OR define('ENC_RES_KEY', '75AEF0FA1B94B3C10D4F5B268F757F11');
defined('REQ_HASH_KEY')  OR define('REQ_HASH_KEY', 'KEY123657234');
defined('RESP_HASH_KEY') OR define('RESP_HASH_KEY', 'KEYRESP123657234');
defined('MERCHANT_ID')   OR define('MERCHANT_ID', '317159');
defined('PASSWORD')      OR define('PASSWORD', 'Test@123');
defined('GATEWAY_URL')   OR define('GATEWAY_URL', 'https://paynetzuat.atomtech.in/ots/aipay/auth');
defined('CALLBACK_URL')  OR define('CALLBACK_URL', 'http://localhost/payment/orderpay/callback');



?>