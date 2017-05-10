<?php namespace professionalweb\payment\contracts;

use Illuminate\Http\Response;

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
     * Get PAn
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
}