<?php

use WPS\Factories;
use WPS\CPT;


/*

Tests Utils functions

*/
class Test_CPT_Query extends WP_UnitTestCase {

	protected static $CPT_Query;
	protected static $DB_Products;
	protected static $mock_product;
	protected static $mock_products;

  static function wpSetUpBeforeClass() {

		self::$CPT_Query 			= Factories\CPT_Query_Factory::build();
		self::$DB_Products 		= Factories\DB\Products_Factory::build();

		self::$mock_product 	= json_decode( file_get_contents( dirname(__FILE__) . "/mock-data/products/products-insert.json") );
		self::$mock_products 	= json_decode( file_get_contents( dirname(__FILE__) . "/mock-data/products-insert.json") );
  }


	function test_it_should_inserting_only() {

		$existing_posts = get_posts([
			'post_type' => WPS_PRODUCTS_POST_TYPE_SLUG,
			'posts_per_page' => -1,
			'nopaging' => true
		]);

		$this->assertInternalType('array', $existing_posts);
		$this->assertEmpty($existing_posts);


		$result = self::$CPT_Query->inserting_only(WPS_PRODUCTS_POST_TYPE_SLUG);

		$this->assertInternalType('bool', $result);
		$this->assertTrue($result);

	}


	function test_it_should_insert_single_post() {

		$existing_posts_before = get_posts([
			'post_type' => WPS_PRODUCTS_POST_TYPE_SLUG,
			'posts_per_page' => -1,
			'nopaging' => true
		]);

		$this->assertInternalType('array', $existing_posts_before);
		$this->assertEmpty($existing_posts_before);


		$result = self::$CPT_Query->insert_posts(self::$mock_product, WPS_PRODUCTS_POST_TYPE_SLUG);
		$this->assertEquals(1, $result);


		$existing_posts_after = get_posts([
			'post_type' => WPS_PRODUCTS_POST_TYPE_SLUG,
			'posts_per_page' => -1,
			'nopaging' => true
		]);

		$this->assertInternalType('array', $existing_posts_after);
		$this->assertNotEmpty($existing_posts_after);
		$this->assertCount(1, $existing_posts_after);

	}


	function test_it_should_insert_many_posts() {

		$existing_posts_before = get_posts([
			'post_type' => WPS_PRODUCTS_POST_TYPE_SLUG,
			'posts_per_page' => -1,
			'nopaging' => true
		]);

		$this->assertInternalType('array', $existing_posts_before);
		$this->assertEmpty($existing_posts_before);


		$result = self::$CPT_Query->insert_posts(self::$mock_products, WPS_PRODUCTS_POST_TYPE_SLUG);
		$this->assertEquals(10, $result);


		$existing_posts_after = get_posts([
			'post_type' => WPS_PRODUCTS_POST_TYPE_SLUG,
			'posts_per_page' => -1,
			'nopaging' => true
		]);

		$this->assertInternalType('array', $existing_posts_after);
		$this->assertNotEmpty($existing_posts_after);
		$this->assertCount(10, $existing_posts_after);

		// $this->factory->post->create( self::$CPT_Model->set_collection_model_defaults( self::$mock_collection ) );

	}




	function test_it_should_construct_posts_col_values() {

		$result_single = self::$CPT_Query->construct_posts_col_values(self::$mock_product, WPS_PRODUCTS_POST_TYPE_SLUG);

		$this->assertInternalType('string', $result_single);
		$this->assertNotFalse( strpos($result_single, "('1'") );
		$this->assertEquals(268, strlen($result_single));


		$result_multi = self::$CPT_Query->construct_posts_col_values(self::$mock_products, WPS_PRODUCTS_POST_TYPE_SLUG);

		$this->assertInternalType('string', $result_multi);
		$this->assertNotFalse( strpos($result_multi, "('1'") );
		$this->assertEquals(3554, strlen($result_multi));

	}



	function test_it_should_find_posts_to_insert() {

		$posts = self::$CPT_Query->find_posts_to_insert(self::$mock_products, CPT::get_all_posts_compressed(WPS_PRODUCTS_POST_TYPE_SLUG));

		$this->assertInternalType('array', $posts);
		$this->assertCount(10, $posts);


		$result = self::$CPT_Query->insert_posts(self::$mock_products, WPS_PRODUCTS_POST_TYPE_SLUG);


		$after = self::$CPT_Query->find_posts_to_insert(self::$mock_products, CPT::get_all_posts_compressed(WPS_PRODUCTS_POST_TYPE_SLUG));

		$this->assertInternalType('array', $after);
		$this->assertCount(0, $after);

	}


	/*

	Updating single post

	*/
	function test_it_should_find_single_post_to_update() {

		$posts_to_update_after_before = self::$CPT_Query->find_posts_to_update(self::$mock_product, WPS_PRODUCTS_POST_TYPE_SLUG);

		$this->assertInternalType('array', $posts_to_update_after_before);
		$this->assertCount(0, $posts_to_update_after_before);


		self::$CPT_Query->insert_posts(self::$mock_product, WPS_PRODUCTS_POST_TYPE_SLUG);


		$posts_to_update_after_insert = self::$CPT_Query->find_posts_to_update(self::$mock_product, WPS_PRODUCTS_POST_TYPE_SLUG);

		$this->assertInternalType('array', $posts_to_update_after_insert);
		$this->assertCount(1, $posts_to_update_after_insert);

	}


	/*

	Updating many posts

	*/
	function test_it_should_find_many_posts_to_update() {

		$posts_to_update_after_before = self::$CPT_Query->find_posts_to_update(self::$mock_products, WPS_PRODUCTS_POST_TYPE_SLUG);

		$this->assertInternalType('array', $posts_to_update_after_before);
		$this->assertCount(0, $posts_to_update_after_before);


		self::$CPT_Query->insert_posts(self::$mock_products, WPS_PRODUCTS_POST_TYPE_SLUG);


		$posts_to_update_after_insert = self::$CPT_Query->find_posts_to_update(self::$mock_products, WPS_PRODUCTS_POST_TYPE_SLUG);

		$this->assertInternalType('array', $posts_to_update_after_insert);
		$this->assertCount(10, $posts_to_update_after_insert);

	}


}
