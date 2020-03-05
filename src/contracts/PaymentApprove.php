<?php namespace professionalweb\payment\contracts;

/**
 * Interface for payment service needs approvement
 * @package professionalweb\payment\contracts
 */
interface PaymentApprove
{
    public const STATUS_APPROVED = 'approved';

    public const STATUS_DECLINED = 'declined';

    public const STATUS_PENDING = 'pending';

    /**
     * Approve transaction by id
     *
     * @param string $id
     *
     * @return bool
     */
    public function approveTransaction($id): bool;

    /**
     * Get transaction status
     *
     * @param string $id
     *
     * @return string
     */
    public function getTransactionStatus($id): string;
}