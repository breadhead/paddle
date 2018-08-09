<?php

require_once __DIR__ . '/test_case.php';

class Generate_License_Activations_Report extends Test_Case {

	public function test_valid_arguments() {
		$this->assertTrue(is_array(
				$this->api->generateLicenseActivationsReport($this->productId)
		));
		$this->assertTrue(is_array(
				$this->api->generateLicenseActivationsReport()
		));
		$this->assertTrue(is_array(
				$this->api->generateLicenseActivationsReport(null, strtotime('1 January 2000'), strtotime('1 January 2100'))
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
		$this->api->generateLicenseActivationsReport($id);
	}

	public function invalidTimestampDataProvider() {
		return array(
			array('string'),
			array(true),
			array(false),
			array(array()),
			array(new stdClass())
		);
	}

	/**
	 * @dataProvider invalidTimestampDataProvider
	 */
	public function test_invalid_start_timestamp($startTimestamp) {
		$this->setExpectedException('InvalidArgumentException', Breadhead\Paddle\Api::ERR_321, 321);
		$this->api->generateLicenseActivationsReport(null, $startTimestamp);
	}

	/**
	 * @dataProvider invalid_timestamp_data_provider
	 */
	public function test_invalid_end_timestamp($endTimestamp) {
		$this->setExpectedException('InvalidArgumentException', Breadhead\Paddle\Api::ERR_322, 322);
		$this->api->generateLicenseActivationsReport(null, null, $endTimestamp);
	}

}
