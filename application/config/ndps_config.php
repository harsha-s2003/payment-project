<?php
/*
* NDPS UAT Configuration
*/

// ===============================
// Keys (UAT Details from doc)
// ===============================
defined('ENC_REQ_KEY')   OR define('ENC_REQ_KEY', 'A4476C2062FFA58980DC8F79EB6A799E'); 
defined('ENC_RES_KEY')   OR define('ENC_RES_KEY', '75AEF0FA1B94B3C10D4F5B268F757F11');
defined('REQ_HASH_KEY')  OR define('REQ_HASH_KEY', 'KEY123657234');
defined('RESP_HASH_KEY') OR define('RESP_HASH_KEY', 'KEYRESP123657234');

// ===============================
// Merchant Details
// ===============================
defined('MERCHANT_ID')   OR define('MERCHANT_ID', '317159');
defined('PASSWORD')      OR define('PASSWORD', 'Test@123');

// ===============================
// Gateway URLs
// ===============================
defined('GATEWAY_URL_AUTH')   OR define('GATEWAY_URL_AUTH', 'https://paynetzuat.atomtech.in/ots/aipay/auth');
defined('GATEWAY_URL_STATUS') OR define('GATEWAY_URL_STATUS', 'https://paynetzuat.atomtech.in/ots/aipay/txnStatus');

// ===============================
// Callback URL (Your website)
// ===============================
defined('CALLBACK_URL')  OR define('CALLBACK_URL', 'http://localhost/payment/orderpay/callback');