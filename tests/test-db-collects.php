<?php

use WPS\Factories\DB_Collects_Factory;

/*

Tests the webhooks for Collects

Mock data comes from the "mock-data" folder and is taken directly from
the example output Shopify uses within their documentation found here:

https://help.shopify.com/api/reference/webhook

Collects are not updated -- only created or deleted

*/
class Test_Sync_Collects extends WP_UnitTestCase {

  protected static $DB_Collects;
  protected static $mock_data_collect;
  protected static $mock_data_collect_id;


  static function setUpBeforeClass() {

    // Assemble
    self::$DB_Collects                    = DB_Collects_Factory::build();
    self::$mock_data_collect              = json_decode( file_get_contents( dirname(__FILE__) . "/mock-data/collect.json") );
    self::$mock_data_collect_id           = self::$mock_data_collect->collect_id;

  }


  /*

  Mock: Product Create

  */
  function test_collect_create() {

    $result = self::$DB_Collects->insert(self::$mock_data_collect, 'collect');
    $this->assertEquals(1, $result);

  }


  /*

  Mock: Product Delete

  */
  function test_collect_delete() {

    $results = self::$DB_Collects->delete( self::$mock_data_collect_id );
    $this->assertEquals(1, $results);

  }


}
