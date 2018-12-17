<?php

use WPS\Factories;

class Test_API_ extends WP_UnitTestCase {

  protected static $API_Settings_Products;
  protected static $DB_Settings_General;


  static function wpSetUpBeforeClass() {

    // Assemble
    self::$API_Settings_Products    = Factories\API\Settings\Products_Factory::build();
    self::$DB_Settings_General      = Factories\DB\Settings_General_Factory::build();

  }


  function test_it_should_() {

  }


}
