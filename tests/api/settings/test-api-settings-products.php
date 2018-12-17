<?php

use WPS\Factories;

class Test_API_Settings_Products extends WP_UnitTestCase {

  protected static $API_Settings_Products;
  protected static $DB_Settings_General;
  protected static $old_color;
  protected static $server;

  static function wpSetUpBeforeClass() {

    global $wp_rest_server;

    // Assemble
    self::$API_Settings_Products    = Factories\API\Settings\Products_Factory::build();
    self::$DB_Settings_General      = Factories\DB\Settings_General_Factory::build();

    self::$server = $wp_rest_server = new \WP_REST_Server;

		do_action('rest_api_init');

  }


  function test_it_should_register_route_products_add_to_cart_color() {

    $register_result = self::$API_Settings_Products->register_route_products_add_to_cart_color();

    $this->assertEquals(true, $register_result);
    $this->assertInternalType('boolean', $register_result);

  }


  function test_it_should_200_update_color_from_api_request() {

    $mock_request   = new \WP_REST_Request('POST', '/' . WP_SHOPIFY_API_NAMESPACE . '/settings/products_add_to_cart_color');
    $response       = self::$server->dispatch( $mock_request );

    $this->assertEquals( 200, $response->get_status() );

  }


  function test_it_should_update_color_from_api_request() {

    $mock_request = new \WP_REST_Request('POST', '/' . WP_SHOPIFY_API_NAMESPACE . '/settings/products_add_to_cart_color');

    $mock_request->set_body_params([
      'color' => '#ffffff'
    ]);

    $result_of_update = self::$API_Settings_Products->update_setting_products_add_to_cart_color($mock_request);

    $this->assertEquals(1, $result_of_update);
    $this->assertInternalType('int', $result_of_update);

  }


}
