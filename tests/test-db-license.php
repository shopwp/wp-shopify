<?php

use WPS\Factories\DB_Settings_License_Factory;

/*

Tests the webhooks for License

License license_key currently doesn't update -- only adds or deletes

*/
class Test_Sync_License extends WP_UnitTestCase {

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


}
