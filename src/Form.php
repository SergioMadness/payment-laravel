<?php namespace professionalweb\payment;

use professionalweb\payment\contracts\Form as IForm;

/**
 * Class that renders form
 * @package professionalweb\payment
 */
class Form implements IForm
{
    /**
     * URL to send form
     *
     * @var string
     */
    private $url;

    /**
     * Request method
     *
     * @var string
     */
    private $method = 'post';

    /**
     * Fields
     *
     * @var array
     */
    private $fields = [];

    public function __construct($url = '', $method = 'post')
    {
        $this->setUrl($url)->setMethod($method);
    }

    /**
     * Set url for form
     *
     * @param string $url
     *
     * @return $this
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Set method for form
     *
     * @param string $method
     *
     * @return $this
     */
    public function setMethod($method)
    {
        $this->method = $method;

        return $this;
    }

    /**
     * Add field
     *
     * @param string $name
     * @param string $value
     *
     * @return $this
     */
    public function addField($name, $value = '')
    {
        $this->fields[$name] = $value;

        return $this;
    }

    /**
     * Set fields
     *
     * @param array $fields
     *
     * @return $this
     */
    public function setField(array $fields)
    {
        $this->fields = $fields;

        return $this;
    }

    /**
     * Render form
     *
     * @return string
     */
    public function render()
    {
        $result = '<form method="' . $this->getMethod() . '" action="' . $this->getAction() . '">' . "\r\n";

        $result .= $this->renderFields();

        $result .= '</form>' . "\r\n";

        return $result;
    }

    /**
     * Render fields
     *
     * @return string
     */
    public function renderFields()
    {
        $result = '';
        foreach ($this->fields as $field => $value) {
            $result .= '<input type="hidden" name="' . $field . '" value="' . $value . '"/>' . "\r\n";
        }

        return $result;
    }

    /**
     * Form action
     *
     * @return string
     */
    public function getAction()
    {
        return $this->url;
    }

    /**
     * Get form method
     *
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }
}