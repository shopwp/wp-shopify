<?php

use WPS\Factories\DB_Images_Factory;

/*

Tests the webhooks for Images

Mock data comes from the "mock-data" folder and is taken directly from
the example output Shopify uses within their documentation found here:

https://help.shopify.com/api/reference/webhook

*/
class Test_Sync_Images extends WP_UnitTestCase {

  protected static $DB_Images;
  protected static $mockDataImage;
  protected static $mockDataImageForUpdate;
  protected static $mockDataImageID;


  static function setUpBeforeClass() {

    // Assemble
    self::$DB_Images                 = DB_Images_Factory::build();
    self::$mockDataImage             = json_decode( file_get_contents( dirname(__FILE__) . "/mock-data/image.json") );
    self::$mockDataImageForUpdate    = json_decode( file_get_contents( dirname(__FILE__) . "/mock-data/image-update.json") );
    self::$mockDataImageID           = self::$mockDataImage->id;

  }


  /*

  Mock: Product Create

  */
  function test_image_create() {

    $result = self::$DB_Images->insert(self::$mockDataImage, 'image');
    $this->assertEquals(1, $result);

  }


  /*

  Mock: Product Update

  */
  function test_image_update() {

    $results = self::$DB_Images->update( self::$mockDataImageID, self::$mockDataImageForUpdate );
    $this->assertEquals(1, $results);

  }


  /*

  Mock: Product Delete

  */
  function test_image_delete() {

    $results = self::$DB_Images->delete( self::$mockDataImageID );
    $this->assertEquals(1, $results);

  }


}
