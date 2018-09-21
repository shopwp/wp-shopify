<?php

use WPS\Factories\WS_Products_Factory;

/*

Tests Utils functions

*/
class Test_WS_Products extends WP_UnitTestCase {

	protected static $WS_Products;

  static function setUpBeforeClass() {

		self::$WS_Products = WS_Products_Factory::build();

  }

















	
	function test_it_should_normalize_products_response() {

		$test_products_obj = new \stdClass;
		$test_products_obj->products = 'products';

		$test_product_listings_obj = new \stdClass;
		$test_product_listings_obj->product_listings = 'product_listings';

		$test_products_obj_response = self::$WS_Products->normalize_products_response($test_products_obj);
		$test_product_listings_obj_response = self::$WS_Products->normalize_products_response($test_product_listings_obj);

		$this->assertEquals('products', $test_products_obj_response);
		$this->assertEquals('product_listings', $test_product_listings_obj_response);

	}







	function test_it_should_find_total_pages_of_product_ids() {

		// The number we pass in replicate the total number of products found
		$result = self::$WS_Products->find_total_pages_of_product_ids(1);
		$result_2 = self::$WS_Products->find_total_pages_of_product_ids(2);
		$result_3 = self::$WS_Products->find_total_pages_of_product_ids(null);
		$result_4 = self::$WS_Products->find_total_pages_of_product_ids('1');
		$result_5 = self::$WS_Products->find_total_pages_of_product_ids('10000');
		$result_6 = self::$WS_Products->find_total_pages_of_product_ids('10001');
		$result_7 = self::$WS_Products->find_total_pages_of_product_ids(49999);

		$this->assertInternalType('int', $result);
		$this->assertEquals(1, $result);

		$this->assertInternalType('int', $result_2);
		$this->assertEquals(1, $result_2);

		$this->assertInternalType('int', $result_3);
		$this->assertEquals(1, $result_3);

		$this->assertInternalType('int', $result_4);
		$this->assertEquals(1, $result_4);

		$this->assertInternalType('int', $result_5);
		$this->assertEquals(1, $result_5);

		$this->assertInternalType('int', $result_6);
		$this->assertEquals(2, $result_6);

		$this->assertInternalType('int', $result_7);
		$this->assertEquals(5, $result_7);

	}






	function test_it_should_find_no_product_ids_left() {

		$collection_ids = [13123,2552242,252354,24623];

		$result_one = self::$WS_Products->no_product_ids_left(0);
		$result_two = self::$WS_Products->no_product_ids_left([]);
		$result_three = self::$WS_Products->no_product_ids_left(1);

		$this->assertTrue($result_one);
		$this->assertFalse($result_two);
		$this->assertFalse($result_three);

	}

}
