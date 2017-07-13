<?php namespace professionalweb\payment\drivers\yandex\payments;

use professionalweb\payment\contracts\PayService;
use professionalweb\payment\contracts\ApplePayService;
use professionalweb\payment\traits\ApplePaySessionStart;
use professionalweb\payment\drivers\yandex\contracts\YandexPayment;

/**
 * Realisation for Yandex.Kassa
 * @package professionalweb\payment\drivers\yandex
 */
class ApplePay implements ApplePayService
{

    use ApplePaySessionStart;

    /**
     * Payment method URL
     */
    const URL_PAYMENT = 'https://payment.yandex.net/api/v2/version';

    /**
     * @var YandexPayment
     */
    private $protocol;

    /**
     * Pay through by ApplePay token
     *
     * @param mixed  $orderId
     * @param mixed  $customerId
     * @param float  $amount
     * @param string $paymentToken
     * @param string $currency
     * @param array  $params
     *
     * @return mixed
     * @throws \Exception
     */
    public function pay($orderId,
                        $customerId,
                        $amount,
                        $paymentToken,
                        $currency = PayService::CURRENCY_RUR,
                        array $params = [])
    {
        $data = [
            'recipient'   => [
                'shopId',
            ],
            'order'       => [
                'clientOrderId' => $orderId,
                'customerId'    => $customerId,
                'value'         => [
                    'amount'   => $amount,
                    'currency' => $currency,
                ],
                'parameters'    => $params,
            ],
            'source'      => 'BankCard',
            'walletType'  => 'ApplePay',
            'paymentData' => $paymentToken,
        ];

        return $this->getProtocol()->drsp($data);
    }

    /**
     * Get protocol wrapper
     *
     * @return YandexPayment
     */
    public function getProtocol()
    {
        return $this->protocol;
    }

    /**
     * Set protocol wrapper
     *
     * @param YandexPayment $protocol
     *
     * @return $this
     */
    public function setProtocol(YandexPayment $protocol)
    {
        $this->protocol = $protocol;

        return $this;
    }


}