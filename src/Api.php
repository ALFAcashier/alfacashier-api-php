<?php

namespace ALFAcashier;

/**
 * Class ALFAcashierAPI
 */
class ALFAcashierAPI {
  /**
   * @var string ALFAcashier API URL
   */
  private $url = 'https://www.alfacashier.com/api';

  /**
   * Validates Promo Code for specific exchange direction (exchange pair).
   * @param string $pair Example: 'BTC_LTC', direction of the exchange, defined in the form [source_currency_code]_[destination_currency_code].
   * @param string $code Example: 'ALFACODE'
   * @return array {"discount": "10"}
   * @throws Exception
   */
  public function promoCode($pair, $code) {
    $result = $this->postRequest('promo_code', [
      'pair' => $pair,
      'code' => $code
    ]);
    if (!empty($result['error'])) {
      throw new ALFAcashier_Exception("Invalid promo_code result, error: " . $result['error']);
    }
    return $result;
  }

  /**
   * This method returns the current exchange rate for specific exchange pair (e.g. BTC_LTC, 1 BTC = XXXX LTC). Exchange rate can quickly change depending on the market.
   * @param string $pair Example: 'BTC_LTC', direction of the exchange, defined in the form [source_currency_code]_[destination_currency_code].
   * @param float $deposit_amount (Optional) example: '4.952', fixed deposit amount you'd like to send for an exchange.
   * @param float $withdrawal_amount (Optional) example: '6.431', fixed withdrawal amount you'd like to get after an exchange is made.
   * @param int $promo_code_discount (Optional) example: '10', discount percent returned from promo code validation.
   * @return array
   * @throws Exception
   */
  public function rate($pair, $deposit_amount = NULL, $withdrawal_amount = NULL, $promo_code_discount = 0) {
    $params = [
      'pair' => $pair,
      'deposit_amount' => $deposit_amount,
      'promo_code_discount' => $promo_code_discount,
    ];
    if ($withdrawal_amount) {
      unset($params['deposit_amount']);
      $params['withdrawal_amount'] = $withdrawal_amount;
    }
    $result = $this->postRequest('rate', $params);
    if (!empty($result['error'])) {
      throw new ALFAcashier_Exception("Invalid rate result, error: " . $result['error']);
    }
    return $result;
  }

  /**
   * Create an exchange order for specific exchange direction.
   * To specify deposit_amount, please set $withdrawal_amount=0.
   * To specify withdrawal_amount, please set $deposit_amount=0.
   * If you specify both deposit_amount and withdrawal_amount as non zero deposit_amount will be used.
   * @param string $pair Example: 'BTC_LTC'. Direction of the exchange, defined in the form [source_currency_code]_[destination_currency_code].
   * @param float $deposit_amount (Optional) example: '4.953', fixed deposit amount you'd like to send for an exchange.
   * @param float $withdrawal_amount (Optional) example: '6.431', fixed withdrawal amount you'd like to get after an exchange is made.
   * @param string $email Example: 'email@example.com'
   * @param array $options Example: {'address': '1FgThhtLdSM1i78vXHGovA3WxzbTWA2mse'}, this array of parameters depends on source_currency_code.
   * @param string $promo_code (Optional) example: 'ALFACODE'.
   * @param string $referral_uid (Optional) example: '12345', affiliate program Referral ID.
   * @return array
   * @throws Exception
   */
  public function create($pair, $deposit_amount, $withdrawal_amount, $email, $options, $promo_code = '', $referral_uid = NULL) {
    $params = [
      'pair' => $pair,
      'promo_code' => $promo_code,
      'deposit_amount' => $deposit_amount,
      'email' => $email,
      'options' => $options,
      'r_uid' => $referral_uid,
    ];
    if (!$referral_uid)
      unset($params['r_uid']);

    if ($withdrawal_amount) {
      $params['withdrawal_amount'] = $withdrawal_amount;
      unset($params['deposit_amount']);
    }

    $result = $this->postRequest('create', $params);
    if (!empty($result['error'])) {
      throw new ALFAcashier_Exception("Invalid order create result, error: " . $result['error']);
    }
    return $result;
  }

