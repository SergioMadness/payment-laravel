<?php namespace professionalweb\payonline;

use Illuminate\Support\ServiceProvider;
use professionalweb\payonline\contracts\PayService;
use professionalweb\payonline\drivers\PayOnlineDriver;

/**
 * PayOnline payment provider
 * @package professionalweb\payment
 */
class PaymentProvider extends ServiceProvider
{
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
            return new PayOnlineDriver(config('payonline'));
        });
        $this->app->singleton(PayOnlineDriver::class, function ($app) {
            return new PayOnlineDriver(config('payonline'));
        });
        $this->app->singleton('\professionalweb\payonline\PayOnline', function ($app) {
            return new PayOnlineDriver(config('payonline'));
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [PayService::class, PayOnlineDriver::class, '\professionalweb\payonline\PayOnline'];
    }
}