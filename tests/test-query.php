<?php

use WPS\Factories;
use WPS\Query;


/*

Tests Utils functions

*/
class Test_Query extends WP_UnitTestCase {

	protected static $Query;
	protected static $DB_Products;
	protected static $mock_product;
	protected static $mock_products;

  static function wpSetUpBeforeClass() {

		self::$Query 			= Factories\Query_Factory::build();
		self::$DB_Products 		= Factories\DB\Products_Factory::build();

  }


	function test_it_should_return_default_query_array() {

		$default_query_array = self::$DB_Products->get_default_products_query();

		$this->assertInternalType('array', $default_query_array);
		$this->assertCount(7, $default_query_array);
		$this->assertArrayHasKey('where', $default_query_array);
		$this->assertArrayHasKey('groupby', $default_query_array);
		$this->assertArrayHasKey('join', $default_query_array);
		$this->assertArrayHasKey('orderby', $default_query_array);
		$this->assertArrayHasKey('distinct', $default_query_array);
		$this->assertArrayHasKey('fields', $default_query_array);
		$this->assertArrayHasKey('limits', $default_query_array);

	}



	function test_it_should_add_default_order_to_query() {

		$query_array = self::$DB_Products->get_default_products_query();

		$attrs = [
			'shortcode_attr_1' => 1,
			'shortcode_attr_2' => 2,
			'shortcode_attr_3' => 3
		];

		$resulting = self::$Query->construct_order_clauses($query_array, $attrs);

		$this->assertArrayHasKey('orderby', $resulting);
		$this->assertNotFalse(strpos($resulting['orderby'], 'DESC'));

	}


	function test_it_should_add_custom_order_to_query() {

		$query_array = self::$DB_Products->get_default_products_query();

		$attrs = [
			'shortcode_attr_1' => 1,
			'shortcode_attr_2' => 2,
			'shortcode_attr_3' => 3,
			'order' => 'ASC'
		];

		$resulting = self::$Query->construct_order_clauses($query_array, $attrs);

		$this->assertArrayHasKey('orderby', $resulting);
		$this->assertNotFalse(strpos($resulting['orderby'], 'ASC'));

	}


}
