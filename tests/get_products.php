<?php

require_once __DIR__ . '/test_case.php';

class Get_Products extends Test_Case {

	public function test_valid_arguments() {
		$response = $this->api->getProducts(10, 0);
		$this->assertTrue(is_array($response));
	}

	public function invalidLimitProvider() {
		return array(
			array('string'),
			array(0),
			array(-1),
			array(false),
			array(true),
			array(array()),
			array(new stdClass())
		);
	}

	/**
	 * @dataProvider invalidLimitProvider
	 */
	public function test_invalid_limit($limit) {
		$this->setExpectedException('InvalidArgumentException', Breadhead\Paddle\Api::ERR_319, 319);
		$this->api->getProducts($limit);
	}

	public function invalidOffsetProvider() {
		return array(
			array('string'),
			array(-1),
			array(false),
			array(true),
			array(array()),
			array(new stdClass())
		);
	}

	/**
	 * @dataProvider invalidOffsetProvider
	 */
	public function test_invalid_offset($offset) {
		$this->setExpectedException('InvalidArgumentException', Breadhead\Paddle\Api::ERR_320, 320);
		$this->api->getProducts(1, $offset);
	}
}
