<?php

namespace Breadhead\Paddle;

/**
 * Paddle.com API PHP wrapper
 * @author Paddle.com
 */
class Api {

    protected $vendor_id;
    protected $vendor_auth_code;
    protected $timeout = 30;
    protected $base_url = 'https://vendors.paddle.com/api/';
    protected $api_version = '2.0';

    /*
     * 1XX - API response errors
     */

    const ERR_100 = 'Unable to find requested license';
    const ERR_101 = 'Bad method call';
    const ERR_102 = 'Bad api key';
    const ERR_103 = 'Timestamp is too old or not valid';
    const ERR_104 = 'License code has already been utilized';
    const ERR_105 = 'License code is not active';
    const ERR_106 = 'Unable to find requested activation';
    const ERR_107 = 'You don\'t have permission to access this resource';
    const ERR_108 = 'Unable to find requested product';
    const ERR_109 = 'Provided currency is not valid';
    const ERR_110 = 'Unable to find requested purchase';
    const ERR_111 = 'Invalid authentication token';
    const ERR_112 = 'Invalid verification token';
    const ERR_113 = 'Invalid padding on decrypted string';
    const ERR_114 = 'Invalid or duplicated affiliate';
    const ERR_115 = 'Invalid or missing affiliate commision';
    const ERR_116 = 'One or more required arguments are missing';
    const ERR_117 = 'Provided expiration time is incorrect';

    /*
     * 2XX - general errors
     */
    const ERR_200 = 'CURL error: ';
    const ERR_201 = 'Incorrect HTTP response code: ';
    const ERR_202 = 'Incorrect API response: ';
    const ERR_203 = 'Timeout must be a positive integer';
    const ERR_204 = 'Vendor credentials not provided';

    /*
     * 3XX - validation errors
     */
    const ERR_300 = '$product_id must be a positive integer';
    const ERR_301 = '$title must be a string';
    const ERR_302 = '$image_url must be a valid url';
    const ERR_303 = '$price must be a number';
    const ERR_304 = '$price must not be negative';
    const ERR_305 = '$return_url must be a valid url';
    const ERR_306 = '$paypal_cancel_url must be a valid url';
    const ERR_307 = '$expires must be a valid timestamp';
    const ERR_308 = '$expires must be in the future';
    const ERR_309 = '$parent_url must be a valid url';
    const ERR_310 = '$affiliates must be an array';
    const ERR_311 = 'provide $affiliates as key->value contained array with vendor_id->vendor_commission';
    const ERR_312 = '$stylesheets must be an array';
    const ERR_313 = 'provide $stylesheets as key->value contained array with stylesheet_type->stylesheet_code';
    const ERR_314 = '$webhook_url can only be set for custom product';
    const ERR_315 = '$webhook_url must be a valid url';
    const ERR_316 = '$discountable is not allowed for custom product';
    const ERR_317 = '$coupon_code is not allowed for custom product';
    const ERR_318 = '$product_id is not allowed for custom product';
    const ERR_319 = '$limit must be a positive integer';
    const ERR_320 = '$offset must be a non negative integer';
    const ERR_321 = '$start_timestamp must be a timestamp';
    const ERR_322 = '$end_timestamp must be a timestamp';
    const ERR_323 = '$vendor_email must be valid';
    const ERR_324 = '$application_icon_url must be a valid url';

    public function __construct($vendor_id = null, $vendor_auth_code = null, $timeout = null) {
        if ($vendor_id && $vendor_auth_code) {
            $this->set_vendor_credentials($vendor_id, $vendor_auth_code);
        }
        if ($timeout !== null) {
            $this->set_timeout($timeout);
        }
    }

    public function set_vendor_credentials($vendor_id, $vendor_auth_code) {
        $this->vendor_id = $vendor_id;
        $this->vendor_auth_code = $vendor_auth_code;
    }

    public function set_timeout($value) {
        if (
        (!filter_var($value, FILTER_VALIDATE_INT, array('options' => array('min_range' => 1))) || !is_numeric($value))
        ) {
            throw new \InvalidArgumentException(self::ERR_203, 203);
        } else {
            $this->timeout = $value;
        }
    }

