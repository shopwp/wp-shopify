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

    $product_data = self::$Templates->gather_single_price_data(100.23, ['a' => 'b']);

    $this->assertInternalType('array', $product_data);
    $this->assertArrayHasKey('price', $product_data);
    $this->assertArrayHasKey('product', $product_data);

    $this->assertEquals(100.23, $product_data['price']);
    $this->assertEquals(['a' => 'b'], $product_data['product']);

  }


  /*

  It should gather multi price template data

  */
  function test_it_should_gather_multi_price_template_data() {

    $product_data = self::$Templates->gather_multi_price_template_data('<span></span>', 40.95, 100.23, ['a' => 'b'] );

    $this->assertInternalType('array', $product_data);
    $this->assertArrayHasKey('price', $product_data);
    $this->assertArrayHasKey('price_first', $product_data);
    $this->assertArrayHasKey('price_last', $product_data);
    $this->assertArrayHasKey('product', $product_data);

    $this->assertInternalType('string', $product_data['price']);
    $this->assertEquals(40.95, $product_data['price_first']);
    $this->assertEquals(100.23, $product_data['price_last']);
    $this->assertEquals(['a' => 'b'], $product_data['product']);

  }



}
