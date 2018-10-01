<?php

use WPS\Factories\DB_Images_Factory;
use WPS\Utils;


/*

Tests the webhooks for Images

Mock data comes from the "mock-data" folder and is taken directly from
the example output Shopify uses within their documentation found here:

https://help.shopify.com/api/reference/webhook

*/
class Test_Sync_Images extends WP_UnitTestCase {

  protected static $DB_Images;
  protected static $mock_data_image;
  protected static $mock_data_image_for_update;
  protected static $mock_existing_image_id;
  protected static $mock_image_insert;
  protected static $mock_image_update;
  protected static $mock_image_delete;
  protected static $mock_product;
  protected static $lookup_key;


  static function wpSetUpBeforeClass() {

    // Assemble
    self::$DB_Images                     = DB_Images_Factory::build();

    self::$mock_product                  = json_decode( file_get_contents( dirname(__FILE__) . "/mock-data/_common/product.json") );
    self::$mock_data_image               = json_decode( file_get_contents( dirname(__FILE__) . "/mock-data/images/image.json") );
    self::$mock_data_image_for_update    = json_decode( file_get_contents( dirname(__FILE__) . "/mock-data/image-update.json") );
    self::$mock_image_insert             = json_decode( file_get_contents( dirname(__FILE__) . "/mock-data/images/images-insert.json") );
    self::$mock_image_update             = json_decode( file_get_contents( dirname(__FILE__) . "/mock-data/images/images-update.json") );
    self::$mock_image_delete             = json_decode( file_get_contents( dirname(__FILE__) . "/mock-data/images/images-delete.json") );

    self::$mock_existing_image_id        = self::$mock_data_image_for_update->id;
    self::$lookup_key                    = self::$DB_Images->lookup_key;

  }


  /*

  Mock: Product Create

  */
  function test_image_create() {

    $result = self::$DB_Images->insert(self::$mock_data_image);
    $this->assertEquals(1, $result);

  }


  /*

  Mock: Product Update

  */
  function test_image_update() {

    $results = self::$DB_Images->update(self::$lookup_key, self::$mock_existing_image_id, self::$mock_data_image_for_update);
    $this->assertEquals(1, $results);

  }


  /*

  Mock: Product Delete

  */
  function test_image_delete() {

    $results = self::$DB_Images->delete_rows(self::$lookup_key, self::$mock_existing_image_id );

    $this->assertEquals(1, $results);

  }









  /*

  Should find images to insert based on mock product

  */
  function test_it_should_find_images_to_insert() {

    $found_items_to_insert = self::$DB_Images->gather_items_for_insertion(
      self::$DB_Images->modify_options( self::$mock_image_insert )
    );

    $this->assertCount(1, $found_items_to_insert);

  }


  /*

  Should find options to delete based on mock product

  */
  function test_it_should_find_images_to_update() {

    $found_items_to_update = self::$DB_Images->gather_items_for_updating(
      self::$DB_Images->modify_options( self::$mock_image_update )
    );

    $this->assertCount(2, $found_items_to_update);

  }


  /*

  Should find options to delete based on mock product

  */
  function test_it_should_find_images_to_delete() {

    $found_items_to_delete = self::$DB_Images->gather_items_for_deletion(
      self::$DB_Images->modify_options( self::$mock_image_delete )
    );

    $this->assertCount(1, $found_items_to_delete);

  }


  /*

  Should perform all three modifications: insert, update, delete

  */
  function test_it_should_modify_images_from_shopify_product() {

    $results = self::$DB_Images->modify_from_shopify( self::$DB_Images->modify_options( self::$mock_product ) );

    // Check if any WP_Errors occured ...
    foreach ( Utils::flatten_array($results) as $result) {
      $this->assertNotWPError($result);
    }

    // Checks that the modification amounts matches mock data
    $this->assertCount(1, $results['created'][0]);
    $this->assertCount(2, $results['updated'][0]);
    $this->assertCount(1, $results['deleted'][0]);

  }


  /*

  Should find all products to delete based on mock product id

  */
  function test_it_should_delete_all_images_by_product_id() {

    $delete_result = self::$DB_Images->delete_images_from_product_id(self::$mock_product->id);

    $this->assertEquals(2, $delete_result);

  }


  /*

  Should rename payload key to lookup key

  */
  function test_it_should_maybe_rename_to_lookup_key() {

    $rename_result = self::$DB_Images->maybe_rename_to_lookup_key(self::$mock_image_insert);

    $this->assertObjectHasAttribute(self::$DB_Images->lookup_key, $rename_result);

  }


  /*

  It should return the complete table name with suffix as string

  */
  function test_it_should_get_table_name() {

    $table_name = self::$DB_Images->get_table_name();

    $this->assertInternalType('string', $table_name );
    $this->assertEquals('wptests_wps_images', $table_name );

  }


  /*

  It should return only the table name suffix as string

  */
  function test_it_should_get_table_name_suffix() {

    $table_name_suffix = self::$DB_Images->table_name_suffix;

    $this->assertInternalType('string', $table_name_suffix );
    $this->assertEquals('wps_images', $table_name_suffix );

  }



}
