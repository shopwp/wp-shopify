<?php

use WPS\Factories\CPT_Model_Factory;

/*

Tests Utils functions

*/
class Test_CPT_Model extends WP_UnitTestCase {

	protected static $CPT_Model;
	protected static $mock_products;


  static function setUpBeforeClass() {

		self::$CPT_Model								= CPT_Model_Factory::build();
		self::$mock_products            = json_decode( file_get_contents( dirname(__FILE__) . "/mock-data/products-insert.json") );

  }


	function test_it_should_insert_or_update_product_post() {

		$post_id = self::$CPT_Model->insert_or_update_product_post( self::$mock_products[0] );

		$this->assertNotWPError($post_id);
		$this->assertGreaterThan(1, $post_id);

	}


}
