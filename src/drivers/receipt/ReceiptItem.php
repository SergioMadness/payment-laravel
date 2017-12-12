<?php namespace professionalweb\payment\drivers\receipt;

use Illuminate\Contracts\Support\Arrayable;

abstract class ReceiptItem implements Arrayable
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
     * @param int $qty
     * @param float $price
     * @param int $tax
     * @param string $currency
     */
    public function __construct($name = null, $qty = null, $price = null, $tax = null, $currency = 'RUR')
    {
        $this->setName($name)->setQty($qty)->setPrice($price)->setTax($tax)->setCurrency($currency);
    }

    /**
     * Get quantity
     *
     * @return int
     */
    public function getQty()
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
    public function setQty($qty)
    {
        $this->qty = $qty;

        return $this;
    }

    /**
     * Get price
     *
     * @return float
     */
    public function getPrice()
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
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get currency
     *
     * @return string
     */
    public function getCurrency()
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
    public function setCurrency($currency)
    {
        $this->currency = $currency;

        return $this;
    }

    /**
     * Get tax
     *
     * @return int
     */
    public function getTax()
    {
        return $this->tax;
    }

    /**
     * Set tax
     *
     * @param int $tax
     *
     * @return $this
     */
    public function setTax($tax)
    {
        $this->tax = $tax;

        return $this;
    }

    /**
     * Get item name
     *
     * @return string
     */
    public function getName()
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
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }
}