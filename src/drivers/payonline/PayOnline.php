<?php

/**
 * PayOnline
 *
 * ------------------------------------------------------------------------------
 *
 * Copyright (c) 2016 ML <create@li.ru>
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 *
 * ------------------------------------------------------------------------------
 *
 * Payonline class.
 * The class provide work according to API specifications the "PayOnline" payment system.
 *
 * @uses SimpleXml library
 * @uses param "fopen_wrappers" ( compile your PHP with directive "allow_url_fopen"=true )
 *
 * @author ml <create@li.ru> since 24.11.2015 10:39
 */
class PayOnline {

    /**
     * The language of interface payment form. Allowed values:
     *	ru - Russian;
     *	en - English;
     *	fr - French;
     *	ka - Georgian;
     *	zn-ch - Chinese.
     *
     * @var string
     */
    public $LANG = 'ru';

    /**
     * The type page of payment form. Allowed values:
     *	[empty] - credit card payment;
     *	qiwi - QIWI payments;
     *	paymaster - WebMoney;
     *	yandexmoney - Yandex.Money;
     *	masterpass - using MasterPass;
     *	select - the page of payment options.
     *
     * @var string
     */
    public $FORM_TYPE = '';

    /**
     * Contains last error after parse PayOnline-response
     *
     * @var string
     */
    public $LAST_ERROR = null;

    /**
     * Response format from "PayOnline"
     *
     * @var string
     */
    private $CONTENT_TYPE = 'text'; // text / xml

    /**
     * "PayOnline"-URL for request payment form.
     *
     * @var string
     */
    private $FORM_URL = 'https://secure.payonlinesystem.com/%s/payment/';

    /**
     * "PayOnline"-URL for transactions
     *
     * @var type
     */
    private $TRANSACTION_URL = 'https://secure.payonlinesystem.com/payment/transaction/';

    /**
     * PayOnline MerchantId
     *
     * @var string
     */
    private $_merchantId = null;

    /**
     * PayOnline SecurityKey
     *
     * @var string
     */
    private $_securityKey = null;

    /**
     * The sequence of array keys for create field "SecurityKey" (according to API-specification)
     *
     * @var array
     */
    private $_chain = array(

        /* Methods */
        'payment'  => array( 'MerchantId', 'OrderId', 'Amount', 'Currency', 'ValidUntil', 'OrderDescription'),
        'callback' => array( 'DateTime', 'TransactionID', 'OrderId', 'Amount', 'Currency' ),

        /* Transactions */
        'auth'     => array( 'MerchantId', 'OrderId', 'Amount', 'Currency' ),
        '3ds'      => array( 'MerchantId', 'TransactionId', 'PARes', 'PD' ),
        'complete' => array( 'MerchantId', 'TransactionId' ),
        'void'     => array( 'MerchantId', 'TransactionId' ),
        'rebill'   => array( 'MerchantId', 'RebillAnchor', 'OrderId', 'Amount', 'Currency' ),
        'refund'   => array( 'MerchantId', 'TransactionId', 'Amount' ),
        'check'    => array( 'MerchantId', 'OrderId', 'Amount', 'Currency' ),
        'search'   => array( 'MerchantId', 'OrderId' ),
        'list'     => array( 'MerchantId', 'DateFrom', 'DateTill', 'Type', 'Status' ),
    );

    /**
     * Constructor
     *
     * @param string $merchantId PayOnline MerchantId
     * @param string $securityKey PayOnline SecurityKey
     */
    public function __construct( $merchantId, $securityKey ) {

        $this->_merchantId = $merchantId;
        $this->_securityKey = $securityKey;
    }

    /**
     * Execute transaction
     *
     * @param string $method Name of transaction "PayOnline"
     * @param array $data Data array (according to API-specification for transaction)
     *
     * @return array Returns "PayOnline"-answer as array. Or NULL - if there is some error.
     */
    public function transaction( $method, $data ) {

        $method = strtolower($method);

        $this->_setParams($method, $data);

        return $this->_request($method, $data);
    }

    /**
     * Returns redirect URL for calling "PayOnline" forms by payments server side
     *
     * @param array $data Data array, according to API-specification for "Standart"-scheme
     *
     * @return string URL
     */
    public function getUrl( $data ) {

        $this->_setParams('payment', $data);

        $url = $this->_getUrl( $data );

        return implode('?', $url);
    }

    /**
     * Get a html form for request "PayOnline" interface
     *
     * @param array $data Data array, according to API-specification for "Standart"-scheme.
     * @param string $id html-attribute "id" of html-form
     * @param string $additionHTML Additional html-content for include into form.
     *
     * @return string Ready html-content of html-form
     */
    public function getForm( $data, $id, $additionHTML ) {

        $this->_setParams('payment', $data);

        $url = $this->_getUrl();

        return $this->_getForm($id, $url['action'], $data, $additionHTML);
    }

    /**
     * Get a separate html-content of form for 3DS-request to issuing bank.
     *
     * @param string $callbackUrl URL-address of Shop(Merchant), to which the response will be sent from the bank
     * @param array $data Response data "PayOnline" on the "auth"-transaction (when demand 3DS)
     * @param string $formId html-attribute "id" of html-form
     * @param string $additionHTML Additional html-content for include into form.
     *
     * @return mixed Returns generated data array or ready html-form
     */
    public function get3dsForm( $callbackUrl, $data, $formId='PayOnlinePaReq', $additionHTML=null ) {

        $out = array();

        $threedSecure = ($this->$CONTENT_TYPE == 'xml')?
            $data['threedSecure']
            : $data;

        $out['TermUrl'] = $callbackUrl;
        $out['PaReq'] = $threedSecure['pareq'];
        $out['MD'] = $data['id'] . ';' . $threedSecure['pd'];

        return $this->_getForm(
            $formId,
            $data['threedSecure']['acsurl'],
            $out,
            $additionHTML
        );
    }

