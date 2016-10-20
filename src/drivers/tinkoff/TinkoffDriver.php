<?php namespace professionalweb\payment\drivers\tinkoff;

require_once 'TinkoffMerchantAPI.php';

use Alcohol\ISO4217;
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
     * @var \TinkoffMerchantAPI
     */
    private $tinkoffClass;

    /**
     * Module config
     *
     * @var array
     */
    private $config;

    public function __construct($config)
    {
        $this->setConfig($config)->setTinkoffClass(new \TinkoffMerchantAPI($config['merchantId'], $config['secretKey'], $config['apiUrl']));
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
        $driver = $this->getTinkoffClass();
        $driver->init($data);

        if ($driver->error != '') {
            throw new \HttpException($driver->error);
        }

        return $driver->paymentUrl;
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

        if (isset($data['Token']) && $this->getTinkoffClass()->genToken($data) == $data['Token']) {
            $result = true;
        }

        return $result;
    }

    /**
     * Get TinkoffMerchantAPI object
     *
     * @return \TinkoffMerchantAPI
     */
    public function getTinkoffClass()
    {
        return $this->tinkoffClass;
    }

    /**
     * Set TinkoffMerchantAPI object
     *
     * @param \TinkoffMerchantAPI $tinkoff
     *
     * @return $this
     */
    public function setTinkoffClass($tinkoff)
    {
        $this->tinkoffClass = $tinkoff;

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

}