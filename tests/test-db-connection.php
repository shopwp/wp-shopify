<?php

use WPS\DB\Settings_Connection;

/*

Tests the webhooks for Connection

Connection key currently doesn't update -- only adds or deletes

*/
class Test_Sync_Connection extends WP_UnitTestCase {

  protected static $Connection;
  protected static $mockDataConnection;
  protected static $mockDataConnectionUpdate;
  protected static $mockDataConnectionID;


  static function setUpBeforeClass() {

    // Assemble
    self::$Connection                     = new Settings_Connection();
    self::$mockDataConnection             = json_decode( file_get_contents( dirname(__FILE__) . "/mock-data/connection.json") );
    self::$mockDataConnectionUpdate       = json_decode( file_get_contents( dirname(__FILE__) . "/mock-data/connection-update.json") );
    self::$mockDataConnectionID           = self::$mockDataConnection->id;

  }


  /*

  Mock: Connection Create

  */
  function test_connection_create() {

    $result = self::$Connection->insert(self::$mockDataConnection, 'connection');

    $this->assertTrue($result);

  }


  /*

  Mock: Connection Update

  */
  function test_connection_update() {

    $results = self::$Connection->update( self::$mockDataConnectionID, self::$mockDataConnectionUpdate );

    $this->assertTrue($results);

  }


  /*

  Mock: Connection Delete

  */
  function test_connection_delete() {

    $results = self::$Connection->delete( self::$mockDataConnectionID );

    $this->assertTrue($results);

  }


}
