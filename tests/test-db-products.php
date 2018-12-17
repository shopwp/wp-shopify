<?php

use WPS\Factories;
use WPS\Options;


/*

Tests the webhooks for Products

Mock data comes from the "mock-data" folder and is taken directly from
the example output Shopify uses within their documentation found here:

https://help.shopify.com/api/reference/webhook

*/
class Test_DB_Products extends WP_UnitTestCase {

  protected static $DB_Products;
  protected static $Templates;
  protected static $mock_data_product;
  protected static $mock_data_product_for_update;
  protected static $mock_data_product_id;
  protected static $mock_data_product_sync_insert;
  protected static $mock_product_insert;
  protected static $mock_product_update;
  protected static $mock_product_delete;
  protected static $mock_product;
  protected static $mock_product_without_image_src;
  protected static $lookup_key;


  static function wpSetUpBeforeClass() {

    // Assemble
    self::$DB_Products                      = Factories\DB\Products_Factory::build();
    self::$Templates                        = Factories\Templates_Factory::build();
    self::$mock_data_product                = json_decode( file_get_contents( dirname(__FILE__) . "/mock-data/product.json") );
    self::$mock_data_product_sync_insert    = json_decode( file_get_contents( dirname(__FILE__) . "/mock-data/product-sync-insert.json") );
    self::$mock_data_product_for_update     = json_decode( file_get_contents( dirname(__FILE__) . "/mock-data/product-update.json") );

    self::$mock_product                     = json_decode( file_get_contents( dirname(__FILE__) . "/mock-data/_common/product.json") );
    self::$mock_product_insert              = json_decode( file_get_contents( dirname(__FILE__) . "/mock-data/products/products-insert.json") );
    self::$mock_product_update              = json_decode( file_get_contents( dirname(__FILE__) . "/mock-data/products/products-update.json") );
    self::$mock_product_delete              = json_decode( file_get_contents( dirname(__FILE__) . "/mock-data/products/products-delete.json") );

    self::$mock_product_without_image_src   = json_decode( file_get_contents( dirname(__FILE__) . "/mock-data/products/product-without-image-src.json") );

    self::$mock_data_product_id             = self::$mock_data_product_for_update->id;
    self::$lookup_key                       = self::$DB_Products->lookup_key;

  }


  /*

  Mock: Product Create

  */
  function test_product_create() {

    $result = self::$DB_Products->insert(self::$mock_data_product);

    $this->assertEquals(1, $result);

  }


  /*

  Mock: Product Update
  The DB update metho

  */
  function test_product_update() {

    $results = self::$DB_Products->update(self::$lookup_key, self::$mock_data_product_id, self::$mock_data_product_for_update);

    $this->assertEquals(1, $results);

  }


  /*

  Mock: Product Delete

  */
  function test_product_delete() {

    $results = self::$DB_Products->delete_rows(self::$lookup_key, self::$mock_data_product_id );

    $this->assertEquals(1, $results);

  }


  /*

  Should find products to insert based on mock product

  */
  function test_it_should_insert_product() {

    $insert_item_result = self::$DB_Products->insert_items_of_type( self::$mock_product_insert);

    $this->assertEquals(1, $insert_item_result);

  }


  /*

  Should find products to update based on mock product

  */
  function test_it_should_update_product() {

    $update_item_result = self::$DB_Products->update_items_of_type( self::$mock_product_update);

    $this->assertEquals(1, $update_item_result);

  }


  /*

  Should update order

  */
  function test_it_should_delete_product() {

    $delete_item_result = self::$DB_Products->delete_items_of_type( self::$mock_product);

    $this->assertEquals(1, $delete_item_result);

  }


  /*

  Should find all products to delete based on mock product id

  */
  function test_it_should_delete_all_products_by_product_id() {

    $delete_result = self::$DB_Products->delete_products_from_product_id(self::$mock_product->id);

    $this->assertEquals(1, $delete_result);

  }


  function test_it_should_find_post_id_from_product_id() {

    $post_id = self::$DB_Products->find_post_id_from_product_id(self::$mock_product->id);

    $this->assertEquals(18353, $post_id);

  }


  /*

  Should rename payload key to lookup key

  */
  function test_it_should_maybe_rename_to_lookup_key() {

    $rename_result = self::$DB_Products->maybe_rename_to_lookup_key(self::$mock_product);

    $this->assertObjectHasAttribute(self::$DB_Products->lookup_key, $rename_result);

  }


  /*

  Should rename payload key to lookup key

  */
  function test_it_should_get_post_id_from_product() {

    $product_data = self::$Templates->get_product_data(18352);

    $result = self::$DB_Products->get_post_id_from_product($product_data);

    $this->assertEquals(18352, $result);

  }


  /*

  It should return the complete table name with suffix as string

  */
  function test_it_should_get_table_name() {

    $table_name = self::$DB_Products->get_table_name();

    $this->assertInternalType('string', $table_name );
    $this->assertEquals('wptests_wps_products', $table_name );

  }


