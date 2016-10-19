<?php namespace professionalweb\payonline\contracts;

interface PayService
{
    /**
     * Rubles
     */
    const CURRENCY_RUR = 'RUB';

    /**
     * Pay
     *
     * @param int    $orderId
     * @param int    $paymentId
     * @param float  $amount
     * @param string $currency
     * @param string $successReturnUrl
     * @param string $failReturnUrl
     * @param string $description
     *
     * @return string
     */
    public function getPaymentLink($orderId,
                                   $paymentId,
                                   $amount,
                                   $currency = self::CURRENCY_RUR,
                                   $successReturnUrl = '',
                                   $failReturnUrl = '',
                                   $description = '');

    /**
     * Validate request
     *
     * @param mixed $data
     *
     * @return bool
     */
    public function validate($data);
}