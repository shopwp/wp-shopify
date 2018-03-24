<?php

use WPS\DB\Images;

/*

Tests the webhooks for Images

Mock data comes from the "mock-data" folder and is taken directly from
the example output Shopify uses within their documentation found here:

https://help.shopify.com/api/reference/webhook

*/
class Test_Sync_Images extends WP_UnitTestCase {

  protected static $Images;
  protected static $mockDataImage;
  protected static $mockDataImageForUpdate;
  protected static $mockDataImageID;


  static function setUpBeforeClass() {

    // Assemble
    self::$Images                    = new Images();
    self::$mockDataImage             = json_decode( file_get_contents( dirname(__FILE__) . "/mock-data/image.json") );
    self::$mockDataImageForUpdate    = json_decode( file_get_contents( dirname(__FILE__) . "/mock-data/image-update.json") );
    self::$mockDataImageID           = self::$mockDataImage->id;

  }


  /*

  Mock: Product Create

  */
  function test_image_create() {

    $result = self::$Images->insert(self::$mockDataImage, 'image');

    $this->assertTrue($result);

  }


  /*

  Mock: Product Update

  */
  function test_image_update() {

    $results = self::$Images->update( self::$mockDataImageID, self::$mockDataImageForUpdate );
    
    $this->assertTrue($results);

  }


  /*

  Mock: Product Delete

  */
  function test_image_delete() {

    $results = self::$Images->delete( self::$mockDataImageID );

    $this->assertTrue($results);

  }


}
