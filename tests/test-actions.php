<?php


/*

Tests Messages functions

*/
class Test_Plugin_Actions extends WP_UnitTestCase {

  function test_it_should_have_wps_collections_args_() {
    $this->assertTrue( has_action('wps_collections_args') );
  }

  function test_it_should_have_wps_collections_args_plugins_loaded_action_registered() {
    $this->assertTrue( has_action('plugins_loaded') );
  }

  function test_it_should_have_wps_collections_args_wps_products_sidebar_action_registered() {
    $this->assertTrue( has_action('wps_products_sidebar') );
  }

  function test_it_should_have_wps_collections_args_wps_product_single_sidebar_action_registered() {
    $this->assertTrue( has_action('wps_product_single_sidebar') );
  }

  function test_it_should_have_wps_collections_args_wps_collections_sidebar_action_registered() {
    $this->assertTrue( has_action('wps_collections_sidebar') );
  }

  function test_it_should_have_wps_collections_args_wps_collection_single_sidebar_action_registered() {
    $this->assertTrue( has_action('wps_collection_single_sidebar') );
  }

  function test_it_should_have_wps_collections_args_wps_collections_pagination_action_registered() {
    $this->assertTrue( has_action('wps_collections_pagination') );
  }

  function test_it_should_have_wps_collections_args_wps_products_pagination_action_registered() {
    $this->assertTrue( has_action('wps_products_pagination') );
  }

  function test_it_should_have_wps_products_header_action_registered() {
    $this->assertTrue( has_action('wps_products_header') );
  }

  function test_it_should_have_wps_products_loop_start_action_registered() {
    $this->assertTrue( has_action('wps_products_loop_start') );
  }

  function test_it_should_have_wps_products_loop_end_action_registered() {
    $this->assertTrue( has_action('wps_products_loop_end') );
  }

  function test_it_should_have_wps_products_item_start_action_registered() {
    $this->assertTrue( has_action('wps_products_item_start') );
  }

  function test_it_should_have_wps_products_item_end_action_registered() {
    $this->assertTrue( has_action('wps_products_item_end') );
  }

  function test_it_should_have_wps_products_item_action_registered() {
    $this->assertTrue( has_action('wps_products_item') );
  }

  function test_it_should_have_wps_products_item_link_start_action_registered() {
    $this->assertTrue( has_action('wps_products_item_link_start') );
  }

  function test_it_should_have_wps_products_item_link_end_action_registered() {
    $this->assertTrue( has_action('wps_products_item_link_end') );
  }

  function test_it_should_have_wps_products_img_action_registered() {
    $this->assertTrue( has_action('wps_products_img') );
  }

  function test_it_should_have_wps_products_title_action_registered() {
    $this->assertTrue( has_action('wps_products_title') );
  }

  function test_it_should_have_wps_products_price_action_registered() {
    $this->assertTrue( has_action('wps_products_price') );
  }

  function test_it_should_have_wps_products_no_results_action_registered() {
    $this->assertTrue( has_action('wps_products_no_results') );
  }

  function test_it_should_have_wps_products_add_to_cart_action_registered() {
    $this->assertTrue( has_action('wps_products_add_to_cart') );
  }

  function test_it_should_have_wps_products_meta_start_action_registered() {
    $this->assertTrue( has_action('wps_products_meta_start') );
  }

  function test_it_should_have_wps_products_quantity_action_registered() {
    $this->assertTrue( has_action('wps_products_quantity') );
  }

  function test_it_should_have_wps_products_options_action_registered() {
    $this->assertTrue( has_action('wps_products_options') );
  }

  function test_it_should_have_wps_products_button_add_to_cart_action_registered() {
    $this->assertTrue( has_action('wps_products_button_add_to_cart') );
  }

  function test_it_should_have_wps_products_actions_group_start_action_registered() {
    $this->assertTrue( has_action('wps_products_actions_group_start') );
  }

  function test_it_should_have_wps_products_actions_group_end_action_registered() {
    $this->assertTrue( has_action('wps_products_actions_group_end') );
  }

  function test_it_should_have_wps_products_notice_inline_action_registered() {
    $this->assertTrue( has_action('wps_products_notice_inline') );
  }

  function test_it_should_have_wps_products_meta_end_action_registered() {
    $this->assertTrue( has_action('wps_products_meta_end') );
  }

  function test_it_should_have_wps_products_related_start_action_registered() {
    $this->assertTrue( has_action('wps_products_related_start') );
  }

  function test_it_should_have_wps_products_related_end_action_registered() {
    $this->assertTrue( has_action('wps_products_related_end') );
  }

  function test_it_should_have_wps_products_related_heading_action_registered() {
    $this->assertTrue( has_action('wps_products_related_heading') );
  }

  function test_it_should_have_wps_products_notice_out_of_stock_action_registered() {
    $this->assertTrue( has_action('wps_products_notice_out_of_stock') );
  }

  function test_it_should_have_wps_product_single_after_action_registered() {
    $this->assertTrue( has_action('wps_product_single_after') );
  }

  function test_it_should_have_wps_product_single_actions_group_start_action_registered() {
    $this->assertTrue( has_action('wps_product_single_actions_group_start') );
  }

  function test_it_should_have_wps_product_single_content_action_registered() {
    $this->assertTrue( has_action('wps_product_single_content') );
  }

  function test_it_should_have_wps_product_single_header_action_registered() {
    $this->assertTrue( has_action('wps_product_single_header') );
  }

  function test_it_should_have_wps_product_single_heading_action_registered() {
    $this->assertTrue( has_action('wps_product_single_heading') );
  }

  function test_it_should_have_wps_product_single_img_action_registered() {
    $this->assertTrue( has_action('wps_product_single_img') );
  }

