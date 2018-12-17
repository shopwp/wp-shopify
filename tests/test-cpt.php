<?php

use WPS\Factories\CPT_Model_Factory;
use WPS\Factories\CPT_Query_Factory;
use WPS\CPT;

/*

Tests Utils functions

*/
class Test_CPT extends WP_UnitTestCase {

	protected static $CPT_Model;
	protected static $CPT_Query;
	protected static $mock_products;
	protected static $mock_product;
	protected static $mock_collection;

  static function wpSetUpBeforeClass() {

		self::$CPT_Model								= CPT_Model_Factory::build();
		self::$CPT_Query								= CPT_Query_Factory::build();
		self::$mock_products            = json_decode( file_get_contents( dirname(__FILE__) . "/mock-data/products-insert.json") );
		self::$mock_product             = json_decode( file_get_contents( dirname(__FILE__) . "/mock-data/_common/product.json") );
		self::$mock_collection          = json_decode( file_get_contents( dirname(__FILE__) . "/mock-data/_common/collection.json") );

  }


	function test_it_should_construct_posts_insert_query() {

		$query_string = self::$CPT_Query->construct_posts_insert_query( self::$mock_products, WPS_PRODUCTS_POST_TYPE_SLUG );

		$this->assertStringStartsWith('INSERT INTO', $query_string);

	}


	function test_it_should_return_valid_post_id() {

		$this->assertInternalType( 'int', self::$CPT_Query->return_post_id('123') );
		$this->assertInternalType( 'int', self::$CPT_Query->return_post_id(123) );
		$this->assertInternalType( 'int', self::$CPT_Query->return_post_id([]) );
		$this->assertInternalType( 'int', self::$CPT_Query->return_post_id(new \WP_Error) );
		$this->assertInternalType( 'int', self::$CPT_Query->return_post_id(false) );
		$this->assertInternalType( 'int', self::$CPT_Query->return_post_id(true) );
		$this->assertInternalType( 'int', self::$CPT_Query->return_post_id( self::$mock_products[0] ) );

	}


	function test_it_should_return_valid_author_id() {
		$this->assertGreaterThan( 0, CPT::return_author_id() );
	}

	function test_it_should_return_post_date() {
		$this->assertInternalType( 'string', self::$CPT_Query->return_post_date() );
	}

	function test_it_should_return_post_date_gmt() {
		$this->assertInternalType( 'string', self::$CPT_Query->return_post_date_gmt() );
	}

	function test_it_should_return_post_content() {
		$this->assertInternalType( 'string', self::$CPT_Query->return_post_content( self::$mock_products[0] ) );
	}

	function test_it_should_return_post_title() {
		$this->assertInternalType( 'string', self::$CPT_Query->return_post_title( self::$mock_products[0] ) );
	}

	function test_it_should_return_post_excerpt() {
		$this->assertInternalType( 'string', self::$CPT_Query->return_post_excerpt( self::$mock_products[0] ) );
	}

	function test_it_should_return_post_status() {
		$this->assertInternalType( 'string', self::$CPT_Query->return_post_status() );
	}

	function test_it_should_return_comment_status() {
		$this->assertInternalType( 'string', self::$CPT_Query->return_comment_status() );
	}

	function test_it_should_return_ping_status() {
		$this->assertInternalType( 'string', self::$CPT_Query->return_ping_status() );
	}

	function test_it_should_return_post_password() {
		$this->assertInternalType( 'string', self::$CPT_Query->return_post_password() );
	}

	function test_it_should_return_post_name() {
		$this->assertInternalType( 'string', self::$CPT_Query->return_post_name( self::$mock_products[0] ) );
	}

	function test_it_should_return_to_ping() {
		$this->assertInternalType( 'string', self::$CPT_Query->return_to_ping() );
	}

	function test_it_should_return_pinged() {
		$this->assertInternalType( 'string', self::$CPT_Query->return_pinged() );
	}

	function test_it_should_return_post_modified() {
		$this->assertInternalType( 'string', self::$CPT_Query->return_post_modified() );
	}

	function test_it_should_return_post_modified_gmt() {
		$this->assertInternalType( 'string', self::$CPT_Query->return_post_modified_gmt() );
	}

	function test_it_should_return_post_content_filtered() {
		$this->assertInternalType( 'string', self::$CPT_Query->return_post_content_filtered() );
	}

