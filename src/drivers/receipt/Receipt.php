<?php namespace professionalweb\payment\drivers\receipt;

use professionalweb\payment\contracts\Receipt as IReceipt;

abstract class Receipt implements IReceipt
{
    /**
     * Phone number or e-mail
     *
     * @var string
     */
    private $contact;

    /**
     * Tax system
     * Система налогообложения магазина (СНО).
     * Параметр необходим, только если у вас несколько систем налогообложения.
     * В остальных случаях не передается.
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
    public function __construct(string $contact = null, array $items = [], int $taxSystem = null)
    {
        $this->setContact($contact)->setItems($items)->setTaxSystem($taxSystem);
    }

    /**
     * Get contact
     *
     * @return string
     */
    public function getContact(): string
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
    public function setContact(string $contact): self
    {
        $this->contact = $contact;

        return $this;
    }

    /**
     * Get tax system
     *
     * @return int
     */
    public function getTaxSystem(): int
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
    public function setTaxSystem(int $taxSystem): self
    {
        $this->taxSystem = $taxSystem;

        return $this;
    }

    /**
     * Get all items in receipt
     *
     * @return ReceiptItem[]
     */
    public function getItems(): array
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
    public function setItems(array $items): self
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
    public function addItem(ReceiptItem $item): self
    {
        $this->items[] = $item;

        return $this;
    }
}