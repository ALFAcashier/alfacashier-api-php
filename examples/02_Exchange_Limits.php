<?php

require_once '../src/Api.php';
require_once '../src/Exception.php';

use ALFAcashier\ALFAcashierAPI;
use ALFAcashier\ALFAcashier_Exception;

// create new ALFAcashierAPI object
$api = new ALFAcashierAPI;

// exchange pair example: BTC_LTC, direction of the exchange, defined in the form [source_currency_code]_[destination_currency_code]
$pair = 'BTC_LTC';

// get current exchange deposit and withdrawal limits for specified exchange pair, more about it - https://www.alfacashier.com/developers#get_requests-limit
/* example:
{
  pair: "BTC_LTC",
  deposit_min: 0.1,
  deposit_max: 2,
  withdrawal_min: 0.2,
  withdrawal_max: 200,
  deposit_day_limit: 1,
  deposit_month_limit: 10,
  withdrawal_day_limit: 1000,
  withdrawal_month_limit: 10000,
}
*/

try {
  echo "Limit result:" . PHP_EOL;
  $result = $api->limit($pair);
  var_dump($result);
} catch (ALFAcashier_Exception $e) {
  echo "Limit method failed: " . $e->getMessage() . PHP_EOL;
}
