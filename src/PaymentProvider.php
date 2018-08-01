<?php namespace professionalweb\payment;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;
use professionalweb\payment\contracts\PaymentFacade;

/**
 * Facade for providers
 * @package professionalweb\payment
 */
class PaymentProvider extends ServiceProvider
{

    protected $defer = true;

    public function boot()
    {
        $loader = AliasLoader::getInstance();
        $loader->alias('Payment', Payment::class);
    }

    /**
     * Bind two classes
     *
     * @return void
     */
    public function register()
    {
        $facade = new Payment();
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
        return ['\Payment', PaymentFacade::class];
    }
}