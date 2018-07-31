<?php

use professionalweb\payment\drivers\payonline\Receipt;
use professionalweb\payment\drivers\payonline\ReceiptItem;
use professionalweb\payment\services\ReceiptService;

class PayOnlineReceiptServiceTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    protected function _before()
    {
    }

    protected function _after()
    {
    }

    /**
     * Test receipt generation
     */
    public function testReceiptGeneration()
    {
        $targetArray = [
            'operation'         => 'Benefit',
            'email'             => 'test@test.com',
            'transactionId'     => 'transaction1000',
            'paymentSystemType' => 'card',
            'totalAmount'       => 260000,
            'goods'             => [
                [
                    'description' => 'test',
                    'quantity'    => 2,
                    'amount'      => 100,
                    'tax'         => ReceiptItem::TAX_NO_VAT,
                ],
                [
                    'description' => 'test2',
                    'quantity'    => 3,
                    'amount'      => 500,
                    'tax'         => ReceiptItem::TAX_VAT_18,
                ],
            ],
        ];

        $receipt = new Receipt();
        $receipt
            ->setTransactionId('transaction1000')
            ->setContact('test@test.com')
            ->setItems([
                new ReceiptItem('test', 2, 100, ReceiptItem::TAX_NO_VAT),
                new ReceiptItem('test2', 3, 500, ReceiptItem::TAX_VAT_18),
            ]);

        $this->assertEquals($receipt->toArray(), $targetArray);
    }

    /**
     * Test receipt service
     *
     * @throws Exception
     */
    public function testReceiptService()
    {
        $receipt = new Receipt();
        $receipt
            ->setTransactionId('transaction1000')
            ->setContact('test@test.com')
            ->setItems([
                new ReceiptItem('test', 2, 100, ReceiptItem::TAX_NO_VAT),
                new ReceiptItem('test2', 3, 500, ReceiptItem::TAX_VAT_18),
            ]);

        $securityKey = 'security-key';
        $merchantId = '111-ddd-fff';

        $hash = md5('RequestBody=' . json_encode($receipt->toArray()) . '&MerchantId=' . $merchantId
            . '&PrivateSecurityKey=' . $securityKey);

        /** @var ReceiptService $service */
        $service = $this->make(ReceiptService::class, [
            'send' => function ($url) use ($hash, $merchantId) {
                $this->assertEquals($url, ReceiptService::URL_FISCAL_SERVICE . '?MerchantId=' . $merchantId
                    . '&PrivateSecurityKey=' . $hash);
            },
        ]);
        $service->setSecurityKey($securityKey)
            ->setMerchantId($merchantId);

        $service->sendReceipt($receipt);
    }
}