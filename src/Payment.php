<?php namespace professionalweb\payment;

use Illuminate\Http\Response;
use professionalweb\payment\contracts\Form;
use professionalweb\payment\contracts\Receipt;
use professionalweb\payment\contracts\PayService;
use professionalweb\payment\contracts\PayProtocol;
use professionalweb\payment\contracts\PaymentFacade;

/**
 * Payment facade
 * @package professionalweb\payment
 */
class Payment implements PaymentFacade
{
    /**
     * Available drivers
     *
     * @var array
     */
    private $drivers = [];

    /**
     * Driver options
     *
     * @var array
     */
    private $driverOptions = [];

    /**
     * Current driver
     *
     * @var PayService
     */
    private $currentDriver;

    /**
     * Name of current driver
     *
     * @var string
     */
    private $currentDriverName;

    /**
     * Pay
     *
     * @param int     $orderId
     * @param int     $paymentId
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
                                   Receipt $receipt = null): string
    {
        return $this->getCurrentDriver()->getPaymentLink($orderId,
            $paymentId,
            $amount,
            $currency,
            $paymentType,
            $successReturnUrl,
            $failReturnUrl,
            $description,
            $extraParams,
            $receipt);
    }

    /**
     * Validate request
     *
     * @param mixed $data
     *
     * @return bool
     */
    public function validate(array $data): bool
    {
        return $this->getCurrentDriver()->validate($data);
    }

    /**
     * Add driver
     *
     * @param string $name
     * @param string $className
     *
     * @return $this
     */
    public function addDriver(string $name, string $className): self
    {
        $this->drivers[$name] = $className;

        return $this;
    }

    /**
     * Set drivers
     *
     * @param array $drivers
     *
     * @return $this
     */
    public function setDrivers(array $drivers): self
    {
        $this->drivers = $drivers;

        return $this;
    }

    /**
     * Get drivers
     *
     * @return array
     */
    public function getDrivers(): array
    {
        return $this->drivers;
    }

    /**
     * Get driver by name
     *
     * @param string $name
     *
     * @return null|string
     */
    public function getDriver($name): ?string
    {
        return $this->getDrivers()[$name];
    }

    /**
     * Set current driver
     *
     * @param string $name
     *
     * @return $this
     */
    public function setCurrentDriver(string $name): PayService
    {
        $this->currentDriverName = $name;

        $this->makeCurrentDriver($name);

        return $this;
    }

    /**
     * Build driver
     *
     * @param string $name
     *
     * @return $this
     */
    protected function makeCurrentDriver(string $name): PayService
    {
        $this->currentDriver = app($this->getDriver($name), [
            'config' => config('payment.' . $name),
        ]);

        return $this;
    }

    /**
     * Get current driver name
     *
     * @return PayService
     */
    public function getCurrentDriver(): PayService
    {
        return $this->currentDriver;
    }

    /**
     * Get name of current driver
     *
     * @return string
     */
    public function getDriverName(): string
    {
        return $this->currentDriverName;
    }

    /**
     * Alias for setCurrentDriver
     *
     * @param string $name
     *
     * @return PayService
     */
    public function driver($name): PayService
    {
        return $this->setCurrentDriver($name);
    }

    /**
     * Parse notification
     *
     * @param array $data
     *
     * @return $this
     */
    public function setResponse(array $data): PayService
    {
        return $this->getCurrentDriver()->setResponse($data);
    }

    /**
     * Get order ID
     *
     * @return string
     */
    public function getOrderId(): string
    {
        return $this->getCurrentDriver()->getOrderId();
    }

    /**
     * Get operation status
     *
     * @return string
     */
    public function getStatus(): string
    {
        return $this->getCurrentDriver()->getStatus();
    }

    /**
     * Is payment succeed
     *
     * @return bool
     */
    public function isSuccess(): bool
    {
        return $this->getCurrentDriver()->isSuccess();
    }

    /**
     * Get transaction ID
     *
     * @return string
     */
    public function getTransactionId(): string
    {
        return $this->getCurrentDriver()->getTransactionId();
    }

    /**
     * Get transaction amount
     *
     * @return float
     */
    public function getAmount(): float
    {
        return $this->getCurrentDriver()->getAmount();
    }

    /**
     * Get error code
     *
     * @return string
     */
    public function getErrorCode(): string
    {
        return $this->getCurrentDriver()->getErrorCode();
    }

    /**
     * Get payment provider
     *
     * @return string
     */
    public function getProvider(): string
    {
        return $this->getCurrentDriver()->getProvider();
    }

    /**
     * Get PAn
     *
     * @return string
     */
    public function getPan(): string
    {
        return $this->getCurrentDriver()->getPan();
    }

