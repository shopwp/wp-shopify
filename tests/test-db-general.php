<?php

use WPS\Factories\DB_Settings_General_Factory;


/*

Tests the webhooks for General

General key currently doesn't update -- only adds or deletes

*/
class Test_DB_General extends WP_UnitTestCase {

  protected static $DB_Settings_General;
  protected static $mock_general_update;
  protected static $mock_general_id;
  protected static $lookup_key;


  static function wpSetUpBeforeClass() {

    // Assemble
    self::$DB_Settings_General       = DB_Settings_General_Factory::build();
    self::$mock_general_update       = json_decode( file_get_contents( dirname(__FILE__) . "/mock-data/general-update.json") );
    self::$mock_general_id           = self::$mock_general_update->id;
    self::$lookup_key                = self::$DB_Settings_General->lookup_key;

  }


  /*

  Mock: Product Update

  */
  function test_general_update() {

    $results = self::$DB_Settings_General->update(self::$lookup_key, self::$mock_general_id, self::$mock_general_update);
    $this->assertEquals(1, $results);

  }


  /*

  Test it should get enable beta setting

  */
  function test_it_should_get_enable_beta() {

    $result = self::$DB_Settings_General->get_enable_beta();

    $this->assertInternalType('boolean', $result);
    $this->assertEquals(false, $result);

  }


  /*

  Test it should get enable beta setting

  */
  function test_it_should_update_enable_beta() {

    $result = self::$DB_Settings_General->update_general(['enable_beta' => 1]);
    $after_update = self::$DB_Settings_General->get_enable_beta();

    $this->assertInternalType('boolean', $after_update);
    $this->assertEquals(true, $after_update);

  }


  /*

  It should return the complete table name with suffix as string

  */
  function test_it_should_get_table_name() {

    $table_name = self::$DB_Settings_General->get_table_name();

    $this->assertInternalType('string', $table_name );
    $this->assertEquals('wptests_wps_settings_general', $table_name );

  }


  /*

  It should return only the table name suffix as string

  */
  function test_it_should_get_table_name_suffix() {

    $table_name_suffix = self::$DB_Settings_General->table_name_suffix;

    $this->assertInternalType('string', $table_name_suffix );
    $this->assertEquals('wps_settings_general', $table_name_suffix );

  }


  /*

  It should get the current add to cart color

  */
  function test_it_should_get_add_to_cart_button_color() {

    $color = self::$DB_Settings_General->get_add_to_cart_color();

    $this->assertInternalType('string', $color );
    $this->assertEquals('#14273b', $color );

  }


  /*

  It should update the current add to cart color

  */
  function test_it_should_update_add_to_cart_color() {

    $update_result = self::$DB_Settings_General->update_add_to_cart_color('#FFF');

    $this->assertEquals(1, $update_result);
    $this->assertInternalType('int', $update_result);

  }


