<?php namespace professionalweb\payment\contracts;

/**
 * Interface for receipt sender
 * @package professionalweb\payment\contracts
 */
interface ReceiptService
{
    /**
     * Send receipt
     *
     * @param Receipt $receipt
     *
     * @return mixed
     */
    public function sendReceipt(Receipt $receipt);
}