<?php

use WPS\DB\Variants;

/*

Tests the webhooks for Variants

Mock data comes from the "mock-data" folder and is taken directly from
the example output Shopify uses within their documentation found here:

https://help.shopify.com/api/reference/webhook

*/
class Test_Sync_Variants extends WP_UnitTestCase {

  protected static $Variants;
  protected static $mockDataVariant;
  protected static $mockDataVariantForUpdate;
  protected static $mockDataVariantID;


  static function setUpBeforeClass() {

    // Assemble
    self::$Variants                    = new Variants();
    self::$mockDataVariant             = json_decode( file_get_contents( dirname(__FILE__) . "/mock-data/variant.json") );
    self::$mockDataVariantForUpdate    = json_decode( file_get_contents( dirname(__FILE__) . "/mock-data/variant-update.json") );
    self::$mockDataVariantID           = self::$mockDataVariant->id;

  }


  public function tearDown() {

  }


  /*

  Mock: Product Create

  */
  function test_variant_create() {

    $result = self::$Variants->insert(self::$mockDataVariant, 'variant');

    $this->assertTrue($result);

  }


  /*

  Mock: Product Update

  */
  function test_variant_update() {

    $results = self::$Variants->update( self::$mockDataVariantID, self::$mockDataVariantForUpdate );

    $this->assertTrue($results);

  }


  /*

  Mock: Product Delete

  */
  function test_variant_delete() {

    $results = self::$Variants->delete( self::$mockDataVariantID );

    $this->assertTrue($results);

  }


}
