<?php

require_once __DIR__ . '/test_case.php';

class Generate_Auth_Code extends Test_Case {

	public function test_valid_arguments() {
		// generate new api credentials
		$generate_auth_code_result = $this->api->generate_auth_code($this->vendor_email, $this->vendor_password);
		$this->assertTrue(is_int($generate_auth_code_result['vendor_id']));
		$this->assertTrue(is_string($generate_auth_code_result['vendor_auth_code']));

		// create new api instance with new credentials
		$api = new Paddle\Api($generate_auth_code_result['vendor_id'], $generate_auth_code_result['vendor_auth_code']);

		// run any api method to check if auth is ok
		$get_customers_report_result = $api->generate_customers_report();
		$this->assertTrue(is_array($get_customers_report_result));
	}

	public function invalid_email_data_provider() {
		return array(
			array('string'),
			array(true),
			array(false),
			array(1)
		);
	}

	/**
	 * @dataProvider invalid_email_data_provider
	 */
	public function test_invalid_email($email) {
		$this->setExpectedException('InvalidArgumentException', Breadhead\Paddle\Api::ERR_323, 323);
		$this->api->generate_auth_code($email, 'password');
	}

}
