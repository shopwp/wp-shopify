<?php

use WPS\Factories\DB_Factory;
use WPS\Factories\DB_Settings_Syncing_Factory;
use WPS\Factories\DB_Products_Factory;


/*

Tests the webhooks for Variants

Mock data comes from the "mock-data" folder and is taken directly from
the example output Shopify uses within their documentation found here:

https://help.shopify.com/api/reference/webhook

*/
class Test_DB extends WP_UnitTestCase {

  protected static $DB;
  protected static $DB_Settings_Syncing;
  protected static $DB_Products;

  protected static $mock_data_product_sync_insert;
  protected static $mock_data_collect_sync_insert;
  protected static $mock_data_collection_custom_sync_insert;
  protected static $mock_data_collection_smart_sync_insert;
  protected static $mock_data_customer_sync_insert;
  protected static $mock_data_option_sync_insert;
  protected static $mock_data_order_sync_insert;
  protected static $mock_data_variant_sync_insert;

  static function wpSetUpBeforeClass() {

    // Assemble
    self::$DB                                       = DB_Factory::build();
    self::$DB_Settings_Syncing                      = DB_Settings_Syncing_Factory::build();
    self::$DB_Products                              = DB_Products_Factory::build();

    self::$mock_data_product_sync_insert            = json_decode( file_get_contents( dirname(__FILE__) . "/mock-data/product-sync-insert.json") );
    self::$mock_data_collect_sync_insert            = json_decode( file_get_contents( dirname(__FILE__) . "/mock-data/collect-sync-insert.json") );
    self::$mock_data_collection_custom_sync_insert  = json_decode( file_get_contents( dirname(__FILE__) . "/mock-data/collection-sync-custom-insert.json") );
    self::$mock_data_collection_smart_sync_insert   = json_decode( file_get_contents( dirname(__FILE__) . "/mock-data/collection-sync-smart-insert.json") );
    self::$mock_data_customer_sync_insert           = json_decode( file_get_contents( dirname(__FILE__) . "/mock-data/customer-sync-insert.json") );
    self::$mock_data_option_sync_insert             = json_decode( file_get_contents( dirname(__FILE__) . "/mock-data/option-sync-insert.json") );
    self::$mock_data_order_sync_insert              = json_decode( file_get_contents( dirname(__FILE__) . "/mock-data/order-sync-insert.json") );
    self::$mock_data_variant_sync_insert            = json_decode( file_get_contents( dirname(__FILE__) . "/mock-data/variant-sync-insert.json") );

  }


  /*

  Tests whether a primary key is renamed correctly

  */
  function test_rename_to_lookup_key_product() {

    $result = self::$DB->rename_to_lookup_key(self::$mock_data_product_sync_insert, 'id', 'product_id');

    $this->assertObjectHasAttribute('product_id', $result);
    $this->assertObjectNotHasAttribute('id', $result);

  }


  function test_rename_to_lookup_key_collect() {

    $result = self::$DB->rename_to_lookup_key(self::$mock_data_collect_sync_insert, 'id', 'collect_id');

    $this->assertObjectHasAttribute('collect_id', $result);
    $this->assertObjectNotHasAttribute('id', $result);

  }


  function test_rename_to_lookup_key_collection_custom() {

    $result = self::$DB->rename_to_lookup_key(self::$mock_data_collection_custom_sync_insert, 'id', 'collection_id');

    $this->assertObjectHasAttribute('collection_id', $result);
    $this->assertObjectNotHasAttribute('id', $result);

  }


  function test_rename_to_lookup_key_collection_smart() {

    $result = self::$DB->rename_to_lookup_key(self::$mock_data_collection_smart_sync_insert, 'id', 'collection_id');

    $this->assertObjectHasAttribute('collection_id', $result);
    $this->assertObjectNotHasAttribute('id', $result);

  }


  function test_rename_to_lookup_key_customer() {

    $result = self::$DB->rename_to_lookup_key(self::$mock_data_customer_sync_insert, 'id', 'customer_id');

    $this->assertObjectHasAttribute('customer_id', $result);
    $this->assertObjectNotHasAttribute('id', $result);

  }


  function test_rename_to_lookup_key_option() {

    $result = self::$DB->rename_to_lookup_key(self::$mock_data_option_sync_insert, 'id', 'option_id');

    $this->assertObjectHasAttribute('option_id', $result);
    $this->assertObjectNotHasAttribute('id', $result);

  }


  function test_rename_to_lookup_key_order() {

    $result = self::$DB->rename_to_lookup_key(self::$mock_data_order_sync_insert, 'id', 'order_id');

    $this->assertObjectHasAttribute('order_id', $result);
    $this->assertObjectNotHasAttribute('id', $result);

  }


  function test_rename_to_lookup_key_variant() {

    $result = self::$DB->rename_to_lookup_key(self::$mock_data_variant_sync_insert, 'id', 'variant_id');

    $this->assertObjectHasAttribute('variant_id', $result);
    $this->assertObjectNotHasAttribute('id', $result);

  }


  function test_has_collate() {
    $this->assertContains( 'COLLATE', self::$DB->collate() );
  }


  /*

  Want to test / verify the return value of the get() method. Need to make
  sure that it's an object we can work with.

  */
  function test_get() {

    $settings_sync  = self::$DB_Settings_Syncing->get();
    $product        = self::$DB_Products->get();

    $this->assertObjectHasAttribute('is_syncing', $settings_sync);
    $this->assertObjectHasAttribute('product_id', $product);

  }


}
