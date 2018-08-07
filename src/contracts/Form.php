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

    /**
     * Render fields
     *
     * @return string
     */
    public function renderFields();

    /**
     * Form action
     *
     * @return string
     */
    public function getAction();

    /**
     * Get form method
     *
     * @return string
     */
    public function getMethod();
}