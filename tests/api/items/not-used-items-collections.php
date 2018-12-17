<?php

use WPS\Factories;

class Test_API_Collections extends WP_UnitTestCase {

  protected static $server;


  static function wpSetUpBeforeClass() {

    // Assemble
    global $wp_rest_server;

		self::$server = $wp_rest_server = new \WP_REST_Server;

		do_action( 'rest_api_init' );

  }



  /*

  Test that API returns 200 status code for the syncing count total

  */
  // function test_it_should_200_set_collections_posts() {
  //
  //   $mock_request   = new \WP_REST_Request('POST', '/' . WP_SHOPIFY_API_NAMESPACE . '/collections/posts');
  //   $response       = self::$server->dispatch( $mock_request );
  //
  //   $this->assertEquals( 200, $response->get_status() );
  //
  // }


  /*

  Test that API returns 200 status code for the syncing count total

  */
  // function test_it_should_200_set_smart_collections_count() {
  //
  //   $mock_request   = new \WP_REST_Request('POST', '/' . WP_SHOPIFY_API_NAMESPACE . '/smart_collections/count');
  //   $response       = self::$server->dispatch( $mock_request );
  //
  //   $this->assertEquals( 200, $response->get_status() );
  //
  // }
  //
  //
  // /*
  //
  // Test that API returns 200 status code for the syncing count total
  //
  // */
  // function test_it_should_200_set_custom_collections_count() {
  //
  //   $mock_request   = new \WP_REST_Request('POST', '/' . WP_SHOPIFY_API_NAMESPACE . '/custom_collections/count');
  //   $response       = self::$server->dispatch( $mock_request );
  //
  //   $this->assertEquals( 200, $response->get_status() );
  //
  // }
  //
  //
  // /*
  //
  // Test that API returns 200 status code for the syncing count total
  //
  // */
  // function test_it_should_200_set_smart_collections() {
  //
  //   $mock_request   = new \WP_REST_Request('POST', '/' . WP_SHOPIFY_API_NAMESPACE . '/smart_collections');
  //   $response       = self::$server->dispatch( $mock_request );
  //
  //   $this->assertEquals( 200, $response->get_status() );
  //
  // }
  //
  //
  // /*
  //
  // Test that API returns 200 status code for the syncing count total
  //
  // */
  // function test_it_should_200_set_custom_collections() {
  //
  //   $mock_request   = new \WP_REST_Request('POST', '/' . WP_SHOPIFY_API_NAMESPACE . '/custom_collections');
  //   $response       = self::$server->dispatch( $mock_request );
  //
  //   $this->assertEquals( 200, $response->get_status() );
  //
  // }


  /*

  Test that API returns 200 status code for the syncing count total

  SLOW

  */
  // function test_it_should_200_set_collections() {
  //
  //   $mock_request   = new \WP_REST_Request('POST', '/' . WP_SHOPIFY_API_NAMESPACE . '/collections');
  //   $response       = self::$server->dispatch( $mock_request );
  //
  //   $this->assertEquals( 200, $response->get_status() );
  //
  // }









}
