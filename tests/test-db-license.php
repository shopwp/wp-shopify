<?php

use WPS\Factories\DB_Settings_License_Factory;

/*

Tests the webhooks for License

License license_key currently doesn't update -- only adds or deletes

*/
class Test_Sync_License extends WP_UnitTestCase {

  protected static $DB_Settings_License;
  protected static $mockDataLicense;
  protected static $mockDataLicenseID;


  static function setUpBeforeClass() {

    // Assemble
    self::$DB_Settings_License         = DB_Settings_License_Factory::build();
    self::$mockDataLicense             = json_decode( file_get_contents( dirname(__FILE__) . "/mock-data/license.json") );
    self::$mockDataLicenseID           = self::$mockDataLicense->license_key;

  }


  /*

  Mock: Product Create

  */
  function test_license_create() {

    $result = self::$DB_Settings_License->insert(self::$mockDataLicense, 'license');
    $this->assertEquals(1, $result);

  }


  /*

  Mock: Product Delete

  */
  function test_license_delete() {

    $results = self::$DB_Settings_License->delete('license_key');
    $this->assertEquals(1, $results);

  }


}
