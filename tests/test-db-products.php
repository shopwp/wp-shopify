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
  protected static $mockDataProduct;
  protected static $mockDataProductForUpdate;
  protected static $mockDataProductID;


  static function setUpBeforeClass() {

    // Assemble
    self::$DB_Products                 = DB_Products_Factory::build();
    self::$mockDataProduct             = json_decode( file_get_contents( dirname(__FILE__) . "/mock-data/product.json") );
    self::$mockDataProductForUpdate    = json_decode( file_get_contents( dirname(__FILE__) . "/mock-data/product-update.json") );
    self::$mockDataProductID           = self::$mockDataProductForUpdate->id;

  }


  public function tearDown() {

  }


  /*

  Mock: Product Create

  */
  function test_product_create() {

    $result = self::$DB_Products->insert( self::$mockDataProduct, 'product' );

    $this->assertEquals(1, $result);

  }


  /*

  Mock: Product Update
  The DB update metho

  */
  function test_product_update() {

    $results = self::$DB_Products->update( self::$mockDataProductID, self::$mockDataProductForUpdate );

    $this->assertEquals(1, $results);

  }


  /*

  Mock: Product Delete

  */
  function test_product_delete() {

    $results = self::$DB_Products->delete( self::$mockDataProductID );

    $this->assertEquals(1, $results);

  }


}
