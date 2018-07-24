<?php namespace professionalweb\payment\drivers\yandex;

use YandexCheckout\Client;
use Illuminate\Http\Response;
use professionalweb\payment\contracts\PayProtocol;

/**
 * Class to work with Yandex.Kassa
 *
 * @package professionalweb\payment\drivers\yandex
 */
class YandexKassa implements PayProtocol
{
    /**
     * @var Client
     */
    private $client;

    /**
     * Shop ID
     *
     * @var int
     */
    private $shopId;

    /**
     * Shop secret key
     *
     * @var string
     */
    private $shopSecret;

    /**
     * Yandex.Kassa constructor.
     *
     * @param int $shopId
     * @param int $shopSecret
     */
    public function __construct($shopId = null, $shopSecret = null)
    {
        $this->setShopId($shopId)->setShopPassword($shopSecret);
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
     * Get payment URL
     *
     * @param mixed $params
     *
     * @return string
     * @throws \Exception
     * @throws \YandexCheckout\Common\Exceptions\ApiException
     * @throws \YandexCheckout\Common\Exceptions\BadApiRequestException
     * @throws \YandexCheckout\Common\Exceptions\ForbiddenException
     * @throws \YandexCheckout\Common\Exceptions\InternalServerError
     * @throws \YandexCheckout\Common\Exceptions\NotFoundException
     * @throws \YandexCheckout\Common\Exceptions\ResponseProcessingException
     * @throws \YandexCheckout\Common\Exceptions\TooManyRequestsException
     * @throws \YandexCheckout\Common\Exceptions\UnauthorizedException
     */
    public function getPaymentUrl($params)
    {
        $response = $this->getClient()->createPayment($params);

        return isset($response['confirmation']) && isset($response['confirmation']['confirmation_url']) ?
            $response['confirmation']['confirmation_url'] : '';
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
        return true;
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
     * Get shop secret key
     *
     * @return string
     */
    public function getShopPassword()
    {
        return $this->shopSecret;
    }

    /**
     * Set shop secret key
     *
     * @param string $shopSecret
     *
     * @return $this;
     */
    public function setShopPassword($shopSecret)
    {
        $this->shopSecret = $shopSecret;

        return $this;
    }


    /**
     * Prepare response on notification request
     *
     * @param mixed $requestData
     * @param int   $errorCode
     *
     * @return string
     */
    public function getNotificationResponse($requestData, $errorCode)
    {
        // TODO: Implement getNotificationResponse() method.
    }

    /**
     * Prepare response on check request
     *
     * @param array $requestData
     * @param int   $errorCode
     *
     * @return string
     */
    public function getCheckResponse($requestData, $errorCode)
    {
        // TODO: Implement getCheckResponse() method.
    }

    /**
     * Create Kassa.Yandex client
     *
     * @return Client
     */
    protected function getClient()
    {
        if ($this->client === null) {
            $this->client = new Client();
            $this->client->setAuth($this->getShopId(), $this->getShopPassword());
        }

        return $this->client;
    }
}