  /**
   * Get Supported Currencies List.
   * List contains currency code (e.g. BTC), currency title (e.g. Bitcoin), and currency availability for deposit and withdrawal.
   * @return array
   * @throws Exception
   */
  public function getCoins() {
    $result = $this->getRequest('getcoins');
    if (!empty($result['error'])) {
      throw new ALFAcashier_Exception("Invalid getcoins result, error: " . $result['error']);
    }
    return $result;
  }

  /**
   * Get current exchange deposit and withdrawal limits for specified exchange direction (exchange pair).
   * @param string $pair example: "BTC_LTC", direction of the exchange, defined in the form [source_currency_code]_[destination_currency_code].
   * @return array
   * @throws Exception
   */
  public function limit($pair) {
    $result = $this->getRequest('limit/' . $pair);
    if (!empty($result['error'])) {
      throw new ALFAcashier_Exception("Invalid limit result, error: " . $result['error']);
    }
    return $result;
  }

  /**
   * Get exchange order status.
   * @param string $secret_key is the secret key of the order, you can get one by creating an order.
   * @return array
   * @throws Exception
   */
  public function status($secret_key) {
    $result = $this->getRequest('status/' . $secret_key);
    if (!empty($result['error'])) {
      throw new ALFAcashier_Exception("Invalid order status result, error: " . $result['error']);
    }
    return $result;
  }

  /**
   * Cancel exchange order.
   * @param string $secret_key is the secret key of the order, you can get one by creating an order.
   * @return array
   * @throws Exception
   */
  public function cancel($secret_key) {
    $result = $this->getRequest('cancel/' . $secret_key);
    if (!empty($result['error'])) {
      throw new ALFAcashier_Exception("Invalid cancel order result, error: " . $result['error']);
    }
    return $result;
  }

  /**
   * Verify specific currency address/account with additional parameters like destination_tag, message or payment_id.
   * @param array $options Example: {'address': '1FgThhtLdSM1i78vXHGovA3WxzbTWA2mse'} This array of parameters depends on source_currency_code
   * @param string $currency Example: "BTC"
   * @return array
   * @throws Exception
   */
  public function validateAddress($options, $currency) {
    $result = $this->postRequest('validateaddress', [
      'currency' => $currency,
      'options' => $options
    ]);
    if (!empty($result['error'])) {
      throw new ALFAcashier_Exception("Invalid validateaddress result, error: " . $result['error']);
    }
    return $result;
  }

  /**
   * POST Request.
   * @param string $method
   * @param array $params
   * @return array
   * @throws Exception
   */
  private function postRequest($method, $params = []) {
    $content = json_encode($params);
    $url = $this->url . '/' . $method . '.json';
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_HEADER, FALSE);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($curl, CURLOPT_HTTPHEADER, ["Content-type: application/json; charset=UTF-8"]);
    curl_setopt($curl, CURLOPT_POST, TRUE);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $content);
    $json_response = curl_exec($curl);
    $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    if ($status != 200) {
      throw new ALFAcashier_Exception("Error: call to URL $url failed with status $status, response $json_response, curl_error " . curl_error($curl) . ", curl_errno " . curl_errno($curl));
    }
    curl_close($curl);
    $response = json_decode($json_response, TRUE);
    return $response;
  }

  /**
   * GET Request.
   * @param string $method
   * @return array
   * @throws Exception
   */
  private function getRequest($method) {
    $curl = curl_init();
    $url = $this->url . '/' . $method . '.json';
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HEADER, FALSE);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($curl, CURLOPT_HTTPHEADER, ["Content-type: application/json; charset=UTF-8"]);
    $output = curl_exec($curl);
    $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    if ($status != 200) {
      throw new ALFAcashier_Exception("Error: call to URL $url failed with status $status, response $output, curl_error " . curl_error($curl) . ", curl_errno " . curl_errno($curl));
    }
    curl_close($curl);
    $response = json_decode($output, TRUE);
    return $response;
  }
}
