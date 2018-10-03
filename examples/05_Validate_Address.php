<?php

// include once ALFAcashier main API class
require_once '../src/Api.php';
require_once '../src/Exception.php';

use ALFAcashier\ALFAcashierAPI;
use ALFAcashier\ALFAcashier_Exception;

// create new ALFAcashierAPI object
$api = new ALFAcashierAPI;

// set the currency code, e.g. for Bitcoin - BTC, to get all supported currency codes please use https://www.alfacashier.com/developers#get_requests-getcoins
$currency = 'BTC';

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

// validate Bitcoin address 1FgThhtLdSM1i78vXHGovA3WxzbTWA2mse
try {
  $options = ['address' => '1FgThhtLdSM1i78vXHGovA3WxzbTWA2mse'];
  echo "Address " . $options['address'] . " validation results:" . PHP_EOL;
  $result = $api->validateAddress($options, $currency);
  var_dump($result);
} catch (ALFAcashier_Exception $e) {
  echo "Address validate method failed: " . $e->getMessage() . PHP_EOL;
}

// validate XRP address with destination tag
try {
  $currency = 'XRP';
  $options = ['account' => 'rExFpwNwwrmFWbX81AqbHJYkq8W6ZoeWE6', 'destination_tag' => '123'];
  echo "Address " . $options['account'] . " validation results:" . PHP_EOL;
  $result = $api->validateAddress($options, $currency);
  var_dump($result);
} catch (ALFAcashier_Exception $e) {
  echo "Address validate method failed: " . $e->getMessage() . PHP_EOL;
}

// validate invalid Bitcoin address
try {
  $currency = 'BTC';
  $options = ['address' => '1FgThhtLdSM1NOTvalid'];
  echo "Address '" . $options['address'] . "' validation results:" . PHP_EOL;
  $result = $api->validateAddress($options, $currency);
} catch (ALFAcashier_Exception $e) {
  echo "Address validate method failed: " . $e->getMessage() . PHP_EOL;
}
