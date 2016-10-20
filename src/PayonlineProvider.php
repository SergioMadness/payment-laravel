<?php namespace professionalweb\payment;

use Illuminate\Support\ServiceProvider;
use professionalweb\payment\contracts\PayService;
use professionalweb\payment\drivers\payonline\PayOnlineDriver;

/**
 * PayOnline payment provider
 * @package professionalweb\payment
 */
class PayOnlineProvider extends ServiceProvider
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
            return new PayOnlineDriver(config('payment.payonline'));
        });
        $this->app->singleton(PayOnlineDriver::class, function ($app) {
            return new PayOnlineDriver(config('payment.payonline'));
        });
        $this->app->singleton('\professionalweb\payment\PayOnline', function ($app) {
            return new PayOnlineDriver(config('payment.payonline'));
        });
        $this->app->bind('\Payment', Payment::class);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [PayService::class, PayOnlineDriver::class, '\professionalweb\payment\PayOnline', '\Payment'];
    }
}