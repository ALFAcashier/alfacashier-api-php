<?php

// include once ALFAcashier main API class
require_once '../src/Api.php';
require_once '../src/Exception.php';

use ALFAcashier\ALFAcashierAPI;
use ALFAcashier\ALFAcashier_Exception;

// create new ALFAcashierAPI object
$api = new ALFAcashierAPI;

// form parameters for order creation, more about order creation here - https://www.alfacashier.com/developers#post_requests-create

// exchange pair example: BTC_LTC, direction of the exchange, defined in the form [source_currency_code]_[destination_currency_code]
$pair = 'BTC_LTC';

// deposit amount, e.g. 0.02 BTC, if you want to use withdrawal amount instead set this value to 0 or NULL
$deposit_amount = 0.02;

// withdrawal amount, we set this to 0, because earlier we've specified deposit_amount 0.02 BTC
// if you want to use withdrawal amount instead (e.g. 5 LTC, $withdrawal_amount = 5), please set $deposit_amount = 0
// if you specify both withdrawal_amount and deposit_amount non 0, deposit_amount will be used
$withdrawal_amount = 0;

// your e-mail address or your customer's e-mail address
$email = 'noreply@alfacashier.com';

// promo code, if you have one
$promo_code = '';

// your referral uid, you can get it here: https://www.alfacashier.com/referral/overview
$referral_uid = '2f777778';

// Litecoin destination address
/*
  For different cryptocurrencies there are different required parameters:

1) Bitcoin, Litecoin, Ethereum, Dash, ZCash, Ethereum Classic:
  options = {address: BITCOINADDRESS}
  address is your cryptocurrency address.
2) Bitcoin Cash
  options = {address: BITCOINCASHADDRESS, legacy_address: LEGACYBITCOINCASHADDRESS}
  address is your cryptocurrency address.
3) XRP
  options = {account: XRPACCOUNT, destination_tag: DESTTAG}
  account is your XRP account and destination_tag is Destination Tag (some exchangers require it).
4) NEM
  options = {address: XEMADDRESS, message: MESSAGE}
  address is your NEM address and message is an optional parameter if you're using shared NEM wallet.
5) Monero
  options = {address: MONEROADDRESS, payment_id: PAYMENTID}
  address if your Monero address and payment_id is an optional parameter if you're using shared Monero wallet.
 */
$options = ['address' => 'La9WJGciWq3Sq1pXTRS4wJsDRC8KAREu97'];

// create new exchange order to exchange Bitcoin (0.02 BTC) to Litecoin (LTC)
// exchange rate is determined automatically
try {
  echo "Order create result:" . PHP_EOL;
  $result = $api->create($pair, $deposit_amount, $withdrawal_amount, $email, $options, $promo_code, $referral_uid);
  var_dump($result);
} catch (ALFAcashier_Exception $e) {
  echo "Order create method failed: " . $e->getMessage() . PHP_EOL;
}

if (!empty($result['secret_key'])) {
  // save secret key of your exchange order to track the order or cancel it
  $secret_key = $result['secret_key'];

  // check exchange order status, more about it - https://www.alfacashier.com/developers#get_requests-status
  try {
    echo "Order status result:" . PHP_EOL;
    $result = $api->status($secret_key);
    var_dump($result);
  } catch (ALFAcashier_Exception $e) {
    echo "Order status method failed: " . $e->getMessage() . PHP_EOL;
  }

  // cancel exchange order
  try {
    echo "Order cancel result:" . PHP_EOL;
    $result = $api->cancel($secret_key);
    var_dump($result);
  } catch (ALFAcashier_Exception $e) {
    echo "Order cancel method failed: " . $e->getMessage() . PHP_EOL;
  }
}
