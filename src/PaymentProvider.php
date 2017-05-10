<?php namespace professionalweb\payment;

use Illuminate\Support\ServiceProvider;
use professionalweb\payment\contracts\PayService;
use professionalweb\payment\drivers\yandex\YandexDriver;
use professionalweb\payment\drivers\tinkoff\TinkoffDriver;
use professionalweb\payment\drivers\payonline\PayOnlineDriver;

/**
 * Facade for providers
 * @package professionalweb\payment
 */
class PaymentProvider extends ServiceProvider
{
    const PAYMENT_TINKOFF = 'tinkoff';
    const PAYMENT_PAYONLINE = 'payonline';
    const PAYMENT_YANDEX = 'yandex';

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
        (new PayOnlineProvider($this->app))->register();
        (new TinkoffProvider($this->app))->register();
        (new YandexProvider($this->app))->register();

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
            self::PAYMENT_YANDEX    => YandexDriver::class,
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