<?php namespace professionalweb\payment\drivers\yandex;

/**
 * Receipt
 * @package professionalweb\payment\drivers\yandex
 */
class Receipt extends \professionalweb\payment\drivers\receipt\Receipt
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

        $contact = $this->getContact();
        $result = [
            filter_var($contact, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone' => $contact,
            'items'                                                         => $items,
        ];
        if (($taxSystem = $this->getTaxSystem()) !== null) {
            $result['tax_system_code'] = $taxSystem;
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