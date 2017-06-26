<?php namespace professionalweb\payment\drivers\yandex;

use Illuminate\Contracts\Support\Arrayable;

/**
 * Receipt
 * @package professionalweb\payment\drivers\yandex
 */
class Receipt implements Arrayable
{
    /**
     * общая СН
     */
    const TAX_SYSTEM_COMMON = 1;

    /**
     * упрощенная СН (доходы)
     */
    const TAX_SYSTEM_SIMPLE_INCOME = 2;

    /**
     * упрощенная СН (доходы минус расходы)
     */
    const TAX_SYSTEM_SIMPLE_NO_OUTCOME = 3;

    /**
     * единый налог на вмененный доход
     */
    const TAX_SYSTEM_SIMPLE_UNIFIED = 4;

    /**
     * единый сельскохозяйственный налог
     */
    const TAX_SYSTEM_SIMPLE_AGRO = 5;

    /**
     * патентная СН
     */
    const TAX_SYSTEM_SIMPLE_PATENT = 5;

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

    /**
     * Receipt to array
     *
     * @return array
     */
    public function toArray()
    {
        $items = array_map(function ($item) {
            /** @var ReceiptItem $item */
            return $item->toArray();
        }, $this->getItems());

        $result = [
            'customerContact' => $this->getContact(),
            'items'           => $items,
        ];
        if (($taxSystem = $this->getTaxSystem()) !== null) {
            $result['taxSystem'] = $taxSystem;
        }

        return $result;
    }

    /**
     * Receipt to json
     *
     * @return string
     */
    public function __toString()
    {
        return json_encode($this->toArray());
    }
}