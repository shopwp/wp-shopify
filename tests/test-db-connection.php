<?php

use WPS\Factories;

/*

Tests the webhooks for Connection

Connection key currently doesn't update -- only adds or deletes

*/
class Test_DB_Connection extends WP_UnitTestCase {

  protected static $DB_Settings_Connection;
  protected static $mock_connection;
  protected static $mock_connection_update;
  protected static $mock_connection_id;
  protected static $lookup_key;


  static function wpSetUpBeforeClass() {

    // Assemble
    self::$DB_Settings_Connection       = Factories\DB\Settings_Connection_Factory::build();
    self::$mock_connection              = json_decode( file_get_contents( dirname(__FILE__) . "/mock-data/connection.json") );
    self::$mock_connection_update       = json_decode( file_get_contents( dirname(__FILE__) . "/mock-data/connection-update.json") );
    self::$mock_connection_id           = self::$mock_connection->id;
    self::$lookup_key                   = self::$DB_Settings_Connection->lookup_key;

  }


  /*

  Mock: Connection Create

  */
  function test_connection_create() {

    // Clear it out first
    self::$DB_Settings_Connection->delete();

    $result = self::$DB_Settings_Connection->insert(self::$mock_connection);
    $this->assertEquals(1, $result);

  }


  /*

  Mock: Connection Update

  */
  function test_connection_update() {

    $results = self::$DB_Settings_Connection->update(self::$lookup_key, self::$mock_connection_id, self::$mock_connection_update);
    $this->assertEquals(1, $results);

  }


  /*

  Mock: Connection Delete

  */
  function test_connection_delete() {

    $results = self::$DB_Settings_Connection->delete();
    $this->assertEquals(1, $results);

  }


  /*

  It should return the complete table name with suffix as string

  */
  function test_it_should_get_table_name() {

    $table_name = self::$DB_Settings_Connection->get_table_name();

    $this->assertInternalType('string', $table_name );
    $this->assertEquals('wptests_wps_settings_connection', $table_name );

  }


  /*

  It should return only the table name suffix as string

  */
  function test_it_should_get_table_name_suffix() {

    $table_name_suffix = self::$DB_Settings_Connection->table_name_suffix;

    $this->assertInternalType('string', $table_name_suffix );
    $this->assertEquals('wps_settings_connection', $table_name_suffix );

  }


  /*

  It should update the current add to cart color

  */
  function test_it_should_have_default_values() {

    $this->assertObjectHasAttribute('default_domain', self::$DB_Settings_Connection);
    $this->assertObjectHasAttribute('default_js_access_token', self::$DB_Settings_Connection);
    $this->assertObjectHasAttribute('default_access_token', self::$DB_Settings_Connection);
    $this->assertObjectHasAttribute('default_app_id', self::$DB_Settings_Connection);
    $this->assertObjectHasAttribute('default_webhook_id', self::$DB_Settings_Connection);
    $this->assertObjectHasAttribute('default_nonce', self::$DB_Settings_Connection);
    $this->assertObjectHasAttribute('default_api_key', self::$DB_Settings_Connection);
    $this->assertObjectHasAttribute('default_password', self::$DB_Settings_Connection);
    $this->assertObjectHasAttribute('default_shared_secret', self::$DB_Settings_Connection);

  }


  /*

  It should have table info props

  */
  function test_it_should_have_table_info_props() {

    $this->assertObjectHasAttribute('table_name_suffix', self::$DB_Settings_Connection);
    $this->assertObjectHasAttribute('table_name', self::$DB_Settings_Connection);
    $this->assertObjectHasAttribute('version', self::$DB_Settings_Connection);
    $this->assertObjectHasAttribute('primary_key', self::$DB_Settings_Connection);
    $this->assertObjectHasAttribute('lookup_key', self::$DB_Settings_Connection);
    $this->assertObjectHasAttribute('cache_group', self::$DB_Settings_Connection);
    $this->assertObjectHasAttribute('type', self::$DB_Settings_Connection);

  }


  /*

  It should update the current add to cart color

  */
  function test_it_should_match_default_values_and_cols_amount() {

    $cols = self::$DB_Settings_Connection->get_columns();
    $default_cols = self::$DB_Settings_Connection->get_column_defaults();

    $col_difference = array_diff_key($cols, $default_cols);

    $this->assertCount(1, $col_difference);
    $this->assertArrayHasKey('id', $col_difference);

  }

}
