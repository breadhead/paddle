<?php

require_once __DIR__ . '/test_case.php';

class Get_Public_Key extends Test_Case {

	public function test_valid_arguments() {
		$public_key = $this->api->get_vendor_public_key();
		$this->assertTrue(is_string($public_key));
	}

}
