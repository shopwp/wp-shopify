<?php

use WPS\DB\Collections_Smart;

/*

Tests the webhooks for Collections_Smart

Mock data comes from the "mock-data" folder and is taken directly from
the example output Shopify uses within their documentation found here:

https://help.shopify.com/api/reference/webhook

*/
class Test_Sync_Collections_Smart extends WP_UnitTestCase {

  protected static $CollectionsSmart;
  protected static $mockDatacollectionsSmart;
  protected static $mockDatacollectionsSmartForUpdate;
  protected static $mockDatacollectionsSmartID;


  static function setUpBeforeClass() {

    // Assemble
    self::$CollectionsSmart                     = new Collections_Smart();
    self::$mockDatacollectionsSmart             = json_decode( file_get_contents( dirname(__FILE__) . "/mock-data/collection-smart.json") );
    self::$mockDatacollectionsSmartForUpdate    = json_decode( file_get_contents( dirname(__FILE__) . "/mock-data/collection-smart-update.json") );
    self::$mockDatacollectionsSmartID           = self::$mockDatacollectionsSmart->collection_id;

  }


  /*

  Mock: Product Create

  */
  function test_smart_collection_create() {

    $results = self::$CollectionsSmart->insert( self::$mockDatacollectionsSmart, 'smart_collection' );

    $this->assertTrue($results);

  }


  /*

  Mock: Product Update

  */
  function test_smart_collection_update() {

    $results = self::$CollectionsSmart->update( self::$mockDatacollectionsSmartID, self::$mockDatacollectionsSmartForUpdate );

    $this->assertTrue($results);

  }


  /*

  Mock: Product Delete

  */
  function test_smart_collection_delete() {

    $results = self::$CollectionsSmart->delete_rows( 'collection_id', self::$mockDatacollectionsSmartID );

    $this->assertTrue($results);

  }


}
