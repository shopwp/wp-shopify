<?php

use WPS\Factories;
use WPS\Utils;


/*

Tests the webhooks for Images

Mock data comes from the "mock-data" folder and is taken directly from
the example output Shopify uses within their documentation found here:

https://help.shopify.com/api/reference/webhook

*/
class Test_DB_Images extends WP_UnitTestCase {

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
    self::$DB_Images                     = Factories\DB\Images_Factory::build();

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


  /*

  It should update the current add to cart color

  */
  function test_it_should_have_default_values() {

    $this->assertObjectHasAttribute('default_image_id', self::$DB_Images);
    $this->assertObjectHasAttribute('default_product_id', self::$DB_Images);
    $this->assertObjectHasAttribute('default_variant_ids', self::$DB_Images);
    $this->assertObjectHasAttribute('default_src', self::$DB_Images);
    $this->assertObjectHasAttribute('default_alt', self::$DB_Images);
    $this->assertObjectHasAttribute('default_position', self::$DB_Images);
    $this->assertObjectHasAttribute('default_created_at', self::$DB_Images);
    $this->assertObjectHasAttribute('default_updated_at', self::$DB_Images);

  }


  /*

  It should have table info props

  */
  function test_it_should_have_table_info_props() {

    $this->assertObjectHasAttribute('table_name_suffix', self::$DB_Images);
    $this->assertObjectHasAttribute('table_name', self::$DB_Images);
    $this->assertObjectHasAttribute('version', self::$DB_Images);
    $this->assertObjectHasAttribute('primary_key', self::$DB_Images);
    $this->assertObjectHasAttribute('lookup_key', self::$DB_Images);
    $this->assertObjectHasAttribute('cache_group', self::$DB_Images);
    $this->assertObjectHasAttribute('type', self::$DB_Images);

  }


  /*

  It should update the current add to cart color

  */
  function test_it_should_match_default_values_and_cols_amount() {

    $cols = self::$DB_Images->get_columns();
    $default_cols = self::$DB_Images->get_column_defaults();

    $col_difference = array_diff_key($cols, $default_cols);

    $this->assertCount(1, $col_difference);
    $this->assertArrayHasKey('id', $col_difference);

  }


  /*

  It should get image extension from url

  */
  function test_it_should_get_image_extension_from_url() {

    $image_url_one = 'https://cdn.shopify.com/s/files/1/2400/7681/products/test_img.jpg?v=1537677359';

    $extension_one = self::$DB_Images->get_image_extension_from_url($image_url_one);

    $this->assertInternalType('string', $extension_one );
    $this->assertEquals('jpg', $extension_one);



    $image_url_two = 'https://cdn.shopify.com/s/files/1/2400/7681/products/test_img.png?v=1537677359';

    $extension_two = self::$DB_Images->get_image_extension_from_url($image_url_two);

    $this->assertInternalType('string', $extension_two );
    $this->assertEquals('png', $extension_two);



    $image_url_three = 'https://cdn.shopify.com/s/files/1/2400/7681/products/test_img@.pjpg';

    $extension_three = self::$DB_Images->get_image_extension_from_url($image_url_three);

    $this->assertInternalType('string', $extension_three );
    $this->assertEquals('pjpg', $extension_three);

  }


  /*

  It should split_from_extension

  */
  function test_it_should_split_from_extension() {

    $image_url_one = 'https://cdn.shopify.com/test_img.jpg?v=1537677359';

    $split_one = self::$DB_Images->split_from_extension($image_url_one, 'jpg');

    $this->assertInternalType('array', $split_one );
    $this->assertCount(2, $split_one);


    $image_url_two = 'https://cdn.shopify.com/test_img@.pnggg?v=1537677359';

    $split_two = self::$DB_Images->split_from_extension($image_url_two, 'pnggg');

    $this->assertInternalType('array', $split_two );
    $this->assertCount(2, $split_two);


    $image_url_three = 'https://cdn.shopify.com/test_img@.pjpg';

    $split_three = self::$DB_Images->split_from_extension($image_url_three, 'pjpg');

    $this->assertInternalType('array', $split_three );
    $this->assertCount(2, $split_three);

  }


