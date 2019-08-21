<?php namespace professionalweb\payment\contracts\recurring;

use professionalweb\payment\contracts\PayService;

/**
 * Interface for payment systems have recurring payments
 * @package professionalweb\payment\contracts\recurring
 */
interface RecurringPayment
{
    /**
     * Get payment token
     *
     * @return string
     */
    public function getRecurringPayment(): string;

    /**
     * Initialize recurring payment
     *
     * @param string $token
     * @param string $accountId
     * @param float  $amount
     * @param string $description
     * @param string $currency
     *
     * @param array  $extraParams
     *
     * @return bool
     */
    public function initPayment(string $token, string $accountId, float $amount, string $description, string $currency = PayService::CURRENCY_RUR_ISO, array $extraParams = []): bool;

    /**
     * Remember payment fo recurring payments
     *
     * @return RecurringPayment
     */
    public function makeRecurring(): self;

    /**
     * Set user id payment will be assigned
     *
     * @param string $id
     *
     * @return RecurringPayment
     */
    public function setUserId(string $id): self;
}