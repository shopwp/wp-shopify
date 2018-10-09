<?php

use WPS\Factories\DB_Settings_License_Factory;

/*

Tests the webhooks for License

License license_key currently doesn't update -- only adds or deletes

*/
class Test_DB_License extends WP_UnitTestCase {

  protected static $DB_Settings_License;
  protected static $mock_license;
  protected static $mock_license_key;


  static function wpSetUpBeforeClass() {

    // Assemble
    self::$DB_Settings_License      = DB_Settings_License_Factory::build();
    self::$mock_license             = json_decode( file_get_contents( dirname(__FILE__) . "/mock-data/license.json") );
    self::$mock_license_key         = self::$mock_license->license_key;

  }


  /*

  Mock: Product Create

  */
  function test_license_create() {

    // Clear out first
    self::$DB_Settings_License->delete(self::$mock_license_key);

    $result = self::$DB_Settings_License->insert(self::$mock_license);

    $this->assertEquals(1, $result);

  }


  /*

  Mock: Product Delete

  */
  function test_license_delete() {

    self::$DB_Settings_License->insert(self::$mock_license);

    $results = self::$DB_Settings_License->delete(self::$mock_license_key);
    $this->assertEquals(1, $results);

  }


  /*

  It should return the complete table name with suffix as string

  */
  function test_it_should_get_table_name() {

    $table_name = self::$DB_Settings_License->get_table_name();

    $this->assertInternalType('string', $table_name );
    $this->assertEquals('wptests_wps_settings_license', $table_name );

  }


  /*

  It should return only the table name suffix as string

  */
  function test_it_should_get_table_name_suffix() {

    $table_name_suffix = self::$DB_Settings_License->table_name_suffix;

    $this->assertInternalType('string', $table_name_suffix );
    $this->assertEquals('wps_settings_license', $table_name_suffix );

  }


  /*

  It should update the current add to cart color

  */
  function test_it_should_have_default_values() {

    $this->assertObjectHasAttribute('default_license_key', self::$DB_Settings_License);
    $this->assertObjectHasAttribute('default_is_local', self::$DB_Settings_License);
    $this->assertObjectHasAttribute('default_expires', self::$DB_Settings_License);
    $this->assertObjectHasAttribute('default_site_count', self::$DB_Settings_License);
    $this->assertObjectHasAttribute('default_checksum', self::$DB_Settings_License);
    $this->assertObjectHasAttribute('default_customer_email', self::$DB_Settings_License);
    $this->assertObjectHasAttribute('default_customer_name', self::$DB_Settings_License);
    $this->assertObjectHasAttribute('default_item_name', self::$DB_Settings_License);
    $this->assertObjectHasAttribute('default_license', self::$DB_Settings_License);
    $this->assertObjectHasAttribute('default_license_limit', self::$DB_Settings_License);
    $this->assertObjectHasAttribute('default_payment_id', self::$DB_Settings_License);
    $this->assertObjectHasAttribute('default_success', self::$DB_Settings_License);
    $this->assertObjectHasAttribute('default_nonce', self::$DB_Settings_License);
    $this->assertObjectHasAttribute('default_activations_left', self::$DB_Settings_License);
    $this->assertObjectHasAttribute('default_is_free', self::$DB_Settings_License);
    $this->assertObjectHasAttribute('default_is_pro', self::$DB_Settings_License);
    $this->assertObjectHasAttribute('default_beta_access', self::$DB_Settings_License);

  }


  /*

  It should have table info props

  */
  function test_it_should_have_table_info_props() {

    $this->assertObjectHasAttribute('table_name_suffix', self::$DB_Settings_License);
    $this->assertObjectHasAttribute('table_name', self::$DB_Settings_License);
    $this->assertObjectHasAttribute('version', self::$DB_Settings_License);
    $this->assertObjectHasAttribute('primary_key', self::$DB_Settings_License);
    $this->assertObjectHasAttribute('lookup_key', self::$DB_Settings_License);
    $this->assertObjectHasAttribute('cache_group', self::$DB_Settings_License);
    $this->assertObjectHasAttribute('type', self::$DB_Settings_License);

  }


  /*

  It should update the current add to cart color

  */
  function test_it_should_match_default_values_and_cols_amount() {

    $cols_count = count( self::$DB_Settings_License->get_columns() );
    $default_cols_count = count( self::$DB_Settings_License->get_column_defaults() );

    $this->assertEquals($cols_count, $default_cols_count);

  }


}