    /**
     * Handler of response from issued bank on 3DS-request.
     * Execute 3DS-transaction to "PayOnline" system
     *
     * @param array $data Response data from the bank as array
     *
     * @return array Returns "PayOnline"-response according to API-specification
     */
    public function commit3ds( $data ) {

        $params = explode(';', $data['MD']);

        $data['TransactionId'] = $params[0];
        $data['PD'] = $params[1];

        return $this->transaction('3ds', $data);
    }

    /**
     * Get "SecurityKey" value for specified transaction "PayOnline"
     *
     * @param string $method Name of transaction "PayOnline'
     * @param array $data Data array for transaction according to API-specification
     *
     * @return string Returns MD5-SecurityKey according to API-specification "PayOnline"
     */
    public function getSecurityKey( $method, $data ) {

        $out = array();

        if ( isset($this->_chain[$method]) ) {

            $chain = $this->_chain[$method];

            foreach( $chain as $v ) {

                if ( isset($data[$v]) ) {
                    $out[] = $v . '=' . $data[$v];
                }
            }
            $out[] = 'PrivateSecurityKey' . '=' . $this->_securityKey;
        }

        return md5( implode('&', $out) );
    }

    /**
     * Returns params for build URL by payments server side
     *
     * @param array $data Data array, according to API-specification for "Standart"-scheme
     * @return array Returns array of [action] - the payments URL, [params] - query params for URL
     */
    private function _getUrl( $data=null ) {

        return array(

            'action' => sprintf( $this->FORM_URL, $this->LANG)
                . (
                ($this->FORM_TYPE == 'select')?
                    ''
                    : ($this->FORM_TYPE? 'select/':'' )
                )
                . $this->FORM_TYPE
        ,
            'params' => http_build_query( (array)$data ),
        );
    }

    /**
     * Return the html-content of form
     *
     * @param string $id Attribute "id" of form
     * @param string $action Attribute "action" of form
     * @param array $fields Data array for form-fields. As ["field_name" => "field_value"]
     * @param string $additionHTML Additional html-content of form
     * @param string $method Attribute "method" of form. Default - "POST"
     *
     * @return string html-content of form
     */
    private function _getForm( $id, $action, $fields, $additionHTML='', $method='post' ) {

        $fieldsHtml = array();

        foreach($fields as $k=>$v) {
            $fieldsHtml[] = '<input type="hidden" name="'
                . $k
                . '" value="'
                . htmlspecialchars($v)
                . '"/>';
        }
        return '<form id="' . $id
        . '" method="' . $method
        . '" action="' . $action
        . '" accept-charset="utf-8">'
        . implode( '', $fieldsHtml )
        . $additionHTML
        . '</form>';
    }

    /**
     * Check and set required parameters for data of transaction
     *
     * @param string $method Name of transaction "PayOnline"
     * @param array &$data Data array (by reference)
     */
    private function _setParams( $method, &$data ) {

        if ( !isset($data['MerchantId']) ) {

            $data['MerchantId'] = $this->_merchantId;
        }

        if ( isset($data['OrderDescription']) ) {

            $data['OrderDescription'] =
                $this->_getOrderDescription(
                    $data['OrderDescription']
                );
        }

        $data['SecurityKey'] = $this->getSecurityKey($method, $data);

        if ( !isset($data['ContentType']) ) {
            $data['ContentType'] = $this->CONTENT_TYPE;
        } else {
            $this->CONTENT_TYPE = $data['ContentType'];
        }
    }

    /**
     * Check the "OrderDescription" value (according to API-specification "PayOnline")
     *
     * @param string $description Value "OrderDescription"
     *
     * @return string Returns value if the meet the conditions
     */
    private function _getOrderDescription( $description ) {

        return
            preg_match("/^[a-zA-Zа-яА-Я0-9 \,\.\!\?\;\:\%\*\(\)\-]{1,100}$/u", $description)?
                $description
                : '';
    }

    /**
     * Execute request to "PayOnline" server
     *
     * @param string $method Name of method
     * @param array $data Data array, according to API-specification
     *
     * @return array Returns response data array. Or NULL - if there is some error.
     */
    private function _request( $method, $data ) {

        $content = http_build_query($data);

        $opts = array(
            'http'=>array(
                'method' => 'POST',
                'header' => "Content-type: application/x-www-form-urlencoded\r\n"
                    . "Content-Length: " . strlen($content) . "\r\n",
                'content' => $content
            )
        );

        $context = stream_context_create($opts);

        $url = $this->TRANSACTION_URL
            . ( ($method=='3ds')? 'auth/' : '' )
            . $method
            . '/';

        $response = file_get_contents($url, false, $context);

        return $response?
            $this->_parseResponse($response)
            : null;
    }

    /**
     * "PayOnline" server response data processing
     *
     * @param string $response Response data
     *
     * @return mixed
     */
    private function _parseResponse( $response ) {

        $out = null;

        switch( $this->CONTENT_TYPE ) {
            case 'xml':
                $out = $this->_parseResponseXml($response);
                break;
            default:
                $out = $this->_parseResponseText($response);
        }
        return $out;
    }

    private function _parseResponseXml( $response ) {

        $out = (array) simplexml_load_string($response);

        $error = libxml_get_last_error();

        if ( $error ) {
            $this->LAST_ERROR = $error->message;
        }

        return $error? null : $out;
    }

    private function _parseResponseText( $response ) {

        $out = null;

        parse_str( $response, $out );

        if ( !$out ) {
            $this->LAST_ERROR = 'Error parse response';
        }

        return $out;
    }
}