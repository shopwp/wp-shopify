<?php

use WPS\Factories;

/*

Tests the webhooks for Collections_Custom

Mock data comes from the "mock-data" folder and is taken directly from
the example output Shopify uses within their documentation found here:

https://help.shopify.com/api/reference/webhook

*/
class Test_DB_Collections_Custom extends WP_UnitTestCase {

  protected static $DB_Collections_Custom;
  protected static $mock_collections;
  protected static $mock_collections_for_update;
  protected static $mock_collections_id;
  protected static $lookup_key;

  static function wpSetUpBeforeClass() {

    // Assemble
    self::$DB_Collections_Custom             = Factories\DB\Collections_Custom_Factory::build();
    self::$mock_collections                  = json_decode( file_get_contents( dirname(__FILE__) . "/mock-data/collection-custom.json") );
    self::$mock_collections_for_update       = json_decode( file_get_contents( dirname(__FILE__) . "/mock-data/collection-custom-update.json") );
    self::$mock_collections_id               = self::$mock_collections->id;
    self::$lookup_key                        = self::$DB_Collections_Custom->lookup_key;

  }


  /*

  Mock: Product Create

  */
  function test_custom_collection_create() {

    $results = self::$DB_Collections_Custom->insert(self::$mock_collections);
    $this->assertEquals(1, $results);

  }


  /*

  Mock: Product Update

  */
  function test_custom_collection_update() {

    $results = self::$DB_Collections_Custom->update( self::$lookup_key, self::$mock_collections_id, self::$mock_collections_for_update );
    $this->assertEquals(1, $results);

  }


  /*

  Mock: Product Delete

  */
  function test_custom_collection_delete() {

    $results = self::$DB_Collections_Custom->delete_rows( self::$lookup_key, self::$mock_collections_id );
    $this->assertEquals(1, $results);

  }


  /*

  Should rename payload key to lookup key

  */
  function test_it_should_maybe_rename_to_lookup_key() {

    $rename_result = self::$DB_Collections_Custom->maybe_rename_to_lookup_key(self::$mock_collections);

    $this->assertObjectHasAttribute(self::$DB_Collections_Custom->lookup_key, $rename_result);

  }


  /*

  It should return the complete table name with suffix as string

  */
  function test_it_should_get_table_name() {

    $table_name = self::$DB_Collections_Custom->get_table_name();

    $this->assertInternalType('string', $table_name );
    $this->assertEquals('wptests_wps_collections_custom', $table_name );

  }


  /*

  It should return only the table name suffix as string

  */
  function test_it_should_get_table_name_suffix() {

    $table_name_suffix = self::$DB_Collections_Custom->table_name_suffix;

    $this->assertInternalType('string', $table_name_suffix );
    $this->assertEquals('wps_collections_custom', $table_name_suffix );

  }


  /*

  It should update the current add to cart color

  */
  function test_it_should_have_default_values() {

    $this->assertObjectHasAttribute('default_collection_id', self::$DB_Collections_Custom);
    $this->assertObjectHasAttribute('default_post_id', self::$DB_Collections_Custom);
    $this->assertObjectHasAttribute('default_title', self::$DB_Collections_Custom);
    $this->assertObjectHasAttribute('default_handle', self::$DB_Collections_Custom);
    $this->assertObjectHasAttribute('default_post_name', self::$DB_Collections_Custom);
    $this->assertObjectHasAttribute('default_body_html', self::$DB_Collections_Custom);
    $this->assertObjectHasAttribute('default_image', self::$DB_Collections_Custom);
    $this->assertObjectHasAttribute('default_metafield', self::$DB_Collections_Custom);
    $this->assertObjectHasAttribute('default_published', self::$DB_Collections_Custom);
    $this->assertObjectHasAttribute('default_published_scope', self::$DB_Collections_Custom);
    $this->assertObjectHasAttribute('default_sort_order', self::$DB_Collections_Custom);
    $this->assertObjectHasAttribute('default_published_at', self::$DB_Collections_Custom);
    $this->assertObjectHasAttribute('default_updated_at', self::$DB_Collections_Custom);

  }


  /*

  It should have table info props

  */
  function test_it_should_have_table_info_props() {

    $this->assertObjectHasAttribute('table_name_suffix', self::$DB_Collections_Custom);
    $this->assertObjectHasAttribute('table_name', self::$DB_Collections_Custom);
    $this->assertObjectHasAttribute('version', self::$DB_Collections_Custom);
    $this->assertObjectHasAttribute('primary_key', self::$DB_Collections_Custom);
    $this->assertObjectHasAttribute('lookup_key', self::$DB_Collections_Custom);
    $this->assertObjectHasAttribute('cache_group', self::$DB_Collections_Custom);
    $this->assertObjectHasAttribute('type', self::$DB_Collections_Custom);

  }


  /*

  It should update the current add to cart color

  */
  function test_it_should_match_default_values_and_cols_amount() {

    $cols = self::$DB_Collections_Custom->get_columns();
    $default_cols = self::$DB_Collections_Custom->get_column_defaults();

    $col_difference = array_diff_key($cols, $default_cols);

    $this->assertCount(1, $col_difference);
    $this->assertArrayHasKey('id', $col_difference);

  }

}
