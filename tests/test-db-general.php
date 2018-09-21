<?php

use WPS\Factories\DB_Settings_General_Factory;

/*

Tests the webhooks for General

General key currently doesn't update -- only adds or deletes

*/
class Test_Sync_General extends WP_UnitTestCase {

  protected static $DB_Settings_General;
  protected static $mock_general_update;
  protected static $mock_general_id;
  protected static $lookup_key;


  static function setUpBeforeClass() {

    // Assemble
    self::$DB_Settings_General       = DB_Settings_General_Factory::build();
    self::$mock_general_update       = json_decode( file_get_contents( dirname(__FILE__) . "/mock-data/general-update.json") );
    self::$mock_general_id           = self::$mock_general_update->id;
    self::$lookup_key                = self::$DB_Settings_General->lookup_key;

  }


  /*

  Mock: Product Update

  */
  function test_general_update() {

    $results = self::$DB_Settings_General->update(self::$lookup_key, self::$mock_general_id, self::$mock_general_update);
    $this->assertEquals(1, $results);

  }


  /*

  Test it should get enable beta setting

  */
  function test_it_should_get_enable_beta() {

    $result = self::$DB_Settings_General->get_enable_beta();

    $this->assertInternalType('boolean', $result);
    $this->assertEquals(false, $result);

  }


  /*

  Test it should get enable beta setting

  */
  function test_it_should_update_enable_beta() {

    $result = self::$DB_Settings_General->update_general(['enable_beta' => 1]);
    $after_update = self::$DB_Settings_General->get_enable_beta();

    $this->assertInternalType('boolean', $after_update);
    $this->assertEquals(true, $after_update);

  }

}
