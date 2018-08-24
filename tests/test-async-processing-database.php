<?php

use WPS\Factories\Async_Processing_Database_Factory;
use WPS\Factories\Migrations_122_Factory;
use WPS\Factories\CPT_Model_Factory;


/*

Tests the webhooks for License

License license_key currently doesn't update -- only adds or deletes

*/
class Test_Async_Processing_Database extends WP_UnitTestCase {

  protected static $Async_Processing_Database;
  protected static $Migrations_122;
  protected static $mock_products;
  protected static $CPT_Model;


  static function setUpBeforeClass() {

    // Assemble
    self::$Async_Processing_Database      = Async_Processing_Database_Factory::build();
    self::$Migrations_122                 = Migrations_122_Factory::build();
    self::$CPT_Model                      = CPT_Model_Factory::build();

    self::$mock_products                  = json_decode( file_get_contents( dirname(__FILE__) . "/mock-data/products-insert.json") );
    self::$Migrations_122->create_migration_db_tables(WPS_TABLE_MIGRATION_SUFFIX_TESTS);

  }

  static function wpTearDownAfterClass() {
    self::$Async_Processing_Database->drop_custom_migration_tables(WPS_TABLE_MIGRATION_SUFFIX_TESTS);
  }


  /*

  test_is_should_drop_all_databases

  */
  function test_is_should_drop_all_databases() {

    $this->assertEmpty( self::$Async_Processing_Database->drop_custom_tables() );
    $this->assertEmpty( self::$Async_Processing_Database->drop_custom_migration_tables(WPS_TABLE_MIGRATION_SUFFIX_TESTS) );

  }


  /*

  test_is_should_drop_all_databases

  */
  function test_is_should_delete_custom_posts() {

    foreach (self::$mock_products as $product) {
      $this->factory->post->create( self::$CPT_Model->set_product_model_defaults($product) );
    }

    $deleted_posts_types = self::$Async_Processing_Database->delete_posts();

    foreach ($deleted_posts_types as $deleted_type) {
      $this->assertNotWPError($deleted_type);
    }

  }


}
