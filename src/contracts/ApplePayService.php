<?php namespace professionalweb\payment\contracts;

/**
 * Interface for ApplePay
 * @package professionalweb\payment\contracts
 */
interface ApplePayService
{
    /**
     * Start session
     *
     * @param string $url
     *
     * @return string
     */
    public function startSession($url);

    /**
     * Pay through by ApplePay token
     *
     * @param mixed  $orderId
     * @param mixed  $customerId
     * @param float  $amount
     * @param string $paymentToken
     * @param string $currency
     *
     * @return mixed
     */
    public function pay($orderId, $customerId, $amount, $paymentToken, $currency = PayService::CURRENCY_RUR);
}