<?php

use WPS\Factories\DB_Settings_Connection_Factory;

/*

Tests the webhooks for Connection

Connection key currently doesn't update -- only adds or deletes

*/
class Test_Sync_Connection extends WP_UnitTestCase {

  protected static $DB_Settings_Connection;
  protected static $mock_connection;
  protected static $mock_connection_update;
  protected static $mock_connection_id;
  protected static $lookup_key;


  static function setUpBeforeClass() {

    // Assemble
    self::$DB_Settings_Connection       = DB_Settings_Connection_Factory::build();
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


}
