<?php

use WPS\Factories\DB_Settings_Connection_Factory;

/*

Tests the webhooks for Connection

Connection key currently doesn't update -- only adds or deletes

*/
class Test_Sync_Connection extends WP_UnitTestCase {

  protected static $DB_Settings_Connection;
  protected static $mockDataConnection;
  protected static $mockDataConnectionUpdate;
  protected static $mockDataConnectionID;


  static function setUpBeforeClass() {

    // Assemble
    self::$DB_Settings_Connection         = DB_Settings_Connection_Factory::build();
    self::$mockDataConnection             = json_decode( file_get_contents( dirname(__FILE__) . "/mock-data/connection.json") );
    self::$mockDataConnectionUpdate       = json_decode( file_get_contents( dirname(__FILE__) . "/mock-data/connection-update.json") );
    self::$mockDataConnectionID           = self::$mockDataConnection->id;

  }


  /*

  Mock: Connection Create

  */
  function test_connection_create() {

    $result = self::$DB_Settings_Connection->insert(self::$mockDataConnection, 'connection');
    $this->assertEquals(1, $result);

  }


  /*

  Mock: Connection Update

  */
  function test_connection_update() {

    $results = self::$DB_Settings_Connection->update( self::$mockDataConnectionID, self::$mockDataConnectionUpdate );
    $this->assertEquals(1, $results);

  }


  /*

  Mock: Connection Delete

  */
  function test_connection_delete() {

    $results = self::$DB_Settings_Connection->delete( self::$mockDataConnectionID );
    $this->assertEquals(1, $results);

  }


}
