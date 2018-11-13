<?php

use WPS\Factories\DB_Factory;
use WPS\Factories\DB_Products_Factory;
use WPS\Factories\Activator_Factory;
use WPS\Factories\Async_Processing_Database_Factory;
use WPS\Factories\DB_Settings_General_Factory;


/*

Multisite tests

*/
class Test_Multisite_DB extends WP_UnitTestCase {

  protected static $DB;
  protected static $DB_Products;
  protected static $Activator;
  protected static $Async_Processing_Database;
  protected static $DB_Settings_General;


  /*

  Setup for entire class

  */
  static function wpSetUpBeforeClass() {

    self::$DB                           = DB_Factory::build();
    self::$DB_Products                  = DB_Products_Factory::build();
    self::$Activator                    = Activator_Factory::build();
    self::$Async_Processing_Database    = Async_Processing_Database_Factory::build();
    self::$DB_Settings_General          = DB_Settings_General_Factory::build();

    wpmu_create_blog('blog-2', '/', 'Blog 2', 1);

  }


  /*

  Tears down for entire class

  */
  static function wpTearDownAfterClass() {

    wpmu_delete_blog(2, true);

  }


  /*

  Tests whether Network is a real multisite

  */
  function test_it_should_be_multisite() {

    $this->assertTrue( is_multisite() );

  }


  /*

  Grabs the list of all available blogs

  */
  function test_it_should_get_network_sites() {

    $blog_ids = self::$DB->get_network_sites();

    $this->assertInternalType('array', $blog_ids);
    $this->assertNotEmpty($blog_ids);
    $this->assertCount(2, $blog_ids);

  }


  /*

  Grabs the list of all available blogs

  */
  function test_it_should_get_network_sites_table_names() {

    $blog_ids = self::$DB->get_network_sites();


    foreach ( $blog_ids as $site_blog_id ) {

      switch_to_blog( $site_blog_id );

      $table_name = self::$DB_Products->get_table_name();

      if ($site_blog_id === '1') {
        $this->assertEquals('wptests_wps_products', $table_name);
      }

      if ($site_blog_id === '2') {
        $this->assertEquals('wptests_2_wps_products', $table_name);
      }

      $this->assertInternalType('string', $table_name);

      restore_current_blog();

    }


  }


  /*

  Creates cusom tables for entire multisite

  */
	function test_it_should_bootstrap_tables_on_multisite() {

		// true simulates network_wide variable
		$results = self::$Activator->bootstrap_tables(true);

    $this->assertInternalType('array', $results);
    $this->assertCount(15, $results['create_db_tables']);
    $this->assertCount(2, $results['set_default_table_values']);


    foreach ($results['create_db_tables'] as $result) {
      $this->assertNotFalse($result);
      $this->assertNotWPError($result);
    }

    foreach ($results['set_default_table_values'] as $result) {
      $this->assertNotFalse($result);
      $this->assertNotWPError($result);
      $this->assertInternalType('int', $result);
      $this->assertEquals(1, $result);
    }


  }


  /*

  Creates cusom tables for entire multisite

  */
	function test_it_should_uninstall_plugin_on_multisite() {

    // Getting uninstall results
		$results = self::$Async_Processing_Database->uninstall_plugin_multisite();

    $this->assertInternalType('array', $results);


    foreach ($results as $result) {

      $this->assertNotFalse($result);
      $this->assertNotWPError($result);

      foreach ($result as $result_type => $result_type_value) {

        if ($result_type === 'delete_posts') {

          foreach ($result_type_value as $deleted_post) {
            $this->assertInternalType('boolean', $deleted_post);
            $this->assertEquals(1, $deleted_post);
          }

        }

        if ($result_type === 'drop_custom_tables') {

          $this->assertNotInternalType('string', $result_type_value);
          $this->assertNotWPError($result_type_value);
          $this->assertEmpty($result_type_value); // means no errors occured

        }


        if ($result_type === 'delete_all_cache') {

          foreach ($result_type_value as $deleted_cache) {
            $this->assertInternalType('boolean', $deleted_post);
            $this->assertEquals(1, $deleted_post);
          }

        }


      }

    }

  }

}
