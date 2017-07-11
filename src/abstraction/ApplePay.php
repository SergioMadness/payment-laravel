<?php namespace professionalweb\payment\abstraction;

use professionalweb\payment\contracts\ApplePayService;

abstract class ApplePay implements ApplePayService
{
    /**
     * Merchant ID
     *
     * @var string
     */
    private $merchantId;

    /**
     * Domain name
     *
     * @var string
     */
    private $domain;

    /**
     * Display name
     *
     * @var string
     */
    private $displayName;

    /**
     * Path to certificate file
     *
     * @var string
     */
    private $certificatePath;

    /**
     * Path to key file
     *
     * @var string
     */
    private $keyPath;

    /**
     * Password for key
     *
     * @var string
     */
    private $keyPassword;

    /**
     * Start session
     *
     * @param string $url
     *
     * @return string
     * @throws \Exception
     */
    public function startSession($url)
    {
        if ("https" !== parse_url($url, PHP_URL_SCHEME) || substr(parse_url($url, PHP_URL_HOST), -10) !== ".apple.com") {
            throw new \Exception('Bad URL');
        }

        $result = null;
        // create a new cURL resource
        $ch = curl_init();
        $data = json_encode([
            'merchantIdentifier' => $this->getMerchantId(),
            'domainName'         => $this->getDomain(),
            'displayName'        => $this->getDisplayName(),
        ]);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSLCERT, base_path($this->getCertificatePath()));
        curl_setopt($ch, CURLOPT_SSLKEY, base_path($this->getKeyPath()));
        curl_setopt($ch, CURLOPT_SSLKEYPASSWD, $this->getKeyPassword());
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        if (($result = curl_exec($ch)) === false) {
            throw new \Exception(curl_error($ch));
        }
        // close cURL resource, and free up system resources
        curl_close($ch);

        return $result;
    }

    /**
     * Get merchant ID
     *
     * @return string
     */
    public function getMerchantId()
    {
        return $this->merchantId;
    }

    /**
     * Set merchant ID
     *
     * @param string $merchantId
     *
     * @return $this
     */
    public function setMerchantId($merchantId)
    {
        $this->merchantId = $merchantId;

        return $this;
    }

    /**
     * Get domain name
     *
     * @return string
     */
    public function getDomain()
    {
        return $this->domain;
    }

    /**
     * Set domain name
     *
     * @param string $domain
     *
     * @return $this
     */
    public function setDomain($domain)
    {
        $this->domain = $domain;

        return $this;
    }

    /**
     * Get display name
     *
     * @return string
     */
    public function getDisplayName()
    {
        return $this->displayName;
    }

    /**
     * Set display name
     *
     * @param string $displayName
     *
     * @return $this
     */
    public function setDisplayName($displayName)
    {
        $this->displayName = $displayName;

        return $this;
    }

    /**
     * Get path to cert
     *
     * @return string
     */
    public function getCertificatePath()
    {
        return $this->certificatePath;
    }

    /**
     * Set path to certificate file
     *
     * @param string $certificatePath
     *
     * @return $this
     */
    public function setCertificatePath($certificatePath)
    {
        $this->certificatePath = $certificatePath;

        return $this;
    }

    /**
     * Get key path
     *
     * @return string
     */
    public function getKeyPath()
    {
        return $this->keyPath;
    }

    /**
     * Set key path
     *
     * @param string $keyPath
     *
     * @return $this
     */
    public function setKeyPath($keyPath)
    {
        $this->keyPath = $keyPath;

        return $this;
    }

    /**
     * Get password for key
     *
     * @return string
     */
    public function getKeyPassword()
    {
        return $this->keyPassword;
    }

    /**
     * Set password for key
     *
     * @param string $keyPassword
     *
     * @return $this
     */
    public function setKeyPassword($keyPassword)
    {
        $this->keyPassword = $keyPassword;

        return $this;
    }
}