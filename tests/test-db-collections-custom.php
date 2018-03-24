<?php

use WPS\DB\Collections_Custom;

/*

Tests the webhooks for Collections_Custom

Mock data comes from the "mock-data" folder and is taken directly from
the example output Shopify uses within their documentation found here:

https://help.shopify.com/api/reference/webhook

*/
class Test_Sync_Collections_Custom extends WP_UnitTestCase {

  protected static $CollectionsCustom;
  protected static $mockDatacollectionsCustom;
  protected static $mockDatacollectionsCustomForUpdate;
  protected static $mockDatacollectionsCustomID;


  static function setUpBeforeClass() {

    // Assemble
    self::$CollectionsCustom                     = new Collections_Custom();
    self::$mockDatacollectionsCustom             = json_decode( file_get_contents( dirname(__FILE__) . "/mock-data/collection-custom.json") );
    self::$mockDatacollectionsCustomForUpdate    = json_decode( file_get_contents( dirname(__FILE__) . "/mock-data/collection-custom-update.json") );
    self::$mockDatacollectionsCustomID           = self::$mockDatacollectionsCustom->collection_id;

  }


  /*

  Mock: Product Create

  */
  function test_custom_collection_create() {

    $results = self::$CollectionsCustom->insert( self::$mockDatacollectionsCustom, 'custom_collection' );

    $this->assertTrue($results);

  }


  /*

  Mock: Product Update

  */
  function test_custom_collection_update() {

    $results = self::$CollectionsCustom->update( self::$mockDatacollectionsCustomID, self::$mockDatacollectionsCustomForUpdate );

    $this->assertTrue($results);

  }


  /*

  Mock: Product Delete

  */
  function test_custom_collection_delete() {

    $results = self::$CollectionsCustom->delete_rows( 'collection_id', self::$mockDatacollectionsCustomID );

    $this->assertTrue($results);

  }


}
