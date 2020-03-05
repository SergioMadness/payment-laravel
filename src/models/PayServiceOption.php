<?php namespace professionalweb\payment\models;

use professionalweb\payment\contracts\PayServiceOption as IPayServiceOption;

class PayServiceOption implements IPayServiceOption
{
    /**
     * @var string
     */
    private $alias;

    /**
     * @var string
     */
    private $label;

    /**
     * @var string
     */
    private $type;

    /**
     * Set alias
     *
     * @param string $alias
     *
     * @return PayServiceOption
     */
    public function setAlias(string $alias): self
    {
        $this->alias = $alias;

        return $this;
    }

    /**
     * Option alias
     *
     * @return string
     */
    public function getAlias(): string
    {
        return $this->alias;
    }

    /**
     * Set readable label
     *
     * @param string $label
     *
     * @return PayServiceOption
     */
    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    /**
     * Readable label
     *
     * @return string
     */
    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * Set field type
     *
     * @param string $type
     *
     * @return PayServiceOption
     */
    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get field type
     *
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'alias' => $this->getAlias(),
            'type'  => $this->getType(),
            'label' => $this->getLabel(),
        ];
    }
}