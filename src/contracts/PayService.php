<?php namespace professionalweb\payment\contracts;

use Illuminate\Http\Response;
use Illuminate\Contracts\Support\Arrayable;

/**
 * Interface payment service
 * @package professionalweb\payment\contracts
 */
interface PayService
{
    /**
     * Rubles
     */
    const CURRENCY_RUR = 'RUB';
    const CURRENCY_RUR_ISO = 643;

    const CURRENCY_UAH = 'UAH';
    const CURRENCY_UAH_ISO = 980;

    const CURRENCY_KZT = 'KZT';
    const CURRENCY_KZT_ISO = 398;

    const PAYMENT_TYPE_CARD = 'card';
    const PAYMENT_TYPE_CASH = 'cash';
    const PAYMENT_TYPE_MOBILE = 'mobile';
    const PAYMENT_TYPE_QIWI = 'qiwi';
    const PAYMENT_TYPE_SBERBANK = 'sberbank';
    const PAYMENT_TYPE_YANDEX_MONEY = 'yandex.money';

    /**
     * Get name of payment service
     *
     * @return string
     */
    public function getName();

    /**
     * Pay
     *
     * @param int       $orderId
     * @param int       $paymentId
     * @param float     $amount
     * @param string    $currency
     * @param string    $paymentType
     * @param string    $successReturnUrl
     * @param string    $failReturnUrl
     * @param string    $description
     * @param array     $extraParams
     * @param Arrayable $receipt
     *
     * @return string
     */
    public function getPaymentLink($orderId,
                                   $paymentId,
                                   $amount,
                                   $currency = self::CURRENCY_RUR,
                                   $paymentType = self::PAYMENT_TYPE_CARD,
                                   $successReturnUrl = '',
                                   $failReturnUrl = '',
                                   $description = '',
                                   $extraParams = [],
                                   $receipt = null);

    /**
     * Validate request
     *
     * @param mixed $data
     *
     * @return bool
     */
    public function validate($data);

    /**
     * Parse notification
     *
     * @param array $data
     *
     * @return $this
     */
    public function setResponse($data);

    /**
     * Get order ID
     *
     * @return string
     */
    public function getOrderId();

    /**
     * Get payment id
     *
     * @return string
     */
    public function getPaymentId();

    /**
     * Get operation status
     *
     * @return string
     */
    public function getStatus();

    /**
     * Is payment succeed
     *
     * @return bool
     */
    public function isSuccess();

    /**
     * Get transaction ID
     *
     * @return string
     */
    public function getTransactionId();

    /**
     * Get transaction amount
     *
     * @return float
     */
    public function getAmount();

    /**
     * Get error code
     *
     * @return int
     */
    public function getErrorCode();

    /**
     * Get payment provider
     *
     * @return string
     */
    public function getProvider();

    /**
     * Get PAN
     *
     * @return string
     */
    public function getPan();

    /**
     * Get payment datetime
     *
     * @return string
     */
    public function getDateTime();

    /**
     * Set transport/protocol wrapper
     *
     * @param PayProtocol $protocol
     *
     * @return $this
     */
    public function setTransport(PayProtocol $protocol);

    /**
     * Prepare response on notification request
     *
     * @param int $errorCode
     *
     * @return Response
     */
    public function getNotificationResponse($errorCode = null);

    /**
     * Prepare response on check request
     *
     * @param int $errorCode
     *
     * @return Response
     */
    public function getCheckResponse($errorCode = null);

    /**
     * Get last error code
     *
     * @return int
     */
    public function getLastError();

    /**
     * Get param by name
     *
     * @param string $name
     *
     * @return mixed
     */
    public function getParam($name);
}