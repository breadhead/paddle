<?php

require_once __DIR__ . '/test_case.php';

class Generate_Custom_Product_Pay_Link extends Test_Case {

	private $title = 'test title';
	private $price = 10;
	private $imageUrl = 'http://example.com';
	private $webhookUrl = 'http://example.com';

	public function test_valid_arguments() {
		$data = array(
			'return_url' => 'http://example.com',
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
		$url = $this->api->generateCustomProductPayLink($this->title, $this->price, $this->imageUrl, $this->webhookUrl, $data);
		$this->assertEquals($url, filter_var($url, FILTER_VALIDATE_URL));
	}

	/**
	 * @dataProvider invalidUrlDataProvider
	 */
	public function test_invalid_return_url($url) {
		$data = array(
			'return_url' => $url
		);

		$this->setExpectedException('InvalidArgumentException', Breadhead\Paddle\Api::ERR_305, 305);
		$this->api->generateCustomProductPayLink($this->title, $this->price, $this->imageUrl, $this->webhookUrl, $data);
	}

	/**
	 * @dataProvider invalidUrlDataProvider
	 */
	public function test_invalid_paypal_cancel_url($url) {
		$data = array(
			'paypal_cancel_url' => $url
		);

		$this->setExpectedException('InvalidArgumentException', Breadhead\Paddle\Api::ERR_306, 306);
		$this->api->generateCustomProductPayLink($this->title, $this->price, $this->imageUrl, $this->webhookUrl, $data);
	}

	public function invalidExpiresDataProvider() {
		return array(
			array('string'),
			array(true),
			array(false)
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
		$this->api->generateCustomProductPayLink($this->title, $this->price, $this->imageUrl, $this->webhookUrl, $data);
	}

	public function test_past_expires() {
		$data = array(
			'expires' => 100
		);

		$this->setExpectedException('InvalidArgumentException', Breadhead\Paddle\Api::ERR_308, 308);
		$this->api->generateCustomProductPayLink($this->title, $this->price, $this->imageUrl, $this->webhookUrl, $data);
	}

	/**
	 * @dataProvider invalidUrlDataProvider
	 */
	public function test_invalid_parent_url($url) {
		$data = array(
			'parent_url' => $url
		);

		$this->setExpectedException('InvalidArgumentException', Breadhead\Paddle\Api::ERR_309, 309);
		$this->api->generateCustomProductPayLink($this->title, $this->price, $this->imageUrl, $this->webhookUrl, $data);
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
		$this->api->generateCustomProductPayLink($this->title, $this->price, $this->imageUrl, $this->webhookUrl, $data);
	}

	public function invalidArrayAffiliatesDataProvider() {
		return array(
			array(array(1))
		);
	}

	/**
	 * @dataProvider invalidArrayAffiliatesDataProvider
	 */
	public function test_invalid_array_affiliates($affiliates) {
		$data = array(
			'affiliates' => $affiliates
		);

		$this->setExpectedException('InvalidArgumentException', Breadhead\Paddle\Api::ERR_311, 311);
		$this->api->generateCustomProductPayLink($this->title, $this->price, $this->imageUrl, $this->webhookUrl, $data);
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
	public function test_not_array_stylesheets($stylesheets) {
		$data = array(
			'stylesheets' => $stylesheets
		);

		$this->setExpectedException('InvalidArgumentException', Breadhead\Paddle\Api::ERR_312, 312);
		$this->api->generateCustomProductPayLink($this->title, $this->price, $this->imageUrl, $this->webhookUrl, $data);
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
		$this->api->generateCustomProductPayLink($this->title, $this->price, $this->imageUrl, $this->webhookUrl, $data);
	}

	/**
	 * @dataProvider invalidUrlDataProvider
	 */
	public function test_invalid_webhook_url($url) {
		$this->setExpectedException('InvalidArgumentException', Breadhead\Paddle\Api::ERR_315, 315);
		$this->api->generateCustomProductPayLink($this->title, $this->price, $this->imageUrl, $url, array());
	}

	public function test_forbidden_discountable() {
		$this->setExpectedException('InvalidArgumentException', Breadhead\Paddle\Api::ERR_316, 316);
		$data = array(
			'discountable' => true
		);
		$this->api->generateCustomProductPayLink($this->title, $this->price, $this->imageUrl, $this->webhookUrl, $data);
	}

	public function test_forbidden_coupon_code() {
		$this->setExpectedException('InvalidArgumentException', Breadhead\Paddle\Api::ERR_317, 317);
		$data = array(
			'coupon_code' => true
		);
		$this->api->generateCustomProductPayLink($this->title, $this->price, $this->imageUrl, $this->webhookUrl, $data);
	}

	public function test_forbidden_product_id() {
		$this->setExpectedException('InvalidArgumentException', Breadhead\Paddle\Api::ERR_318, 318);
		$data = array(
			'product_id' => true
		);
		$this->api->generateCustomProductPayLink($this->title, $this->price, $this->imageUrl, $this->webhookUrl, $data);
	}

}
