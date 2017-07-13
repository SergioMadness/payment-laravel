<?php namespace professionalweb\payment\drivers\yandex\payments;

use Namshi\JOSE\SimpleJWS;

/**
 * Yandex payment implementation
 * @package professionalweb\payment\drivers\yandex\payments
 */
class YandexPayment implements \professionalweb\payment\drivers\yandex\contracts\YandexPayment
{
    /**
     * Payment method URL
     */
    const URL_PAYMENT = 'https://payment.yandex.net/api/v2/payments/dsrpWallet';

    /**
     * Server info URL
     */
    const URL_VERSION = 'https://payment.yandex.net/api/v2/version';

    /**
     * Shop id
     *
     * @var string
     */
    private $shopId;

    /**
     * Test operations
     *
     * @var bool
     */
    private $isTest;

    /**
     * Path to private key
     *
     * @var string
     */
    private $pathToPrivateKey;

    /**
     * Password for private key
     *
     * @var string
     */
    private $privateKeyPassword;

    /**
     * Call API method
     *
     * @param string $method
     * @param string $url
     * @param array  $params
     *
     * @return mixed
     * @throws \Exception
     */
    public function call($method, $url, array $params = [])
    {
        $ch = curl_init();

        if (strtolower($method) === 'get') {
            curl_setopt($ch, CURLOPT_URL, $url . '?request=' . $this->generateToken($params));
        } else {
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
                'request' => $this->generateToken($params),
            ]));
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        if (($result = curl_exec($ch)) === false) {
            throw new \Exception(curl_error($ch));
        }
        // close cURL resource, and free up system resources
        curl_close($ch);

        return $result;
    }

    /**
     * Generate JWS
     *
     * @param array $payload
     *
     * @return string
     */
    protected function generateToken($payload)
    {
        $jws = new SimpleJWS([
            'alg' => 'ES256',
            'iss' => 'shopId:' . $this->getShopId(),
            'aud' => $this->getIsTest() ? 'test' : 'production',
            'iat' => round(microtime(true) * 1000),
        ]);
        $jws->setPayload($payload);

        $privateKey = openssl_pkey_get_private(file_get_contents($this->getPathToPrivateKey()), $this->getPrivateKeyPassword());
        $jws->sign($privateKey);

        return $jws->getTokenString();
    }

    /**
     * Get shop ID
     *
     * @return string
     */
    public function getShopId()
    {
        return $this->shopId;
    }

    /**
     * Set shop ID
     *
     * @param string $shopId
     *
     * @return $this
     */
    public function setShopId($shopId)
    {
        $this->shopId = $shopId;

        return $this;
    }

    /**
     * Check is test
     *
     * @return boolean
     */
    public function getIsTest()
    {
        return $this->isTest;
    }

    /**
     * Set is test
     *
     * @param boolean $isTest
     *
     * @return $this
     */
    public function setIsTest($isTest)
    {
        $this->isTest = $isTest;

        return $this;
    }

    /**
     * @return string
     */
    public function getPathToPrivateKey()
    {
        return $this->pathToPrivateKey;
    }

    /**
     * Set path to private key
     *
     * @param string $pathToPrivateKey
     *
     * @return $this
     */
    public function setPathToPrivateKey($pathToPrivateKey)
    {
        $this->pathToPrivateKey = $pathToPrivateKey;

        return $this;
    }

    /**
     * @return string
     */
    public function getPrivateKeyPassword()
    {
        return $this->privateKeyPassword;
    }

    /**
     * Set password for private key
     *
     * @param string $privateKeyPassword
     *
     * @return $this
     */
    public function setPrivateKeyPassword($privateKeyPassword)
    {
        $this->privateKeyPassword = $privateKeyPassword;

        return $this;
    }


    /**
     * Get server info
     *
     * @return mixed
     */
    public function version()
    {
        return $this->call('GET', self::URL_VERSION);
    }

    /**
     * Create application instance
     *
     * @return mixed
     */
    public function instance()
    {
        // TODO: Implement instance() method.
    }

    /**
     * Digital Secure Remote Payment (DSRP)
     *
     * @param array $params
     *
     * @return mixed
     */
    public function drsp(array $params)
    {
        return $this->call('POST', self::URL_PAYMENT, $params);
    }
}