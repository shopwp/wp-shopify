<?php

use WPS\Factories\Config_Factory;

/*

Tests the webhooks for General

General key currently doesn't update -- only adds or deletes

*/
class Test_Config extends WP_UnitTestCase {


  static function wpSetUpBeforeClass() {

    // Assemble
    // self::$Config                      = Config_Factory::build();

  }

  function test_is_defined_wps_shopify_header_verify_webhooks() {
    $this->assertTrue( defined('WPS_SHOPIFY_HEADER_VERIFY_WEBHOOKS') );
  }

  function test_is_defined_wps_shopify_header_api_call_limit() {
    $this->assertTrue( defined('WPS_SHOPIFY_HEADER_API_CALL_LIMIT') );
  }

  function test_shopify_header_verify_webhooks() {
    $this->assertTrue( defined('WPS_SHOPIFY_HEADER_VERIFY_WEBHOOKS') );
  }

  function test_shopify_header_verify_domain() {
    $this->assertTrue( defined('WPS_SHOPIFY_HEADER_VERIFY_DOMAIN') );
  }

  function test_settings_connection_option_name() {
    $this->assertTrue( defined('WPS_SETTINGS_CONNECTION_OPTION_NAME') );
  }

  function test_settings_general_option_name() {
    $this->assertTrue( defined('WPS_SETTINGS_GENERAL_OPTION_NAME') );
  }

  function test_settings_license_option_name() {
    $this->assertTrue( defined('WPS_SETTINGS_LICENSE_OPTION_NAME') );
  }

  function test_plugin_name() {
    $this->assertTrue( defined('WPS_PLUGIN_NAME') );
  }

  function test_plugin_name_full() {
    $this->assertTrue( defined('WPS_PLUGIN_NAME_FULL') );
  }

  function test_plugin_name_full_encoded() {
    $this->assertTrue( defined('WPS_PLUGIN_NAME_ENCODED') );
  }

  function test_plugin_name_js() {
    $this->assertTrue( defined('WPS_PLUGIN_NAME_JS') );
  }

  function test_plugin_text_domain() {
    $this->assertTrue( defined('WPS_PLUGIN_TEXT_DOMAIN') );
  }

  function test_plugin_version() {
    $this->assertTrue( defined('WPS_NEW_PLUGIN_VERSION') );
  }

  function test_plugin_author() {
    $this->assertTrue( defined('WPS_NEW_PLUGIN_AUTHOR') );
  }

  function test_plugin_root_file() {
    $this->assertTrue( defined('WPS_PLUGIN_ROOT_PATH') );
  }

  function test_plugin_free_root_file() {
    $this->assertTrue( defined('WPS_FREE_FILE_ROOT') );
  }

  function test_plugin_pro_root_file() {
    $this->assertTrue( defined('WPS_PRO_FILE_ROOT') );
  }

  function test_plugin_dir_path() {
    $this->assertTrue( defined('WPS_PLUGIN_DIR_PATH') );
  }

  function test_plugin_url() {
    $this->assertTrue( defined('WPS_PLUGIN_URL') );
  }

  function test_cart_cache_expiration() {
    $this->assertTrue( defined('WPS_CART_CACHE_EXPIRATION') );
  }

  function test_plugin_env() {
    $this->assertTrue( defined('WPS_PLUGIN_ENV') );
  }

  function test_plugin_file() {
    $this->assertTrue( defined('WPS_PLUGIN_ROOT_FILE') );
  }

  function test_plugin_basename() {
    $this->assertTrue( defined('WPS_PLUGIN_BASENAME') );
  }

  function test_relative_template_dir() {
    $this->assertTrue( defined('WPS_RELATIVE_TEMPLATE_DIR') );
  }

  function test_checkout_base_url() {
    $this->assertTrue( defined('WPS_CHECKOUT_BASE_URL') );
  }

  function test_shopify_rate_limit() {
    $this->assertTrue( defined('WPS_SHOPIFY_RATE_LIMIT') );
  }

  function test_languages_folder() {
    $this->assertTrue( defined('WPS_LANGUAGES_FOLDER') );
  }

  function test_products_post_type_slug() {
    $this->assertTrue( defined('WPS_PRODUCTS_POST_TYPE_SLUG') );
  }

  function test_collections_post_type_slug() {
    $this->assertTrue( defined('WPS_COLLECTIONS_POST_TYPE_SLUG') );
  }

  function test_plugin_table_name_images() {
    $this->assertTrue( defined('WPS_TABLE_NAME_IMAGES') );
  }