	function test_it_should_return_post_parent() {
		$this->assertInternalType( 'int', self::$CPT_Query->return_post_parent() );
	}

	function test_it_should_return_guid() {
		$this->assertInternalType( 'string', self::$CPT_Query->return_guid( self::$CPT_Query->construct_post_guid( self::$mock_products[0], WPS_PRODUCTS_POST_TYPE_SLUG ) ) );
	}

	function test_it_should_return_menu_order() {
		$this->assertInternalType( 'int', self::$CPT_Query->return_menu_order() );
	}

	function test_it_should_return_post_type() {
		$this->assertInternalType( 'string', self::$CPT_Query->return_post_type(WPS_PRODUCTS_POST_TYPE_SLUG) );
	}

	function test_it_should_return_post_mime_type() {
		$this->assertInternalType( 'string', self::$CPT_Query->return_post_mime_type() );
	}

	function test_it_should_return_comment_count() {
		$this->assertInternalType( 'int', self::$CPT_Query->return_comment_count() );
	}


	/*

	insert_posts contains many sub functions so this test goes through many
	different processes.

	Will always insert at least 2 posts, so we also should check this.

	*/
	function test_it_should_insert_posts() {

		$results = self::$CPT_Query->insert_posts( self::$mock_products, WPS_PRODUCTS_POST_TYPE_SLUG );

		$this->assertNotWPError($results);
		$this->assertGreaterThan(1, $results);

  }

	function test_it_should_update_posts() {

		foreach (self::$mock_products as $product) {
      $this->factory->post->create( self::$CPT_Model->set_product_model_defaults($product) );
    }


		$existing_products = CPT::get_all_posts_compressed(WPS_PRODUCTS_POST_TYPE_SLUG);
		$products_to_update = self::$CPT_Query->find_posts_to_update(self::$mock_products, WPS_PRODUCTS_POST_TYPE_SLUG);

		$results = self::$CPT_Query->update_posts( $products_to_update, false, WPS_PRODUCTS_POST_TYPE_SLUG );

		$this->assertNotWPError($results);
		$this->assertGreaterThan(1, $results);

	}


	/*

	Checks whether collections posts exist

	*/
	function test_it_should_check_whether_collections_posts_exist() {

		$this->assertFalse( CPT::posts_exist(WPS_COLLECTIONS_POST_TYPE_SLUG) );

		$this->factory->post->create( self::$CPT_Model->set_collection_model_defaults( self::$mock_collection ) );

		$this->assertTrue( CPT::posts_exist(WPS_COLLECTIONS_POST_TYPE_SLUG) );

  }


	/*

	Checks whether collections posts exist

	*/
	function test_it_should_check_num_of_posts_collections() {

		$this->assertEquals(0, CPT::num_of_posts(WPS_COLLECTIONS_POST_TYPE_SLUG) );

		$this->factory->post->create( self::$CPT_Model->set_collection_model_defaults( self::$mock_collection ) );

		$this->assertEquals(1, CPT::num_of_posts(WPS_COLLECTIONS_POST_TYPE_SLUG) );

  }


	/*

	Checks whether collections posts exist

	*/
	function test_it_should_check_num_of_posts_products() {

		$this->assertEquals(0, CPT::num_of_posts(WPS_PRODUCTS_POST_TYPE_SLUG) );

		$this->factory->post->create( self::$CPT_Model->set_product_model_defaults( self::$mock_product ) );

		$this->assertEquals(1, CPT::num_of_posts(WPS_PRODUCTS_POST_TYPE_SLUG) );

  }


	/*

	Responsible for testing whether a post ID is assigned to a post object and returned

	*/
	function test_it_should_set_post_id() {

		$result = CPT::set_post_id( new \stdClass, 123 );

		$this->assertObjectHasAttribute('post_id', $result);
		$this->assertEquals(123, $result->post_id);

	}



	function test_it_should_get_all_posts_compressed() {

		$result = self::$CPT_Query->insert_posts(self::$mock_product, WPS_PRODUCTS_POST_TYPE_SLUG);

		$posts = CPT::get_all_posts_compressed(WPS_PRODUCTS_POST_TYPE_SLUG);

		$this->assertInternalType('array', $posts);
		$this->assertCount(1, $posts);

		$this->assertArrayHasKey('ID', $posts[0]);
		$this->assertArrayHasKey('post_name', $posts[0]);


	}


}
