<?php

use WPS\DB\Shop;

/*

Tests the webhooks for Shop

Mock data comes from the "mock-data" folder and is taken directly from
the example output Shopify uses within their documentation found here:

https://help.shopify.com/api/reference/webhook

*/
class Test_Sync_Shop extends WP_UnitTestCase {

  protected static $Shop;
  protected static $mockDataShop;
  protected static $mockDataShopForUpdate;
  protected static $mockDataShopID;


  static function setUpBeforeClass() {

    // Assemble
    self::$Shop                     = new Shop();
    self::$mockDataShop             = json_decode( file_get_contents( dirname(__FILE__) . "/mock-data/shop.json") );
    self::$mockDataShopForUpdate    = json_decode( file_get_contents( dirname(__FILE__) . "/mock-data/shop-update.json") );
    self::$mockDataShopID           = self::$mockDataShop->id;

  }


  /*

  Mock: Product Create

  */
  function test_shop_create() {

    $result = self::$Shop->insert(self::$mockDataShop, 'shop');

    $this->assertTrue($result);

  }


  /*

  Mock: Product Update

  */
  function test_shop_update() {

    $results = self::$Shop->update( self::$mockDataShopID, self::$mockDataShopForUpdate );
    
    $this->assertTrue($results);

  }


  /*

  Mock: Product Delete

  */
  function test_shop_delete() {

    $results = self::$Shop->delete( self::$mockDataShopID );

    $this->assertTrue($results);

  }


}
