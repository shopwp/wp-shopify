<?php

use WPS\Factories\DB_Orders_Factory;

/*

Tests the webhooks for Orders

Mock data comes from the "mock-data" folder and is taken directly from
the example output Shopify uses within their documentation found here:

https://help.shopify.com/api/reference/webhook

*/
class Test_Sync_Orders extends WP_UnitTestCase {

  protected static $DB_Orders;
  protected static $mockDataOrder;
  protected static $mockDataOrderForUpdate;
  protected static $mockDataOrderID;


  static function setUpBeforeClass() {

    // Assemble
    self::$DB_Orders                 = DB_Orders_Factory::build();
    self::$mockDataOrder             = json_decode( file_get_contents( dirname(__FILE__) . "/mock-data/order.json") );
    self::$mockDataOrderForUpdate    = json_decode( file_get_contents( dirname(__FILE__) . "/mock-data/order-update.json") );
    self::$mockDataOrderID           = self::$mockDataOrder->order_id;

  }


  /*

  Mock: Product Create

  */
  function test_order_create() {

    $result = self::$DB_Orders->insert(self::$mockDataOrder, 'order');
    $this->assertEquals(1, $result);

  }


  /*

  Mock: Product Update

  */
  function test_order_update() {

    $results = self::$DB_Orders->update( self::$mockDataOrderID, self::$mockDataOrderForUpdate );
    $this->assertEquals(1, $results);

  }


  /*

  Mock: Product Delete

  */
  function test_order_delete() {

    $results = self::$DB_Orders->delete( self::$mockDataOrderID );
    $this->assertEquals(1, $results);

  }


}
