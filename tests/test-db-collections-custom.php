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
  protected static $mock_collections;
  protected static $mock_collections_for_update;
  protected static $mock_collections_id;
  protected static $lookup_key;

  static function setUpBeforeClass() {

    // Assemble
    self::$DB_Collections_Custom             = DB_Collections_Custom_Factory::build();
    self::$mock_collections                  = json_decode( file_get_contents( dirname(__FILE__) . "/mock-data/collection-custom.json") );
    self::$mock_collections_for_update       = json_decode( file_get_contents( dirname(__FILE__) . "/mock-data/collection-custom-update.json") );
    self::$mock_collections_id               = self::$mock_collections->id;
    self::$lookup_key                        = self::$DB_Collections_Custom->lookup_key;

  }


  /*

  Mock: Product Create

  */
  function test_custom_collection_create() {

    $results = self::$DB_Collections_Custom->insert(self::$mock_collections);
    $this->assertEquals(1, $results);

  }


  /*

  Mock: Product Update

  */
  function test_custom_collection_update() {

    $results = self::$DB_Collections_Custom->update( self::$lookup_key, self::$mock_collections_id, self::$mock_collections_for_update );
    $this->assertEquals(1, $results);

  }


  /*

  Mock: Product Delete

  */
  function test_custom_collection_delete() {

    $results = self::$DB_Collections_Custom->delete_rows( self::$lookup_key, self::$mock_collections_id );
    $this->assertEquals(1, $results);

  }


  /*

  Should rename payload key to lookup key

  */
  function test_it_should_maybe_rename_to_lookup_key() {

    $rename_result = self::$DB_Collections_Custom->maybe_rename_to_lookup_key(self::$mock_collections);

    $this->assertObjectHasAttribute(self::$DB_Collections_Custom->lookup_key, $rename_result);

  }

}
