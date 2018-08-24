<?php

use WPS\Factories\Migrations_122_Factory;

/*

Tests Utils functions

*/
class Test_Migrations extends WP_UnitTestCase {

	protected static $Migrations_122;

  static function setUpBeforeClass() {

		self::$Migrations_122		= Migrations_122_Factory::build();

  }


	/*

	If this test fails, create_migration_db_tables will return an array of error messages

	*/
	function test_it_should_create_migration_db_tables() {
		$this->assertEmpty( self::$Migrations_122->create_migration_db_tables( WPS_TABLE_MIGRATION_SUFFIX ) );
  }


	/*

	If this test fails, run_insert_to_queries will return an array of error messages

	*/
	function test_it_should_run_insert_to_queries() {
		$this->assertEmpty( self::$Migrations_122->run_insert_to_queries() );
  }


	/*

	If this test fails, delete_old_tables will return an array of error messages

	*/
	function test_it_should_delete_old_tables() {
		$this->assertEmpty( self::$Migrations_122->delete_old_tables() );
  }


	/*

	If this test fails, rename_migration_tables will return an array of error messages
	TODO: This function doesnt currently test the query. Need to fix.

	*/
	function test_it_should_rename_migration_tables() {
		$this->assertEmpty( self::$Migrations_122->rename_migration_tables() );
  }


}
