<?php namespace professionalweb\payment\drivers\tinkoff;

use Alcohol\ISO4217;
use professionalweb\payment\contracts\PayProtocol;
use professionalweb\payment\contracts\PayService;

/**
 * Payment service. Pay, Check, etc
 * @package AlpinaDigital\Services
 */
class TinkoffDriver implements PayService
{
    /**
     * TinkoffMerchantAPI object
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
        $data = [
            'OrderId'     => $orderId,
            'Amount'      => round($amount * 100),
            'Currency'    => (new ISO4217())->getByAlpha3($currency)['numeric'],
            'Description' => $description,
            'DATA'        => 'PaymentId=' . $paymentId,
        ];

        $paymentUrl = $this->getTransport()->getPaymentUrl($data);

        $this->response['PaymentId'] = $this->getTransport()->getPaymentId();

        return $paymentUrl;
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
     * Set transport/protocol wrapper
     *
     * @param PayProtocol $protocol
     *
     * @return $this
     */
    public function setTransport(PayProtocol $protocol)
    {
        $this->transport = $protocol;

        return $this;
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