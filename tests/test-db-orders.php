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
  protected static $mock_order;
  protected static $mock_order_for_update;
  protected static $mock_existing_order_id;
  protected static $mock_order_update;
  protected static $mock_order_insert;
  protected static $mock_order_delete;
  protected static $lookup_key;


  static function wpSetUpBeforeClass() {

    // Assemble
    self::$DB_Orders                 = DB_Orders_Factory::build();
    self::$mock_order                = json_decode( file_get_contents( dirname(__FILE__) . "/mock-data/orders/order.json") );
    self::$mock_order_for_update     = json_decode( file_get_contents( dirname(__FILE__) . "/mock-data/orders/order-update.json") );
    self::$mock_order_update         = json_decode( file_get_contents( dirname(__FILE__) . "/mock-data/orders/orders-update.json") );
    self::$mock_order_insert         = json_decode( file_get_contents( dirname(__FILE__) . "/mock-data/orders/orders-insert.json") );
    self::$mock_order_delete         = json_decode( file_get_contents( dirname(__FILE__) . "/mock-data/orders/orders-delete.json") );
    self::$mock_existing_order_id    = self::$mock_order_for_update->id;
    self::$lookup_key                = self::$DB_Orders->lookup_key;

  }


  /*

  Mock: Order Create

  */
  function test_order_create() {

    $result = self::$DB_Orders->insert(self::$mock_order);

    $this->assertEquals(1, $result);

  }


  /*

  Mock: Order Update

  */
  function test_order_update() {

    $results = self::$DB_Orders->update(self::$lookup_key, self::$mock_existing_order_id, self::$mock_order_for_update);

    $this->assertEquals(1, $results);

  }


  /*

  Mock: Order Delete

  */
  function test_order_delete() {

    $results = self::$DB_Orders->delete_rows(self::$lookup_key, self::$mock_existing_order_id);

    $this->assertEquals(1, $results);

  }


  /*

  Should update order

  */
  function test_it_should_update_order() {

    $update_item_result = self::$DB_Orders->update_items_of_type(self::$mock_order_update);

    $this->assertEquals(1, $update_item_result);

  }


  /*

  Should update order

  */
  function test_it_should_insert_order() {

    $insert_item_result = self::$DB_Orders->insert_items_of_type(self::$mock_order_insert);

    $this->assertEquals(1, $insert_item_result);

  }


  /*

  Should update order

  */
  function test_it_should_delete_order() {

    $delete_item_result = self::$DB_Orders->delete_items_of_type(self::$mock_order_delete);

    $this->assertEquals(1, $delete_item_result);

  }


  /*

  Should rename payload key to lookup key

  */
  function test_it_should_maybe_rename_to_lookup_key() {

    $rename_result = self::$DB_Orders->maybe_rename_to_lookup_key(self::$mock_order_insert);

    $this->assertObjectHasAttribute(self::$DB_Orders->lookup_key, $rename_result);

  }


  /*

  It should return the complete table name with suffix as string

  */
  function test_it_should_get_table_name() {

    $table_name = self::$DB_Orders->get_table_name();

    $this->assertInternalType('string', $table_name );
    $this->assertEquals('wptests_wps_orders', $table_name );

  }


  /*

  It should return only the table name suffix as string

  */
  function test_it_should_get_table_name_suffix() {

    $table_name_suffix = self::$DB_Orders->table_name_suffix;

    $this->assertInternalType('string', $table_name_suffix );
    $this->assertEquals('wps_orders', $table_name_suffix );

  }


}
