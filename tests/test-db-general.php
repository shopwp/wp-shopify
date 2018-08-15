<?php

use WPS\Factories\DB_Settings_General_Factory;

/*

Tests the webhooks for General

General key currently doesn't update -- only adds or deletes

*/
class Test_Sync_General extends WP_UnitTestCase {

  protected static $DB_Settings_General;
  protected static $mockDataGeneralUpdate;
  protected static $mockDataGeneralID;


  static function setUpBeforeClass() {

    // Assemble
    self::$DB_Settings_General         = DB_Settings_General_Factory::build();
    self::$mockDataGeneralUpdate       = json_decode( file_get_contents( dirname(__FILE__) . "/mock-data/general-update.json") );
    self::$mockDataGeneralID           = self::$mockDataGeneralUpdate->id;

  }


  /*

  Mock: Product Update

  */
  function test_general_update() {

    $results = self::$DB_Settings_General->update( self::$mockDataGeneralID, self::$mockDataGeneralUpdate );
    $this->assertEquals(1, $results);

  }


}
