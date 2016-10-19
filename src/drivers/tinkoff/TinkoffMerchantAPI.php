<?php
/**
 * File TinkoffMerchantAPI
 *
 * PHP version 5.3
 *
 * @category Tinkoff
 * @package  Tinkoff
 * @author   Shuyskiy Sergey <s.shuyskiy@tinkoff.ru>
 * @license  http://opensource.org/licenses/MIT MIT license
 * @link     http://tinkoff.ru
 */
//namespace Tinkoff;

//use HttpException;

/**
 * Class TinkoffMerchantAPI
 *
 * @category Tinkoff
 * @package  Tinkoff
 * @author   Shuyskiy Sergey <s.shuyskiy@tinkoff.ru>
 * @license  http://opensource.org/licenses/MIT MIT license
 * @link     http://tinkoff.ru
 * @property integer     orderId
 * @property integer     Count
 * @property bool|string error
 * @property bool|string response
 * @property bool|string customerKey
 * @property bool|string status
 * @property bool|string paymentUrl
 * @property bool|string paymentId
 */
class TinkoffMerchantAPI
{
    private $_api_url;
    private $_terminalKey;
    private $_secretKey;
    private $_paymentId;
    private $_status;
    private $_error;
    private $_response;
    private $_paymentUrl;

    /**
     * Constructor
     *
     * @param string $terminalKey Your Terminal name
     * @param string $secretKey   Secret key for terminal
     * @param string $api_url     Url for API
     */
    public function __construct($terminalKey, $secretKey, $api_url)
    {
        $this->_api_url = $api_url;
        $this->_terminalKey = $terminalKey;
        $this->_secretKey = $secretKey;
    }

    /**
     * Get class property or json key value
     *
     * @param mixed $name Name for property or json key
     *
     * @return bool|string
     */
    public function __get($name)
    {
        switch ($name) {
            case 'paymentId':
                return $this->_paymentId;
            case 'status':
                return $this->_status;
            case 'error':
                return $this->_error;
            case 'paymentUrl':
                return $this->_paymentUrl;
            case 'response':
                return htmlentities($this->_response);
            default:
                if ($this->_response) {
                    if ($json = json_decode($this->_response, true)) {
                        foreach ($json as $key => $value) {
                            if (strtolower($name) == strtolower($key)) {
                                return $json[$key];
                            }
                        }
                    }
                }

                return false;
        }
    }

    /**
     * Initialize the payment
     *
     * @param mixed $args mixed You could use associative array or url params string
     *
     * @return bool
     */
    public function init($args)
    {
        return $this->buildQuery('Init', $args);
    }

    /**
     * Get state of payment
     *
     * @param mixed $args Can be associative array or string
     *
     * @return mixed
     */
    public function getState($args)
    {
        return $this->buildQuery('GetState', $args);
    }

    /**
     * Confirm 2-staged payment
     *
     * @param mixed $args Can be associative array or string
     *
     * @return mixed
     */
    public function confirm($args)
    {
        return $this->buildQuery('Confirm', $args);
    }

    /**
     * Performs recursive (re) payment - direct debiting of funds from the
     * account of the Buyer's credit card.
     *
     * @param mixed $args Can be associative array or string
     *
     * @return mixed
     */
    public function charge($args)
    {
        return $this->buildQuery('Charge', $args);
    }

    /**
     * Registers in the terminal buyer Seller. (Init do it automatically)
     *
     * @param mixed $args Can be associative array or string
     *
     * @return mixed
     */
    public function addCustomer($args)
    {
        return $this->buildQuery('AddCustomer', $args);
    }

    /**
     * Returns the data stored for the terminal buyer Seller.
     *
     * @param mixed $args Can be associative array or string
     *
     * @return mixed
     */
    public function getCustomer($args)
    {
        return $this->buildQuery('GetCustomer', $args);
    }

    /**
     * Deletes the data of the buyer.
     *
     * @param mixed $args Can be associative array or string
     *
     * @return mixed
     */
    public function removeCustomer($args)
    {
        return $this->buildQuery('RemoveCustomer', $args);
    }

    /**
     * Returns a list of bounded card from the buyer.
     *
     * @param mixed $args Can be associative array or string
     *
     * @return mixed
     */
    public function getCardList($args)
    {
        return $this->buildQuery('GetCardList', $args);
    }

    /**
     * Removes the customer's bounded card.
     *
     * @param mixed $args Can be associative array or string
     *
     * @return mixed
     */
    public function removeCard($args)
    {
        return $this->buildQuery('RemoveCard', $args);
    }

    /**
     * The method is designed to send all unsent notification
     *
     * @return mixed
     */
    public function resend()
    {
        return $this->buildQuery('Resend', []);
    }

    /**
     * Builds a query string and call sendRequest method.
     * Could be used to custom API call method.
     *
     * @param string $path API method name
     * @param mixed  $args query params
     *
     * @return mixed
     * @throws HttpException
     */
    public function buildQuery($path, $args)
    {
        $url = $this->_api_url;
        if (is_array($args)) {
            if (!array_key_exists('TerminalKey', $args)) {
                $args['TerminalKey'] = $this->_terminalKey;
            }
            if (!array_key_exists('Token', $args)) {
                $args['Token'] = $this->_genToken($args);
            }
        }
        $url = $this->_combineUrl($url, $path);


        return $this->_sendRequest($url, $args);
    }

    /**
     * Generates token
     *
     * @param array $args array of query params
     *
     * @return string
     */
    private function _genToken($args)
    {
        $token = '';
        $args['Password'] = $this->_secretKey;
        ksort($args);
        foreach ($args as $arg) {
            $token .= $arg;
        }
        $token = hash('sha256', $token);

        return $token;
    }

    /**
     * Public method form token generation
     *
     * @param array $args
     *
     * @return string
     */
    public function genToken($args)
    {
        return $this->_genToken($args);
    }

    /**
     * Combines parts of URL. Simply gets all parameters and puts '/' between
     *
     * @return string
     */
    private function _combineUrl()
    {
        $args = func_get_args();
        $url = '';
        foreach ($args as $arg) {
            if (is_string($arg)) {
                if ($arg[strlen($arg) - 1] !== '/') {
                    $arg .= '/';
                }
                $url .= $arg;
            } else {
                continue;
            }
        }

        return $url;
    }

    /**
     * Main method. Call API with params
     *
     * @param string $api_url API Url
     * @param array  $args    API params
     *
     * @return mixed
     * @throws HttpException
     */
    private function _sendRequest($api_url, $args)
    {
        $this->_error = '';
        //todo add string $args support
        //$proxy = 'http://192.168.5.22:8080';
        //$proxyAuth = '';
        if (is_array($args)) {
            $args = http_build_query($args);
        }
//        Debug::trace($args);
        if ($curl = curl_init()) {
            curl_setopt($curl, CURLOPT_URL, $api_url);
            curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $args);
            $out = curl_exec($curl);

            $this->_response = $out;
            $json = json_decode($out);
            if ($json) {
                if (@$json->ErrorCode !== "0") {
                    $this->_error = @$json->Details;
                } else {
                    $this->_paymentUrl = @$json->PaymentURL;
                    $this->_paymentId = @$json->PaymentId;
                    $this->_status = @$json->Status;
                }
            }

            curl_close($curl);

            return $out;

        } else {
            throw new HttpException(
                'Can not create connection to ' . $api_url . ' with args '
                . $args, 404
            );
        }
    }
}
