<?php

use WPS\Factories\DB_Products_Factory;

/*

Tests the webhooks for Products

Mock data comes from the "mock-data" folder and is taken directly from
the example output Shopify uses within their documentation found here:

https://help.shopify.com/api/reference/webhook

*/
class Test_Sync_Products extends WP_UnitTestCase {

  protected static $DB_Products;
  protected static $mock_data_product;
  protected static $mock_data_product_for_update;
  protected static $mock_data_product_id;
  protected static $mock_data_product_sync_insert;


  static function setUpBeforeClass() {

    // Assemble
    self::$DB_Products                      = DB_Products_Factory::build();
    self::$mock_data_product                = json_decode( file_get_contents( dirname(__FILE__) . "/mock-data/product.json") );
    self::$mock_data_product_sync_insert    = json_decode( file_get_contents( dirname(__FILE__) . "/mock-data/product-sync-insert.json") );
    self::$mock_data_product_for_update     = json_decode( file_get_contents( dirname(__FILE__) . "/mock-data/product-update.json") );
    self::$mock_data_product_id             = self::$mock_data_product_for_update->product_id;

  }


  /*

  Mock: Product Create

  */
  function test_product_create() {

    $result = self::$DB_Products->insert( self::$mock_data_product, 'product' );

    $this->assertEquals(1, $result);

  }


  /*

  Mock: Product Update
  The DB update metho

  */
  function test_product_update() {

    $results = self::$DB_Products->update( self::$mock_data_product_id, self::$mock_data_product_for_update );

    $this->assertEquals(1, $results);

  }


  /*

  Mock: Product Delete

  */
  function test_product_delete() {

    $results = self::$DB_Products->delete( self::$mock_data_product_id );

    $this->assertEquals(1, $results);

  }


  function test_it_should_insert_product() {

    $results = self::$DB_Products->insert_product( self::$mock_data_product_sync_insert );

    $this->assertEquals(1, $results);
  

  }


}
