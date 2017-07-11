<?php namespace professionalweb\payment;

use Illuminate\Support\ServiceProvider;
use professionalweb\payment\contracts\ApplePayService;
use professionalweb\payment\contracts\PayService;
use professionalweb\payment\drivers\yandex\ApplePay;
use professionalweb\payment\drivers\yandex\YandexKassa;
use professionalweb\payment\drivers\yandex\YandexDriver;


/**
 * Yandex payment provider
 * @package professionalweb\payment
 */
class YandexProvider extends ServiceProvider
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
            return (new YandexDriver(config('payment.yandex')))->setTransport(
                new YandexKassa(config('payment.yandex.merchantId'), config('payment.yandex.scid'), config('payment.yandex.secretKey'), config('payment.yandex.isTest') ? YandexKassa::ESHOP_URL_DEMO : YandexKassa::ESHOP_URL_PROD)
            );
        });
        $this->app->singleton(YandexDriver::class, function ($app) {
            return (new YandexDriver(config('payment.yandex')))->setTransport(
                new YandexKassa(config('payment.yandex.merchantId'), config('payment.yandex.scid'), config('payment.yandex.secretKey'), config('payment.yandex.isTest') ? YandexKassa::ESHOP_URL_DEMO : YandexKassa::ESHOP_URL_PROD)
            );
        });
        $this->app->singleton('\professionalweb\payment\Yandex', function ($app) {
            return (new YandexDriver(config('payment.yandex')))->setTransport(
                new YandexKassa(config('payment.yandex.merchantId'), config('payment.yandex.scid'), config('payment.yandex.secretKey'), config('payment.yandex.isTest') ? YandexKassa::ESHOP_URL_DEMO : YandexKassa::ESHOP_URL_PROD)
            );
        });
        $this->app->singleton(ApplePayService::class, function ($app) {
            return (new ApplePay())
                ->setCertificatePath(\Config::get('payment.applePay.certificatePath'))
                ->setKeyPath(\Config::get('payment.applePay.keyPath'))
                ->setKeyPassword(\Config::get('payment.applePay.keyPassword'))
                ->setDomain(\Config::get('payment.applePay.domain'))
                ->setMerchantId(\Config::get('payment.applePay.merchantId'))
                ->setDisplayName(\Config::get('payment.applePay.displayName'));
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
        return [PayService::class, YandexDriver::class, '\professionalweb\payment\Yandex', '\Payment', ApplePayService::class];
    }
}