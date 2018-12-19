<?php

use WPS\Factories;

/*

Multisite tests

*/
class Test_Layout_Data extends WP_UnitTestCase {

  protected static $Layout_Data;


  /*

  Setup for entire class

  */
  static function wpSetUpBeforeClass() {

    self::$Layout_Data = Factories\Layout\Data_Factory::build();

  }


  /*

  Tears down for entire class

  */
  static function wpTearDownAfterClass() {


  }



	function test_it_should_format_shortcode_attr_multi() {

    $result = self::$Layout_Data->format_shortcode_attr('as, sd');

    $this->assertInternalType('array', $result);
    $this->assertNotEmpty($result);
    $this->assertCount(2, $result);

  }


  function test_it_should_format_shortcode_attr_extra_comma() {

    // user could only enter a comma and not another value
    $result = self::$Layout_Data->format_shortcode_attr('as, ');

    $this->assertInternalType('array', $result);
    $this->assertNotEmpty($result);
    $this->assertCount(1, $result);

  }


  function test_it_should_format_shortcode_attr_extra_space() {

    // space important to test
    $result = self::$Layout_Data->format_shortcode_attr('hello! ');

    $this->assertInternalType('string', $result);
    $this->assertEquals('hello!', $result);

  }


  function test_it_should_format_shortcode_no_string() {

    // space important to test
    $result = self::$Layout_Data->format_shortcode_attr([]);

    $this->assertInternalType('string', $result);
    $this->assertEquals('', $result);

  }


  function test_it_should_format_shortcode_attrs_empty() {

    // space important to test
    $result = self::$Layout_Data->format_shortcode_attrs([]);

    $this->assertInternalType('array', $result);
    $this->assertEmpty($result);
    $this->assertCount(0, $result);

  }


  function test_it_should_format_shortcode_attrs_one() {

    $result = self::$Layout_Data->format_shortcode_attrs(['limit' => '10']);

    $this->assertInternalType('array', $result);
    $this->assertNotEmpty($result);
    $this->assertCount(1, $result);

  }


  /*

  Verify that whitelisting shortcode attributes works. Should not return
  attrs that we do not whitelist

  */
  function test_it_should_build_query_params_whitelist_works() {

    $result = self::$Layout_Data->format_products_shortcode_args(['limittt' => '10']);

    $this->assertInternalType('array', $result);
    $this->assertNotEmpty($result);
    $this->assertArrayNotHasKey("custom", $result);

  }


  /*

  Verify that whitelisting shortcode attributes works. Should not return
  attrs that we do not whitelist

  */
  function test_it_should_build_query_params_all_whitelist() {

    $result = self::$Layout_Data->format_products_shortcode_args([
      'limit'             => '10',
      'order'             => '10',
      'orderby'           => '10',
      'ids'               => '10',
      'slugs'             => '10',
      'titles'            => '10',
      'desc'              => '10',
      'tags'              => '10',
      'vendors'           => '10',
      'variants'          => '10',
      'types'             => '10',
      'options'           => '10',
      'available'         => '10',
      'collections'       => '10',
      'collection_slugs'  => '10',
      'items-per-row'     => '10',
      'pagination'        => '10',
      'page'              => '10',
      'add-to-cart'       => '10',
      'breadcrumbs'       => '10',
      'keep-permalinks'   => '10',
      'description'       => '10',
      'add-to-cart-text'  => '10'
    ]);

    $this->assertInternalType('array', $result);
    $this->assertNotEmpty($result);

    $this->assertArrayHasKey("custom", $result);

    $this->assertArrayHasKey("limit", $result['custom']);
    $this->assertArrayHasKey("order", $result['custom']);
    $this->assertArrayHasKey("orderby", $result['custom']);
    $this->assertArrayHasKey("ids", $result['custom']);
    $this->assertArrayHasKey("slugs", $result['custom']);
    $this->assertArrayHasKey("titles", $result['custom']);
    $this->assertArrayHasKey("desc", $result['custom']);
    $this->assertArrayHasKey("tags", $result['custom']);
    $this->assertArrayHasKey("vendors", $result['custom']);
    $this->assertArrayHasKey("variants", $result['custom']);
    $this->assertArrayHasKey("types", $result['custom']);
    $this->assertArrayHasKey("options", $result['custom']);
    $this->assertArrayHasKey("available", $result['custom']);
    $this->assertArrayHasKey("collections", $result['custom']);
    $this->assertArrayHasKey("collection_slugs", $result['custom']);
    $this->assertArrayHasKey("items-per-row", $result['custom']);
    $this->assertArrayHasKey("pagination", $result['custom']);
    $this->assertArrayHasKey("add-to-cart", $result['custom']);
    $this->assertArrayHasKey("add-to-cart-text", $result['custom']);
    $this->assertArrayHasKey("breadcrumbs", $result['custom']);
    $this->assertArrayHasKey("keep-permalinks", $result['custom']);
    $this->assertArrayHasKey("description", $result['custom']);


  }

}
