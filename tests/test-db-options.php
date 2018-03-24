<?php

use WPS\DB\Options;

/*

Tests the webhooks for Options

Mock data comes from the "mock-data" folder and is taken directly from
the example output Shopify uses within their documentation found here:

https://help.shopify.com/api/reference/webhook

*/
class Test_Sync_Options extends WP_UnitTestCase {

  protected static $Options;
  protected static $mockDataOption;
  protected static $mockDataOptionForUpdate;
  protected static $mockDataOptionID;


  static function setUpBeforeClass() {

    // Assemble
    self::$Options                    = new Options();
    self::$mockDataOption             = json_decode( file_get_contents( dirname(__FILE__) . "/mock-data/option.json") );
    self::$mockDataOptionForUpdate    = json_decode( file_get_contents( dirname(__FILE__) . "/mock-data/option-update.json") );
    self::$mockDataOptionID           = self::$mockDataOption->id;

  }


  /*

  Mock: Product Create

  */
  function test_option_create() {

    $result = self::$Options->insert(self::$mockDataOption, 'option');

    $this->assertTrue($result);

  }


  /*

  Mock: Product Update

  */
  function test_option_update() {

    $results = self::$Options->update( self::$mockDataOptionID, self::$mockDataOptionForUpdate );
    $this->assertTrue($results);

  }


  /*

  Mock: Product Delete

  */
  function test_option_delete() {

    $results = self::$Options->delete( self::$mockDataOptionID );

    $this->assertTrue($results);

  }


}
