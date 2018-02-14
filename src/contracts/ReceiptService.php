<?php namespace professionalweb\payment\contracts;

use Illuminate\Contracts\Support\Arrayable;

/**
 * Interface for receipt sender
 * @package professionalweb\payment\contracts
 */
interface ReceiptService
{
    /**
     * Send receipt
     *
     * @param Arrayable $receipt
     *
     * @return mixed
     */
    public function sendReceipt(Arrayable $receipt);
}