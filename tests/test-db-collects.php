<?php

use WPS\DB\Collects;

/*

Tests the webhooks for Collects

Mock data comes from the "mock-data" folder and is taken directly from
the example output Shopify uses within their documentation found here:

https://help.shopify.com/api/reference/webhook

Collects are not updated -- only created or deleted

*/
class Test_Sync_Collects extends WP_UnitTestCase {

  protected static $Collects;
  protected static $mockDataCollect;
  protected static $mockDataCollectID;


  static function setUpBeforeClass() {

    // Assemble
    self::$Collects                       = new Collects();
    self::$mockDataCollect                = json_decode( file_get_contents( dirname(__FILE__) . "/mock-data/collect.json") );
    self::$mockDataCollectID              = self::$mockDataCollect->id;

  }


  /*

  Mock: Product Create

  */
  function test_collect_create() {

    $result = self::$Collects->insert(self::$mockDataCollect, 'collect');

    $this->assertTrue($result);

  }


  /*

  Mock: Product Delete

  */
  function test_collect_delete() {

    $results = self::$Collects->delete( self::$mockDataCollectID );

    $this->assertTrue($results);

  }


}
