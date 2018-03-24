<?php

use WPS\DB\Settings_License;

/*

Tests the webhooks for License

License key currently doesn't update -- only adds or deletes

*/
class Test_Sync_License extends WP_UnitTestCase {

  protected static $License;
  protected static $mockDataLicense;
  protected static $mockDataLicenseID;


  static function setUpBeforeClass() {

    // Assemble
    self::$License                     = new Settings_License();
    self::$mockDataLicense             = json_decode( file_get_contents( dirname(__FILE__) . "/mock-data/license.json") );
    self::$mockDataLicenseID           = self::$mockDataLicense->key;

  }


  /*

  Mock: Product Create

  */
  function test_license_create() {

    $result = self::$License->insert(self::$mockDataLicense, 'license');

    $this->assertTrue($result);

  }


  /*

  Mock: Product Delete

  */
  function test_license_delete() {

    $results = self::$License->delete('key');

    $this->assertTrue($results);

  }


}
