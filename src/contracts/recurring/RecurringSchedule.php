<?php namespace professionalweb\payment\contracts\recurring;

/**
 * Interface for schedule
 * @package professionalweb\payment\contracts\recurring
 */
interface RecurringSchedule
{
    /**
     * Set payment token
     *
     * @param string $token
     *
     * @return RecurringSchedule
     */
    public function setToken(string $token): self;

    /**
     * Set payment description
     *
     * @param string $description
     *
     * @return RecurringSchedule
     */
    public function setDescription(string $description): self;

    /**
     * Set user's e-mail
     *
     * @param string $email
     *
     * @return RecurringSchedule
     */
    public function setEmail(string $email): self;

    /**
     * Set payment amount
     *
     * @param float $amount
     *
     * @return RecurringSchedule
     */
    public function setAmount(float $amount): self;

    /**
     * Set payment currency
     *
     * @param string $currency
     *
     * @return RecurringSchedule
     */
    public function setCurrency(string $currency): self;

    /**
     * Set account id
     *
     * @param string $id
     *
     * @return RecurringSchedule
     */
    public function setAccountId(string $id): self;

    /**
     * Set payment need confirmation
     *
     * @param bool $flag
     *
     * @return RecurringSchedule
     */
    public function needConfirmation(bool $flag = true): self;

    /**
     * Set date of first payment
     *
     * @param string $startDate
     *
     * @return RecurringSchedule
     */
    public function setStartDate(string $startDate): self;

    /**
     * Max payment quantity
     *
     * @param int $qty
     *
     * @return RecurringSchedule
     */
    public function setMaxPayments(int $qty): self;

    /**
     * Process payment daily
     *
     * @return RecurringSchedule
     */
    public function daily(): self;

    /**
     * Process payment weekly
     *
     * @return RecurringSchedule
     */
    public function weekly(): self;

    /**
     * Process payment every month
     *
     * @return RecurringSchedule
     */
    public function monthly(): self;

    /**
     * Process payment every year
     *
     * @return RecurringSchedule
     */
    public function yearly(): self;

    /**
     * Process payment every $days days
     *
     * @param int $days
     *
     * @return RecurringSchedule
     */
    public function every(int $days): self;

    /**
     * Get schedule id
     *
     * @return string
     */
    public function getId(): string;

    /**
     * Get account id
     *
     * @return string
     */
    public function getAccountId(): string;

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription(): string;

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail(): string;

    /**
     * Get payment amount
     *
     * @return float
     */
    public function getAmount(): float;

    /**
     * Get currency
     *
     * @return string
     */
    public function getCurrency(): string;

    /**
     * Check confirmation needed
     *
     * @return bool
     */
    public function isNeedConfirmation(): bool;

    /**
     * Get first payment date
     *
     * @return string
     */
    public function getStartDate(): string;

    /**
     * Get payment interval
     *
     * @return string
     */
    public function getInterval(): string;

    /**
     * Check schedule is active
     *
     * @return bool
     */
    public function isActive(): bool;
}