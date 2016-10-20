<?php namespace professionalweb\payment;

use Illuminate\Support\Facades\Facade;
use professionalweb\payment\contracts\PayService;

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
     * @var string
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
        $currentDriver = $this->getCurrentDriver();
        return \App::make($this->getDriver($currentDriver), [
            'config' => config('payment.' . $currentDriver),
        ])->getPaymentLink($orderId,
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
        $currentDriver = $this->getCurrentDriver();

        return \App::make($this->getDriver($currentDriver), [
            'config' => config('payment.' . $currentDriver),
        ])->validate($data);
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
        $this->currentDriver = $name;

        return $this;
    }

    /**
     * Get current driver name
     *
     * @return string
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
}