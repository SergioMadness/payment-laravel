<?php namespace professionalweb\payment\contracts;

use Illuminate\Contracts\Support\Arrayable;

/**
 * Interface for receipt item
 * @package professionalweb\payment\contracts
 */
interface ReceiptItem extends Arrayable
{
    /**
     * Get quantity
     *
     * @return int
     */
    public function getQty(): int;

    /**
     * Get price
     *
     * @return float
     */
    public function getPrice(): float;

    /**
     * Get currency
     *
     * @return string
     */
    public function getCurrency(): string;

    /**
     * Get tax
     *
     * @return mixed
     */
    public function getTax();

    /**
     * Get item name
     *
     * @return string
     */
    public function getName(): string;
}