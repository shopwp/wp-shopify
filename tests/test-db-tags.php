<?php

use WPS\Factories\DB_Tags_Factory;

/*

Tests the webhooks for Tags

Mock data comes from the "mock-data" folder and is taken directly from
the example output Shopify uses within their documentation found here:

https://help.shopify.com/api/reference/webhook

Tags are not updated -- only created or deleted

*/
class Test_Sync_Tags extends WP_UnitTestCase {

  protected static $DB_Tags;
  protected static $mockDataTag;
  protected static $mockDataTagID;


  static function setUpBeforeClass() {

    // Assemble
    self::$DB_Tags                 = DB_Tags_Factory::build();
    self::$mockDataTag             = json_decode( file_get_contents( dirname(__FILE__) . "/mock-data/tag.json") );
    self::$mockDataTagID           = self::$mockDataTag->tag_id;

  }


  /*

  Mock: Product Create

  */
  function test_tag_create() {

    $result = self::$DB_Tags->insert(self::$mockDataTag, 'tag');

    $this->assertEquals(1, $result);

  }


  /*

  Mock: Product Delete

  */
  function test_tag_delete() {

    $results = self::$DB_Tags->delete( self::$mockDataTagID );

    $this->assertEquals(1, $results);

  }


}
