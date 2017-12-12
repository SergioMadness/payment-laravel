<?php namespace professionalweb\payment\drivers\tinkoff;

/**
 * Receipt
 * @package professionalweb\payment\drivers\tinkoff
 */
class Receipt extends \professionalweb\payment\drivers\receipt\Receipt
{
    /**
     * общая СН
     */
    const TAX_SYSTEM_COMMON = 'osn';

    /**
     * упрощенная СН (доходы)
     */
    const TAX_SYSTEM_SIMPLE_INCOME = 'usn_income';

    /**
     * упрощенная СН (доходы минус расходы)
     */
    const TAX_SYSTEM_SIMPLE_NO_OUTCOME = 'usn_income_outcome';

    /**
     * единый налог на вмененный доход
     */
    const TAX_SYSTEM_SIMPLE_UNIFIED = 'envd';

    /**
     * единый сельскохозяйственный налог
     */
    const TAX_SYSTEM_SIMPLE_AGRO = 'esn';

    /**
     * патентная СН
     */
    const TAX_SYSTEM_SIMPLE_PATENT = 'patent';

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