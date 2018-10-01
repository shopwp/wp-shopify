<?php

use WPS\Factories\DB_Settings_General_Factory;
use WPS\Factories\DB_Settings_Syncing_Factory;
use WPS\Factories\Activator_Factory;
use WPS\Utils;

/*

Tests Utils functions

*/
class Test_Activator extends WP_UnitTestCase {

	protected static $DB_Settings_General;
	protected static $DB_Settings_Syncing;
	protected static $Activator;


	/*

	Setup ...

	*/
  static function wpSetUpBeforeClass() {

		self::$DB_Settings_General		= DB_Settings_General_Factory::build();
		self::$DB_Settings_Syncing		= DB_Settings_Syncing_Factory::build();
    self::$Activator 							= Activator_Factory::build();

  }


	function test_it_should_set_default_settings_general_table_values() {

		// Need to delete before inserting since data already exists in these tables
		self::$DB_Settings_General->delete();

		$settings_general_result = self::$DB_Settings_General->insert_default_values();

		$this->assertEquals(1, $settings_general_result);

  }


	function test_it_should_set_default_settings_syncing_table_values() {

		// Need to delete before inserting since data already exists in these tables
		self::$DB_Settings_Syncing->delete();

		$settings_syncing_result = self::$DB_Settings_Syncing->insert_default_values();

		$this->assertEquals(1, $settings_syncing_result);

  }


}
