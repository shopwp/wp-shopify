<?php

use WPS\Factories\DB_Shop_Factory;

/*

Tests the webhooks for Shop

Mock data comes from the "mock-data" folder and is taken directly from
the example output Shopify uses within their documentation found here:

https://help.shopify.com/api/reference/webhook

*/
class Test_Sync_Shop extends WP_UnitTestCase {

  protected static $DB_Shop;
  protected static $mockDataShop;
  protected static $mockDataShopForUpdate;
  protected static $mockDataShopID;


  static function setUpBeforeClass() {

    // Assemble
    self::$DB_Shop                  = DB_Shop_Factory::build();
    self::$mockDataShop             = json_decode( file_get_contents( dirname(__FILE__) . "/mock-data/shop.json") );
    self::$mockDataShopForUpdate    = json_decode( file_get_contents( dirname(__FILE__) . "/mock-data/shop-update.json") );
    self::$mockDataShopID           = self::$mockDataShop->id;

  }


  /*

  Mock: Product Create

  */
  function test_shop_create() {

    $result = self::$DB_Shop->insert(self::$mockDataShop, 'shop');

    $this->assertEquals(1, $result);

  }


  /*

  Mock: Product Update

  */
  function test_shop_update() {

    $results = self::$DB_Shop->update( self::$mockDataShopID, self::$mockDataShopForUpdate );

    $this->assertEquals(1, $results);

  }


  /*

  Mock: Product Delete

  */
  function test_shop_delete() {

    $results = self::$DB_Shop->delete( self::$mockDataShopID );

    $this->assertEquals(1, $results);

  }


}