  /*

  It should update the current add to cart color

  */
  function test_it_should_have_default_values() {

    $this->assertObjectHasAttribute('default_webhooks', self::$DB_Settings_General);
    $this->assertObjectHasAttribute('default_id', self::$DB_Settings_General);
    $this->assertObjectHasAttribute('default_plugin_version', self::$DB_Settings_General);
    $this->assertObjectHasAttribute('default_plugin_author', self::$DB_Settings_General);
    $this->assertObjectHasAttribute('default_plugin_textdomain', self::$DB_Settings_General);
    $this->assertObjectHasAttribute('default_plugin_name', self::$DB_Settings_General);
    $this->assertObjectHasAttribute('default_num_posts', self::$DB_Settings_General);
    $this->assertObjectHasAttribute('default_title_as_alt', self::$DB_Settings_General);
    $this->assertObjectHasAttribute('default_price_with_currency', self::$DB_Settings_General);
    $this->assertObjectHasAttribute('default_styles_all', self::$DB_Settings_General);
    $this->assertObjectHasAttribute('default_selective_sync_all', self::$DB_Settings_General);
    $this->assertObjectHasAttribute('default_selective_sync_products', self::$DB_Settings_General);
    $this->assertObjectHasAttribute('default_selective_sync_collections', self::$DB_Settings_General);
    $this->assertObjectHasAttribute('default_selective_sync_customers', self::$DB_Settings_General);
    $this->assertObjectHasAttribute('default_selective_sync_orders', self::$DB_Settings_General);
    $this->assertObjectHasAttribute('default_selective_sync_shop', self::$DB_Settings_General);
    $this->assertObjectHasAttribute('default_products_link_to_shopify', self::$DB_Settings_General);
    $this->assertObjectHasAttribute('default_show_breadcrumbs', self::$DB_Settings_General);
    $this->assertObjectHasAttribute('default_hide_pagination', self::$DB_Settings_General);
    $this->assertObjectHasAttribute('default_is_free', self::$DB_Settings_General);
    $this->assertObjectHasAttribute('default_is_pro', self::$DB_Settings_General);
    $this->assertObjectHasAttribute('default_related_products_show', self::$DB_Settings_General);
    $this->assertObjectHasAttribute('default_related_products_sort', self::$DB_Settings_General);
    $this->assertObjectHasAttribute('default_related_products_amount', self::$DB_Settings_General);
    $this->assertObjectHasAttribute('default_allow_insecure_webhooks', self::$DB_Settings_General);
    $this->assertObjectHasAttribute('default_save_connection_only', self::$DB_Settings_General);
    $this->assertObjectHasAttribute('default_app_uninstalled', self::$DB_Settings_General);
    $this->assertObjectHasAttribute('default_items_per_request', self::$DB_Settings_General);
    $this->assertObjectHasAttribute('default_enable_beta', self::$DB_Settings_General);
    $this->assertObjectHasAttribute('default_enable_cart_terms', self::$DB_Settings_General);
    $this->assertObjectHasAttribute('default_cart_terms_content', self::$DB_Settings_General);
    $this->assertObjectHasAttribute('default_url_products', self::$DB_Settings_General);
    $this->assertObjectHasAttribute('default_url_collections', self::$DB_Settings_General);
    $this->assertObjectHasAttribute('default_add_to_cart_color', self::$DB_Settings_General);
    $this->assertObjectHasAttribute('default_variant_color', self::$DB_Settings_General);
    $this->assertObjectHasAttribute('default_checkout_color', self::$DB_Settings_General);
  	$this->assertObjectHasAttribute('default_cart_counter_color', self::$DB_Settings_General);
  	$this->assertObjectHasAttribute('default_cart_icon_color', self::$DB_Settings_General);
  	$this->assertObjectHasAttribute('default_products_heading_toggle', self::$DB_Settings_General);
  	$this->assertObjectHasAttribute('default_products_heading', self::$DB_Settings_General);
  	$this->assertObjectHasAttribute('default_collections_heading_toggle', self::$DB_Settings_General);
  	$this->assertObjectHasAttribute('default_collections_heading', self::$DB_Settings_General);
  	$this->assertObjectHasAttribute('default_related_products_heading_toggle', self::$DB_Settings_General);
  	$this->assertObjectHasAttribute('default_related_products_heading', self::$DB_Settings_General);
  	$this->assertObjectHasAttribute('default_products_images_sizing_toggle', self::$DB_Settings_General);
  	$this->assertObjectHasAttribute('default_products_images_sizing_width', self::$DB_Settings_General);
  	$this->assertObjectHasAttribute('default_products_images_sizing_height', self::$DB_Settings_General);
  	$this->assertObjectHasAttribute('default_products_images_sizing_crop', self::$DB_Settings_General);
  	$this->assertObjectHasAttribute('default_products_images_sizing_scale', self::$DB_Settings_General);
    $this->assertObjectHasAttribute('default_collections_images_sizing_toggle', self::$DB_Settings_General);
  	$this->assertObjectHasAttribute('default_collections_images_sizing_width', self::$DB_Settings_General);
  	$this->assertObjectHasAttribute('default_collections_images_sizing_height', self::$DB_Settings_General);
  	$this->assertObjectHasAttribute('default_collections_images_sizing_crop', self::$DB_Settings_General);
  	$this->assertObjectHasAttribute('default_collections_images_sizing_scale', self::$DB_Settings_General);
    $this->assertObjectHasAttribute('default_related_products_images_sizing_toggle', self::$DB_Settings_General);
  	$this->assertObjectHasAttribute('default_related_products_images_sizing_width', self::$DB_Settings_General);
  	$this->assertObjectHasAttribute('default_related_products_images_sizing_height', self::$DB_Settings_General);
  	$this->assertObjectHasAttribute('default_related_products_images_sizing_crop', self::$DB_Settings_General);
  	$this->assertObjectHasAttribute('default_related_products_images_sizing_scale', self::$DB_Settings_General);

  }


  /*

  It should have table info props

  */
  function test_it_should_have_table_info_props() {

    $this->assertObjectHasAttribute('table_name_suffix', self::$DB_Settings_General);
    $this->assertObjectHasAttribute('table_name', self::$DB_Settings_General);
    $this->assertObjectHasAttribute('version', self::$DB_Settings_General);
    $this->assertObjectHasAttribute('primary_key', self::$DB_Settings_General);
    $this->assertObjectHasAttribute('lookup_key', self::$DB_Settings_General);
    $this->assertObjectHasAttribute('cache_group', self::$DB_Settings_General);
    $this->assertObjectHasAttribute('type', self::$DB_Settings_General);

  }


  /*

  It should update the current add to cart color

  */
  function test_it_should_match_default_values_and_cols_amount() {

    $cols_count = count( self::$DB_Settings_General->get_columns() );
    $default_cols_count = count( self::$DB_Settings_General->get_column_defaults() );

    $this->assertEquals($cols_count, $default_cols_count);

  }

}