  /*

  It should build_width_height_filter

  */
  function test_it_should_build_width_height_filter() {

    $image_url_one = self::$DB_Images->build_width_height_filter(0, 0);

    $this->assertInternalType('string', $image_url_one );
    $this->assertEquals('', $image_url_one);


    $image_url_two = self::$DB_Images->build_width_height_filter(300, 0);

    $this->assertInternalType('string', $image_url_two );
    $this->assertEquals('_300x', $image_url_two);


    $image_url_three = self::$DB_Images->build_width_height_filter(0, 500);

    $this->assertInternalType('string', $image_url_three );
    $this->assertEquals('_x500', $image_url_three);


    $image_url_four = self::$DB_Images->build_width_height_filter(600, 500);

    $this->assertInternalType('string', $image_url_four );
    $this->assertEquals('_600x500', $image_url_four);


    $image_url_five = self::$DB_Images->build_width_height_filter(-1, false);

    $this->assertInternalType('string', $image_url_five );
    $this->assertEquals('', $image_url_five);


    $image_url_six = self::$DB_Images->build_width_height_filter([], 500);

    $this->assertInternalType('string', $image_url_six );
    $this->assertEquals('_x500', $image_url_six);


    $image_url_seven = self::$DB_Images->build_width_height_filter(100);

    $this->assertInternalType('string', $image_url_seven );
    $this->assertEquals('_100x', $image_url_seven);

  }


  /*

  It should split image_url

  */
  function test_it_should_split_image_url() {

    $parts = self::$DB_Images->split_image_url('https://cdn.shopify.com/test_img.png?v=1537677359');

    $this->assertInternalType('array', $parts );
    $this->assertCount(3, $parts);
    $this->assertArrayHasKey('extension', $parts);
    $this->assertArrayHasKey('before_extension', $parts);
    $this->assertArrayHasKey('after_extension', $parts);
    $this->assertEquals('png', $parts['extension']);
    $this->assertEquals('https://cdn.shopify.com/test_img', $parts['before_extension']);
    $this->assertEquals('?v=1537677359', $parts['after_extension']);

  }


  /*

  It should build crop filter

  */
  function test_it_should_build_crop_filter() {

    $crop_one = self::$DB_Images->build_crop_filter('center');

    $this->assertInternalType('string', $crop_one);
    $this->assertEquals('_crop_center', $crop_one);


    $crop_two = self::$DB_Images->build_crop_filter();

    $this->assertInternalType('string', $crop_two);
    $this->assertEquals('', $crop_two);


    $crop_three = self::$DB_Images->build_crop_filter([1,2,3]);

    $this->assertInternalType('string', $crop_three);
    $this->assertEquals('', $crop_three);

  }


  /*

  It should build crop filter

  */
  function test_it_should_build_scale_filter() {

    $scale_one = self::$DB_Images->build_scale_filter(2);

    $this->assertInternalType('string', $scale_one);
    $this->assertEquals('@2x', $scale_one);


    $scale_two = self::$DB_Images->build_scale_filter();

    $this->assertInternalType('string', $scale_two);
    $this->assertEquals('', $scale_two);


    $scale_three = self::$DB_Images->build_scale_filter([1,2,3]);

    $this->assertInternalType('string', $scale_three);
    $this->assertEquals('', $scale_three);

  }


  /*

  It should add custom size to image url

  */
  function test_it_should_add_custom_size_to_image_url() {

    $url_one = self::$DB_Images->add_custom_size_to_image_url(200, 300, 'https://cdn.shopify.com/tesyyt_img?v=12313');

    $this->assertInternalType('string', $url_one);
    $this->assertEquals('https://cdn.shopify.com/tesyyt_img?v=12313', $url_one);


    $url_two = self::$DB_Images->add_custom_size_to_image_url(200, 300, 'https://cdn.shopify.com/testimage.png?v=12313');

    $this->assertInternalType('string', $url_two);
    $this->assertEquals('https://cdn.shopify.com/testimage_200x300.png?v=12313', $url_two);

  }


