<?php namespace professionalweb\payment\drivers\payonline;

use professionalweb\payment\contracts\PayService;

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
     * @var \PayOnline
     */
    private $payonline;

    /**
     * Module config
     *
     * @var array
     */
    private $config;

    /**
     * Notification info
     *
     * @var array
     */
    protected $response;

    public function __construct($config)
    {
        $this->setConfig($config)->setPayonline(new \PayOnline($config['merchantId'], $config['secretKey']));
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
        if (empty($successReturnUrl)) {
            $successReturnUrl = $this->getConfig()['successURL'];
        }
        if (empty($failReturnUrl)) {
            $failReturnUrl = $this->getConfig()['failURL'];
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
     * @return \PayOnline
     */
    public function getPayonline()
    {
        return $this->payonline;
    }

    /**
     * Set Payonline object
     *
     * @param \PayOnline $payonline
     *
     * @return $this
     */
    public function setPayonline($payonline)
    {
        $this->payonline = $payonline;

        return $this;
    }

    /**
     * Get configuration
     *
     * @return array
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * Set driver configuration
     *
     * @param array $config
     *
     * @return $this
     */
    public function setConfig($config)
    {
        $this->config = $config;

        return $this;
    }

    /**
     * Parse notification
     *
     * @param array $data
     *
     * @return mixed
     */
    public function setResponse($data)
    {
        $this->response = $data;

        return $this;
    }

    /**
     * Get order ID
     *
     * @return string
     */
    public function getOrderId()
    {
        return $this->response['OrderId'];
    }

    /**
     * Get operation status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->response['Code'];
    }

    /**
     * Is payment succeed
     *
     * @return bool
     */
    public function isSuccess()
    {
        return $this->response['ErrorCode'] == 0;
    }

    /**
     * Get transaction ID
     *
     * @return string
     */
    public function getTransactionId()
    {
        return $this->response['TransactionID'];
    }

    /**
     * Get transaction amount
     *
     * @return float
     */
    public function getAmount()
    {
        return $this->response['Amount'];
    }

    /**
     * Get error code
     *
     * @return int
     */
    public function getErrorCode()
    {
        return $this->response['ErrorCode'];
    }

    /**
     * Get payment provider
     *
     * @return string
     */
    public function getProvider()
    {
        return $this->response['Provider'];
    }

    /**
     * Get PAn
     *
     * @return string
     */
    public function getPan()
    {
        return $this->response['CardNumber'];
    }

    /**
     * Get payment datetime
     *
     * @return string
     */
    public function getDateTime()
    {
        return $this->response['DateTime'];
    }
}