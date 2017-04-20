<?php namespace professionalweb\payment\drivers\yandex;

use professionalweb\payment\contracts\PayProtocol;
use professionalweb\payment\contracts\PayService;

/**
 * Payment service. Pay, Check, etc
 * @package AlpinaDigital\Services
 */
class YandexDriver implements PayService
{

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

    /**
     * @var PayProtocol
     */
    private $transport;

    /**
     * Last error code
     *
     * @var int
     */
    private $lastError = 0;

    public function __construct($config)
    {
        $this->setConfig($config);
    }

    /**
     * Pay
     *
     * @param int        $orderId
     * @param int        $paymentId
     * @param float      $amount
     * @param int|string $currency
     * @param string     $successReturnUrl
     * @param string     $failReturnUrl
     * @param string     $description
     *
     * @return string
     * @throws \Exception
     */
    public function getPaymentLink($orderId,
                                   $paymentId,
                                   $amount,
                                   $currency = self::CURRENCY_RUR_ISO,
                                   $successReturnUrl = '',
                                   $failReturnUrl = '',
                                   $description = '')
    {
        return $this->getTransport()->getPaymentUrl([
            'customerName' => $orderId,
            'sum'          => $amount,
        ]);
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
        return ($this->lastError = $this->getTransport()->validate($data)) === 0;
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
        $data['DateTime'] = date('Y-m-d H:i:s');
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
        return $this->getResponseParam('Status');
    }

    /**
     * Is payment succeed
     *
     * @return bool
     */
    public function isSuccess()
    {
        return $this->getResponseParam('Success', 'false') === 'true';
    }

    /**
     * Get transaction ID
     *
     * @return string
     */
    public function getTransactionId()
    {
        return $this->getResponseParam('PaymentId');
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
        return 'card';
    }

    /**
     * Get PAn
     *
     * @return string
     */
    public function getPan()
    {
        return $this->getResponseParam('Pan');
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
     * Get transport
     *
     * @return PayProtocol
     */
    public function getTransport()
    {
        return $this->transport;
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
     * Prepare response on notification request
     *
     * @param int $errorCode
     *
     * @return string
     */
    public function getNotificationResponse($errorCode = null)
    {
        return $this->getTransport()->getNotificationResponse($this->response, $errorCode !== null ?: $this->getLastError());
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
        return $this->getTransport()->getNotificationResponse($this->response, $errorCode !== null ?: $this->getLastError());
    }

    /**
     * Get last error code
     *
     * @return int
     */
    public function getLastError()
    {
        return $this->lastError;
    }

}