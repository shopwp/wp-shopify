<?php

namespace WPS;

use WPS\Utils;
use WPS\Transients;

/*

Class Post Types

*/
class CPT {

  protected static $instantiated = null;
  private $Config;


  /*

  Initialize the class and set its properties.

  */
  public function __construct($Config) {
    $this->config = $Config;
    $this->general = $this->config->wps_get_settings_general();
  }


  /*

  Creates a new class if one hasn't already been created.
  Ensures only one instance is used.

  */
  public static function instance() {

    if (is_null(self::$instantiated)) {
      self::$instantiated = new self();
    }

    return self::$instantiated;

  }


  //
  // CPT: Products
  //
  public function wps_post_type_products() {

    $labels = array(
      'name'                => _x('Products', 'Post Type General Name', 'text_domain'),
      'singular_name'       => _x('Product', 'Post Type Singular Name', 'text_domain'),
      'menu_name'           => __('Products', 'text_domain'),
      'parent_item_colon'   => __('Parent Item:', 'text_domain'),
      'new_item'            => __('Add New Product', 'text_domain'),
      'edit_item'           => __('Edit Product', 'text_domain'),
      'not_found'           => __('No Products found', 'text_domain'),
      'not_found_in_trash'  => __('No Products found in trash', 'text_domain')
    );

    $args = array(
      'label'               => __('Products', 'text_domain'),
      'description'         => __('Custom Post Type for Products', 'text_domain'),
      'labels'              => $labels,
      'supports'            => array('title'),
      'hierarchical'        => false,
      'public'              => true,
      'show_ui'             => true,
      'show_in_menu'        => false,
      'menu_position'       => 100,
      'menu_icon'           => 'dashicons-megaphone',
      'show_in_admin_bar'   => true,
      'show_in_nav_menus'   => true,
      'can_export'          => true,
      'has_archive'         => true,
      'exclude_from_search' => false,
      'publicly_queryable'  => true,
      'capability_type'     => 'post',
      'rewrite'             => array(
        'slug' => $this->general->url_products
      )
    );

    Transients::check_rewrite_rules();

    register_post_type('wps_products', $args);

  }


  //
  // CPT: Collections
  //
  public function wps_post_type_collections() {

    $labels = array(
      'name'                => _x('Collections', 'Post Type General Name', 'text_domain'),
      'singular_name'       => _x('Collection', 'Post Type Singular Name', 'text_domain'),
      'menu_name'           => __('Collections', 'text_domain'),
      'parent_item_colon'   => __('Parent Item:', 'text_domain'),
      'new_item'            => __('Add New Collection', 'text_domain'),
      'edit_item'           => __('Edit Collection', 'text_domain'),
      'not_found'           => __('No Collections found', 'text_domain'),
      'not_found_in_trash'  => __('No Collections found in trash', 'text_domain')
    );

    $args = array(
      'label'               => __('Collections', 'text_domain'),
      'description'         => __('Custom Post Type for Collections', 'text_domain'),
      'labels'              => $labels,
      'supports'            => array('title'),
      'hierarchical'        => false,
      'public'              => true,
      'show_ui'             => true,
      'show_in_menu'        => false,
      'menu_position'       => 100,
      'menu_icon'           => 'dashicons-megaphone',
      'show_in_admin_bar'   => true,
      'show_in_nav_menus'   => true,
      'can_export'          => true,
      'has_archive'         => true,
      'exclude_from_search' => false,
      'publicly_queryable'  => true,
      'capability_type'     => 'post',
      'rewrite'             => array(
        'slug' => $this->general->url_collections
      )

    );

    Transients::check_rewrite_rules();

    register_post_type('wps_collections', $args);

  }


  /*

  Insert New CPT Product

  */
  public static function wps_insert_new_product($product) {

    $newProductModel = array(
      'post_title'    => property_exists($product, 'title') ? $product->title : '',
      'post_content'  => '',
      'post_status'   => 'publish',
      'post_type'     => 'wps_products',
      'post_name'			=> property_exists($product, 'handle') ? $product->handle : ''
    );

    // Insert post and return the ID or error object if fail
    return wp_insert_post($newProductModel, true);

  }


  /*

  Update an existing CPT product

  */
  public static function wps_update_existing_product($product) {

    $found_post_id = Utils::wps_find_post_id_from_new_product($product);

    $product_args = array(
      'ID'            => !empty($found_post_id) ? $found_post_id : null,
      'post_title'    => property_exists($product, 'title') ? $product->title : '',
      'post_content'  => '',
      'post_status'   => 'publish',
      'post_type'     => 'wps_products',
      'post_name'			=> property_exists($product, 'handle') ? $product->handle : ''
    );

    // Needed to ensure working pages
    // flush_rewrite_rules();

    // Insert post and return the ID or error object if fail
    return wp_insert_post($product_args, true);


  }


  /*

  Insert New Collections

  */
  public static function wps_insert_new_collection($collection) {

    $newCollectionModel = array(
      'post_title'    => property_exists($collection, 'title') ? $collection->title : '',
      'post_content'  => '',
      'post_status'   => 'publish',
      'post_type'     => 'wps_collections',
      'post_name'			=> property_exists($collection, 'handle') ? $collection->handle : ''
    );

    // Insert post and return the ID or error object if fail
    return wp_insert_post($newCollectionModel, true);

  }


  /*

  Update existing products

  */
  public static function wps_update_existing_collection($collection) {

    $found_post_id = Utils::wps_find_post_id_from_new_collection($collection);

    $collection_args = array(
      'ID'            => !empty($found_post_id) ? $found_post_id : null,
      'post_title'    => property_exists($collection, 'title') ? $collection->title : '',
      'post_content'  => '',
      'post_status'   => 'publish',
      'post_type'     => 'wps_collections',
      'post_name'			=> property_exists($collection, 'handle') ? $collection->handle : ''
    );

    // Needed to ensure working pages
    // flush_rewrite_rules();

    // Insert post and return the ID or error object if fail
    return wp_insert_post($collection_args, true);

  }


  /*

  wps_plugin_name_add_rewrite_rules

  TODO: Optimize, ensure not conflicting with other plugins

  */
  function wps_plugin_name_add_rewrite_rules() {

    add_rewrite_rule('page/([0-9]+)?$', 'index.php?post_type=wps_products&paged=$matches[1]', 'top');

  }


  function wps_custom_query_vars_filter($vars) {

    $vars[] = 'wps_related_products';
    return $vars;

  }


  /*

  Register

  */
  public function init() {

    add_action( 'init', array($this, 'wps_post_type_products') );
		add_action( 'init', array($this, 'wps_post_type_collections') );
    add_filter( 'init', array($this, 'wps_plugin_name_add_rewrite_rules') );
    // add_filter( 'query_vars', array($this, 'wps_custom_query_vars_filter') );

  }


}
