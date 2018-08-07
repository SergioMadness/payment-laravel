<?php namespace professionalweb\payment\contracts;

/**
 * Interface for payment system protocol wrappers
 *
 * @package professionalweb\payment\contracts
 */
interface PayProtocol
{
    /**
     * Get payment URL
     *
     * @param mixed $params
     *
     * @return string
     */
    public function getPaymentUrl($params);

    /**
     * Prepare parameters
     *
     * @param array $params
     *
     * @return array
     */
    public function prepareParams($params);

    /**
     * Validate params
     *
     * @param mixed $params
     *
     * @return bool
     */
    public function validate($params);

    /**
     * Get payment ID
     *
     * @return mixed
     */
    public function getPaymentId();

    /**
     * Prepare response on notification request
     *
     * @param mixed $requestData
     * @param int   $errorCode
     *
     * @return string
     */
    public function getNotificationResponse($requestData, $errorCode);

    /**
     * Prepare response on check request
     *
     * @param array $requestData
     * @param int   $errorCode
     *
     * @return string
     */
    public function getCheckResponse($requestData, $errorCode);
}