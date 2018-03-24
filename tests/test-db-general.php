<?php

use WPS\DB\Settings_General;

/*

Tests the webhooks for General

General key currently doesn't update -- only adds or deletes

*/
class Test_Sync_General extends WP_UnitTestCase {

  protected static $General;
  protected static $mockDataGeneralUpdate;
  protected static $mockDataGeneralID;


  static function setUpBeforeClass() {

    // Assemble
    self::$General                     = new Settings_General();
    self::$mockDataGeneralUpdate       = json_decode( file_get_contents( dirname(__FILE__) . "/mock-data/general-update.json") );
    self::$mockDataGeneralID           = self::$mockDataGeneralUpdate->id;

  }


  /*

  Mock: Product Update

  */
  function test_general_update() {

    $results = self::$General->update( self::$mockDataGeneralID, self::$mockDataGeneralUpdate );

    $this->assertTrue($results);

  }


}
