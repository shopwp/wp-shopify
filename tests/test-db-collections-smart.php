<?php

use WPS\Factories\DB_Collections_Smart_Factory;

/*

Tests the webhooks for Collections_Smart

Mock data comes from the "mock-data" folder and is taken directly from
the example output Shopify uses within their documentation found here:

https://help.shopify.com/api/reference/webhook

*/
class Test_DB_Collections_Smart extends WP_UnitTestCase {

  protected static $DB_Collections_Smart;
  protected static $mock_collections;
  protected static $mock_collections_for_update;
  protected static $mock_collections_id;
  protected static $lookup_key;


  static function wpSetUpBeforeClass() {

    // Assemble
    self::$DB_Collections_Smart             = DB_Collections_Smart_Factory::build();
    self::$mock_collections                 = json_decode( file_get_contents( dirname(__FILE__) . "/mock-data/collection-smart.json") );
    self::$mock_collections_for_update      = json_decode( file_get_contents( dirname(__FILE__) . "/mock-data/collection-smart-update.json") );
    self::$mock_collections_id              = self::$mock_collections->id;
    self::$lookup_key                       = self::$DB_Collections_Smart->lookup_key;

  }


  /*

  Mock: Product Create

  */
  function test_smart_collection_create() {

    $results = self::$DB_Collections_Smart->insert(self::$mock_collections);
    $this->assertEquals(1, $results);

  }


  /*

  Mock: Product Update

  */
  function test_smart_collection_update() {

    $results = self::$DB_Collections_Smart->update( self::$lookup_key, self::$mock_collections_id, self::$mock_collections_for_update );
    $this->assertEquals(1, $results);

  }


  /*

  Mock: Product Delete

  */
  function test_smart_collection_delete() {

    $results = self::$DB_Collections_Smart->delete_rows( self::$lookup_key, self::$mock_collections_id );
    $this->assertEquals(1, $results);

  }


  /*

  Should rename payload key to lookup key

  */
  function test_it_should_maybe_rename_to_lookup_key() {

    $rename_result = self::$DB_Collections_Smart->maybe_rename_to_lookup_key(self::$mock_collections);

    $this->assertObjectHasAttribute(self::$DB_Collections_Smart->lookup_key, $rename_result);

  }


  /*

  It should return the complete table name with suffix as string

  */
  function test_it_should_get_table_name() {

    $table_name = self::$DB_Collections_Smart->get_table_name();

    $this->assertInternalType('string', $table_name );
    $this->assertEquals('wptests_wps_collections_smart', $table_name );

  }


  /*

  It should return only the table name suffix as string

  */
  function test_it_should_get_table_name_suffix() {

    $table_name_suffix = self::$DB_Collections_Smart->table_name_suffix;

    $this->assertInternalType('string', $table_name_suffix );
    $this->assertEquals('wps_collections_smart', $table_name_suffix );

  }


  /*

  It should update the current add to cart color

  */
  function test_it_should_have_default_values() {

		$this->assertObjectHasAttribute('default_collection_id', self::$DB_Collections_Smart);
		$this->assertObjectHasAttribute('default_post_id', self::$DB_Collections_Smart);
		$this->assertObjectHasAttribute('default_title', self::$DB_Collections_Smart);
		$this->assertObjectHasAttribute('default_handle', self::$DB_Collections_Smart);
    $this->assertObjectHasAttribute('default_post_name', self::$DB_Collections_Smart);
		$this->assertObjectHasAttribute('default_body_html', self::$DB_Collections_Smart);
		$this->assertObjectHasAttribute('default_image', self::$DB_Collections_Smart);
		$this->assertObjectHasAttribute('default_rules', self::$DB_Collections_Smart);
		$this->assertObjectHasAttribute('default_disjunctive', self::$DB_Collections_Smart);
		$this->assertObjectHasAttribute('default_sort_order', self::$DB_Collections_Smart);
		$this->assertObjectHasAttribute('default_published_at', self::$DB_Collections_Smart);
		$this->assertObjectHasAttribute('default_updated_at', self::$DB_Collections_Smart);

  }


  /*

  It should have table info props

  */
  function test_it_should_have_table_info_props() {

    $this->assertObjectHasAttribute('table_name_suffix', self::$DB_Collections_Smart);
    $this->assertObjectHasAttribute('table_name', self::$DB_Collections_Smart);
    $this->assertObjectHasAttribute('version', self::$DB_Collections_Smart);
    $this->assertObjectHasAttribute('primary_key', self::$DB_Collections_Smart);
    $this->assertObjectHasAttribute('lookup_key', self::$DB_Collections_Smart);
    $this->assertObjectHasAttribute('cache_group', self::$DB_Collections_Smart);
    $this->assertObjectHasAttribute('type', self::$DB_Collections_Smart);

  }


  /*

  It should update the current add to cart color

  */
  function test_it_should_match_default_values_and_cols_amount() {

    $cols = self::$DB_Collections_Smart->get_columns();
    $default_cols = self::$DB_Collections_Smart->get_column_defaults();

    $col_difference = array_diff_key($cols, $default_cols);

    $this->assertCount(1, $col_difference);
    $this->assertArrayHasKey('id', $col_difference);

  }


}
