<?php namespace professionalweb\payment\drivers\payonline;

/**
 * Receipt
 * @package professionalweb\payment\drivers\payonline
 */
class Receipt extends \professionalweb\payment\drivers\receipt\Receipt
{
    /**
     * Operation type "Benefit"
     */
    const OPERATION_TYPE_BENEFIT = 'Benefit';

    /**
     * Operation type "Charge"
     */
    const OPERATION_TYPE_CHARGE = 'Charge';

    /**
     * No taxes
     */
    const TAX_NONE = 'none';

    /**
     * 0%
     */
    const TAX_VAT0 = 'vat0';

    /**
     * 10%
     */
    const TAX_VAT10 = 'vat10';

    /**
     * 18%
     */
    const TAX_VAT18 = 'vat18';

    /**
     * 10/110
     */
    const TAX_VAT110 = 'vat110';

    /**
     * 18/118
     */
    const TAX_VAT118 = 'vat118';

    /**
     * Phone number
     *
     * @var string
     */
    private $email;

    /**
     * Receipt constructor.
     *
     * @param string $phone
     * @param string $email
     * @param array|null $items
     * @param int $taxSystem
     */
    public function __construct($phone = null, $email = null, array $items = [], $taxSystem = null)
    {
        parent::__construct($phone, $items, $taxSystem);
        $this->setEmail($email);
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param $email
     * @return $this
     */
    public function setEmail($email)
    {
        $this->email = $email;

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
            'Phone' => $this->getPhone(),
            'Email' => $this->getEmail(),
            'Items' => $items,
        ];
        if (($taxSystem = $this->getTaxSystem()) !== null) {
            $result['Taxation'] = $taxSystem;
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