    /**
     * Make a http call to Paddle API and return response
     * @param string $path
     * @param array $parameters
     * @return array
     * @throws \Exception
     */
    private function http_call($path, $method, $parameters = array()) {
        if (!$this->vendor_id || !$this->vendor_auth_code) {
            throw new \Exception(self::ERR_204, 204);
        }

        // add auth data to parameters and build http query string
        $parameters['vendor_id'] = $this->vendor_id;
        $parameters['vendor_auth_code'] = $this->vendor_auth_code;
        $parameters = http_build_query($parameters);

        // make a curl call
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_TIMEOUT, $this->timeout);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        if (strtoupper($method) == 'POST') {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $parameters);
        } else if (strtoupper($method) == 'GET') {
            $path = $path . '?' . $parameters;
        }
        curl_setopt($ch, CURLOPT_URL, $this->base_url . $this->api_version . $path);
        $str_api_response = curl_exec($ch);
        $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curl_error = curl_error($ch);
        curl_close($ch);

        // check for curl error
        if (strlen($curl_error) > 0) {
            throw new \Exception(self::ERR_200 . $curl_error, 200);
        }

        // check for http error
        if ($http_status >= 400) {
            throw new \Exception(self::ERR_201 . $http_status, 201);
        }

        $arr_api_response = json_decode($str_api_response, true);

        // check if api response is well-formed
        if (!is_array($arr_api_response)) {
            throw new \Exception(self::ERR_202 . $str_api_response, 202);
        }

        // check is api response is correct
        if ($arr_api_response['success'] !== true) {
            throw new \Exception('API error: ' . constant('self::ERR_' . $arr_api_response['error']['code']), $arr_api_response['error']['code']);
        }

        return $arr_api_response['response'];
    }

    /**
     * Generate pay link for regular product
     * @param int $product_id - the id of the product
     * @param array $optional_arguments - an associative array of optional parameters:
     * - string 'title' - override product title
     * - string 'image_url' - override product image
     * - float 'price' - override product price
     * - string 'return_url' - url to redirect to after transaction is complete
     * - bool 'discountable' - whether coupon can be apply to checkout by user
     * - string 'coupon_code' - discount coupon code
     * - bool 'locker_visible' - whether product is visible in user's locker
     * - bool 'quantity_variable' - whether product quantity can be changed by user
     * - string 'paypal_cancel_url' - url to redirect to when paypal transaction was canceled
     * - int 'expires' - checkout expiration date, timestamp
     * - bool 'is_popup' - whether checkout is being displayed as popup
     * - string 'parent_url' - url to redirect to when close button on checkout popup is clicked
     * - array 'affiliates' - every element should contain affiliate_id as key, and affiliate_commission as value
     * Commission value should be float, so commission '0.1' equals 10%.
     * - array 'stylesheets' - every element should contain stylesheet type as key, and code as value
     * @return string - pay link
     */
    public function generate_product_pay_link($product_id, array $optional_arguments = array()) {

        $data = array();
        $data['product_id'] = Filters::filter_product_id($product_id);
        if (isset($optional_arguments['title'])) {
            $data['title'] = Filters::filter_title($optional_arguments['title']);
        }
        if (isset($optional_arguments['image_url'])) {
            $data['image_url'] = Filters::filter_image_url($optional_arguments['image_url']);
        }
        if (isset($optional_arguments['price'])) {
            $data['price'] = Filters::filter_price($optional_arguments['price']);
        }
        if (isset($optional_arguments['return_url'])) {
            $data['return_url'] = Filters::filter_return_url($optional_arguments['return_url']);
        }
        if (isset($optional_arguments['discountable'])) {
            $data['discountable'] = Filters::filter_discountable($optional_arguments['discountable']);
        }
        if (isset($optional_arguments['coupon_code'])) {
            $data['discountable'] = 1;
        }
        if (isset($optional_arguments['locker_visible'])) {
            $data['locker_visible'] = Filters::filter_locker_visible($optional_arguments['locker_visible']);
        }
        if (isset($optional_arguments['quantity_variable'])) {
            $data['quantity_variable'] = Filters::filter_quantity_variable($optional_arguments['quantity_variable']);
        }
        if (isset($optional_arguments['paypal_cancel_url'])) {
            $data['paypal_cancel_url'] = Filters::filter_paypal_cancel_url($optional_arguments['paypal_cancel_url']);
        }
        if (isset($optional_arguments['expires'])) {
            $data['expires'] = Filters::filter_expires($optional_arguments['expires']);
        }
        if (isset($optional_arguments['is_popup'])) {
            $data['is_popup'] = Filters::filter_is_popup($optional_arguments['is_popup']);
        }
        if (isset($optional_arguments['parent_url'])) {
            $data['parent_url'] = Filters::filter_parent_url($optional_arguments['parent_url']);
        }
        if (isset($optional_arguments['affiliates'])) {
            $data['affiliates'] = Filters::filter_affiliates($optional_arguments['affiliates']);
        }
        if (isset($optional_arguments['stylesheets'])) {
            $data['stylesheets'] = Filters::filter_stylesheets($optional_arguments['stylesheets']);
        }
        // check if webhook_url is provided (forbidden)
        if (isset($optional_arguments['webhook_url'])) {
            throw new \InvalidArgumentException(\Paddle\Api::ERR_314, 314);
        }
        $response = $this->http_call('/product/generate_pay_link', 'POST', $data);
        return $response['url'];
    }

    /**
     * Generate pay link for custom (not existing in Paddle database) product
     * @param string $title - title of custom product
     * @param float $price - price of custom product
     * @param string $image_url - image of custom product
     * @param string $webhook_url - webhook_url of custom product
     * @param array $optional_arguments - an associative array of optional parameters:
     * - string 'return_url' - url to redirect to after transaction is complete
     * - bool 'locker_visible' - whether product is visible in user's locker
     * - bool 'quantity_variable' - whether product quantity can be changed by user
     * - string 'paypal_cancel_url' - url to redirect to when paypal transaction was canceled
     * - int 'expires' - checkout expiration date, timestamp
     * - bool 'is_popup' - whether checkout is being displayed as popup
     * - string 'parent_url' - url to redirect to when close button on checkout popup is clicked
     * - array 'affiliates' - every element should contain affiliate_id as key, and affiliate_commission as value.
     * Commission value should be float, so commission '0.1' equals 10%.
     * - array 'stylesheets' - every element should contain stylesheet type as key, and code as value
     * @return string - pay link
     */
    public function generate_custom_product_pay_link($title, $price, $image_url, $webhook_url, array $optional_arguments) {
        $data = array();
        $data['title'] = Filters::filter_title($title);
        $data['price'] = Filters::filter_price($price);
        $data['image_url'] = Filters::filter_image_url($image_url);
        $data['webhook_url'] = Filters::filter_webhook_url($webhook_url);
        if (isset($optional_arguments['return_url'])) {
            $data['return_url'] = Filters::filter_return_url($optional_arguments['return_url']);
        }
        if (isset($optional_arguments['locker_visible'])) {
            $data['locker_visible'] = Filters::filter_locker_visible($optional_arguments['locker_visible']);
        }
        if (isset($optional_arguments['quantity_variable'])) {
            $data['quantity_variable'] = Filters::filter_quantity_variable($optional_arguments['quantity_variable']);
        }
        if (isset($optional_arguments['paypal_cancel_url'])) {
            $data['paypal_cancel_url'] = Filters::filter_paypal_cancel_url($optional_arguments['paypal_cancel_url']);
        }
        if (isset($optional_arguments['expires'])) {
            $data['expires'] = Filters::filter_expires($optional_arguments['expires']);
        }
        if (isset($optional_arguments['is_popup'])) {
            $data['is_popup'] = Filters::filter_is_popup($optional_arguments['is_popup']);
        }
        if (isset($optional_arguments['parent_url'])) {
            $data['parent_url'] = Filters::filter_parent_url($optional_arguments['parent_url']);
        }
        if (isset($optional_arguments['affiliates'])) {
            $data['affiliates'] = Filters::filter_affiliates($optional_arguments['affiliates']);
        }
        if (isset($optional_arguments['stylesheets'])) {
            $data['stylesheets'] = Filters::filter_stylesheets($optional_arguments['stylesheets']);
        }

        if (isset($optional_arguments['passthrough'])) {
            $data['passthrough'] = $optional_arguments['passthrough'];
        }
        if (isset($optional_arguments['customer_email'])) {
            $data['customer_email'] = $optional_arguments['customer_email'];
        }
        // discountable (forbidden)
        if (isset($optional_arguments['discountable'])) {
            throw new \InvalidArgumentException(\Api::ERR_316, 316);
        }
        // coupon_code (forbidden)
        if (isset($optional_arguments['coupon_code'])) {
            throw new \InvalidArgumentException(\Api::ERR_317, 317);
        }
        // check product_id (forbidden)
        if (isset($optional_arguments['product_id'])) {
            throw new \InvalidArgumentException(\Api::ERR_318, 318);
        }

        $response = $this->http_call('/product/generate_pay_link', 'POST', $data);
        return $response['url'];
    }

    /**
     * Generate license code for framework product
     * @param int $product_id - the id of the product
     * @return string - license code
     */
    public function generate_license($product_id) {
        $data = array();
        $data['product_id'] = Filters::filter_product_id($product_id);
        $response = $this->http_call('/product/generate_license', 'POST', $data);
        return $response['license_code'];
    }

    /**
     * Get paginated list of products including details of each product
     * @param int $limit - number of returned products
     * @param int $offset - offset from first product
     * @return array - returned array contains:
     * - int 'total' - total number of products
     * - int 'count' - number of returned products
     * - array 'products' - returned products, each of whitch contains:
     * - int 'id' - id of the product
     * - string 'name' - name of the product
     * - string 'description' - description of the product
     * - float 'base_price' - base price of the product
     * - float 'sale_price' - sale price of the product
     * - array 'screenshots' - screenshots of the product
     * - string 'icon' - image of the product
     */
    public function get_products($limit = 1, $offset = 0) {
        $data = array();
        $data['limit'] = Filters::filter_limit($limit);
        $data['offset'] = Filters::filter_offset($offset);
        return $this->http_call('/product/get_products', 'POST', $data);
    }

    /**
     * Get an array of customers details
     * @param int $product_id - the id of product for which report will be created,
     * if not provided report will contain all products data
     * @return array - returned array contains:
     * - string 'full_name' - full name of the customer
     * - string 'email' - email address of the customer
     */
    public function generate_customers_report($product_id = null) {
        $data = array();
        if (isset($product_id)) {
            $data['product_id'] = Filters::filter_product_id($product_id);
        }
        return $this->http_call('/report/customers', 'GET', $data);
    }

    /**
     * Get an array of sent licenses details
     * @param int $product_id - the id of product for which report will be created
     * if not provided report will contain all products data
     * @return array - returned array contains:
     * - string 'customer_name' - full name of the customer
     * - string 'customer_email' - email address of the customer
     * - string 'product_name' - name of the product
     * - string 'license_code' - license code
     */
    public function generate_sent_licenses_report($product_id = null) {
        $data = array();
        if (isset($product_id)) {
            $data['product_id'] = Filters::filter_product_id($product_id);
        }
        return $this->http_call('/report/sent_licenses', 'GET', $data);
    }

    /**
     * Get an array of orders details
     * @param int $product_id - the id of product for which report will be created
     * if not provided report will contain all products data
     * @param int $start_timestamp - report start time
     * @param int $end_timestamp - report end date
     * @return array - returned array contains:
     * - int 'order_id' - id of the order
     * - string 'product_name' - name of the product
     * - float 'your_earnings' - your earnings
     * - string 'earnings_currency' - earnings currency
     * - string 'sale_date' - sale date
     */
    public function generate_orders_report($product_id = null, $start_timestamp = null, $end_timestamp = null) {
        $data = array();
        if (isset($product_id)) {
            $data['product_id'] = Filters::filter_product_id($product_id);
        }
        if (isset($start_timestamp)) {
            $data['from_date'] = Filters::filter_start_timestamp($start_timestamp);
        }
        if (isset($end_timestamp)) {
            $data['to_date'] = Filters::filter_end_timestamp($end_timestamp);
        }
        return $this->http_call('/report/orders', 'GET', $data);
    }

    /**
     * Get an array of license activations details
     * Activations are reportable the day after they occur - so any activations from today will not be included
     * @param int $product_id - the id of product for which report will be created
     * if not provided report will contain all products data
     * @param int $start_timestamp - report start time
     * @param int $end_timestamp - report end date
     * @return array - returned array contains:
     * - string 'license_code' - license code
     * - string 'activation_date' - activation date
     * - string 'customer_ip' - customer ip
     * - string 'customer_email' - customer email
     */
    public function generate_license_activations_report($product_id = null, $start_timestamp = null, $end_timestamp = null) {
        $data = array();
        if (isset($product_id)) {
            $data['product_id'] = Filters::filter_product_id($product_id);
        }
        if (isset($start_timestamp)) {
            $data['from_date'] = Filters::filter_start_timestamp($start_timestamp);
        }
        if (isset($end_timestamp)) {
            $data['to_date'] = Filters::filter_end_timestamp($end_timestamp);
        }
        return $this->http_call('/report/license_activations', 'GET', $data);
    }

    /**
     * Generate credentials to be used to call other API methods
     * @param string $vendor_email
     * @param string $vendor_password
     * @return array - returned array contains:
     * - int 'vendor_id'
     * - string 'vendor_auth_code'
     */
    public function generate_auth_code($vendor_email, $vendor_password) {
        $data = array();
        $data['email'] = Filters::filter_email($vendor_email);
        $data['password'] = $vendor_password;
        return $this->http_call('/user/auth', 'POST', $data);
    }

    /**
     * Register external application and receive auth code, that application can use to call API methods
     * @param string $application_name - application name
     * @param string $application_description - application description
     * @param string $application_icon_url - application icon url
     * @return string
     */
    public function register_external_application($application_name, $application_description, $application_icon_url) {
        $data = array();
        $data['name'] = $application_name;
        $data['description'] = $application_description;
        $data['icon'] = Filters::filter_application_icon_url($application_icon_url);
        $response = $this->http_call('/user/getcode', 'POST', $data);
        return $response['code'];
    }

    /**
     * Get vendor public key
     * @return string
     */
    public function get_vendor_public_key() {
        $response = $this->http_call('/user/get_public_key', 'POST');
        return $response['public_key'];
    }

}
