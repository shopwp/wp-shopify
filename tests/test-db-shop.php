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
  protected static $mock_shop;
  protected static $mock_shop_for_update;
  protected static $mock_shop_id;
  protected static $lookup_key;


  static function wpSetUpBeforeClass() {

    // Assemble
    self::$DB_Shop                 = DB_Shop_Factory::build();
    self::$mock_shop               = json_decode( file_get_contents( dirname(__FILE__) . "/mock-data/shop.json") );
    self::$mock_shop_for_update    = json_decode( file_get_contents( dirname(__FILE__) . "/mock-data/shop-update.json") );
    self::$mock_shop_id            = self::$mock_shop->id;
    self::$lookup_key              = self::$DB_Shop->lookup_key;

  }


  /*

  Mock: Product Create

  */
  function test_shop_create() {

    // Clear first
    self::$DB_Shop->delete( self::$mock_shop_id );

    $result = self::$DB_Shop->insert(self::$mock_shop);

    $this->assertEquals(1, $result);

  }


  /*

  Mock: Product Update

  */
  function test_shop_update() {

    $results = self::$DB_Shop->update(self::$lookup_key, self::$mock_shop_id, self::$mock_shop_for_update);

    $this->assertEquals(1, $results);

  }


  /*

  Mock: Product Delete

  */
  function test_shop_delete() {

    $results = self::$DB_Shop->delete(self::$mock_shop_id);

    $this->assertEquals(1, $results);

  }


  /*

  It should return the complete table name with suffix as string

  */
  function test_it_should_get_table_name() {

    $table_name = self::$DB_Shop->get_table_name();

    $this->assertInternalType('string', $table_name );
    $this->assertEquals('wptests_wps_shop', $table_name );

  }


  /*

  It should return only the table name suffix as string

  */
  function test_it_should_get_table_name_suffix() {

    $table_name_suffix = self::$DB_Shop->table_name_suffix;

    $this->assertInternalType('string', $table_name_suffix );
    $this->assertEquals('wps_shop', $table_name_suffix );

  }


  /*

  Mock: Product Delete

  */
  function test_shop_insert_province_max_chars_error() {

    $result = self::$DB_Shop->insert(["province" => "MinnesotaMinnesota MinnesotaMinnesota MinnesotaMinnesota MinnesotaMinnesota MinnesotaMinnesota MinnesotaMinnesotaMinnesotaMinnesota MinnesotaMinnesota MinnesotaMinnesota MinnesotaMinnesota MinnesotaMMM"]);

    $this->assertWPError($result);

  }


}
