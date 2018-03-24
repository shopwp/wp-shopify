<?php

use WPS\DB\Orders;

/*

Tests the webhooks for Orders

Mock data comes from the "mock-data" folder and is taken directly from
the example output Shopify uses within their documentation found here:

https://help.shopify.com/api/reference/webhook

*/
class Test_Sync_Orders extends WP_UnitTestCase {

  protected static $Orders;
  protected static $mockDataOrder;
  protected static $mockDataOrderForUpdate;
  protected static $mockDataOrderID;


  static function setUpBeforeClass() {

    // Assemble
    self::$Orders                    = new Orders();
    self::$mockDataOrder             = json_decode( file_get_contents( dirname(__FILE__) . "/mock-data/order.json") );
    self::$mockDataOrderForUpdate    = json_decode( file_get_contents( dirname(__FILE__) . "/mock-data/order-update.json") );
    self::$mockDataOrderID           = self::$mockDataOrder->id;

  }


  /*

  Mock: Product Create

  */
  function test_order_create() {

    $result = self::$Orders->insert(self::$mockDataOrder, 'order');

    $this->assertTrue($result);

  }


  /*

  Mock: Product Update

  */
  function test_order_update() {

    $results = self::$Orders->update( self::$mockDataOrderID, self::$mockDataOrderForUpdate );
    $this->assertTrue($results);

  }


  /*

  Mock: Product Delete

  */
  function test_order_delete() {

    $results = self::$Orders->delete( self::$mockDataOrderID );

    $this->assertTrue($results);

  }


}
