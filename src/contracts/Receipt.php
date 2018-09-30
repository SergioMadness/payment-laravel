<?php namespace professionalweb\payment\contracts;

use Illuminate\Contracts\Support\Arrayable;

/**
 * Interface for receipt
 * @package professionalweb\payment\contracts
 */
interface Receipt extends Arrayable
{
    /**
     * Get contact
     *
     * @return string
     */
    public function getContact(): string;

    /**
     * Get tax system
     *
     * @return int
     */
    public function getTaxSystem(): int;

    /**
     * Get all items in receipt
     *
     * @return ReceiptItem[]
     */
    public function getItems(): array;
}