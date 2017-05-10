<?php namespace professionalweb\payment;

use Illuminate\Support\Facades\Facade;
use professionalweb\payment\contracts\PayProtocol;
use professionalweb\payment\contracts\PayService;

/**
 * Payment facade
 * @package professionalweb\payment
 */
class Payment extends Facade implements PayService
{
    /**
     * Available drivers
     *
     * @var array
     */
    private $drivers = [];

    /**
     * Current driver
     *
     * @var PayService
     */
    private $currentDriver;

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'payment';
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
        return $this->getCurrentDriver()->getPaymentLink($orderId,
            $paymentId,
            $amount,
            $currency,
            $successReturnUrl,
            $failReturnUrl,
            $description);
    }

    /**
     * Validate request
     *
     * @param mixed $data
     *
     * @return bool
     */
    public function validate($data)
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
    public function addDriver($name, $className)
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
    public function setDrivers(array $drivers)
    {
        $this->drivers = $drivers;

        return $this;
    }

    /**
     * Get drivers
     *
     * @return array
     */
    public function getDrivers()
    {
        return $this->drivers;
    }

    /**
     * Get driver by name
     *
     * @param string $name
     *
     * @return mixed
     */
    public function getDriver($name)
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
    public function setCurrentDriver($name)
    {
        $this->currentDriver = \App::make($this->getDriver($name), [
            'config' => config('payment.' . $name),
        ]);

        return $this;
    }

    /**
     * Get current driver name
     *
     * @return PayService
     */
    public function getCurrentDriver()
    {
        return $this->currentDriver;
    }

    /**
     * Alias for setCurrentDriver
     *
     * @param string $name
     *
     * @return Payment
     */
    public function driver($name)
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
    public function setResponse($data)
    {
        return $this->getCurrentDriver()->setResponse($data);
    }

    /**
     * Get order ID
     *
     * @return string
     */
    public function getOrderId()
    {
        return $this->getCurrentDriver()->getOrderId();
    }

    /**
     * Get operation status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->getCurrentDriver()->getStatus();
    }

    /**
     * Is payment succeed
     *
     * @return bool
     */
    public function isSuccess()
    {
        return $this->getCurrentDriver()->isSuccess();
    }

    /**
     * Get transaction ID
     *
     * @return string
     */
    public function getTransactionId()
    {
        return $this->getCurrentDriver()->getTransactionId();
    }

    /**
     * Get transaction amount
     *
     * @return float
     */
    public function getAmount()
    {
        return $this->getCurrentDriver()->getAmount();
    }

    /**
     * Get error code
     *
     * @return int
     */
    public function getErrorCode()
    {
        return $this->getCurrentDriver()->getErrorCode();
    }

    /**
     * Get payment provider
     *
     * @return string
     */
    public function getProvider()
    {
        return $this->getCurrentDriver()->getProvider();
    }

    /**
     * Get PAn
     *
     * @return string
     */
    public function getPan()
    {
        return $this->getCurrentDriver()->getPan();
    }

    /**
     * Get payment datetime
     *
     * @return string
     */
    public function getDateTime()
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
    public function setTransport(PayProtocol $protocol)
    {
        return $this;
    }

    /**
     * Prepare response on notification request
     *
     * @param int $errorCode
     *
     * @return string
     */
    public function getNotificationResponse($errorCode = 0)
    {
        return $this->getCurrentDriver()->getNotificationResponse($errorCode);
    }

    /**
     * Prepare response on check request
     *
     * @param int $errorCode
     *
     * @return string
     */
    public function getCheckResponse($errorCode = 0)
    {
        return $this->getCurrentDriver()->getNotificationResponse($errorCode);
    }

    /**
     * Get last error code
     *
     * @return int
     */
    public function getLastError()
    {
        $this->getCurrentDriver()->getLastError();
    }
}