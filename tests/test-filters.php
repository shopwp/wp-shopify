<?php


/*

Tests Messages functions

*/
class Test_Plugin_Filters extends WP_UnitTestCase {


  function test_it_should_have_wps_collections_args_filter_registered() {
    $this->assertTrue( has_filter('wps_collections_args') );
  }

  function test_it_should_have_wps_collections_custom_args_filter_registered() {
    $this->assertTrue( has_filter('wps_collections_custom_args') );
  }

  function test_it_should_have_wps_collections_custom_args_items_per_row_filter_registered() {
    $this->assertTrue( has_filter('wps_collections_custom_args_items_per_row') );
  }

  function test_it_should_have_wps_collection_single_products_heading_class_filter_registered() {
    $this->assertTrue( has_filter('wps_collection_single_products_heading_class') );
  }

  function test_it_should_have_wps_products_pagination_range_filter_registered() {
    $this->assertTrue( has_filter('wps_products_pagination_range') );
  }

  function test_it_should_have_wps_products_pagination_next_link_text_filter_registered() {
    $this->assertTrue( has_filter('wps_products_pagination_next_link_text') );
  }

  function test_it_should_have_wps_products_pagination_prev_link_text_filter_registered() {
    $this->assertTrue( has_filter('wps_products_pagination_prev_link_text') );
  }

  function test_it_should_have_wps_products_pagination_first_page_text_filter_registered() {
    $this->assertTrue( has_filter('wps_products_pagination_first_page_text') );
  }

  function test_it_should_have_wps_products_pagination_show_as_prev_next_filter_registered() {
    $this->assertTrue( has_filter('wps_products_pagination_show_as_prev_next') );
  }

  function test_it_should_have_wps_products_pagination_prev_page_text_filter_registered() {
    $this->assertTrue( has_filter('wps_products_pagination_prev_page_text') );
  }

  function test_it_should_have_wps_products_pagination_next_page_text_filter_registered() {
    $this->assertTrue( has_filter('wps_products_pagination_next_page_text') );
  }

  function test_it_should_have_wps_products_args_filter_registered() {
    $this->assertTrue( has_filter('wps_products_args') );
  }

  function test_it_should_have_wps_products_args_posts_per_page_filter_registered() {
    $this->assertTrue( has_filter('wps_products_args_posts_per_page') );
  }

  function test_it_should_have_wps_products_args_orderby_filter_registered() {
    $this->assertTrue( has_filter('wps_products_args_orderby') );
  }

  function test_it_should_have_wps_products_args_paged_filter_registered() {
    $this->assertTrue( has_filter('wps_products_args_paged') );
  }

  function test_it_should_have_wps_products_custom_args_filter_registered() {
    $this->assertTrue( has_filter('wps_products_custom_args') );
  }

  function test_it_should_have_wps_products_custom_args_items_per_row_filter_registered() {
    $this->assertTrue( has_filter('wps_products_custom_args_items_per_row') );
  }

  function test_it_should_have_wps_products_price_multi_filter_registered() {
    $this->assertTrue( has_filter('wps_products_price_multi') );
  }

  function test_it_should_have_wps_products_price_one_filter_registered() {
    $this->assertTrue( has_filter('wps_products_price_one') );
  }

  function test_it_should_have_wps_products_related_args_posts_per_page_filter_registered() {
    $this->assertTrue( has_filter('wps_products_related_args_posts_per_page') );
  }

  function test_it_should_have_wps_products_related_show_filter_registered() {
    $this->assertTrue( has_filter('wps_products_related_show') );
  }

  function test_it_should_have_wps_products_related_filters_filter_registered() {
    $this->assertTrue( has_filter('wps_products_related_filters') );
  }

  function test_it_should_have_wps_products_related_args_orderby_filter_registered() {
    $this->assertTrue( has_filter('wps_products_related_args_orderby') );
  }

  function test_it_should_have_wps_products_related_args_filter_registered() {
    $this->assertTrue( has_filter('wps_products_related_args') );
  }

  function test_it_should_have_wps_products_related_custom_args_filter_registered() {
    $this->assertTrue( has_filter('wps_products_related_custom_args') );
  }

  function test_it_should_have_wps_products_related_custom_items_per_row_filter_registered() {
    $this->assertTrue( has_filter('wps_products_related_custom_items_per_row') );
  }

  function test_it_should_have_wps_product_single_thumbs_class_filter_registered() {
    $this->assertTrue( has_filter('wps_product_single_thumbs_class') );
  }

  function test_it_should_have_wps_product_single_price_filter_registered() {
    $this->assertTrue( has_filter('wps_product_single_price') );
  }

  function test_it_should_have_wps_product_single_price_multi_filter_registered() {
    $this->assertTrue( has_filter('wps_product_single_price_multi') );
  }

  function test_it_should_have_wps_product_single_price_one_filter_registered() {
    $this->assertTrue( has_filter('wps_product_single_price_one') );
  }

  function test_it_should_have_wps_products_link_filter_registered() {
    $this->assertTrue( has_filter('wps_products_link') );
  }

  function test_it_should_have_wps_products_pagination_start_filter_registered() {
    $this->assertTrue( has_filter('wps_products_pagination_start') );
  }

  function test_it_should_have_wps_products_pagination_end_filter_registered() {
    $this->assertTrue( has_filter('wps_products_pagination_end') );
  }


}
