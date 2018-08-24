<?php

use WPS\Factories\DB_Variants_Factory;

/*

Tests the webhooks for Variants

Mock data comes from the "mock-data" folder and is taken directly from
the example output Shopify uses within their documentation found here:

https://help.shopify.com/api/reference/webhook

*/
class Test_Sync_Variants extends WP_UnitTestCase {

  protected static $DB_Variants;
  protected static $mockDataVariant;
  protected static $mockDataVariantForUpdate;
  protected static $mockDataVariantID;


  static function setUpBeforeClass() {

    // Assemble
    self::$DB_Variants                 = DB_Variants_Factory::build();
    self::$mockDataVariant             = json_decode( file_get_contents( dirname(__FILE__) . "/mock-data/variant.json") );
    self::$mockDataVariantForUpdate    = json_decode( file_get_contents( dirname(__FILE__) . "/mock-data/variant-update.json") );
    self::$mockDataVariantID           = self::$mockDataVariant->variant_id;

  }


  public function tearDown() {

  }


  /*

  Mock: Product Create

  */
  function test_variant_create() {

    $result = self::$DB_Variants->insert(self::$mockDataVariant, 'variant');
    $this->assertEquals(1, $result);

  }


  /*

  Mock: Product Update

  */
  function test_variant_update() {

    $results = self::$DB_Variants->update( self::$mockDataVariantID, self::$mockDataVariantForUpdate );
    $this->assertEquals(1, $results);

  }


  /*

  Mock: Product Delete

  */
  function test_variant_delete() {

    $results = self::$DB_Variants->delete( self::$mockDataVariantID );
    $this->assertEquals(1, $results);

  }


}
