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
     * Payment by card
     */
    const PAYMENT_TYPE_CARD = 'card';

    /**
     * Payment through WebMoney
     */
    const PAYMENT_TYPE_WEBMONEY = 'wm';

    /**
     * Payment through Yandex.Money
     */
    const PAYMENT_TYPE_YANDEX_MONEY = 'yd';

    /**
     * Payment through Qiwi
     */
    const PAYMENT_TYPE_QIWI = 'qiwi';

    /**
     * Payment through custom payment system
     */
    const PAYMENT_TYPE_CUSTOM = 'custom';

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
     * Operation type
     *
     * @var string
     */
    private $operation;

    /**
     * Transaction id
     *
     * @var string
     */
    private $transactionId;

    /**
     * Payment type
     *
     * @var string
     */
    private $paymentType;

    /**
     * Receipt constructor.
     *
     * @param string     $email
     * @param array|null $items
     * @param string     $paymentType
     * @param string     $transactionId
     * @param string     $taxSystem
     * @param string     $operation
     */
    public function __construct($email = null, array $items = [], $paymentType = self::PAYMENT_TYPE_CARD, $transactionId = null,
                                $taxSystem = self::TAX_VAT18, $operation = self::OPERATION_TYPE_BENEFIT)
    {
        parent::__construct($email, $items, $taxSystem);

        $this->setOperation($operation)->setPaymentType($paymentType)->setTransactionId($transactionId);
    }

    /**
     * Get payment type
     *
     * @return string
     */
    public function getPaymentType()
    {
        return $this->paymentType;
    }

    /**
     * Set payment type
     *
     * @param string $paymentType
     *
     * @return Receipt
     */
    public function setPaymentType($paymentType)
    {
        $this->paymentType = $paymentType;

        return $this;
    }

    /**
     * Get transaction id
     *
     * @return string
     */
    public function getTransactionId()
    {
        return $this->transactionId;
    }

    /**
     * Set transaction id
     *
     * @param string $transactionId
     *
     * @return Receipt
     */
    public function setTransactionId($transactionId)
    {
        $this->transactionId = $transactionId;

        return $this;
    }

    /**
     * Set operation type
     *
     * @param string $operation
     *
     * @return $this
     */
    public function setOperation($operation)
    {
        $this->operation = $operation;

        return $this;
    }

    /**
     * Get operation type
     *
     * @return string
     */
    public function getOperation()
    {
        return $this->operation;
    }

    /**
     * Receipt to array
     *
     * @return array
     */
    public function toArray()
    {
        $totalAmount = 0;
        $items = array_map(function ($item) use (&$totalAmount) {
            /** @var ReceiptItem $item */
            $totalAmount += $item->getPrice() * $item->getPrice();

            return $item->toArray();
        }, $this->getItems());

        $result = [
            'operation'         => $this->getOperation(),
            'email'             => $this->getContact(),
            'transactionId'     => $this->getTransactionId(),
            'paymentSystemType' => $this->getPaymentType(),
            'totalAmount'       => $totalAmount,
            'goods'             => $items,
        ];

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