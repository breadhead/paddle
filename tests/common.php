<?php

require_once __DIR__ . '/test_case.php';

class Common extends Test_Case {

	/**
	 * Check if fields are being set via constructor as well as via setters
	 */
	public function test_api_setters() {
		$api1 = new Breadhead\Paddle\Api(1, 1, 1);
		$api2 = new Breadhead\Paddle\Api();
		$api2->setVendorCredentials(1, 1);
		$api2->setTimeout(1);
		$this->assertEquals($api1, $api2);
	}

	public function test_invalid_credentials() {
		$this->setExpectedException('Exception', Breadhead\Paddle\Api::ERR_107, 107);
		$api1 = new Breadhead\Paddle\Api(1, 1);
		$api1->generateCustomersReport();
	}

	/**
	 * Call not existing host
	 */
	public function test_curl_error() {
		$this->setExpectedException('Exception', Breadhead\Paddle\Api::ERR_200, 200);
		$this->setPrivateField($this->api, 'base_url', '');
		$this->setPrivateField($this->api, 'api_version', '');
		$this->callPrivateMethod($this->api, 'httpCall', array('not_existing_host', 'GET'));
	}

	/**
	 * Call url that returns http 404
	 */
	public function test_invalid_http_response_code() {
		$this->setExpectedException('Exception', Breadhead\Paddle\Api::ERR_201, 201);
		$this->setPrivateField($this->api, 'base_url', '');
		$this->setPrivateField($this->api, 'api_version', '');
		$this->callPrivateMethod($this->api, 'httpCall', array('http://pay.paddle.com', 'GET'));
	}

	/**
	 * Call valid, but not API host to get not valid API response
	 */
	public function test_invalid_api_response() {
		$this->setExpectedException('Exception', Breadhead\Paddle\Api::ERR_202, 202);
		$this->setPrivateField($this->api, 'base_url', '');
		$this->setPrivateField($this->api, 'api_version', '');
		$this->callPrivateMethod($this->api, 'httpCall', array('http://example.com', 'GET'));
	}

	public function invalidTimeoutDataProvider() {
		return array(
			array('string'),
			array(true),
			array(false),
			array(0),
			array(-1)
		);
	}

	/**
	 * @dataProvider invalidTimeoutDataProvider
	 */
	public function test_invalid_timeout($value) {
		$this->setExpectedException('InvalidArgumentException', Breadhead\Paddle\Api::ERR_203, 203);
		new Breadhead\Paddle\Api(1, 1, $value);
	}

	public function test_vendor_credentials_not_provided() {
		$this->setExpectedException('Exception', Breadhead\Paddle\Api::ERR_204, 204);
		$api = new Breadhead\Paddle\Api();
		$api->generateCustomersReport();
	}

}
