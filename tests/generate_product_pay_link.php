<?php

require_once __DIR__ . '/test_case.php';

class Generate_Product_Pay_Link extends Test_Case {

	public function test_valid_arguments() {
		$data = array(
			'title' => 'test title',
			'image_url' => 'http://example.com',
			'price' => 10,
			'return_url' => 'http://example.com',
			'discountable' => true,
			'coupon_code' => $this->productCouponCode,
			'locker_visible' => true,
			'quantity_variable' => true,
			'paypal_cancel_url' => 'http://example.com',
			'expires' => strtotime('10 December 2030'),
			'is_popup' => true,
			'parent_url' => 'http://example.com',
			'affiliates' => array(
				$this->affiliateId => $this->affiliateCommission
			),
			'stylesheets' => array(
				$this->vendorStylesheetType => $this->vendorStylesheetIdentifier
			)
		);

		// check if valid paylink was returned by api
		$url = $this->api->generateProductPayLink($this->productId, $data);
		$this->assertEquals($url, filter_var($url, FILTER_VALIDATE_URL));

		$data = array(
			'discountable' => false,
			'locker_visible' => false,
			'quantity_variable' => false,
			'is_popup' => false
		);

		// check if valid paylink was returned by api
		$url = $this->api->generateProductPayLink($this->productId, $data);
		$this->assertEquals($url, filter_var($url, FILTER_VALIDATE_URL));
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
		$this->api->generateProductPayLink($id);
	}

	public function invalidTitleDataProvider() {
		return array(
			array(100),
			array(true),
			array(false),
			array(array()),
			array(new stdClass())
		);
	}

	/**
	 * @dataProvider invalidTitleDataProvider
	 */
	public function test_invalid_title($title) {
		$data = array(
			'title' => $title
		);

		$this->setExpectedException('InvalidArgumentException', Breadhead\Paddle\Api::ERR_301, 301);
		$this->api->generateProductPayLink($this->productId, $data);
	}

	public function test_forbidden_webhook_url() {
		$data = array(
			'webhook_url' => 'http://example.com'
		);

		$this->setExpectedException('InvalidArgumentException', Breadhead\Paddle\Api::ERR_314, 314);
		$this->api->generateProductPayLink($this->productId, $data);
	}

	/**
	 * @dataProvider invalidUrlDataProvider
	 */
	public function test_invalid_image_url($url) {
		$data = array(
			'image_url' => $url
		);

		$this->setExpectedException('InvalidArgumentException', Breadhead\Paddle\Api::ERR_302, 302);
		$this->api->generateProductPayLink($this->productId, $data);
	}

	public function nonNumericPriceDataProvider() {
		return array(
			array('string'),
			array(true),
			array(false),
			array(array()),
			array(new stdClass())
		);
	}

	/**
	 * @dataProvider nonNumericPriceDataProvider
	 */
	public function test_non_numeric_price($price) {
		$data = array(
			'price' => $price
		);

		$this->setExpectedException('InvalidArgumentException', Breadhead\Paddle\Api::ERR_303, 303);
		$this->api->generateProductPayLink($this->productId, $data);
	}

	public function test_negative_price() {
		$data = array(
			'price' => -1
		);

		$this->setExpectedException('InvalidArgumentException', Breadhead\Paddle\Api::ERR_304, 304);
		$this->api->generateProductPayLink($this->productId, $data);
	}

	/**
	 * @dataProvider invalidUrlDataProvider
	 */
	public function test_invalid_return_url($url) {
		$data = array(
			'return_url' => $url
		);

		$this->setExpectedException('InvalidArgumentException', Breadhead\Paddle\Api::ERR_305, 305);
		$this->api->generateProductPayLink($this->productId, $data);
	}

	/**
	 * @dataProvider invalidUrlDataProvider
	 */
	public function test_invalid_paypal_cancel_url($url) {
		$data = array(
			'paypal_cancel_url' => $url
		);

		$this->setExpectedException('InvalidArgumentException', Breadhead\Paddle\Api::ERR_306, 306);
		$this->api->generateProductPayLink($this->productId, $data);
	}

	public function invalidExpiresDataProvider() {
		return array(
			array('string'),
			array(true),
			array(false),
		);
	}

	/**
	 * @dataProvider invalidExpiresDataProvider
	 */
	public function test_invalid_expires($expires) {
		$data = array(
			'expires' => $expires
		);

		$this->setExpectedException('InvalidArgumentException', Breadhead\Paddle\Api::ERR_307, 307);
		$this->api->generateProductPayLink($this->productId, $data);
	}

	public function test_past_expires() {
		$data = array(
			'expires' => 100
		);

		$this->setExpectedException('InvalidArgumentException', Breadhead\Paddle\Api::ERR_308, 308);
		$this->api->generateProductPayLink($this->productId, $data);
	}

	/**
	 * @dataProvider invalidUrlDataProvider
	 */
	public function test_invalid_parent_url($url) {
		$data = array(
			'parent_url' => $url
		);

		$this->setExpectedException('InvalidArgumentException', Breadhead\Paddle\Api::ERR_309, 309);
		$this->api->generateProductPayLink($this->productId, $data);
	}

	public function notArrayAffiliatesDataProvider() {
		return array(
			array('string'),
			array(true),
			array(false),
			array(1)
		);
	}

	/**
	 * @dataProvider notArrayAffiliatesDataProvider
	 */
	public function test_not_array_affiliates($affiliates) {
		$data = array(
			'affiliates' => $affiliates
		);

		$this->setExpectedException('InvalidArgumentException', Breadhead\Paddle\Api::ERR_310, 310);
		$this->api->generateProductPayLink($this->productId, $data);
	}

	public function invalidArrayAffiliatesDataProvider() {
		return array(
			array(array('foo' => null))
		);
	}

	/**
	 * @dataProvider invalidArrayAffiliatesDataProvider
	 */
	public function invalid_array_array_affiliates($affiliates) {
		$data = array(
			'affiliates' => $affiliates
		);

		$this->setExpectedException('InvalidArgumentException', Breadhead\Paddle\Api::ERR_311, 311);
		$this->api->generateProductPayLink($this->productId, $data);
	}

	public function notArrayStylesheetsDataProvider() {
		return array(
			array('string'),
			array(true),
			array(false),
			array(1)
		);
	}

	/**
	 * @dataProvider notArrayStylesheetsDataProvider
	 */
	public function test_not_array_invalid_stylesheets($stylesheets) {
		$data = array(
			'stylesheets' => $stylesheets
		);

		$this->setExpectedException('InvalidArgumentException', Breadhead\Paddle\Api::ERR_312, 312);
		$this->api->generateProductPayLink($this->productId, $data);
	}

	public function invalidArrayStylesheetsDataProvider() {
		return array(
			array(array(1))
		);
	}

	/**
	 * @dataProvider invalidArrayStylesheetsDataProvider
	 */
	public function test_invalid_array_stylesheets($stylesheets) {
		$data = array(
			'stylesheets' => $stylesheets
		);

		$this->setExpectedException('InvalidArgumentException', Breadhead\Paddle\Api::ERR_313, 313);
		$this->api->generateProductPayLink($this->productId, $data);
	}

}
