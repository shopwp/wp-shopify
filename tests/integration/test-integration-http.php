<?php

use WPS\Factories\HTTP_Factory;
use WPS\Factories\Shopify_API_Factory;

/*

Multisite tests

*/
class Test_HTTP extends WP_UnitTestCase {

  protected static $HTTP;
  protected static $Shopify_API;
  protected static $response_client_error;
  protected static $response_server_error;


  /*

  Setup for entire class

  */
  static function wpSetUpBeforeClass() {

    self::$HTTP                   = HTTP_Factory::build();
    self::$Shopify_API            = Shopify_API_Factory::build();

    self::$response_client_error  = wp_remote_request('http://wppstest.test/api/wpshopify/v1/productss');
    self::$response_server_error  = wp_remote_request('');

  }


  /*

  test_it_should_get_client_error_message

  */
  // function test_it_should_get_client_error_message() {
  //
  //   $client_error_messages = self::$HTTP->get_client_error_message(self::$response_client_error);
  //
  //   $this->assertInternalType('string', $client_error_messages);
  //   $this->assertNotEquals(false, $client_error_messages);
  //
  // }


  /*

  test_it_should_get_server_error_message

  */
  // function test_it_should_get_server_error_message() {
  //
  //   $server_error_messages = self::$HTTP->get_server_error_message(self::$response_client_error);
  //
  //   $this->assertInternalType('string', $server_error_messages);
  //   $this->assertNotEquals(false, $server_error_messages);
  //
  // }


  /*

  test_if_is_client_error

  */
  // function test_if_is_client_error() {
  //
  //   $is_client_error = self::$HTTP->is_client_error(self::$response_client_error);
  //
  //   $this->assertInternalType('boolean', $is_client_error);
  //   $this->assertTrue($is_client_error);
  //
  // }

  /*

  test_it_should_get_client_error_message

  */
  function test_if_is_server_error() {

    $is_server_error = self::$HTTP->is_server_error(self::$response_server_error);

    $this->assertInternalType('boolean', $is_server_error);
    $this->assertTrue($is_server_error);


  }

}
