<?php

use WPS\Factories\API_Factory;

/*

Tests Utils functions

*/
class Test_API extends WP_UnitTestCase {

	protected static $API_Factory;

	/*

	Setup ...

	*/
  static function wpSetUpBeforeClass() {

		self::$API_Factory = API_Factory::build();

  }


	function test_it_should_get_valid_api_error() {

		// Need to delete before inserting since data already exists in these tables
		$result = self::$API_Factory->error('ROUTE', 'MESSAGE', 500);

		$this->assertWPError($result);
		$this->assertEquals('MESSAGE', $result->get_error_message() );
		$this->assertEquals('ROUTE', $result->get_error_code() );

		$error_data = $result->get_error_data();

		$this->assertInternalType('array', $error_data);
		$this->assertEquals(500, $error_data['status'] );


  }


}