  /*

  It should add custom size to image url

  Size is added to the URL first because that's what the function assumes

  */
  function test_it_should_add_custom_crop_to_image_url() {

    $settings_1 = [
      'crop'    => 'top',
      'width'   => 200,
      'height'  => 200
    ];

    // Size is added to the URL first because that's what the function assumes
    $url_one = self::$DB_Images->add_custom_crop_to_image_url($settings_1, 'https://cdn.shopify.com/tesyyt_img_200x200.jpg?v=12313');

    $this->assertInternalType('string', $url_one);
    $this->assertEquals('https://cdn.shopify.com/tesyyt_img_200x200_crop_top.jpg?v=12313', $url_one);


    $settings_2 = [
      'crop' => '',
      'width'   => 200,
      'height'  => 200
    ];

    $url_two = self::$DB_Images->add_custom_crop_to_image_url($settings_2, 'https://cdn.shopify.com/tesyyt_img_200x200.jpg?v=12313');

    $this->assertInternalType('string', $url_two);
    $this->assertEquals('https://cdn.shopify.com/tesyyt_img_200x200.jpg?v=12313', $url_two);


    $settings_3 = [
      'crop' => false,
      'width'   => 200,
      'height'  => 200
    ];

    $url_three = self::$DB_Images->add_custom_crop_to_image_url($settings_3, 'https://cdn.shopify.com/tesyyt_img_200x200.jpg?v=12313');

    $this->assertInternalType('string', $url_three);
    $this->assertEquals('https://cdn.shopify.com/tesyyt_img_200x200.jpg?v=12313', $url_three);


    $settings_4 = [
      'crop' => 'top',
      'width'   => 0,
      'height'  => 0
    ];

    $url_four = self::$DB_Images->add_custom_crop_to_image_url($settings_4, 'https://cdn.shopify.com/tesyyt_img.jpg?v=12313');

    $this->assertInternalType('string', $url_four);
    $this->assertEquals('https://cdn.shopify.com/tesyyt_img.jpg?v=12313', $url_four);


    $settings_5 = [
      'crop' => 'top'
    ];

    $url_five = self::$DB_Images->add_custom_crop_to_image_url($settings_5, 'https://cdn.shopify.com/tesyyt_img.jpg?v=12313');

    $this->assertInternalType('string', $url_five);
    $this->assertEquals('https://cdn.shopify.com/tesyyt_img.jpg?v=12313', $url_five);

  }


  /*

  It should add custom size to image url

  */
  function test_it_should_add_custom_scale_to_image_url() {

    $url_one = self::$DB_Images->add_custom_scale_to_image_url(1, 'https://cdn.shopify.com/tesyyt_img_200x200.jpg?v=12313');

    $this->assertInternalType('string', $url_one);
    $this->assertEquals('https://cdn.shopify.com/tesyyt_img_200x200.jpg?v=12313', $url_one);


    $url_two = self::$DB_Images->add_custom_scale_to_image_url(2, 'https://cdn.shopify.com/tesyyt_img_200x200.jpg?v=12313');

    $this->assertInternalType('string', $url_two);
    $this->assertEquals('https://cdn.shopify.com/tesyyt_img_200x200@2x.jpg?v=12313', $url_two);


    $url_three = self::$DB_Images->add_custom_scale_to_image_url(0, 'https://cdn.shopify.com/tesyyt_img_200x200.jpg?v=12313');

    $this->assertInternalType('string', $url_three);
    $this->assertEquals('https://cdn.shopify.com/tesyyt_img_200x200.jpg?v=12313', $url_three);

  }


  /*

  It should add custom size to image url

  */
  function test_it_should_add_custom_sizing_to_image_url() {

    // One
    $url_one = self::$DB_Images->add_custom_sizing_to_image_url([
      'src' 		=> 'https://cdn.shopify.com/test_img.jpg?v=12313',
  		'width' 	=> 200,
  		'height'  => 200,
  		'crop' 	  => 'none',
  		'scale' 	=> 0,
    ]);

    $this->assertInternalType('string', $url_one);
    $this->assertEquals('https://cdn.shopify.com/test_img_200x200.jpg?v=12313', $url_one);


    // Two
    $url_twp = self::$DB_Images->add_custom_sizing_to_image_url([
      'src' 		=> 'https://cdn.shopify.com/test_img.jpg?v=12313',
  		'width' 	=> 0,
  		'height'  => 200,
  		'crop' 	  => 'center',
  		'scale' 	=> 2,
    ]);

    $this->assertInternalType('string', $url_twp);
    $this->assertEquals('https://cdn.shopify.com/test_img_x200_crop_center@2x.jpg?v=12313', $url_twp);


  }



}
