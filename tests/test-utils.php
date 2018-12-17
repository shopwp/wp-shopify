<?php

use WPS\Utils;

/*

Tests Utils functions

*/
class Test_Utils extends WP_UnitTestCase {

  protected static $WS;
	protected static $mock_products;
	protected static $mock_product_without_image_src;
  protected static $options_one;
  protected static $options_three;

  static function wpSetUpBeforeClass() {

    self::$mock_products     								 = json_decode( file_get_contents( dirname(__FILE__) . "/mock-data/products.json") );
		self::$mock_product_without_image_src    = json_decode( file_get_contents( dirname(__FILE__) . "/mock-data/products/product-without-image-src.json") );

    self::$options_one = json_decode( file_get_contents( dirname(__FILE__) . "/mock-data/options/options-one.json") );
    self::$options_three = json_decode( file_get_contents( dirname(__FILE__) . "/mock-data/options/options-three.json") );

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
		$wp_error = Utils::wp_error([
      'message_lookup' 	=> 'test',
      'call_method' 		=> __METHOD__,
      'call_line' 			=> __LINE__
    ]);


		$this->assertTrue( Utils::array_not_empty($non_empty_array) );
		$this->assertFalse( Utils::array_not_empty($empty_array) );
		$this->assertFalse( Utils::array_not_empty($false) );
		$this->assertFalse( Utils::array_not_empty($true) );
		$this->assertFalse( Utils::array_not_empty($wp_error) );

	}


	function test_it_should_have_prop() {

		$mock_obj = new \stdClass();
		$mock_obj->propA = true;

		$this->assertTrue( Utils::has($mock_obj, 'propA') );
		$this->assertFalse( Utils::has($mock_obj, 'propB') );
		$this->assertTrue( Utils::has(['propC' => 'hell I\'m here'], 'propC') );

	}


  /*

  Responsible for renaming payload key to lookup key

  */
  function test_it_should_flatten_image_prop() {

		$result = Utils::flatten_image_prop(self::$mock_product_without_image_src);

		$this->assertStringStartsWith('https://cdn', $result->image);

  }


	/*

  Responsible for renaming payload key to lookup key

  */
  function test_it_should_return_hash_static_num() {

		$result = Utils::hash_static_num('ThisIsATestString');

		$this->assertInternalType('int', $result);
		$this->assertEquals(1519694869, $result);

  }


  /*

  Responsible for converting an array to an object

  */
  function test_it_should_convert_array_to_object() {

    $result = Utils::convert_array_to_object([
      'propA' => 'valA',
      'propB' => false,
      'propC' => 123,
      'propD' => [],
      'propE' => new \stdClass
    ]);

    $this->assertInternalType('object', $result);
    $this->assertObjectHasAttribute('propA', $result);
    $this->assertObjectHasAttribute('propB', $result);
    $this->assertObjectHasAttribute('propC', $result);
    $this->assertObjectHasAttribute('propD', $result);
    $this->assertObjectHasAttribute('propE', $result);

  }


  /*

  Responsible for getting last index of an array

  */
  function test_it_should_get_last_index() {

    $result = Utils::get_last_index(5);

    $this->assertEquals(4, $result);

  }



  function test_it_should_get_options_button_width_three() {

    $width = Utils::get_options_button_width(self::$options_three);

    $this->assertEquals(3, $width);


  }


  function test_it_should_get_options_button_width_two() {

    $width = Utils::get_options_button_width(self::$options_one);

    $this->assertEquals(2, $width);

  }


  function test_it_has_option_property() {

    $result_one = Utils::has_option_property(0, 'option');
    $result_two = Utils::has_option_property(0, 'option_values');
    $result_three = Utils::has_option_property(0, '');
    $result_four = Utils::has_option_property(0, false);
    $result_five = Utils::has_option_property(0, true);
    $result_six = Utils::has_option_property(0, 'optionalloneword');

    $this->assertTrue($result_one);
    $this->assertTrue($result_two);
    $this->assertFalse($result_three);
    $this->assertFalse($result_four);
    $this->assertFalse($result_five);
    $this->assertTrue($result_six);

  }

  function test_that_it_is_collections_sorted() {

    $result_true = Utils::is_collections_sorted(['custom' => ['collections' => "Test"]]);
    $result_false = Utils::is_collections_sorted(['custom' => ['titles' => "Test"]]);

    $this->assertInternalType('boolean', $result_true);
    $this->assertEquals(true, $result_true);

    $this->assertInternalType('boolean', $result_false);
    $this->assertEquals(false, $result_false);

  }


	function test_it_should_get_proper_wp_error() {

		$request_url_error = Utils::wp_error([
  		'message_lookup' 	=> 'request_url_not_found',
  		'call_method' 		=> __METHOD__,
  		'call_line' 			=> __LINE__
  	]);

    $this->assertWPError($request_url_error);
    $this->assertInternalType('string', $request_url_error->get_error_message());

	}

}
