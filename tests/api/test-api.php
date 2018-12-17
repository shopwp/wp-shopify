<?php

use WPS\Factories\API_Factory;

/*

Tests Utils functions

*/
class Test_API extends WP_UnitTestCase {

	protected static $API;

	/*

	Setup ...

	*/
  static function wpSetUpBeforeClass() {

		self::$API = API_Factory::build();

  }


	function test_it_should_get_valid_api_error() {

		// Need to delete before inserting since data already exists in these tables
		$result = self::$API->send_error('THIS IS AN ERROR MESSAGE');

		$this->assertNotWPError($result);
		$this->assertEquals('error', $result['type'] );
		$this->assertEquals('THIS IS AN ERROR MESSAGE', $result['message'] );


  }


	function test_it_should_get_valid_api_warning() {

		// Need to delete before inserting since data already exists in these tables
		$result = self::$API->send_warning('THIS IS A WARNING MESSAGE');

		$this->assertNotWPError($result);
		$this->assertEquals('warning', $result['type'] );
		$this->assertEquals('THIS IS A WARNING MESSAGE', $result['message'] );


  }


}
