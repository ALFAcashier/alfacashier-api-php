<?php

// include once ALFAcashier main API class
require_once '../src/Api.php';
require_once '../src/Exception.php';

use ALFAcashier\ALFAcashierAPI;
use ALFAcashier\ALFAcashier_Exception;

// create new ALFAcashierAPI object
$api = new ALFAcashierAPI;

// exchange pair example: BTC_LTC, direction of the exchange, defined in the form [source_currency_code]_[destination_currency_code]
$pair = 'BTC_LTC';

// deposit amount, e.g. 0.02 BTC, if you want to use withdrawal amount instead set this value to 0 or NULL
$deposit_amount = 0.02;

// withdrawal amount, we set this to 0, because earlier we've specified deposit_amount 0.02 BTC
// if you want to use withdrawal amount instead (e.g. 5 LTC, $withdrawal_amount = 5), please set $deposit_amount = 0
// if you specify both withdrawal_amount and deposit_amount non 0, deposit_amount will be used
$withdrawal_amount = 0;

// promo code, if you have one
$promo_code = 'ABCDEF';

$promo_code_discount = 0;
try{
  // get promo code discount percent, more about promo code validation - https://www.alfacashier.com/developers#post_requests-promo_code
  $promo = $api->promoCode($promo_code, $pair);
  if ($promo['discount'])
    $promo_code_discount = $promo['discount'];
} catch (ALFAcashier_Exception $e) {
  echo $e->getMessage() . "\n";
}

// get exchange rate with applied promo code discount, more about it - https://www.alfacashier.com/developers#post_requests-rate
try {
  echo "Rate result:" . PHP_EOL;
  $result = $api->rate($pair, $deposit_amount, $withdrawal_amount, $promo_code_discount);
  var_dump($result);
} catch (ALFAcashier_Exception $e) {
  echo "Rate method failed: " . $e->getMessage() . PHP_EOL;
}
