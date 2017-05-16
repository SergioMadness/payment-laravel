<?php namespace professionalweb\payment\contracts;

/**
 * Interface of payment facade
 * @package professionalweb\payment\contracts
 */
interface PaymentFacade extends PayService
{
    /**
     * Set current driver
     *
     * @param string $name
     *
     * @return $this
     */
    public function setCurrentDriver($name);

    /**
     * Get current driver name
     *
     * @return PayService
     */
    public function getCurrentDriver();

    /**
     * Get name of current driver
     *
     * @return string
     */
    public function getDriverName();
}