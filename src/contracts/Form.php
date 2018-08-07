<?php namespace professionalweb\payment\contracts;

/**
 * Interface for class renders form
 * @package professionalweb\payment\contracts
 */
interface Form
{
    /**
     * Render form
     *
     * @return string
     */
    public function render();
}