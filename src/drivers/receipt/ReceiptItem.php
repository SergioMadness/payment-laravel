<?php namespace professionalweb\payment\drivers\receipt;

use professionalweb\payment\contracts\ReceiptItem as IReceiptItem;

abstract class ReceiptItem implements IReceiptItem
{
    /**
     * Quantity
     *
     * @var int
     */
    private $qty;

    /**
     * Item price
     *
     * @var float
     */
    private $price;

    /**
     * Currency
     *
     * @var string
     */
    private $currency;

    /**
     * Tax
     * Ставка НДС
     *
     * @var int
     */
    private $tax;

    /**
     * Item name
     *
     * @var string
     */
    private $name;

    /**
     * ReceiptItem constructor.
     *
     * @param string $name
     * @param int    $qty
     * @param float  $price
     * @param mixed    $tax
     * @param string $currency
     */
    public function __construct(string $name = null, int $qty = null, float $price = null, $tax = null, string $currency = 'RUB')
    {
        $this->setName($name)->setQty($qty)->setPrice($price)->setTax($tax)->setCurrency($currency);
    }

    /**
     * Get quantity
     *
     * @return int
     */
    public function getQty(): int
    {
        return $this->qty;
    }

    /**
     * Set quantity
     *
     * @param int $qty
     *
     * @return $this
     */
    public function setQty(int $qty): self
    {
        $this->qty = $qty;

        return $this;
    }

    /**
     * Get price
     *
     * @return float
     */
    public function getPrice(): float
    {
        return $this->price;
    }

    /**
     * Set price
     *
     * @param float $price
     *
     * @return $this
     */
    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get currency
     *
     * @return string
     */
    public function getCurrency(): string
    {
        return $this->currency;
    }

    /**
     * Set currency
     *
     * @param string $currency
     *
     * @return $this
     */
    public function setCurrency(string $currency): self
    {
        $this->currency = $currency;

        return $this;
    }

    /**
     * Get tax
     *
     * @return mixed
     */
    public function getTax()
    {
        return $this->tax;
    }

    /**
     * Set tax
     *
     * @param mixed $tax
     *
     * @return $this
     */
    public function setTax($tax): self
    {
        $this->tax = $tax;

        return $this;
    }

    /**
     * Get item name
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Set item name
     *
     * @param string $name
     *
     * @return $this
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }
}