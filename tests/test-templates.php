<?php

use WPS\Factories\Templates_Factory;


/*

Tests Templates functions

*/
class Test_Templates extends WP_UnitTestCase {

  protected static $Templates;



  static function wpSetUpBeforeClass() {

    // Assemble
    self::$Templates = Templates_Factory::build();

  }


	/*

	It should delete cached collection queries

	*/
	function test_it_should_get_product_data() {

		$product_data = self::$Templates->get_product_data(18352);

		$this->assertInternalType('object', $product_data);
		$this->assertObjectHasAttribute('details', $product_data);
		$this->assertObjectHasAttribute('variants', $product_data);
		$this->assertObjectHasAttribute('product_id', $product_data);
		$this->assertObjectHasAttribute('post_id', $product_data);

	}


  /*

  It should gather single price data

  */
  function test_it_should_gather_single_price_data() {

    $params = [
      'first_price'     => 100.23,
      'product'         => ['a' => 'b']
    ];

    $product_data = self::$Templates->gather_single_price_data($params);

    $this->assertInternalType('array', $product_data);
    $this->assertArrayHasKey('price', $product_data);
    $this->assertArrayHasKey('product', $product_data);

    $this->assertEquals(100.23, $product_data['first_price']);
    $this->assertEquals(['a' => 'b'], $product_data['product']);

  }


  /*

  It should gather multi price template data

  */
  function test_it_should_gather_multi_price_template_data() {

    $params = [
      'first_price' 						=> 40.95,
      'last_price' 							=> 100.23,
      'product' 								=> ['a' => 'b'],
      'showing_compare_at' 			=> false,
      'showing_price_range' 		=> true,
      'variants_amount' 				=> 2
    ];

    $product_data = self::$Templates->gather_multi_price_template_data('<span></span>', $params);

    $this->assertInternalType('array', $product_data);
    $this->assertArrayHasKey('first_price', $product_data);
    $this->assertArrayHasKey('last_price', $product_data);
    $this->assertArrayHasKey('product', $product_data);

    $this->assertEquals(40.95, $product_data['first_price']);
    $this->assertEquals(100.23, $product_data['last_price']);
    $this->assertEquals(['a' => 'b'], $product_data['product']);

  }



}
