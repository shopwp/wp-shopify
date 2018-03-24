<?php

use WPS\DB\Products;
use WPS\Config;
use WPS\Utils;

/*

Tests the webhooks for Products

Mock data comes from the "mock-data" folder and is taken directly from
the example output Shopify uses within their documentation found here:

https://help.shopify.com/api/reference/webhook

*/
class Test_Sync_Products extends WP_UnitTestCase {

  protected static $Products;
  protected static $mockDataProduct;
  protected static $mockDataProductForUpdate;
  protected static $mockDataProductID;


  static function setUpBeforeClass() {

    // Assemble
    self::$Products                    = new Products( new Config() );
    self::$mockDataProduct             = json_decode( file_get_contents( dirname(__FILE__) . "/mock-data/product.json") );
    self::$mockDataProductForUpdate    = json_decode( file_get_contents( dirname(__FILE__) . "/mock-data/product-update.json") );
    self::$mockDataProductID           = Utils::wps_find_product_id(self::$mockDataProductForUpdate);

  }


  public function tearDown() {

  }


  /*

  Mock: Product Create

  */
  function test_product_create() {

    $result = self::$Products->insert( self::$mockDataProduct, 'product' );

    $this->assertTrue($result);

  }


  /*

  Mock: Product Update
  The DB update metho

  */
  function test_product_update() {

    $results = self::$Products->update( self::$mockDataProductID, self::$mockDataProductForUpdate );

    $this->assertTrue($results);

  }


  /*

  Mock: Product Delete

  */
  function test_product_delete() {

    $results = self::$Products->delete( self::$mockDataProductID );

    $this->assertTrue($results);

  }


}
