<?php namespace professionalweb\payment\contracts\recurring;

/**
 * Interface for payment systems has their own schedule for recurring payments
 * @package professionalweb\payment\contracts\recurring
 */
interface RecurringPaymentSchedule
{
    /**
     * Create schedule
     *
     * @return RecurringSchedule
     */
    public function schedule(): RecurringSchedule;

    /**
     * Create schedule.
     *
     * @param RecurringSchedule|null $schedule
     *
     * @return string Schedule id/token
     */
    public function saveSchedule(RecurringSchedule $schedule): string;

    /**
     * Remove schedule
     *
     * @param string $token
     *
     * @return bool
     */
    public function removeSchedule(string $token): bool;

    /**
     * Get schedule by id
     *
     * @param string $id
     *
     * @return RecurringSchedule
     */
    public function getSchedule(string $id): RecurringSchedule;

    /**
     * Get list of schedules
     *
     * @param string|null $accountId
     *
     * @return array|[]RecurringSchedule
     */
    public function getAllSchedules(string $accountId = null): array;
}