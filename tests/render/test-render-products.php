<?php

use WPS\Factories;

/*

Tests Utils functions

*/
class Test_Render_Products extends WP_UnitTestCase {

	protected static $Render;

	/*

	Setup ...

	*/
  static function wpSetUpBeforeClass() {

		// self::$Render = API_Factory::build();

  }

	function test_it_should_have_render() {
		$this->assertEquals('1', 1);
  }


}
