<?php

use WPS\Factories\DB_Tags_Factory;
use WPS\Utils;


/*

Tests the webhooks for Tags

Mock data comes from the "mock-data" folder and is taken directly from
the example output Shopify uses within their documentation found here:

https://help.shopify.com/api/reference/webhook

Tags are not updated -- only created or deleted

*/
class Test_DB_Tags extends WP_UnitTestCase {

  protected static $DB_Tags;
  protected static $mock_data_tag_id;
  protected static $mock_data_tag;
  protected static $mock_product;
  protected static $mock_product_without_tags;
  protected static $mock_tag_delete;
  protected static $lookup_key;

  static function wpSetUpBeforeClass() {

    // Assemble
    self::$DB_Tags                        = DB_Tags_Factory::build();
    self::$mock_product                   = json_decode( file_get_contents( dirname(__FILE__) . "/mock-data/_common/product.json") );
    self::$mock_data_tag                  = json_decode( file_get_contents( dirname(__FILE__) . "/mock-data/tags/tag.json") );
    self::$mock_product_without_tags      = json_decode( file_get_contents( dirname(__FILE__) . "/mock-data/tags/tags-insert.json") );
    self::$mock_tag_delete                = json_decode( file_get_contents( dirname(__FILE__) . "/mock-data/tags/tags-delete.json") );
    self::$mock_data_tag_id               = "nesciunt";
    self::$lookup_key                     = self::$DB_Tags->lookup_key;

  }


  /*

  Mock: Product Create

  */
  function test_tag_create() {

    $result = self::$DB_Tags->insert(self::$mock_data_tag);

    $this->assertEquals(1, $result);

  }


  /*

  Mock: Product Delete

  */
  function test_tag_delete() {

    $results = self::$DB_Tags->delete_rows('tag', self::$mock_data_tag_id );

    $this->assertEquals(1, $results);

  }


  /*

  Should find tags to insert based on mock product

  */
  function test_it_should_find_tags_to_insert() {

    $tags_to_insert = self::$DB_Tags->construct_tags_for_insert( self::$mock_product_without_tags, 0 );

    $found_items_to_insert = self::$DB_Tags->gather_items_for_insertion(
      self::$DB_Tags->modify_options( self::$DB_Tags->add_tags_to_product( $tags_to_insert, self::$mock_product_without_tags ) )
    );

    $this->assertCount(1, $found_items_to_insert);

  }


  /*

  Should find tags to delete based on mock product

  */
  function test_it_should_find_tags_to_delete() {

    $found_items_to_delete = self::$DB_Tags->gather_items_for_deletion(
      self::$DB_Tags->modify_options( self::$DB_Tags->add_tags_to_product( self::$DB_Tags->construct_tags_for_insert( self::$mock_tag_delete, 0 ), self::$mock_tag_delete) )
    );

    $this->assertCount(1, $found_items_to_delete);

  }


  /*

  Should perform all three modifications: insert, update, delete

  */
  function test_it_should_modify_tags_from_shopify_product() {

    $results = self::$DB_Tags->modify_from_shopify( self::$DB_Tags->modify_options( self::$DB_Tags->add_tags_to_product( self::$DB_Tags->construct_tags_for_insert( self::$mock_product, 0 ), self::$mock_product) ) );

    // Check if any WP_Errors occured ...
    foreach ( Utils::flatten_array($results) as $result) {
      $this->assertNotWPError($result);
    }

    // Checks that the modification amounts matches mock data
    $this->assertCount(1, $results['created'][0]);
    $this->assertCount(1, $results['deleted'][0]);

  }


  /*

  Should find all tags to delete based on mock product id

  */
  function test_it_should_delete_all_tags_by_product_id() {

    $delete_result = self::$DB_Tags->delete_tags_from_product_id(self::$mock_product->id);

    $this->assertEquals(3, $delete_result);

  }


  /*

  Should find all tags to delete based on mock product id

  */
  function test_it_should_create_tag_id() {

    $tag_result = self::$DB_Tags->create_tag_id( (array) self::$mock_data_tag );

    $this->assertInternalType('int', $tag_result);
		$this->assertEquals(4205435227, $tag_result);

  }


  /*

  It should return the complete table name with suffix as string

  */
  function test_it_should_get_table_name() {

    $table_name = self::$DB_Tags->get_table_name();

    $this->assertInternalType('string', $table_name );
    $this->assertEquals('wptests_wps_tags', $table_name );

  }


  /*

  It should return only the table name suffix as string

  */
  function test_it_should_get_table_name_suffix() {

    $table_name_suffix = self::$DB_Tags->table_name_suffix;

    $this->assertInternalType('string', $table_name_suffix );
    $this->assertEquals('wps_tags', $table_name_suffix );

  }


  /*

  It should update the current add to cart color

  */
  function test_it_should_have_default_values() {

    $this->assertObjectHasAttribute('default_id', self::$DB_Tags);
    $this->assertObjectHasAttribute('default_tag_id', self::$DB_Tags);
    $this->assertObjectHasAttribute('default_product_id', self::$DB_Tags);
    $this->assertObjectHasAttribute('default_post_id', self::$DB_Tags);
    $this->assertObjectHasAttribute('default_tag', self::$DB_Tags);

  }


  /*

  It should have table info props

  */
  function test_it_should_have_table_info_props() {

    $this->assertObjectHasAttribute('table_name_suffix', self::$DB_Tags);
    $this->assertObjectHasAttribute('table_name', self::$DB_Tags);
    $this->assertObjectHasAttribute('version', self::$DB_Tags);
    $this->assertObjectHasAttribute('primary_key', self::$DB_Tags);
    $this->assertObjectHasAttribute('lookup_key', self::$DB_Tags);
    $this->assertObjectHasAttribute('cache_group', self::$DB_Tags);
    $this->assertObjectHasAttribute('type', self::$DB_Tags);

  }


  /*

  It should update the current add to cart color

  */
  function test_it_should_match_default_values_and_cols_amount() {

    $cols_count = count( self::$DB_Tags->get_columns() );
    $default_cols_count = count( self::$DB_Tags->get_column_defaults() );

    $this->assertEquals($cols_count, $default_cols_count);

  }


}
