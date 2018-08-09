<?php

require_once __DIR__ . '/test_case.php';

class Generate_Auth_Code extends Test_Case {

	public function test_valid_arguments() {
		// generate new api credentials
		$generateAuthCodeResult = $this->api->generateAuthCode($this->vendorEmail, $this->vendorPassword);
		$this->assertTrue(is_int($generateAuthCodeResult['vendor_id']));
		$this->assertTrue(is_string($generateAuthCodeResult['vendor_auth_code']));

		// create new api instance with new credentials
		$api = new \Breadhead\Paddle\Api($generateAuthCodeResult['vendor_id'], $generateAuthCodeResult['vendor_auth_code']);

		// run any api method to check if auth is ok
		$getCustomersReportResult = $api->generateCustomersReport();
		$this->assertTrue(is_array($getCustomersReportResult));
	}

	public function invalidEmailDataProvider() {
		return array(
			array('string'),
			array(true),
			array(false),
			array(1)
		);
	}

	/**
	 * @dataProvider invalidEmailDataProvider
	 */
	public function test_invalid_email($email) {
		$this->setExpectedException('InvalidArgumentException', Breadhead\Paddle\Api::ERR_323, 323);
		$this->api->generateAuthCode($email, 'password');
	}

}
