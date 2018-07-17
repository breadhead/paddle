<?php

// autoload paddle library classes
require_once __DIR__ . '/../autoload.php';

class Test_Case extends PHPUnit_Framework_TestCase {

	const CONFIG_FILE_NAME = 'config.php';

	protected $vendor_email;
	protected $vendor_password;
	protected $vendor_id;
	protected $vendor_auth_code;
	protected $vendor_stylesheet_type;
	protected $vendor_stylesheet_identifier;
	protected $affiliate_id;
	protected $affiliate_commission;
	protected $product_id;
	protected $product_coupon_code;
	protected $framework_product_id;
	protected $api;

	public function setUp() {
		parent::setUp();

		// load tests config
		$config = require __DIR__ . '/' . self::CONFIG_FILE_NAME;
		foreach ($config as $key => $value) {
			$this->{$key} = $value;
		}

		// create an api instance
		$this->api = new Breadhead\Paddle\Api($this->vendor_id, $this->vendor_auth_code);
	}

	public function test_setup() {
		$missing_config_field_message = 'Please provide all config variables in ' . self::CONFIG_FILE_NAME;
		$this->assertTrue($this->vendor_email !== null, $missing_config_field_message);
		$this->assertTrue($this->vendor_password !== null, $missing_config_field_message);
		$this->assertTrue($this->vendor_id !== null, $missing_config_field_message);
		$this->assertTrue($this->vendor_auth_code !== null, $missing_config_field_message);
		$this->assertTrue($this->vendor_stylesheet_type !== null, $missing_config_field_message);
		$this->assertTrue($this->vendor_stylesheet_identifier !== null, $missing_config_field_message);
		$this->assertTrue($this->affiliate_id !== null, $missing_config_field_message);
		$this->assertTrue($this->affiliate_commission !== null, $missing_config_field_message);
		$this->assertTrue($this->product_id !== null, $missing_config_field_message);
		$this->assertTrue($this->product_coupon_code !== null, $missing_config_field_message);
		$this->assertTrue($this->api !== null, 'Api object not created properly');
	}

	public function invalid_url_data_provider() {
		return array(
			array('example.com'),
			array('abc'),
			array(100),
			array(true),
			array(false),
			array(array()),
			array(new stdClass())
		);
	}

	/**
	 * Call protected/private method of a class
	 * @param type $object instantiated object that we will run method on
	 * @param type $methodName method name to call
	 * @param array $parameters array of parameters to pass into method
	 */
	protected function call_private_method($object, $methodName, array $parameters = array()) {
		$reflection = new \ReflectionClass(get_class($object));
		$method = $reflection->getMethod($methodName);
		$method->setAccessible(true);
		$method->invokeArgs($object, $parameters);
	}

	/**
	 * Set protected/private field of a class
	 * @param object $object instantiated object that we will run method on
	 * @param string $field_name field name to set
	 * @param mixed $field_value field value to set
	 */
	protected function set_private_field($object, $field_name, $field_value) {
		$reflector = new ReflectionProperty(get_class($object), $field_name);
		$reflector->setAccessible(true);
		$reflector->setValue($object, $field_value);
	}

}
