<?php namespace professionalweb\payment;

use Illuminate\Support\ServiceProvider;
use professionalweb\payment\contracts\PayService;
use professionalweb\payment\drivers\payonline\PayOnlineDriver;
use professionalweb\payment\drivers\tinkoff\TinkoffDriver;

class PaymentProvider extends ServiceProvider
{
    const PAYMENT_TINKOFF = 'tinkoff';
    const PAYMENT_PAYONLINE = 'payonline';

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Bind two classes
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(PayService::class, function ($app) {
            return $this->getFacade();
        });
        $this->app->bind('\Payment', function ($app) {
            return $this->getFacade();
        });
    }

    protected function getFacade()
    {
        return (new Payment())->setDrivers([
            self::PAYMENT_TINKOFF   => TinkoffDriver::class,
            self::PAYMENT_PAYONLINE => PayOnlineDriver::class,
        ])->setCurrentDriver(config('payment.default_driver'));
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['\Payment', PayService::class];
    }
}