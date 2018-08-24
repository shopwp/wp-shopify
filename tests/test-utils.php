<?php

use WPS\Utils;

/*

Tests Utils functions

*/
class Test_Utils extends WP_UnitTestCase {

	protected static $Messages;
  protected static $WS;
	protected static $mock_products;

  static function setUpBeforeClass() {

    self::$mock_products        = json_decode( file_get_contents( dirname(__FILE__) . "/mock-data/products.json") );

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


	function test_it_should_filter_data_by() {

		$results = Utils::filter_data_by(self::$mock_products, ['variants', 'options']);

		foreach ($results as $product) {
			$this->assertEquals(false, property_exists($product, 'options'));
			$this->assertEquals(false, property_exists($product, 'variants'));
		}

  }


	function test_is_array_not_empty() {

		$non_empty_array = ['variants', 'options'];
		$empty_array = [];
		$false = false;
		$true = true;
		$wp_error = new \WP_Error('Test');

		$this->assertTrue( Utils::array_not_empty($non_empty_array) );
		$this->assertFalse( Utils::array_not_empty($empty_array) );
		$this->assertFalse( Utils::array_not_empty($false) );
		$this->assertFalse( Utils::array_not_empty($true) );
		$this->assertFalse( Utils::array_not_empty($wp_error) );

	}


}
