<?php namespace professionalweb\payment\drivers\receipt;

use Illuminate\Contracts\Support\Arrayable;

abstract class Receipt implements Arrayable
{
    /**
     * Phone number or e-mail
     *
     * @var string
     */
    private $contact;

    /**
     * Tax system
     * Система налогообложения магазина (СНО). Параметр необходим, только если у вас несколько систем налогообложения. В остальных случаях не передается.
     *
     * @var int
     */
    private $taxSystem;

    /**
     * Items
     *
     * @var ReceiptItem[]
     */
    private $items = [];

    /**
     * Receipt constructor.
     *
     * @param string     $contact
     * @param array|null $items
     * @param int        $taxSystem
     */
    public function __construct($contact = null, array $items = [], $taxSystem = null)
    {
        $this->setContact($contact)->setItems($items)->setTaxSystem($taxSystem);
    }

    /**
     * Get contact
     *
     * @return string
     */
    public function getContact()
    {
        return $this->contact;
    }

    /**
     * Set contact
     *
     * @param string $contact
     *
     * @return $this
     */
    public function setContact($contact)
    {
        $this->contact = $contact;

        return $this;
    }

    /**
     * Get tax system
     *
     * @return int
     */
    public function getTaxSystem()
    {
        return $this->taxSystem;
    }

    /**
     * Set tax system
     *
     * @param int $taxSystem
     *
     * @return $this
     */
    public function setTaxSystem($taxSystem)
    {
        $this->taxSystem = $taxSystem;

        return $this;
    }

    /**
     * Get all items in receipt
     *
     * @return ReceiptItem[]
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * Set items in receipt
     *
     * @param ReceiptItem[] $items
     *
     * @return $this
     */
    public function setItems(array $items)
    {
        $this->items = $items;

        return $this;
    }

    /**
     * Add item
     *
     * @param ReceiptItem $item
     *
     * @return $this
     */
    public function addItem(ReceiptItem $item)
    {
        $this->items[] = $item;

        return $this;
    }
}