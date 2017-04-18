<?php namespace professionalweb\payment\drivers\tinkoff;

use professionalweb\payment\contracts\PayProtocol;

require_once 'TinkoffMerchantAPI.php';

/**
 * Wrapper for Tinkoff protocol
 * @package professionalweb\payment\drivers\tinkoff
 */
class TinkoffProtocol extends \TinkoffMerchantAPI implements PayProtocol
{

    /**
     * Get payment URL
     *
     * @param mixed $params
     *
     * @return string
     * @throws \Exception
     */
    public function getPaymentUrl($params)
    {
        $this->init($params);
        if ($this->error != '') {
            throw new \Exception($this->error);
        }

        return $this->paymentUrl;
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
        $result = false;

        if (isset($data['Token'])) {
            $token = $data['Token'];
            unset($data['Token']);
            if ($token != '' && $this->genToken($data) == $token) {
                $result = true;
            }
        }

        return $result;
    }

    /**
     * Get payment ID
     *
     * @return mixed
     */
    public function getPaymentId()
    {
        return $this->paymentId;
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
        return $errorCode ? 'OK' : 'ERROR';
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
        return $errorCode ? 'OK' : 'ERROR';
    }
}