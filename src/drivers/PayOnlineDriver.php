<?php namespace professionalweb\payonline\drivers;

use professionalweb\payonline\contracts\PayService;

require_once 'PayOnline.php';

/**
 * Payment service. Pay, Check, etc
 * @package AlpinaDigital\Services
 */
class PayOnlineDriver implements PayService
{
    /**
     * Payonline object
     *
     * @var PayOnline
     */
    private $payonline;

    public function __construct($config)
    {
        $this->setPayonline(new \PayOnline($config['merchantId'], $config['secretKey']));
    }

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
                                   $description = '')
    {
        if (empty($failReturnUrl)) {
            $failReturnUrl = \Config::get('payonline.successURL');
        }
        if (empty($failReturnUrl)) {
            $failReturnUrl = \Config::get('payonline.failURL');
        }
        $data = [
            'OrderId'          => $orderId,
            'Amount'           => number_format(round($amount, 2), 2, '.', ''),
            'Currency'         => $currency,
            'OrderDescription' => $description,
            'PaymentId'        => $paymentId,
            'ReturnUrl'        => $successReturnUrl,
            'FailUrl'          => $failReturnUrl,
        ];

        return $this->getPayonline()->getUrl($data);
    }

    /**
     * Validate request
     *
     * @param array $data
     *
     * @return bool
     */
    public function validate($data)
    {
        $result = false;

        if (isset($data['SecurityKey']) && $this->getPayonline()->getSecurityKey('callback', $data) == $data['SecurityKey']) {
            $result = true;
        }

        return $result;
    }

    /**
     * Get payonline object
     *
     * @return PayOnline
     */
    public function getPayonline()
    {
        return $this->payonline;
    }

    /**
     * Set Payonline object
     *
     * @param PayOnline $payonline
     *
     * @return $this
     */
    public function setPayonline($payonline)
    {
        $this->payonline = $payonline;

        return $this;
    }


}