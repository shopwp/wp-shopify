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

  static function setUpBeforeClass() {

		self::$DB_Settings_General		= DB_Settings_General_Factory::build();
		self::$DB_Settings_Syncing		= DB_Settings_Syncing_Factory::build();
    self::$Activator 							= Activator_Factory::build();

  }


	function test_it_should_set_default_table_values() {

		$results = [];

		$results[] = self::$DB_Settings_General->insert_default_values();
		$results[] = self::$DB_Settings_Syncing->insert_default_values();

		foreach ($results as $value) {
			$this->assertEquals(1, $value);
		}

  }


}
