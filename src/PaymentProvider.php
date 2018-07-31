<?php namespace professionalweb\payment;

use Illuminate\Support\ServiceProvider;
use professionalweb\payment\contracts\PayService;
use professionalweb\payment\contracts\PaymentFacade;

/**
 * Facade for providers
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
        $facade = new Payment();
        $this->app->instance(PayService::class, $facade);
        $this->app->instance('\Payment', $facade);
        $this->app->instance(PaymentFacade::class, $facade);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['\Payment', PayService::class, PaymentFacade::class];
    }
}