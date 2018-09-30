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
     * @param array $params
     *
     * @return string
     */
    public function getPaymentUrl(array $params): string;

    /**
     * Prepare parameters
     *
     * @param array $params
     *
     * @return array
     */
    public function prepareParams(array $params): array;

    /**
     * Validate params
     *
     * @param array $params
     *
     * @return bool
     */
    public function validate(array $params): bool;

    /**
     * Get payment ID
     *
     * @return string
     */
    public function getPaymentId(): string;

    /**
     * Prepare response on notification request
     *
     * @param mixed $requestData
     * @param int   $errorCode
     *
     * @return string
     */
    public function getNotificationResponse($requestData, $errorCode): string;

    /**
     * Prepare response on check request
     *
     * @param array $requestData
     * @param int   $errorCode
     *
     * @return string
     */
    public function getCheckResponse($requestData, $errorCode): string;
}