  function test_it_should_have_wps_product_single_imgs_action_registered() {
    $this->assertTrue( has_action('wps_product_single_imgs') );
  }

  function test_it_should_have_wps_product_single_imgs_feat_placeholder_action_registered() {
    $this->assertTrue( has_action('wps_product_single_imgs_feat_placeholder') );
  }

  function test_it_should_have_wps_product_single_imgs_feat_action_registered() {
    $this->assertTrue( has_action('wps_product_single_imgs_feat') );
  }

  function test_it_should_have_wps_product_single_info_start_action_registered() {
    $this->assertTrue( has_action('wps_product_single_info_start') );
  }

  function test_it_should_have_wps_product_single_info_end_action_registered() {
    $this->assertTrue( has_action('wps_product_single_info_end') );
  }

  function test_it_should_have_wps_product_single_gallery_start_action_registered() {
    $this->assertTrue( has_action('wps_product_single_gallery_start') );
  }

  function test_it_should_have_wps_product_single_gallery_end_action_registered() {
    $this->assertTrue( has_action('wps_product_single_gallery_end') );
  }

  function test_it_should_have_wps_product_single_start_action_registered() {
    $this->assertTrue( has_action('wps_product_single_start') );
  }

  function test_it_should_have_wps_product_single_end_action_registered() {
    $this->assertTrue( has_action('wps_product_single_end') );
  }

  function test_it_should_have_wps_product_single_thumbs_start_action_registered() {
    $this->assertTrue( has_action('wps_product_single_thumbs_start') );
  }

  function test_it_should_have_wps_product_single_thumbs_end_action_registered() {
    $this->assertTrue( has_action('wps_product_single_thumbs_end') );
  }

  function test_it_should_have_wps_collections_header_action_registered() {
    $this->assertTrue( has_action('wps_collections_header') );
  }

  function test_it_should_have_wps_collections_loop_start_action_registered() {
    $this->assertTrue( has_action('wps_collections_loop_start') );
  }

  function test_it_should_have_wps_collections_loop_end_action_registered() {
    $this->assertTrue( has_action('wps_collections_loop_end') );
  }

  function test_it_should_have_wps_collections_item_start_action_registered() {
    $this->assertTrue( has_action('wps_collections_item_start') );
  }

  function test_it_should_have_wps_collections_item_end_action_registered() {
    $this->assertTrue( has_action('wps_collections_item_end') );
  }

  function test_it_should_have_wps_collections_item_action_registered() {
    $this->assertTrue( has_action('wps_collections_item') );
  }

  function test_it_should_have_wps_collections_item_before_action_registered() {
    $this->assertTrue( has_action('wps_collections_item_before') );
  }

  function test_it_should_have_wps_collections_item_after_action_registered() {
    $this->assertTrue( has_action('wps_collections_item_after') );
  }

  function test_it_should_have_wps_collections_img_action_registered() {
    $this->assertTrue( has_action('wps_collections_img') );
  }

  function test_it_should_have_wps_collections_title_action_registered() {
    $this->assertTrue( has_action('wps_collections_title') );
  }

  function test_it_should_have_wps_collections_no_results_action_registered() {
    $this->assertTrue( has_action('wps_collections_no_results') );
  }

  function test_it_should_have_wps_collection_single_start_action_registered() {
    $this->assertTrue( has_action('wps_collection_single_start') );
  }

  function test_it_should_have_wps_collection_single_header_action_registered() {
    $this->assertTrue( has_action('wps_collection_single_header') );
  }

  function test_it_should_have_wps_collection_single_img_action_registered() {
    $this->assertTrue( has_action('wps_collection_single_img') );
  }

  function test_it_should_have_wps_collection_single_content_action_registered() {
    $this->assertTrue( has_action('wps_collection_single_content') );
  }

  function test_it_should_have_wps_collection_single_products_action_registered() {
    $this->assertTrue( has_action('wps_collection_single_products') );
  }

  function test_it_should_have_wps_collection_single_products_list_action_registered() {
    $this->assertTrue( has_action('wps_collection_single_products_list') );
  }

  function test_it_should_have_wps_collection_single_products_heading_action_registered() {
    $this->assertTrue( has_action('wps_collection_single_products_heading') );
  }

  function test_it_should_have_wps_collection_single_end_action_registered() {
    $this->assertTrue( has_action('wps_collection_single_end') );
  }

  function test_it_should_have_wps_collection_single_product_action_registered() {
    $this->assertTrue( has_action('wps_collection_single_product') );
  }

  function test_it_should_have_wps_collection_single_heading_action_registered() {
    $this->assertTrue( has_action('wps_collection_single_heading') );
  }

  function test_it_should_have_wps_breadcrumbs_action_registered() {
    $this->assertTrue( has_action('wps_breadcrumbs') );
  }

  function test_it_should_have_wps_cart_icon_action_registered() {
    $this->assertTrue( has_action('wps_cart_icon') );
  }

  function test_it_should_have_wps_cart_counter_action_registered() {
    $this->assertTrue( has_action('wps_cart_counter') );
  }

  function test_it_should_have_wps_cart_checkout_btn_action_registered() {
    $this->assertTrue( has_action('wps_cart_checkout_btn') );
  }

  function test_it_should_have_wps_cart_terms_action_registered() {
    $this->assertTrue( has_action('wps_cart_terms') );
  }

  function test_it_should_have_wps_on_plugin_activate_action_registered() {
    $this->assertTrue( has_action('wps_on_plugin_activate') );
  }

  function test_it_should_have_wps_on_plugin_deactivate_action_registered() {
    $this->assertTrue( has_action('wps_on_plugin_deactivate') );
  }

}
