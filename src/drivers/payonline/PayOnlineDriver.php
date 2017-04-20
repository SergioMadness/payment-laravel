<?php namespace professionalweb\payment\drivers\payonline;

use professionalweb\payment\contracts\PayService;
use professionalweb\payment\contracts\PayProtocol;

/**
 * Payment service. Pay, Check, etc
 * @package AlpinaDigital\Services
 */
class PayOnlineDriver implements PayService
{
    /**
     * Payonline object
     *
     * @var PayProtocol
     */
    private $transport;

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
        $this->setConfig($config);
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

        return $this->getTransport()->getPaymentUrl($data);
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
        return $this->getTransport()->validate($data);
    }

    /**
     * Set transport
     *
     * @param PayProtocol $transport
     *
     * @return $this
     */
    public function setTransport(PayProtocol $transport)
    {
        $this->transport = $transport;

        return $this;
    }

    /**
     * Get protocol wrapper
     *
     * @return PayProtocol
     */
    public function getTransport()
    {
        return $this->transport;
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
     * Get response param by name
     *
     * @param string $name
     * @param string $default
     *
     * @return mixed|string
     */
    public function getResponseParam($name, $default = '')
    {
        return isset($this->response[$name]) ? $this->response[$name] : $default;
    }

    /**
     * Get order ID
     *
     * @return string
     */
    public function getOrderId()
    {
        return $this->getResponseParam('OrderId');
    }

    /**
     * Get operation status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->getResponseParam('Code');
    }

    /**
     * Is payment succeed
     *
     * @return bool
     */
    public function isSuccess()
    {
        return $this->getResponseParam('ErrorCode', 1) == 0;
    }

    /**
     * Get transaction ID
     *
     * @return string
     */
    public function getTransactionId()
    {
        return $this->getResponseParam('TransactionID');
    }

    /**
     * Get transaction amount
     *
     * @return float
     */
    public function getAmount()
    {
        return $this->getResponseParam('Amount');
    }

    /**
     * Get error code
     *
     * @return int
     */
    public function getErrorCode()
    {
        return $this->getResponseParam('ErrorCode');
    }

    /**
     * Get payment provider
     *
     * @return string
     */
    public function getProvider()
    {
        return $this->getResponseParam('Provider');
    }

    /**
     * Get PAn
     *
     * @return string
     */
    public function getPan()
    {
        return $this->getResponseParam('CardNumber');
    }

    /**
     * Get payment datetime
     *
     * @return string
     */
    public function getDateTime()
    {
        return $this->getResponseParam('DateTime');
    }

    /**
     * Prepare response on notification request
     *
     * @param int $errorCode
     *
     * @return string
     */
    public function getNotificationResponse($errorCode = null)
    {
        return $this->getTransport()->getNotificationResponse($this->response, $errorCode);
    }

    /**
     * Prepare response on check request
     *
     * @param int $errorCode
     *
     * @return string
     */
    public function getCheckResponse($errorCode = null)
    {
        return $this->getTransport()->getNotificationResponse($this->response, $errorCode);
    }

    /**
     * Get last error code
     *
     * @return int
     */
    public function getLastError()
    {
        return 0;
    }
}