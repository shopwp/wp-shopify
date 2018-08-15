<?php

use WPS\Factories\DB_Collections_Custom_Factory;

/*

Tests the webhooks for Collections_Custom

Mock data comes from the "mock-data" folder and is taken directly from
the example output Shopify uses within their documentation found here:

https://help.shopify.com/api/reference/webhook

*/
class Test_Sync_Collections_Custom extends WP_UnitTestCase {

  protected static $DB_Collections_Custom;
  protected static $mockDatacollectionsCustom;
  protected static $mockDatacollectionsCustomForUpdate;
  protected static $mockDatacollectionsCustomID;


  static function setUpBeforeClass() {

    // Assemble
    self::$DB_Collections_Custom                 = DB_Collections_Custom_Factory::build();
    self::$mockDatacollectionsCustom             = json_decode( file_get_contents( dirname(__FILE__) . "/mock-data/collection-custom.json") );
    self::$mockDatacollectionsCustomForUpdate    = json_decode( file_get_contents( dirname(__FILE__) . "/mock-data/collection-custom-update.json") );
    self::$mockDatacollectionsCustomID           = self::$mockDatacollectionsCustom->collection_id;

  }


  /*

  Mock: Product Create

  */
  function test_custom_collection_create() {

    $results = self::$DB_Collections_Custom->insert( self::$mockDatacollectionsCustom, 'custom_collection' );
    $this->assertEquals(1, $results);

  }


  /*

  Mock: Product Update

  */
  function test_custom_collection_update() {

    $results = self::$DB_Collections_Custom->update( self::$mockDatacollectionsCustomID, self::$mockDatacollectionsCustomForUpdate );
    $this->assertEquals(1, $results);

  }


  /*

  Mock: Product Delete

  */
  function test_custom_collection_delete() {

    $results = self::$DB_Collections_Custom->delete_rows( 'collection_id', self::$mockDatacollectionsCustomID );
    $this->assertEquals(1, $results);

  }


}