  function test_plugin_table_name_variants() {
    $this->assertTrue( defined('WPS_TABLE_NAME_VARIANTS') );
  }

  function test_plugin_table_name_tags() {
    $this->assertTrue( defined('WPS_TABLE_NAME_TAGS') );
  }

  function test_plugin_table_name_shop() {
    $this->assertTrue( defined('WPS_TABLE_NAME_SHOP') );
  }

  function test_plugin_table_name_settings_license() {
    $this->assertTrue( defined('WPS_TABLE_NAME_SETTINGS_LICENSE') );
  }

  function test_plugin_table_name_settings_general() {
    $this->assertTrue( defined('WPS_TABLE_NAME_SETTINGS_GENERAL') );
  }

  function test_plugin_table_name_settings_connection() {
    $this->assertTrue( defined('WPS_TABLE_NAME_SETTINGS_CONNECTION') );
  }

  function test_plugin_table_name_settings_syncing() {
    $this->assertTrue( defined('WPS_TABLE_NAME_SETTINGS_SYNCING') );
  }

  function test_plugin_table_name_products() {
    $this->assertTrue( defined('WPS_TABLE_NAME_PRODUCTS') );
  }

  function test_plugin_table_name_orders() {
    $this->assertTrue( defined('WPS_TABLE_NAME_ORDERS') );
  }

  function test_plugin_table_name_options() {
    $this->assertTrue( defined('WPS_TABLE_NAME_OPTIONS') );
  }

  function test_plugin_table_name_customers() {
    $this->assertTrue( defined('WPS_TABLE_NAME_CUSTOMERS') );
  }

  function test_plugin_table_name_collects() {
    $this->assertTrue( defined('WPS_TABLE_NAME_COLLECTS') );
  }

  function test_plugin_table_name_collections_smart() {
    $this->assertTrue( defined('WPS_TABLE_NAME_COLLECTIONS_SMART') );
  }

  function test_plugin_table_name_collections_custom() {
    $this->assertTrue( defined('WPS_TABLE_NAME_COLLECTIONS_CUSTOM') );
  }

  function test_plugin_table_name_wp_posts() {
    $this->assertTrue( defined('WPS_TABLE_NAME_WP_POSTS') );
  }

  function test_plugin_table_name_wp_postmeta() {
    $this->assertTrue( defined('WPS_TABLE_NAME_WP_POSTMETA') );
  }

  function test_plugin_table_name_wp_term_relationships() {
    $this->assertTrue( defined('WPS_TABLE_NAME_WP_TERM_RELATIONSHIPS') );
  }

  function test_plugin_table_name_wp_options() {
    $this->assertTrue( defined('WPS_TABLE_NAME_WP_OPTIONS') );
  }

  function test_plugin_default_currency() {
    $this->assertTrue( defined('WPS_DEFAULT_CURRENCY') );
  }

  function test_plugin_default_currency_symbol() {
    $this->assertTrue( defined('WPS_DEFAULT_CURRENCY_SYMBOL') );
  }

  function test_plugin_nonce_action_backend() {
    $this->assertTrue( defined('WPS_BACKEND_NONCE_ACTION') );
  }

  function test_plugin_nonce_action_frontend() {
    $this->assertTrue( defined('WPS_FRONTEND_NONCE_ACTION') );
  }

  function test_fallback_image_alt_text() {
    $this->assertTrue( defined('WPS_FALLBACK_IMAGE_ALT_TEXT') );
  }

  function test_total_webhooks_count() {
    $this->assertTrue( defined('WPS_TOTAL_WEBHOOKS_COUNT') );
  }

  function test_shopify_domain_suffix() {
    $this->assertTrue( defined('WPS_SHOPIFY_DOMAIN_SUFFIX') );
  }

  function test_plugin_table_migration_suffix() {
    $this->assertTrue( defined('WPS_TABLE_MIGRATION_SUFFIX') );
  }

  function test_plugin_table_migration_suffix_tests() {
    $this->assertTrue( defined('WPS_TABLE_MIGRATION_SUFFIX_TESTS') );
  }

  function test_plugin_pro_subdirectory_name() {
    $this->assertTrue( defined('WPS_PRO_SUBDIRECTORY_NAME') );
  }

  function test_plugin_free_subdirectory_name() {
    $this->assertTrue( defined('WPS_FREE_SUBDIRECTORY_NAME') );
  }

  function test_plugin_shopify_max_items_per_page() {
    $this->assertTrue( defined('WPS_MAX_ITEMS_PER_REQUEST') );
  }

