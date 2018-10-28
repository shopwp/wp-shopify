<?php

use WPS\Factories\API_Settings_Products_Factory;
use WPS\Factories\DB_Settings_General_Factory;


class Test_API_Settings_Products extends WP_UnitTestCase {

  protected static $API_Settings_Products;
  protected static $DB_Settings_General;
  protected static $old_color;

  static function wpSetUpBeforeClass() {

    // Assemble
    self::$API_Settings_Products    = API_Settings_Products_Factory::build();
    self::$DB_Settings_General      = DB_Settings_General_Factory::build();

  }


  function test_it_should_register_route_products_add_to_cart_color() {

    $register_result = self::$API_Settings_Products->register_route_products_add_to_cart_color();

    $this->assertEquals(true, $register_result);
    $this->assertInternalType('boolean', $register_result);

  }


  function test_it_should_update_color_from_api_request() {

    $mock_request = new WP_REST_Request('POST', '/wpshopify/v1/settings/products_add_to_cart_color');

    $mock_request->set_body_params([
      'color' => '#ffffff'
    ]);


    $result_of_update = self::$API_Settings_Products->update_setting_products_add_to_cart_color($mock_request);


    $this->assertEquals(1, $result_of_update);
    $this->assertInternalType('int', $result_of_update);

  }


}
