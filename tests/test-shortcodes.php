<?php

/*

Tests shortcodes

*/
class Test_Plugin_Shortcodes extends WP_UnitTestCase {

  function test_it_should_have_wps_products_shortcode() {
    $this->assertTrue( shortcode_exists('wps_products') );
  }

  function test_it_should_have_wps_collections_shortcode() {
    $this->assertTrue( shortcode_exists('wps_collections') );
  }

  function test_it_should_have_wps_cart_shortcode() {
    $this->assertTrue( shortcode_exists('wps_cart') );
  }

}
