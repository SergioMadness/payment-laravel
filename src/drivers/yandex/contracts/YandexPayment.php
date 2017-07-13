<?php namespace professionalweb\payment\drivers\yandex\contracts;

/**
 * Interface for Yandex Payment
 * @package professionalweb\payment\drivers\yandex\contracts
 */
interface YandexPayment
{
    /**
     * Get server info
     *
     * @return mixed
     */
    public function version();

    /**
     * Create application instance
     *
     * @return mixed
     */
    public function instance();

    /**
     * Digital Secure Remote Payment (DSRP)
     *
     * @param array $params
     *
     * @return mixed
     */
    public function drsp(array $params);
}