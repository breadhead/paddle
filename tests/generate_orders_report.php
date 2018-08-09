<?php

require_once __DIR__ . '/test_case.php';

class Generate_Orders_Report extends Test_Case {

	public function test_valid_arguments() {
		$this->assertTrue(is_array(
				$this->api->generateOrdersReport($this->productId)
		));
		$this->assertTrue(is_array(
				$this->api->generateOrdersReport()
		));
		$this->assertTrue(is_array(
				$this->api->generateOrdersReport(null, strtotime('1 January 2000'), strtotime('1 January 2100'))
		));
	}

	public function invalidProductIdDataProvider() {
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
	 * @dataProvider invalidProductIdDataProvider
	 */
	public function test_invalid_product_id($id) {
		$this->setExpectedException('InvalidArgumentException', Breadhead\Paddle\Api::ERR_300, 300);
		$this->api->generateOrdersReport($id);
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
		$this->api->generateOrdersReport(null, $startTimestamp);
	}

	/**
	 * @dataProvider invalidTimestampDataProvider
	 */
	public function test_invalid_end_timestamp($endTimestamp) {
		$this->setExpectedException('InvalidArgumentException', Breadhead\Paddle\Api::ERR_322, 322);
		$this->api->generateOrdersReport(null, null, $endTimestamp);
	}

}
