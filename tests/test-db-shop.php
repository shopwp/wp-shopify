<?php

use WPS\Factories\DB_Shop_Factory;

/*

Tests the webhooks for Shop

Mock data comes from the "mock-data" folder and is taken directly from
the example output Shopify uses within their documentation found here:

https://help.shopify.com/api/reference/webhook

*/
class Test_DB_Shop extends WP_UnitTestCase {

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



  /*

  It should update the current add to cart color

  */
  function test_it_should_have_default_values() {

    $this->assertObjectHasAttribute('default_name', self::$DB_Shop);
    $this->assertObjectHasAttribute('default_myshopify_domain', self::$DB_Shop);
    $this->assertObjectHasAttribute('default_shop_owner', self::$DB_Shop);
    $this->assertObjectHasAttribute('default_phone', self::$DB_Shop);
    $this->assertObjectHasAttribute('default_email', self::$DB_Shop);
    $this->assertObjectHasAttribute('default_address1', self::$DB_Shop);
    $this->assertObjectHasAttribute('default_address2', self::$DB_Shop);
    $this->assertObjectHasAttribute('default_city', self::$DB_Shop);
    $this->assertObjectHasAttribute('default_zip', self::$DB_Shop);
    $this->assertObjectHasAttribute('default_country', self::$DB_Shop);
    $this->assertObjectHasAttribute('default_country_code', self::$DB_Shop);
    $this->assertObjectHasAttribute('default_country_name', self::$DB_Shop);
    $this->assertObjectHasAttribute('default_currency', self::$DB_Shop);
    $this->assertObjectHasAttribute('default_latitude', self::$DB_Shop);
    $this->assertObjectHasAttribute('default_longitude', self::$DB_Shop);
    $this->assertObjectHasAttribute('default_money_format', self::$DB_Shop);
    $this->assertObjectHasAttribute('default_money_with_currency_format', self::$DB_Shop);
    $this->assertObjectHasAttribute('default_weight_unit', self::$DB_Shop);
    $this->assertObjectHasAttribute('default_primary_locale', self::$DB_Shop);
    $this->assertObjectHasAttribute('default_province', self::$DB_Shop);
    $this->assertObjectHasAttribute('default_province_code', self::$DB_Shop);
    $this->assertObjectHasAttribute('default_timezone', self::$DB_Shop);
    $this->assertObjectHasAttribute('default_created_at', self::$DB_Shop);
    $this->assertObjectHasAttribute('default_updated_at', self::$DB_Shop);
    $this->assertObjectHasAttribute('default_domain', self::$DB_Shop);
    $this->assertObjectHasAttribute('default_source', self::$DB_Shop);
    $this->assertObjectHasAttribute('default_customer_email', self::$DB_Shop);
    $this->assertObjectHasAttribute('default_iana_timezone', self::$DB_Shop);
    $this->assertObjectHasAttribute('default_taxes_included', self::$DB_Shop);
    $this->assertObjectHasAttribute('default_tax_shipping', self::$DB_Shop);
    $this->assertObjectHasAttribute('default_county_taxes', self::$DB_Shop);
    $this->assertObjectHasAttribute('default_plan_display_name', self::$DB_Shop);
    $this->assertObjectHasAttribute('default_plan_name', self::$DB_Shop);
    $this->assertObjectHasAttribute('default_has_discounts', self::$DB_Shop);
    $this->assertObjectHasAttribute('default_has_gift_cards', self::$DB_Shop);
    $this->assertObjectHasAttribute('default_google_apps_domain', self::$DB_Shop);
    $this->assertObjectHasAttribute('default_google_apps_login_enabled', self::$DB_Shop);
    $this->assertObjectHasAttribute('default_money_in_emails_format', self::$DB_Shop);
    $this->assertObjectHasAttribute('default_money_with_currency_in_emails_format', self::$DB_Shop);
    $this->assertObjectHasAttribute('default_eligible_for_payments', self::$DB_Shop);
    $this->assertObjectHasAttribute('default_requires_extra_payments_agreement', self::$DB_Shop);
    $this->assertObjectHasAttribute('default_password_enabled', self::$DB_Shop);
    $this->assertObjectHasAttribute('default_has_storefront', self::$DB_Shop);
    $this->assertObjectHasAttribute('default_eligible_for_card_reader_giveaway', self::$DB_Shop);
    $this->assertObjectHasAttribute('default_finances', self::$DB_Shop);
    $this->assertObjectHasAttribute('default_primary_location_id', self::$DB_Shop);
    $this->assertObjectHasAttribute('default_checkout_api_supported', self::$DB_Shop);
    $this->assertObjectHasAttribute('default_multi_location_enabled', self::$DB_Shop);
    $this->assertObjectHasAttribute('default_setup_required', self::$DB_Shop);
    $this->assertObjectHasAttribute('default_force_ssl', self::$DB_Shop);
    $this->assertObjectHasAttribute('default_pre_launch_enabled', self::$DB_Shop);

  }


  /*

  It should have table info props

  */
  function test_it_should_have_table_info_props() {

    $this->assertObjectHasAttribute('table_name_suffix', self::$DB_Shop);
    $this->assertObjectHasAttribute('table_name', self::$DB_Shop);
    $this->assertObjectHasAttribute('version', self::$DB_Shop);
    $this->assertObjectHasAttribute('primary_key', self::$DB_Shop);
    $this->assertObjectHasAttribute('lookup_key', self::$DB_Shop);
    $this->assertObjectHasAttribute('cache_group', self::$DB_Shop);
    $this->assertObjectHasAttribute('type', self::$DB_Shop);

  }


  /*

  It should update the current add to cart color

  */
  function test_it_should_match_default_values_and_cols_amount() {

    $cols = self::$DB_Shop->get_columns();
    $default_cols = self::$DB_Shop->get_column_defaults();

    $col_difference = array_diff_key($cols, $default_cols);

    $this->assertCount(1, $col_difference);
    $this->assertArrayHasKey('id', $col_difference);
    
  }



}
