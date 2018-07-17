<?php

require_once __DIR__ . '/test_case.php';

class Generate_License_Activations_Report extends Test_Case {

	public function test_valid_arguments() {
		$this->assertTrue(is_array(
				$this->api->generate_license_activations_report($this->product_id)
		));
		$this->assertTrue(is_array(
				$this->api->generate_license_activations_report()
		));
		$this->assertTrue(is_array(
				$this->api->generate_license_activations_report(null, strtotime('1 January 2000'), strtotime('1 January 2100'))
		));
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
		$this->api->generate_license_activations_report($id);
	}

	public function invalid_timestamp_data_provider() {
		return array(
			array('string'),
			array(true),
			array(false),
			array(array()),
			array(new stdClass())
		);
	}

	/**
	 * @dataProvider invalid_timestamp_data_provider
	 */
	public function test_invalid_start_timestamp($start_timestamp) {
		$this->setExpectedException('InvalidArgumentException', Breadhead\Paddle\Api::ERR_321, 321);
		$this->api->generate_license_activations_report(null, $start_timestamp);
	}

	/**
	 * @dataProvider invalid_timestamp_data_provider
	 */
	public function test_invalid_end_timestamp($end_timestamp) {
		$this->setExpectedException('InvalidArgumentException', Breadhead\Paddle\Api::ERR_322, 322);
		$this->api->generate_license_activations_report(null, null, $end_timestamp);
	}

}
