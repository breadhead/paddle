<?php

require_once __DIR__ . '/test_case.php';

class Generate_Customers_Report extends Test_Case {

	public function test_valid_arguments() {
		$this->assertTrue(is_array($this->api->generateCustomersReport($this->productId)));
		$this->assertTrue(is_array($this->api->generateCustomersReport()));
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
		$this->api->generateCustomersReport($id);
	}

}
