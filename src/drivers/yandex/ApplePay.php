<?php namespace professionalweb\payment\drivers\yandex;

use professionalweb\payment\contracts\PayService;

/**
 * Realisation for Yandex.Kassa
 * @package professionalweb\payment\drivers\yandex
 */
class ApplePay extends \professionalweb\payment\abstraction\ApplePay
{
    /**
     * Payment method URL
     */
    const URL_PAYMENT = 'https://money.yandex.ru/api/v2/payments/dsrpWallet';

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

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        if (($result = curl_exec($ch)) === false) {
            throw new \Exception(curl_error($ch));
        }
        // close cURL resource, and free up system resources
        curl_close($ch);
    }
}