<?php

use WPS\Factories;

class Test_API_Indicator extends WP_UnitTestCase {

  protected static $API_Syncing_Indicator;
  protected static $server;


  static function wpSetUpBeforeClass() {

    // Assemble
    global $wp_rest_server;
    self::$API_Syncing_Indicator = Factories\API\Syncing\Indicator_Factory::build();
		self::$server = $wp_rest_server = new \WP_REST_Server;

		do_action( 'rest_api_init' );

  }


  /*

  Test that API returns 200 status code for the syncing count total

  */
  function test_it_should_200_set_syncing_indicator() {

    $mock_request   = new \WP_REST_Request('POST', '/' . WP_SHOPIFY_API_NAMESPACE . '/syncing/indicator');
    $response       = self::$server->dispatch( $mock_request );

    $this->assertEquals( 200, $response->get_status() );

  }


}
