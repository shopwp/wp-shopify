<?php

use WPS\Factories\DB_Factory;

/*

Tests the webhooks for Variants

Mock data comes from the "mock-data" folder and is taken directly from
the example output Shopify uses within their documentation found here:

https://help.shopify.com/api/reference/webhook

*/
class Test_DB extends WP_UnitTestCase {

  protected static $DB;
  protected static $mock_data_product_sync_insert;
  protected static $mock_data_collect_sync_insert;
  protected static $mock_data_collection_custom_sync_insert;
  protected static $mock_data_collection_smart_sync_insert;
  protected static $mock_data_customer_sync_insert;
  protected static $mock_data_option_sync_insert;
  protected static $mock_data_order_sync_insert;
  protected static $mock_data_variant_sync_insert;

  static function setUpBeforeClass() {

    // Assemble
    self::$DB = DB_Factory::build();
    self::$mock_data_product_sync_insert = json_decode( file_get_contents( dirname(__FILE__) . "/mock-data/product-sync-insert.json") );
    self::$mock_data_collect_sync_insert = json_decode( file_get_contents( dirname(__FILE__) . "/mock-data/collect-sync-insert.json") );
    self::$mock_data_collection_custom_sync_insert = json_decode( file_get_contents( dirname(__FILE__) . "/mock-data/collection-sync-custom-insert.json") );
    self::$mock_data_collection_smart_sync_insert = json_decode( file_get_contents( dirname(__FILE__) . "/mock-data/collection-sync-smart-insert.json") );
    self::$mock_data_customer_sync_insert = json_decode( file_get_contents( dirname(__FILE__) . "/mock-data/customer-sync-insert.json") );
    self::$mock_data_option_sync_insert = json_decode( file_get_contents( dirname(__FILE__) . "/mock-data/option-sync-insert.json") );
    self::$mock_data_order_sync_insert = json_decode( file_get_contents( dirname(__FILE__) . "/mock-data/order-sync-insert.json") );
    self::$mock_data_variant_sync_insert = json_decode( file_get_contents( dirname(__FILE__) . "/mock-data/variant-sync-insert.json") );

  }


  /*

  Tests whether a primary key is renamed correctly

  */
  function test_rename_primary_key_product() {

    $result = self::$DB->rename_primary_key(self::$mock_data_product_sync_insert, 'product_id');

    $this->assertObjectHasAttribute('product_id', $result);
    $this->assertObjectNotHasAttribute('id', $result);

  }


  function test_rename_primary_key_collect() {

    $result = self::$DB->rename_primary_key(self::$mock_data_collect_sync_insert, 'collect_id');

    $this->assertObjectHasAttribute('collect_id', $result);
    $this->assertObjectNotHasAttribute('id', $result);

  }


  function test_rename_primary_key_collection_custom() {

    $result = self::$DB->rename_primary_key(self::$mock_data_collection_custom_sync_insert, 'collection_id');

    $this->assertObjectHasAttribute('collection_id', $result);
    $this->assertObjectNotHasAttribute('id', $result);

  }


  function test_rename_primary_key_collection_smart() {

    $result = self::$DB->rename_primary_key(self::$mock_data_collection_smart_sync_insert, 'collection_id');

    $this->assertObjectHasAttribute('collection_id', $result);
    $this->assertObjectNotHasAttribute('id', $result);

  }


  function test_rename_primary_key_customer() {

    $result = self::$DB->rename_primary_key(self::$mock_data_customer_sync_insert, 'customer_id');

    $this->assertObjectHasAttribute('customer_id', $result);
    $this->assertObjectNotHasAttribute('id', $result);

  }


  function test_rename_primary_key_option() {

    $result = self::$DB->rename_primary_key(self::$mock_data_option_sync_insert, 'option_id');

    $this->assertObjectHasAttribute('option_id', $result);
    $this->assertObjectNotHasAttribute('id', $result);

  }


  function test_rename_primary_key_order() {

    $result = self::$DB->rename_primary_key(self::$mock_data_order_sync_insert, 'order_id');

    $this->assertObjectHasAttribute('order_id', $result);
    $this->assertObjectNotHasAttribute('id', $result);

  }


  function test_rename_primary_key_variant() {

    $result = self::$DB->rename_primary_key(self::$mock_data_variant_sync_insert, 'variant_id');

    $this->assertObjectHasAttribute('variant_id', $result);
    $this->assertObjectNotHasAttribute('id', $result);

  }


  function test_has_collate() {
    $this->assertContains( 'COLLATE', self::$DB->collate() );
  }

}
