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





/* Enter NDPS UAT kit parameters */

// ✅ Test Keys (docs se lo, neeche example placeholder hai)
define('ENC_KEY', 'Your_Test_EncKey_Here'); 
define('SECURE_SECRET', 'Your_Test_SecureSecret_Here'); 

// ✅ API Version
define('VERSION', '1'); 
define('PASSCODE', '12345678'); // Must be 8 digits only, no special chars

// ✅ NDPS Merchant Details
define('MERCHANTID', '317159');    // NDPS Test MerchantId (given in docs)
define('TERMINALID', 'TEST_TERM'); // NDPS Test TerminalId (if not given, keep blank)
define('BANKID', '24520');         // Keep same if NDPS docs mention
define('MCC', '8641');             // Standard Merchant Category Code

// ✅ NDPS UAT URLs
define('GATEWAYURL', 'https://pgtest.atomtech.in/paynetz/epi/fts');     // Transaction Init
define('REFUNDURL',  'https://pgtest.atomtech.in/paynetz/epi/refund');  // Refund
define('STATUSURL',  'https://pgtest.atomtech.in/paynetz/epi/status');  // Txn Status

// ✅ Return / Callback URL (your domain)
define('RETURNURL', 'http://localhost/epay/response.php'); 
// ⚠️ Important: Update this with your live domain callback file when going live

?>