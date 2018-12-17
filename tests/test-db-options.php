<?php

use WPS\Factories;
use WPS\Utils;


/*

Tests the webhooks for Options

Mock data comes from the "mock-data" folder and is taken directly from
the example output Shopify uses within their documentation found here:

https://help.shopify.com/api/reference/webhook

*/
class Test_DB_Options extends WP_UnitTestCase {

  protected static $DB_Options;
  protected static $mock_data_option;
  protected static $mock_option_for_update;
  protected static $mock_existing_option_id;
  protected static $mock_option_insert;
  protected static $mock_option_update;
  protected static $mock_option_delete;
  protected static $mock_product;
  protected static $lookup_key;


  static function wpSetUpBeforeClass() {

    // Assemble
    self::$DB_Options                     = Factories\DB\Options_Factory::build();
    self::$mock_data_option               = json_decode( file_get_contents( dirname(__FILE__) . "/mock-data/options/option.json") );
    self::$mock_option_for_update         = json_decode( file_get_contents( dirname(__FILE__) . "/mock-data/options/option-update.json") );

    // Simulates the actual product payload from Shopify
    self::$mock_product                   = json_decode( file_get_contents( dirname(__FILE__) . "/mock-data/_common/product.json") );
    self::$mock_option_insert             = json_decode( file_get_contents( dirname(__FILE__) . "/mock-data/options/options-insert.json") );
    self::$mock_option_update             = json_decode( file_get_contents( dirname(__FILE__) . "/mock-data/options/options-update.json") );
    self::$mock_option_delete             = json_decode( file_get_contents( dirname(__FILE__) . "/mock-data/options/options-delete.json") );

    self::$mock_existing_option_id        = self::$mock_option_for_update->id;
    self::$lookup_key                     = self::$DB_Options->lookup_key;

  }


  /*

  Mock: Product Create

  */
  function test_option_create() {

    $result = self::$DB_Options->insert(self::$mock_data_option);
    $this->assertEquals(1, $result);

  }


  /*

  Mock: Product Update

  */
  function test_option_update() {

    $results = self::$DB_Options->update(self::$lookup_key, self::$mock_existing_option_id, self::$mock_option_for_update);
    $this->assertEquals(1, $results);

  }


  /*

  Mock: Product Delete

  */
  function test_option_delete() {

    $results = self::$DB_Options->delete_rows(self::$lookup_key, self::$mock_existing_option_id);
    $this->assertEquals(1, $results);

  }


  /*

  Should find options to insert based on mock product

  */
  function test_it_should_find_options_to_insert() {

    $found_items_to_insert = self::$DB_Options->gather_items_for_insertion(
      self::$DB_Options->modify_options( self::$mock_option_insert )
    );

    $this->assertCount(1, $found_items_to_insert);

  }


  /*

  Should find options to delete based on mock product

  */
  function test_it_should_find_options_to_update() {

    $found_items_to_update = self::$DB_Options->gather_items_for_updating(
      self::$DB_Options->modify_options( self::$mock_option_update )
    );

    $this->assertCount(1, $found_items_to_update);

  }


  /*

  Should find options to delete based on mock product

  */
  function test_it_should_find_options_to_delete() {

    $found_items_to_delete = self::$DB_Options->gather_items_for_deletion(
      self::$DB_Options->modify_options( self::$mock_option_delete )
    );

    $this->assertCount(3, $found_items_to_delete);

  }


  /*

  Should perform all three modifications: insert, update, delete

  */
  function test_is_should_modify_options_from_shopify_product() {

    $results = self::$DB_Options->modify_from_shopify( self::$DB_Options->modify_options( self::$mock_product ) );

    // Check if any WP_Errors occured ...
    foreach ( Utils::flatten_array($results) as $result) {
      $this->assertNotWPError($result);
    }

    // Checks that the modification amounts matches mock data
    $this->assertCount(1, $results['created'][0]);
    $this->assertCount(3, $results['updated'][0]);
    $this->assertCount(1, $results['deleted'][0]);

  }


  /*

  Should find all products to delete based on mock product id

  */
  function test_it_should_delete_all_options_by_product_id() {

    $delete_result = self::$DB_Options->delete_options_from_product_id(self::$mock_product->id);

    $this->assertEquals(3, $delete_result);

  }


  /*

  Should rename payload key to lookup key

  */
  function test_it_should_maybe_rename_to_lookup_key() {

    $rename_result = self::$DB_Options->maybe_rename_to_lookup_key(self::$mock_option_insert);

    $this->assertObjectHasAttribute(self::$DB_Options->lookup_key, $rename_result);

  }


  /*

  It should return the complete table name with suffix as string

  */
  function test_it_should_get_table_name() {

    $table_name = self::$DB_Options->get_table_name();

    $this->assertInternalType('string', $table_name );
    $this->assertEquals('wptests_wps_options', $table_name );

  }


  /*

  It should return only the table name suffix as string

  */
  function test_it_should_get_table_name_suffix() {

    $table_name_suffix = self::$DB_Options->table_name_suffix;

    $this->assertInternalType('string', $table_name_suffix );
    $this->assertEquals('wps_options', $table_name_suffix );

  }


  /*

  It should update the current add to cart color

  */
  function test_it_should_have_default_values() {

    $this->assertObjectHasAttribute('default_option_id', self::$DB_Options);
    $this->assertObjectHasAttribute('default_product_id', self::$DB_Options);
    $this->assertObjectHasAttribute('default_name', self::$DB_Options);
    $this->assertObjectHasAttribute('default_position', self::$DB_Options);
    $this->assertObjectHasAttribute('default_values', self::$DB_Options);

  }


  /*

  It should have table info props

  */
  function test_it_should_have_table_info_props() {

    $this->assertObjectHasAttribute('table_name_suffix', self::$DB_Options);
    $this->assertObjectHasAttribute('table_name', self::$DB_Options);
    $this->assertObjectHasAttribute('version', self::$DB_Options);
    $this->assertObjectHasAttribute('primary_key', self::$DB_Options);
    $this->assertObjectHasAttribute('lookup_key', self::$DB_Options);
    $this->assertObjectHasAttribute('cache_group', self::$DB_Options);
    $this->assertObjectHasAttribute('type', self::$DB_Options);

  }


  /*

  It should update the current add to cart color

  */
  function test_it_should_match_default_values_and_cols_amount() {

    $cols = self::$DB_Options->get_columns();
    $default_cols = self::$DB_Options->get_column_defaults();

    $col_difference = array_diff_key($cols, $default_cols);

    $this->assertCount(1, $col_difference);
    $this->assertArrayHasKey('id', $col_difference);

  }


}
