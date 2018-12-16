<?php

require_once __DIR__ . '/test_case.php';

class registerExternalApplication extends Test_Case {

	private $name = 'test name';
	private $description = 'test description';
	private $icon = 'http://example.com';

	public function test_valid_arguments() {
		$code = $this->api->registerExternalApplication($this->name, $this->description, $this->icon);
		$this->assertTrue(is_string($code));
	}

	/**
	 * @dataProvider invalid_url_data_provider
	 */
	public function test_invalid_application_icon_url($icon) {
		$this->setExpectedException('InvalidArgumentException', Breadhead\Paddle\Api::ERR_324, 324);
		$this->api->registerExternalApplication($this->name, $this->description, $icon);
	}

}
