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
    public const CURRENCY_RUR = 'RUB';
    public const CURRENCY_RUR_ISO = 643;

    public const CURRENCY_UAH = 'UAH';
    public const CURRENCY_UAH_ISO = 980;

    public const CURRENCY_KZT = 'KZT';
    public const CURRENCY_KZT_ISO = 398;

    public const PAYMENT_TYPE_CARD = 'card';
    public const PAYMENT_TYPE_CASH = 'cash';
    public const PAYMENT_TYPE_MOBILE = 'mobile';
    public const PAYMENT_TYPE_QIWI = 'qiwi';
    public const PAYMENT_TYPE_SBERBANK = 'sberbank';
    public const PAYMENT_TYPE_YANDEX_MONEY = 'yandex.money';
    public const PAYMENT_TYPE_ALFABANK = 'alfabank';

    public const RESPONSE_SUCCESS = 1;
    public const RESPONSE_ERROR = 0;
    public const RESPONSE_ERROR_WRONG_ORDER = 2;
    public const RESPONSE_ERROR_WRONG_PAYMENT = 3;
    public const RESPONSE_ERROR_WRONG_AMOUNT = 4;

    /**
     * Get name of payment service
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Pay
     *
     * @param mixed   $orderId
     * @param mixed   $paymentId
     * @param float   $amount
     * @param string  $currency
     * @param string  $paymentType
     * @param string  $successReturnUrl
     * @param string  $failReturnUrl
     * @param string  $description
     * @param array   $extraParams
     * @param Receipt $receipt
     *
     * @return string
     */
    public function getPaymentLink($orderId,
                                   $paymentId,
                                   float $amount,
                                   string $currency = self::CURRENCY_RUR,
                                   string $paymentType = self::PAYMENT_TYPE_CARD,
                                   string $successReturnUrl = '',
                                   string $failReturnUrl = '',
                                   string $description = '',
                                   array $extraParams = [],
                                   Receipt $receipt = null): string;

    /**
     * Payment system need form
     * You can not get url for redirect
     *
     * @return bool
     */
    public function needForm(): bool;

    /**
     * Generate payment form
     *
     * @param mixed   $orderId
     * @param mixed   $paymentId
     * @param float   $amount
     * @param string  $currency
     * @param string  $paymentType
     * @param string  $successReturnUrl
     * @param string  $failReturnUrl
     * @param string  $description
     * @param array   $extraParams
     * @param Receipt $receipt
     *
     * @return Form
     */
    public function getPaymentForm($orderId,
                                   $paymentId,
                                   float $amount,
                                   string $currency = self::CURRENCY_RUR,
                                   string $paymentType = self::PAYMENT_TYPE_CARD,
                                   string $successReturnUrl = '',
                                   string $failReturnUrl = '',
                                   string $description = '',
                                   array $extraParams = [],
                                   Receipt $receipt = null): Form;

    /**
     * Validate request
     *
     * @param array $data
     *
     * @return bool
     */
    public function validate(array $data): bool;

    /**
     * Parse notification
     *
     * @param array $data
     *
     * @return $this
     */
    public function setResponse(array $data): self;

    /**
     * Get order ID
     *
     * @return string
     */
    public function getOrderId(): string;

    /**
     * Get payment id
     *
     * @return string
     */
    public function getPaymentId(): string;

    /**
     * Get operation status
     *
     * @return string
     */
    public function getStatus(): string;

    /**
     * Is payment succeed
     *
     * @return bool
     */
    public function isSuccess(): bool;

    /**
     * Get transaction ID
     *
     * @return string
     */
    public function getTransactionId(): string;

    /**
     * Get transaction amount
     *
     * @return float
     */
    public function getAmount(): float;

    /**
     * Get error code
     *
     * @return string
     */
    public function getErrorCode(): string;

    /**
     * Get payment provider
     *
     * @return string
     */
    public function getProvider(): string;

    /**
     * Get PAN
     *
     * @return string
     */
    public function getPan(): string;

    /**
     * Get payment datetime
     *
     * @return string
     */
    public function getDateTime(): string;

    /**
     * Get payment currency
     *
     * @return string
     */
    public function getCurrency(): string;

    /**
     * Get card type. Visa, MC etc
     *
     * @return string
     */
    public function getCardType(): string;

    /**
     * Get card expiration date
     *
     * @return string
     */
    public function getCardExpDate(): string;

    /**
     * Get cardholder name
     *
     * @return string
     */
    public function getCardUserName(): string;

    /**
     * Get card issuer
     *
     * @return string
     */
    public function getIssuer(): string;

    /**
     * Get e-mail
     *
     * @return string
     */
    public function getEmail(): string;

    /**
     * Get payment type. "GooglePay" for example
     *
     * @return string
     */
    public function getPaymentType(): string;

    /**
     * Set transport/protocol wrapper
     *
     * @param PayProtocol $protocol
     *
     * @return $this
     */
    public function setTransport(PayProtocol $protocol): self;

    /**
     * Prepare response on notification request
     *
     * @param int $errorCode
     *
     * @return Response
     */
    public function getNotificationResponse(int $errorCode = null): Response;

    /**
     * Prepare response on check request
     *
     * @param int $errorCode
     *
     * @return Response
     */
    public function getCheckResponse(int $errorCode = null): Response;

    /**
     * Get last error code
     *
     * @return int
     */
    public function getLastError(): int;

    /**
     * Get param by name
     *
     * @param string $name
     *
     * @return mixed
     */
    public function getParam(string $name);

    /**
     * Get pay service options
     *
     * @return array
     */
    public static function getOptions(): array;
}