  /*

  It should return only the table name suffix as string

  */
  function test_it_should_get_table_name_suffix() {

    $table_name_suffix = self::$DB_Products->table_name_suffix;

    $this->assertInternalType('string', $table_name_suffix );
    $this->assertEquals('wps_products', $table_name_suffix );

  }


  function test_it_should_create_new_table() {

    $result = self::$DB_Products->create_table_if_doesnt_exist('this_is_a_new_table');

    $created_table_transient = Options::get('wp_shopify_table_exists_this_is_a_new_table');

    $this->assertEquals('1', $created_table_transient);

    $this->assertInternalType('array', $result);
    $this->assertEquals(['this_is_a_new_table' => 'Created table this_is_a_new_table'], $result);

  }


  function test_it_should_not_create_existing_table() {

    $result = self::$DB_Products->create_table_if_doesnt_exist('wptests_wps_products');

    $this->assertInternalType('boolean', $result);
    $this->assertFalse($result);

  }


  /*

  It should update the current add to cart color

  */
  function test_it_should_have_default_values() {

    $this->assertObjectHasAttribute('default_product_id', self::$DB_Products);
    $this->assertObjectHasAttribute('default_post_id', self::$DB_Products);
    $this->assertObjectHasAttribute('default_title', self::$DB_Products);
    $this->assertObjectHasAttribute('default_body_html', self::$DB_Products);
    $this->assertObjectHasAttribute('default_handle', self::$DB_Products);
    $this->assertObjectHasAttribute('default_post_name', self::$DB_Products);
    $this->assertObjectHasAttribute('default_image', self::$DB_Products);
    $this->assertObjectHasAttribute('default_images', self::$DB_Products);
    $this->assertObjectHasAttribute('default_vendor', self::$DB_Products);
    $this->assertObjectHasAttribute('default_product_type', self::$DB_Products);
    $this->assertObjectHasAttribute('default_published_scope', self::$DB_Products);
    $this->assertObjectHasAttribute('default_published_at', self::$DB_Products);
    $this->assertObjectHasAttribute('default_updated_at', self::$DB_Products);
    $this->assertObjectHasAttribute('default_created_at', self::$DB_Products);
    $this->assertObjectHasAttribute('default_admin_graphql_api_id', self::$DB_Products);

  }


  /*

  It should have table info props

  */
  function test_it_should_have_table_info_props() {

    $this->assertObjectHasAttribute('table_name_suffix', self::$DB_Products);
    $this->assertObjectHasAttribute('table_name', self::$DB_Products);
    $this->assertObjectHasAttribute('version', self::$DB_Products);
    $this->assertObjectHasAttribute('primary_key', self::$DB_Products);
    $this->assertObjectHasAttribute('lookup_key', self::$DB_Products);
    $this->assertObjectHasAttribute('cache_group', self::$DB_Products);
    $this->assertObjectHasAttribute('type', self::$DB_Products);

  }


  /*

  It should update the current add to cart color

  */
  function test_it_should_match_default_values_and_cols_amount() {

    $cols = self::$DB_Products->get_columns();
    $default_cols = self::$DB_Products->get_column_defaults();

    $col_difference = array_diff_key($cols, $default_cols);

    $this->assertCount(1, $col_difference);
    $this->assertArrayHasKey('id', $col_difference);

  }


  /*

  The native wordpres 'post_name' should ALWAYS equal the custom DB_Products 'post_name'

  */
  function test_it_should_have_matching_post_names() {


    /*

    Assemble

    */
    $product_1 = self::$DB_Products->copy(self::$mock_product_insert);
    $product_1->product_id = 999999995332079;
    $product_1->handle = 'example-pants®';

    $post_1_id = $this->factory->post->create([
      'post_title'    => 'Example Pants',
      'post_name'     => sanitize_title($product_1->handle),
			'post_type'     => 'wps_products',
			'meta_input' => [
				'product_id' => 999999995332079
			]
    ]);


    $product_2 = self::$DB_Products->copy(self::$mock_product_insert);
    $product_2->product_id = 9999999953320799;
    $product_2->handle = '♥example♥pants♥';

    $post_2_id = $this->factory->post->create([
      'post_title'    => 'Example Pants',
      'post_name'     => sanitize_title($product_2->handle),
			'post_type'     => 'wps_products',
			'meta_input' => [
				'product_id' => 9999999953320799
			]
    ]);


    /*

    Act

    */
    self::$DB_Products->insert($product_1);
    $product_1_data = self::$DB_Products->get_products_from_product_id(999999995332079);
    $post_1_data = get_post($post_1_id);

    self::$DB_Products->insert($product_2);
    $product_2_data = self::$DB_Products->get_products_from_product_id(9999999953320799);
    $post_2_data = get_post($post_2_id);


    /*

    Assert

    */
    $this->assertInternalType('string', $post_1_data->post_name);
    $this->assertInternalType('string', $product_1_data[0]->post_name);
    $this->assertEquals($post_1_data->post_name, $product_1_data[0]->post_name);

    $this->assertInternalType('string', $post_2_data->post_name);
    $this->assertInternalType('string', $product_2_data[0]->post_name);
    $this->assertEquals($post_2_data->post_name, $product_2_data[0]->post_name);

  }



}