  function test_plugin_shopify_primary_key() {
    $this->assertTrue( defined('WPS_SHOPIFY_PAYLOAD_KEY') );
  }

  function test_plugin_products_lookup_key() {
    $this->assertTrue( defined('WPS_PRODUCTS_LOOKUP_KEY') );
  }

  function test_plugin_collections_lookup_key() {
    $this->assertTrue( defined('WPS_COLLECTIONS_LOOKUP_KEY') );
  }

  function test_plugin_default_cart_terms_content() {
    $this->assertTrue( defined('WPS_DEFAULT_CART_TERMS_CONTENT') );
  }

  function test_plugin_default_add_to_cart_color() {
    $this->assertTrue( defined('WPS_DEFAULT_ADD_TO_CART_COLOR') );
  }

  function test_plugin_default_variant_color() {
    $this->assertTrue( defined('WPS_DEFAULT_VARIANT_COLOR') );
  }

  function test_plugin_default_cart_counter_color() {
    $this->assertTrue( defined('WPS_DEFAULT_CART_COUNTER_COLOR') );
  }

  function test_plugin_default_cart_icon_color() {
    $this->assertTrue( defined('WPS_DEFAULT_CART_ICON_COLOR') );
  }

  function test_plugin_default_products_heading() {
    $this->assertTrue( defined('WPS_DEFAULT_PRODUCTS_HEADING') );
  }

  function test_plugin_default_collections_heading() {
    $this->assertTrue( defined('WPS_DEFAULT_COLLECTIONS_HEADING') );
  }

  function test_plugin_default_related_products_heading() {
    $this->assertTrue( defined('WPS_DEFAULT_RELATED_PRODUCTS_HEADING') );
  }

  function test_plugin_default_products_images_sizing_width() {
    $this->assertTrue( defined('WPS_DEFAULT_PRODUCTS_IMAGES_SIZING_WIDTH') );
  }

  function test_plugin_default_products_images_sizing_height() {
    $this->assertTrue( defined('WPS_DEFAULT_PRODUCTS_IMAGES_SIZING_HEIGHT') );
  }

  function test_plugin_default_products_images_sizing_crop() {
    $this->assertTrue( defined('WPS_DEFAULT_PRODUCTS_IMAGES_SIZING_CROP') );
  }

  function test_plugin_default_products_images_sizing_scale() {
    $this->assertTrue( defined('WPS_DEFAULT_PRODUCTS_IMAGES_SIZING_SCALE') );
  }

  function test_plugin_default_collections_images_sizing_width() {
    $this->assertTrue( defined('WPS_DEFAULT_COLLECTIONS_IMAGES_SIZING_WIDTH') );
  }

  function test_plugin_default_collections_images_sizing_height() {
    $this->assertTrue( defined('WPS_DEFAULT_COLLECTIONS_IMAGES_SIZING_HEIGHT') );
  }

  function test_plugin_default_collections_images_sizing_crop() {
    $this->assertTrue( defined('WPS_DEFAULT_COLLECTIONS_IMAGES_SIZING_CROP') );
  }

  function test_plugin_default_collections_images_sizing_scale() {
    $this->assertTrue( defined('WPS_DEFAULT_COLLECTIONS_IMAGES_SIZING_SCALE') );
  }

  function test_plugin_default_related_products_images_sizing_width() {
    $this->assertTrue( defined('WPS_DEFAULT_RELATED_PRODUCTS_IMAGES_SIZING_WIDTH') );
  }

  function test_plugin_default_related_products_images_sizing_height() {
    $this->assertTrue( defined('WPS_DEFAULT_RELATED_PRODUCTS_IMAGES_SIZING_HEIGHT') );
  }

  function test_plugin_default_related_products_images_sizing_crop() {
    $this->assertTrue( defined('WPS_DEFAULT_RELATED_PRODUCTS_IMAGES_SIZING_CROP') );
  }

  function test_plugin_default_related_products_images_sizing_scale() {
    $this->assertTrue( defined('WPS_DEFAULT_RELATED_PRODUCTS_IMAGES_SIZING_SCALE') );
  }

  function test_placeholder_image_src() {
    $this->assertTrue( defined('WPS_PLACEHOLDER_IMAGE_SRC') );
  }

  function test_enable_custom_checkout_domain() {
    $this->assertTrue( defined('WPS_DEFAULT_ENABLE_CUSTOM_CHECKOUT_DOMAIN') );
  }

}
