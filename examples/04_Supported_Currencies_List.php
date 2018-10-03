<?php

// include once ALFAcashier main API class
require_once '../src/Api.php';
require_once '../src/Exception.php';

use ALFAcashier\ALFAcashierAPI;
use ALFAcashier\ALFAcashier_Exception;

// create new ALFAcashierAPI object
$api = new ALFAcashierAPI;

/*
* get supported currencies list, more about it here - https://www.alfacashier.com/developers#get_requests-getcoins
* e.g.
  {
  "litecoin" : {
    "currency" : "LTC",
    "withdrawal" : false,
    "deposit" : false,
    "title" : "Litecoin"
  },
  "nem" : {
    "currency" : "XEM",
    "withdrawal" : true,
    "deposit" : true,
    "title" : "NEM"
  },
  ...
  means that Litecoin is not available for deposit or withdrawal, but NEM is available both for deposit and withdrawal
*/

try {
  echo "Getcoins result:" . PHP_EOL;
  $result = $api->getCoins();
  var_dump($result);
} catch (ALFAcashier_Exception $e) {
  echo "Getcoins method failed: " . $e->getMessage() . PHP_EOL;
}