    /**
     * Get payment datetime
     *
     * @return string
     */
    public function getDateTime(): string
    {
        return $this->getCurrentDriver()->getDateTime();
    }

    /**
     * Set transport/protocol wrapper
     *
     * @param PayProtocol $protocol
     *
     * @return $this
     */
    public function setTransport(PayProtocol $protocol): PayService
    {
        return $this;
    }

    /**
     * Prepare response on notification request
     *
     * @param int $errorCode
     *
     * @return Response
     */
    public function getNotificationResponse(int $errorCode = null): Response
    {
        return $this->getCurrentDriver()->getNotificationResponse($errorCode);
    }

    /**
     * Prepare response on check request
     *
     * @param int $errorCode
     *
     * @return Response
     */
    public function getCheckResponse(int $errorCode = null): Response
    {
        return $this->getCurrentDriver()->getCheckResponse($errorCode);
    }

    /**
     * Get last error code
     *
     * @return int
     */
    public function getLastError(): int
    {
        return $this->getCurrentDriver()->getLastError();
    }

    /**
     * Get param by name
     *
     * @param string $name
     *
     * @return mixed
     */
    public function getParam(string $name)
    {
        return $this->getCurrentDriver()->getParam($name);
    }

    /**
     * Get name of payment service
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->getCurrentDriver()->getName();
    }

    /**
     * Get payment id
     *
     * @return string
     */
    public function getPaymentId(): string
    {
        return $this->getCurrentDriver()->getPaymentId();
    }

    /**
     * Register driver
     *
     * @param string $alias
     * @param string $className
     *
     * @param array  $options
     *
     * @return PayService
     */
    public function registerDriver(string $alias, string $className, array $options = []): PayService
    {
        return $this->addDriver($alias, $className)->addDriverOptions($alias, $options);
    }

    /**
     * Set options for driver
     *
     * @param string $alias
     * @param array  $options
     *
     * @return PayService
     */
    protected function addDriverOptions(string $alias, array $options): PayService
    {
        $this->driverOptions[$alias] = $options;

        return $this;
    }

    /**
     * Get available drivers
     *
     * @return array
     */
    public function drivers(): array
    {
        return $this->drivers;
    }

    /**
     * Payment system need form
     * You can not get url for redirect
     *
     * @return bool
     */
    public function needForm(): bool
    {
        return $this->getCurrentDriver()->needForm();
    }

    /**
     * Generate payment form
     *
     * @param int     $orderId
     * @param int     $paymentId
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
                                   Receipt $receipt = null): Form
    {
        return $this->getCurrentDriver()->getPaymentForm($orderId,
            $paymentId,
            $amount,
            $currency,
            $paymentType,
            $successReturnUrl,
            $failReturnUrl,
            $description,
            $extraParams,
            $receipt);
    }

    /**
     * Get pay service options
     *
     * @return array
     */
    public static function getOptions(): array
    {
        return app(self::class)->getDriverOptions();
    }

    /**
     * Build driber by name
     *
     * @param string $driver
     *
     * @return null|PayService
     */
    public function driverInstance(string $driver): ?PayService
    {
        if (($driverClass = $this->getDriver($driver)) !== null) {
            return app($driverClass, [
                'config' => config('payment.' . $driverClass),
            ]);
        }

        return null;
    }

    /**
     * Get driver options
     *
     * @param string|null $driver
     *
     * @return array
     */
    public function getDriverOptions(string $driver = null): array
    {
        return $this->driverOptions[$driver] ?? [];
    }

    /**
     * Get payment currency
     *
     * @return string
     */
    public function getCurrency(): string
    {
        return $this->getCurrentDriver()->getCurrency();
    }

    /**
     * Get card type. Visa, MC etc
     *
     * @return string
     */
    public function getCardType(): string
    {
        return $this->getCurrentDriver()->getCardType();
    }

    /**
     * Get card expiration date
     *
     * @return string
     */
    public function getCardExpDate(): string
    {
        return $this->getCurrentDriver()->getCardExpDate();
    }

    /**
     * Get cardholder name
     *
     * @return string
     */
    public function getCardUserName(): string
    {
        return $this->getCurrentDriver()->getCardUserName();
    }

    /**
     * Get card issuer
     *
     * @return string
     */
    public function getIssuer(): string
    {
        return $this->getCurrentDriver()->getIssuer();
    }

    /**
     * Get e-mail
     *
     * @return string
     */
    public function getEmail(): string
    {
        return $this->getCurrentDriver()->getEmail();
    }

    /**
     * Get payment type. "GooglePay" for example
     *
     * @return string
     */
    public function getPaymentType(): string
    {
        return $this->getCurrentDriver()->getPaymentType();
    }
}