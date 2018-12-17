<?php

use WPS\Factories;

class Test_API_Syncing_Counts extends WP_UnitTestCase {

  protected static $API_Syncing_Counts;
  protected static $server;

  static function wpSetUpBeforeClass() {

    global $wp_rest_server;

    // Assemble
    self::$API_Syncing_Counts    = Factories\API\Syncing\Counts_Factory::build();

		self::$server = $wp_rest_server = new \WP_REST_Server;

		do_action( 'rest_api_init' );

  }


  function test_it_should_register_route_syncing_set_counts() {

    $register_result = self::$API_Syncing_Counts->register_route_syncing_set_counts();

    $this->assertEquals(true, $register_result);
    $this->assertInternalType('boolean', $register_result);

  }


  /*

  Test that API returns 200 status code for the syncing count total

  */
  function test_it_should_200_set_syncing_counts() {

    $mock_request   = new \WP_REST_Request('POST', '/' . WP_SHOPIFY_API_NAMESPACE . '/syncing/counts');
    $response       = self::$server->dispatch( $mock_request );

    $this->assertEquals( 200, $response->get_status() );

  }


  /*

  Test that API returns 200 status code for the syncing count total

  */
  function test_it_should_200_set_syncing_counts_total() {

    $mock_request   = new \WP_REST_Request('GET', '/' . WP_SHOPIFY_API_NAMESPACE . '/syncing/counts');
    $response       = self::$server->dispatch( $mock_request );

    $this->assertEquals( 200, $response->get_status() );

  }


  /*

  Test that API sets the syncing count total

  */
  function test_it_should_set_syncing_counts() {

    $mock_request = new WP_REST_Request('POST', '/' . WP_SHOPIFY_API_NAMESPACE . '/syncing/counts');

    $response           = self::$server->dispatch( $mock_request );
    $response_data      = $response->get_data();

    foreach ($response_data as $update) {

      $this->assertEquals(true, $update);
      $this->assertInternalType('boolean', $update);

    }

    $this->assertCount(8, $response_data);
    $this->assertInternalType('array', $response_data);

  }


}
