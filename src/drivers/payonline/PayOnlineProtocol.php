<?php namespace professionalweb\payment\drivers\payonline;

use professionalweb\payment\contracts\PayProtocol;

require_once 'PayOnline.php';

/**
 * Wrapper for PayOnline protocol
 * @package professionalweb\payment\drivers\payonline
 */
class PayOnlineProtocol extends \PayOnline implements PayProtocol
{

    /**
     * Get payment form URL
     *
     * @param mixed $params
     *
     * @return string
     */
    public function getPaymentUrl($params)
    {
        return $this->getUrl($params);
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

        if (isset($params['SecurityKey']) && $this->getSecurityKey('callback', $params) == $params['SecurityKey']) {
            $result = true;
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
        return null;
    }

    /**
     * Prepare response on notification request
     *
     * @param mixed $requestData
     * @param int   $errorCode
     *
     * @return string
     */
    public function getNotificationResponse($requestData, $errorCode = null)
    {
        return $errorCode > 0 ? response('ERROR') : response('OK');
    }

    /**
     * Prepare response on check request
     *
     * @param array $requestData
     * @param int   $errorCode
     *
     * @return string
     */
    public function getCheckResponse($requestData, $errorCode = null)
    {
        return $errorCode > 0 ? response('ERROR') : response('OK');
    }
}