<?php namespace professionalweb\payment\contracts\recurring;

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