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
}