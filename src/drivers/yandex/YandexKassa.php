<?php namespace professionalweb\payment\drivers\yandex;

use Illuminate\Http\Response;
use professionalweb\payment\contracts\PayProtocol;

/**
 * Class to work with Yandex.Kassa
 *
 * @package professionalweb\payment\drivers\yandex
 */
class YandexKassa implements PayProtocol
{
    const ESHOP_URL_DEMO = 'https://demomoney.yandex.ru/eshop.xml';
    const ESHOP_URL_PROD = 'https://money.yandex.ru/eshop.xml';

    const RESPONSE_ROOT_CHECK = 'checkOrderResponse';
    const RESPONSE_ROOT_AVISO = 'paymentAvisoResponse';

    /**
     * Ya URL
     *
     * @var string
     */
    private $eshopUrl;

    /**
     * Shop ID
     *
     * @var int
     */
    private $shopId;

    /**
     * Shop window ID
     *
     * @var int
     */
    private $scid;

    /**
     * Shop password
     *
     * @var string
     */
    private $shopPassword;

    /**
     * YandexKassa constructor.
     *
     * @param int    $shopId
     * @param int    $scid
     * @param null   $password
     * @param string $url
     */
    public function __construct($shopId = null, $scid = null, $password = null, $url = self::ESHOP_URL_PROD)
    {
        $this->setEshopUrl($url)->setShopId($shopId)->setScid($scid)->setShopPassword($password);
    }

    /**
     * Send POST request to Yandex.Kassa
     *
     * @param string $url
     * @param array  $params
     *
     * @return string
     */
    protected function sendPostRequest($url, array $params)
    {
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_USERAGENT, 'Yandex.Money.SDK/PHP');
        curl_setopt($curl, CURLOPT_POST, 1);
        $query = http_build_query($params);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $query);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HEADER, 1);
        $body = curl_exec($curl);

        return $body;
    }

    /**
     * Parse string with headers
     *
     * @param string $headersStr
     *
     * @return array
     */
    protected function parseHeaders($headersStr)
    {
        $result = [];

        $arrRequests = explode("\r\n\r\n", $headersStr);

        for ($index = 0; $index < count($arrRequests) - 1; $index++) {

            foreach (explode("\r\n", $arrRequests[$index]) as $i => $line) {
                if ($i === 0)
                    $result[$index]['http_code'] = $line;
                else {
                    list ($key, $value) = explode(': ', $line);
                    $result[$index][mb_strtolower($key)] = $value;
                }
            }
        }

        return $result;
    }

    /**
     * Get URL
     *
     * @return string
     */
    public function getEshopUrl()
    {
        return $this->eshopUrl;
    }

    /**
     * Set URL
     *
     * @param string $eshopUrl
     *
     * @return $this
     */
    public function setEshopUrl($eshopUrl)
    {
        $this->eshopUrl = $eshopUrl;

        return $this;
    }

    /**
     * @return int
     */
    public function getShopId()
    {
        return $this->shopId;
    }

    /**
     * Set Shop id
     *
     * @param int $shopId
     *
     * @return $this
     */
    public function setShopId($shopId)
    {
        $this->shopId = $shopId;

        return $this;
    }

    /**
     * Get SCID
     *
     * @return int
     */
    public function getScid()
    {
        return $this->scid;
    }

    /**
     * Set SCID
     *
     * @param int $scid
     *
     * @return $this
     */
    public function setScid($scid)
    {
        $this->scid = $scid;

        return $this;
    }


    /**
     * Get payment URL
     *
     * @param mixed $params
     *
     * @return string
     */
    public function getPaymentUrl($params)
    {
        $response = $this->sendPostRequest($this->getEshopUrl(), [
            'shopId'         => $this->getShopId(),
            'scid'           => $this->getScid(),
            'customerNumber' => $params['customerNumber'],
            'sum'            => $params['sum'],
        ]);

        $headers = $this->parseHeaders($response);

        return isset($headers[0]) && isset($headers[0]['location']) ? $headers[0]['location'] : null;
    }

    /**
     * Validate params
     *
     * @param mixed $params
     *
     * @return bool
     */
    public function validate($params)
    {
        return $this->checkSign($params);
    }

    /**
     * Checking the MD5 sign.
     *
     * @param  array $request payment parameters
     *
     * @return int true if MD5 hash is correct
     */
    private function checkSign($request)
    {
        $str = $request['action'] . ";" .
            $request['orderSumAmount'] . ";" . $request['orderSumCurrencyPaycash'] . ";" .
            $request['orderSumBankPaycash'] . ";" . $request['shopId'] . ";" .
            $request['invoiceId'] . ";" . trim($request['customerNumber']) . ";" . $this->getShopPassword();
        $md5 = strtoupper(md5($str));
        if ($md5 != strtoupper($request['md5'])) {
            return 1;
        }

        return 0;
    }

    /**
     * Get payment ID
     *
     * @return mixed
     */
    public function getPaymentId()
    {
        // TODO: Implement getPaymentId() method.
    }

    /**
     * Get shop password
     *
     * @return string
     */
    public function getShopPassword()
    {
        return $this->shopPassword;
    }

    /**
     * Set shop password
     *
     * @param string $shopPassword
     *
     * @return $this;
     */
    public function setShopPassword($shopPassword)
    {
        $this->shopPassword = $shopPassword;

        return $this;
    }


    /**
     * Prepare response on notification request
     *
     * @param mixed $requestData
     * @param int   $errorCode
     *
     * @return Response
     */
    public function getNotificationResponse($requestData, $errorCode)
    {
        return response($this->prepareXML(self::RESPONSE_ROOT_AVISO, $errorCode, $requestData['invoiceId']), 200, ['Content-Type' => 'text/xml']);
    }

    /**
     * Prepare response on check request
     *
     * @param array $requestData
     * @param int   $errorCode
     *
     * @return Response
     */
    public function getCheckResponse($requestData, $errorCode)
    {
        return response($this->prepareXML(self::RESPONSE_ROOT_CHECK, $errorCode, $requestData['invoiceId']), 200, ['Content-Type' => 'text/xml']);
    }

    /**
     * Prepare XML for response
     *
     * @param string $rootName
     * @param int    $errorCode
     * @param int    $invoiceId
     *
     * @return string
     */
    protected function prepareXML($rootName, $errorCode, $invoiceId)
    {
        return '<?xml version="1.0" encoding="UTF-8"?><' . $rootName . ' performedDatetime="' . date('Y-m-d\TH:i:s.000P') .
        '" code="' . $errorCode . '"  invoiceId="' . $invoiceId . '" shopId="' . $this->getShopId() . '"/>';
    }
}