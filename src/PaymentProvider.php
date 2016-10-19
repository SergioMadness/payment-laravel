<?php namespace professionalweb\payment;

use Illuminate\Support\ServiceProvider;

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
        switch (config('payment.default_driver')) {
            case self::PAYMENT_TINKOFF :
                (new TinkoffProvider($this->app))->register();
                break;
            case self::PAYMENT_PAYONLINE:
                (new PayOnlineProvider($this->app))->register();
                break;
        }
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        switch (config('payment.default_driver')) {
            case self::PAYMENT_TINKOFF :
                return (new TinkoffProvider($this->app))->provides();
            case self::PAYMENT_PAYONLINE:
                return (new PayOnlineProvider($this->app))->provides();
        }
    }
}