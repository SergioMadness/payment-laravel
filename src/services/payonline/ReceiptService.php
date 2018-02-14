<?php namespace professionalweb\payment\services\payonline;

use Illuminate\Contracts\Support\Arrayable;

/**
 * PayOnline service to send receipts
 * @package professionalweb\payment\services\payonline
 */
class ReceiptService implements \professionalweb\payment\contracts\ReceiptService
{

    /**
     * Url to send invoices
     */
    const URL_FISCAL_SERVICE = 'https://secure.payonlinesystem.com/Services/Fiscal/Request.ashx';

    /**
     * Security key
     *
     * @var string
     */
    private $securityKey;

    /**
     * Merchant id
     *
     * @var string
     */
    private $merchantId;

    public function __construct($merchantId = null, $securityKey = null)
    {
        $this->setMerchantId($merchantId)->setSecurityKey($securityKey);
    }

    /**
     * Send receipt
     *
     * @param Arrayable $receipt
     *
     * @return mixed
     */
    public function sendReceipt(Arrayable $receipt)
    {
        $receiptArr = $receipt->toArray();

        return $this->send(self::URL_FISCAL_SERVICE . '?MerchantId=' . $this->getMerchantId()
            . '&PrivateSecurityKey=' . $this->getHash($receiptArr), $receiptArr);
    }

    /**
     * Get security hash
     *
     * @param array $receipt
     *
     * @return string
     */
    protected function getHash(array $receipt)
    {
        return md5('RequestBody=' . json_encode($receipt) . '&MerchantId=' . $this->getMerchantId() . '&PrivateSecurityKey=' . $this->getSecurityKey());
    }

    /**
     * @return string
     */
    public function getSecurityKey()
    {
        return $this->securityKey;
    }

    /**
     * Set security key
     *
     * @param string $securityKey
     *
     * @return ReceiptService
     */
    public function setSecurityKey($securityKey)
    {
        $this->securityKey = $securityKey;

        return $this;
    }

    /**
     * Get merchant id
     *
     * @return string
     */
    public function getMerchantId()
    {
        return $this->merchantId;
    }

    /**
     * Set merchant id
     *
     * @param string $merchantId
     *
     * @return ReceiptService
     */
    public function setMerchantId($merchantId)
    {
        $this->merchantId = $merchantId;

        return $this;
    }

    /**
     * Send receipt
     *
     * @param string $url
     * @param array  $params
     *
     * @return mixed
     */
    protected function send($url, array $params)
    {
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_USERAGENT, 'Payonline.SDK/PHP');
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($params));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HEADER, 1);
        curl_setopt($curl, CURLOPT_HTTPHEADER, ['Expect:', 'Content-Type: application/json']);

        $body = curl_exec($curl);

        return $body;
    }
}