<?php

require plugin_dir_path( __FILE__ ) . '../vendor/autoload.php';

use WPS\Utils;

/*

Tests Utils functions

*/
class Test_Utils extends WP_UnitTestCase {

	protected static $Messages;
  protected static $WS;

  static function setUpBeforeClass() {

    // Assemble

  }


  /*

  Mock: Utils::is_available_to_buy()

  */
  function test_is_available_to_buy() {

    // Mock #1 - Expecting to return TRUE
    $mock_variant = new \stdClass;
    $mock_variant->inventory_policy = 'deny';
    $mock_variant->inventory_quantity = 7;
    $mock_variant->inventory_management = 'shopify';

    $this->assertTrue( Utils::is_available_to_buy($mock_variant) );


    // Mock #2 - Expecting to return TRUE
    $mock_variant->inventory_policy = 'deny';
    $mock_variant->inventory_quantity = 0;
    $mock_variant->inventory_management = null;

    $this->assertTrue( Utils::is_available_to_buy($mock_variant) );


    // Mock #3 - Expecting to return TRUE
    $mock_variant->inventory_policy = 'continue';
    $mock_variant->inventory_quantity = 0;
    $mock_variant->inventory_management = 'shopify';

    $this->assertTrue( Utils::is_available_to_buy($mock_variant) );


    // Mock #4 - Expecting to return TRUE
    $mock_variant->inventory_policy = 'deny';
    $mock_variant->inventory_quantity = -1;
    $mock_variant->inventory_management = 'shopify';

    $this->assertFalse( Utils::is_available_to_buy($mock_variant) );


    // Mock #5 - Expecting to return FALSE
    $mock_variant->inventory_policy = 'deny';
    $mock_variant->inventory_quantity = 0;
    $mock_variant->inventory_management = 'shopify';

    $this->assertFalse( Utils::is_available_to_buy($mock_variant) );

  }


}
