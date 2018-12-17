<?php

use WPS\Factories;

class Test_API_Syncing_Status extends WP_UnitTestCase {

  protected static $API_Syncing_Counts;
  protected static $server;


  static function wpSetUpBeforeClass() {

    // Assemble
    global $wp_rest_server;
    self::$API_Syncing_Counts = Factories\API\Syncing\Status_Factory::build();
		self::$server = $wp_rest_server = new \WP_REST_Server;

		do_action( 'rest_api_init' );

  }


  /*

  Test that API returns 200 status code for the syncing count total

  */
  function test_it_should_200_get_syncing_status() {

    $mock_request   = new \WP_REST_Request('GET', '/' . WP_SHOPIFY_API_NAMESPACE . '/syncing/status');
    $response       = self::$server->dispatch( $mock_request );

    $this->assertEquals( 200, $response->get_status() );

  }


  /*

  Test that API returns 200 status code for the syncing count total

  */
  function test_it_should_200_get_syncing_status_posts() {

    $mock_request   = new \WP_REST_Request('GET', '/' . WP_SHOPIFY_API_NAMESPACE . '/syncing/status/posts');
    $response       = self::$server->dispatch( $mock_request );

    $this->assertEquals( 200, $response->get_status() );

  }


  /*

  Test that API returns 200 status code for the syncing count total

  */
  function test_it_should_200_get_syncing_status_webhooks() {

    $mock_request   = new \WP_REST_Request('GET', '/' . WP_SHOPIFY_API_NAMESPACE . '/syncing/status/webhooks');
    $response       = self::$server->dispatch( $mock_request );

    $this->assertEquals( 200, $response->get_status() );

  }


  /*

  Test that API returns 200 status code for the syncing count total

  */
  function test_it_should_200_get_syncing_status_removal() {

    $mock_request   = new \WP_REST_Request('GET', '/' . WP_SHOPIFY_API_NAMESPACE . '/syncing/status/removal');
    $response       = self::$server->dispatch( $mock_request );

    $this->assertEquals( 200, $response->get_status() );

  }


  /*

  Test that API returns 200 status code for the syncing count total

  */
  function test_it_should_200_get_syncing_stop() {

    $mock_request   = new \WP_REST_Request('POST', '/' . WP_SHOPIFY_API_NAMESPACE . '/syncing/stop');
    $response       = self::$server->dispatch( $mock_request );

    $this->assertEquals( 200, $response->get_status() );

  }


  /*

  Test that API returns 200 status code for the syncing count total

  */
  function test_it_should_200_get_syncing_notices() {

    $mock_request   = new \WP_REST_Request('GET', '/' . WP_SHOPIFY_API_NAMESPACE . '/syncing/notices');
    $response       = self::$server->dispatch( $mock_request );

    $this->assertEquals( 200, $response->get_status() );

  }



}
