<?php

use WPS\Factories\DB_Variants_Factory;
use WPS\Utils;


/*

Tests the webhooks for Variants

Mock data comes from the "mock-data" folder and is taken directly from
the example output Shopify uses within their documentation found here:

https://help.shopify.com/api/reference/webhook

*/
class Test_Sync_Variants extends WP_UnitTestCase {

  protected static $DB_Variants;
  protected static $mock_variant;
  protected static $mock_variant_for_update;
  protected static $mock_variant_id;
  protected static $mock_variant_insert;
  protected static $mock_variant_update;
  protected static $mock_variant_delete;
  protected static $mock_variants;
  protected static $mock_product;
  protected static $lookup_key;
  protected static $mock_variants_same_price;


  static function wpSetUpBeforeClass() {

    // Assemble
    self::$DB_Variants                 = DB_Variants_Factory::build();

    // Simulates the actual product payload from Shopify
    self::$mock_product                = json_decode( file_get_contents( dirname(__FILE__) . "/mock-data/_common/product.json") );
    self::$mock_variant                = json_decode( file_get_contents( dirname(__FILE__) . "/mock-data/variants/variant.json") );
    self::$mock_variant_for_update     = json_decode( file_get_contents( dirname(__FILE__) . "/mock-data/variants/variant-update.json") );
    self::$mock_variant_insert         = json_decode( file_get_contents( dirname(__FILE__) . "/mock-data/variants/variants-insert.json") );
    self::$mock_variant_update         = json_decode( file_get_contents( dirname(__FILE__) . "/mock-data/variants/variants-update.json") );
    self::$mock_variant_delete         = json_decode( file_get_contents( dirname(__FILE__) . "/mock-data/variants/variants-delete.json") );
    self::$mock_variants               = json_decode( file_get_contents( dirname(__FILE__) . "/mock-data/variants/variants.json") );
    self::$mock_variants_same_price    = json_decode( file_get_contents( dirname(__FILE__) . "/mock-data/variants/variants-same-price.json") );
    self::$mock_variant_id             = self::$mock_variant_for_update->id;
    self::$lookup_key                  = self::$DB_Variants->lookup_key;

  }

  /*

  Mock: Product Create

  */
  function test_variant_create() {

    $result = self::$DB_Variants->insert(self::$mock_variant);
    $this->assertEquals(1, $result);

  }

  /*

  Mock: Product Update

  */
  function test_variant_update() {

    $results = self::$DB_Variants->update(self::$lookup_key, self::$mock_variant_id, self::$mock_variant_for_update);
    $this->assertEquals(1, $results);

  }

  /*

  Mock: Product Delete

  */
  function test_variant_delete() {

    $results = self::$DB_Variants->delete_rows(self::$lookup_key, self::$mock_variant_id);

    $this->assertEquals(1, $results);

  }


  /*

  Should find variants to insert based on mock product

  */
  function test_it_should_find_variants_to_insert() {

    $found_items_to_insert = self::$DB_Variants->gather_items_for_insertion(
      self::$DB_Variants->modify_options( self::$mock_variant_insert )
    );

    $this->assertCount(1, $found_items_to_insert);

  }


  /*

  Should find variants to delete based on mock product

  */
  function test_it_should_find_variants_to_update() {

    $found_items_to_update = self::$DB_Variants->gather_items_for_updating(
      self::$DB_Variants->modify_options( self::$mock_variant_update )
    );

    $this->assertCount(4, $found_items_to_update);

  }


  /*

  Should find variants to delete based on mock product

  */
  function test_it_should_find_variants_to_delete() {

    $found_items_to_delete = self::$DB_Variants->gather_items_for_deletion(
      self::$DB_Variants->modify_options( self::$mock_variant_delete )
    );

    $this->assertCount(1, $found_items_to_delete);

  }


