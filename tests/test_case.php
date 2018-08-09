<?php

// autoload paddle library classes
require_once __DIR__ . '/../autoload.php';

class Test_Case extends PHPUnit_Framework_TestCase {

	const CONFIG_FILE_NAME = 'config.php';

	protected $vendorEmail;
	protected $vendorPassword;
	protected $vendorId;
	protected $vendorAuthCode;
	protected $vendorStylesheetType;
	protected $vendorStylesheetIdentifier;
	protected $affiliateId;
	protected $affiliateCommission;
	protected $productId;
	protected $productCouponCode;
	protected $frameworkProductId;
	protected $api;

	public function setUp() {
		parent::setUp();

		// load tests config
		$config = require __DIR__ . '/' . self::CONFIG_FILE_NAME;
		foreach ($config as $key => $value) {
			$this->{$key} = $value;
		}

		// create an api instance
		$this->api = new Breadhead\Paddle\Api($this->vendorId, $this->vendorAuthCode);
	}

	public function test_setup() {
		$missingConfigFieldMessage = 'Please provide all config variables in ' . self::CONFIG_FILE_NAME;
		$this->assertTrue($this->vendorEmail !== null, $missingConfigFieldMessage);
		$this->assertTrue($this->vendorPassword !== null, $missingConfigFieldMessage);
		$this->assertTrue($this->vendorId !== null, $missingConfigFieldMessage);
		$this->assertTrue($this->vendorAuthCode !== null, $missingConfigFieldMessage);
		$this->assertTrue($this->vendorStylesheetType !== null, $missingConfigFieldMessage);
		$this->assertTrue($this->vendorStylesheetIdentifier !== null, $missingConfigFieldMessage);
		$this->assertTrue($this->affiliateId !== null, $missingConfigFieldMessage);
		$this->assertTrue($this->affiliateCommission !== null, $missingConfigFieldMessage);
		$this->assertTrue($this->productId !== null, $missingConfigFieldMessage);
		$this->assertTrue($this->productCouponCode !== null, $missingConfigFieldMessage);
		$this->assertTrue($this->api !== null, 'Api object not created properly');
	}

	public function invalidUrlDataProvider() {
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
     * @throws
	 */
	protected function callPrivateMethod($object, $methodName, array $parameters = array()) {
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
     * @throws
	 */
	protected function setPrivateField($object, $field_name, $field_value) {
		$reflector = new ReflectionProperty(get_class($object), $field_name);
		$reflector->setAccessible(true);
		$reflector->setValue($object, $field_value);
	}

}
