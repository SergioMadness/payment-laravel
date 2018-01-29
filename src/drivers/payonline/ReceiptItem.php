<?php namespace professionalweb\payment\drivers\payonline;

/**
 * Receipt item
 * @package professionalweb\payment\drivers\payonline
 */
class ReceiptItem extends \professionalweb\payment\drivers\receipt\ReceiptItem
{
    /**
     * без НДС
     */
    const TAX_NO_VAT = 'none';

    /**
     * НДС по ставке 0%
     */
    const TAX_VAT_0 = 'vat0';

    /**
     * НДС чека по ставке 10%
     */
    const TAX_VAT_10 = 'vat10';

    /**
     * НДС чека по ставке 18%
     */
    const TAX_VAT_18 = 'vat18';

    /**
     * НДС чека по расчетной ставке 10/110
     */
    const TAX_VAT_110 = 'vat110';

    /**
     * НДС чека по расчетной ставке 18/118
     */
    const TAX_VAT_118 = 'vat118';

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'Name' => mb_substr($this->getName(), 0, 128),
            'Price' => (float)$this->getPrice(),
            'Quantity' => (int)$this->getQty(),
            'Amount' => (float)($this->getPrice() * $this->getQty()),
            'Tax' => $this->getTax(),
        ];
    }
}