  /*

  Should perform all three modifications: insert, update, delete

  */
  function test_it_should_modify_variants_from_shopify_product() {

    $results = self::$DB_Variants->modify_from_shopify( self::$DB_Variants->modify_options( self::$mock_product ) );

    foreach ( Utils::flatten_array($results) as $result) {
      $this->assertNotWPError($result);
    }

    // Checks that the modification amounts matches mock data
    $this->assertCount(2, $results['created'][0]);
    $this->assertCount(5, $results['updated'][0]);
    $this->assertCount(1, $results['deleted'][0]);

  }


  /*

  Should find all products to delete based on mock product id

  */
  function test_it_should_delete_all_variants_by_product_id() {

    $delete_result = self::$DB_Variants->delete_variants_from_product_id(self::$mock_product->id);

    $this->assertEquals(4, $delete_result);

  }


  /*

  Should rename payload key to lookup key

  */
  function test_it_should_maybe_rename_to_lookup_key() {

    $rename_result = self::$DB_Variants->maybe_rename_to_lookup_key(self::$mock_variant);

    $this->assertObjectHasAttribute(self::$DB_Variants->lookup_key, $rename_result);


  }


  /*

  It should get variants from post ID

  */
  function test_it_should_get_in_stock_variants_from_post_id() {

    $variants = self::$DB_Variants->get_in_stock_variants_from_post_id(18352);

    $this->assertInternalType('array', $variants);
    $this->assertCount(1, $variants);

  }


  /*

  It should get variants amount

  */
  function test_it_should_get_variants_amount() {

    $variants = self::$DB_Variants->get_variants_amount(self::$mock_variants);

    $this->assertInternalType('int', $variants);
    $this->assertEquals(4, $variants);

  }


  /*

  It should sort variants by price

  */
  function test_it_should_sort_variants_by_price() {

    $variants = self::$DB_Variants->sort_variants_by_price(self::$mock_variants);

    $this->assertEquals(6, $variants[0]->price);
    $this->assertEquals(16.82, $variants[1]->price);
    $this->assertEquals(26.82, $variants[2]->price);
    $this->assertEquals(136.82, $variants[3]->price);

  }


  /*

  It should get first variant price

  */
  function test_it_should_get_first_variant_price() {

    $price = self::$DB_Variants->get_first_variant_price(self::$mock_variants);

    $this->assertEquals(26.82, $price);

  }


  /*

  It should get last variant price

  */
  function test_it_should_get_last_variant_price() {

    $last_index = Utils::get_last_index( self::$DB_Variants->get_variants_amount(self::$mock_variants) );

    $price = self::$DB_Variants->get_last_variant_price(self::$mock_variants, $last_index);

    $this->assertEquals(136.82, $price);

  }


  /*

  It should get last variant price

  */
  function test_it_should_check_if_all_variant_prices_match() {

    $last_index = Utils::get_last_index( self::$DB_Variants->get_variants_amount(self::$mock_variants_same_price) );

    $first_variant_price 	= self::$DB_Variants->get_first_variant_price(self::$mock_variants_same_price);
    $last_variant_price = self::$DB_Variants->get_last_variant_price(self::$mock_variants_same_price, $last_index );

    $same_price = self::$DB_Variants->check_if_all_variant_prices_match($last_variant_price, $first_variant_price);

    $this->assertTrue($same_price);

  }


  /*

  It should return the complete table name with suffix as string

  */
  function test_it_should_get_table_name() {

    $table_name = self::$DB_Variants->get_table_name();

    $this->assertInternalType('string', $table_name );
    $this->assertEquals('wptests_wps_variants', $table_name );

  }


  /*

  It should return only the table name suffix as string

  */
  function test_it_should_get_table_name_suffix() {

    $table_name_suffix = self::$DB_Variants->table_name_suffix;

    $this->assertInternalType('string', $table_name_suffix );
    $this->assertEquals('wps_variants', $table_name_suffix );

  }

}
