<?php namespace professionalweb\payment;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;
use professionalweb\payment\contracts\PaymentFacade;
use professionalweb\payment\facades\Payment as LPaymenFacade;

/**
 * Facade for providers
 * @package professionalweb\payment
 */
class PaymentProvider extends ServiceProvider
{

    public function boot(): void
    {
        $this->app->alias('Payment', LPaymenFacade::class);
    }

    /**
     * Bind two classes
     *
     * @return void
     */
    public function register(): void
    {
        $facade = new Payment();
        $this->app->instance('\Payment', $facade);
        $this->app->instance(PaymentFacade::class, $facade);
    }
}