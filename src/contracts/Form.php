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
    public function render(): string;

    /**
     * Render fields
     *
     * @return string
     */
    public function renderFields(): string;

    /**
     * Form action
     *
     * @return string
     */
    public function getAction(): string;

    /**
     * Get form method
     *
     * @return string
     */
    public function getMethod(): string;
}