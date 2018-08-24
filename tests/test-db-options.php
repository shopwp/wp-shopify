<?php

use WPS\Factories\DB_Options_Factory;

/*

Tests the webhooks for Options

Mock data comes from the "mock-data" folder and is taken directly from
the example output Shopify uses within their documentation found here:

https://help.shopify.com/api/reference/webhook

*/
class Test_Sync_Options extends WP_UnitTestCase {

  protected static $DB_Options;
  protected static $mockDataOption;
  protected static $mockDataOptionForUpdate;
  protected static $mockDataOptionID;


  static function setUpBeforeClass() {

    // Assemble
    self::$DB_Options                 = DB_Options_Factory::build();
    self::$mockDataOption             = json_decode( file_get_contents( dirname(__FILE__) . "/mock-data/option.json") );
    self::$mockDataOptionForUpdate    = json_decode( file_get_contents( dirname(__FILE__) . "/mock-data/option-update.json") );
    self::$mockDataOptionID           = self::$mockDataOption->option_id;

  }


  /*

  Mock: Product Create

  */
  function test_option_create() {

    $result = self::$DB_Options->insert(self::$mockDataOption, 'option');
    $this->assertEquals(1, $result);

  }


  /*

  Mock: Product Update

  */
  function test_option_update() {

    $results = self::$DB_Options->update( self::$mockDataOptionID, self::$mockDataOptionForUpdate );
    $this->assertEquals(1, $results);

  }


  /*

  Mock: Product Delete

  */
  function test_option_delete() {

    $results = self::$DB_Options->delete( self::$mockDataOptionID );
    $this->assertEquals(1, $results);

  }


}
