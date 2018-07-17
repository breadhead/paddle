<?php

require_once __DIR__ . '/test_case.php';

class Generate_Sent_Licenses_Report extends Test_Case {

	public function test_valid_arguments() {
		$this->assertTrue(is_array($this->api->generate_sent_licenses_report($this->product_id)));
		$this->assertTrue(is_array($this->api->generate_sent_licenses_report()));
	}

	public function invalid_product_id_data_provider() {
		return array(
			array('string'),
			array(0),
			array(-1),
			array(true),
			array(false),
			array(array()),
			array(new stdClass())
		);
	}

	/**
	 * @dataProvider invalid_product_id_data_provider
	 */
	public function test_invalid_product_id($id) {
		$this->setExpectedException('InvalidArgumentException', Breadhead\Paddle\Api::ERR_300, 300);
		$this->api->generate_sent_licenses_report($id);
	}

}
