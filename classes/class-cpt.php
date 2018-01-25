<?php

namespace WPS;

use WPS\Utils;
use WPS\Transients;

// If this file is called directly, abort.
if (!defined('ABSPATH')) {
	exit;
}

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
  public static function instance($Config) {

    if (is_null(self::$instantiated)) {
      self::$instantiated = new self($Config);
    }

    return self::$instantiated;

  }


  public static function wps_add_meta_to_cpt($posts) {

    $postsNew = Utils::wps_convert_object_to_array($posts);

    return array_map(function($post) {
      $post['post_meta'] = get_post_meta($post['ID']);
      return $post;
    }, $postsNew);

  }


  public static function wps_get_all_cpt_by_type($type) {

    $posts = get_posts(array(
			'posts_per_page' => -1,
			'post_type' => $type
		));

    return self::wps_add_meta_to_cpt($posts);

  }


  /*

  CPT: Products

  */
  public function wps_post_type_products() {

    if ( post_type_exists( 'wps_products' ) ) {
      return;
    }


    // If falsey or not an object ...
    if (empty($this->general) || !is_object($this->general)) {
      $permalink = 'products';

    } else {
      $permalink = $this->general->url_products;
    }


    $labels = array(
      'name'                => _x('Products', 'Post Type General Name', 'wp-shopify'),
      'singular_name'       => _x('Product', 'Post Type Singular Name', 'wp-shopify'),
      'menu_name'           => __('Products', 'wp-shopify'),
      'parent_item_colon'   => __('Parent Item:', 'wp-shopify'),
      'new_item'            => __('Add New Product', 'wp-shopify'),
      'edit_item'           => __('Edit Product', 'wp-shopify'),
      'not_found'           => __('No Products found', 'wp-shopify'),
      'not_found_in_trash'  => __('No Products found in trash', 'wp-shopify')
    );


    $args = array(
      'label'               => __('Products', 'wp-shopify'),
      'description'         => __('Custom Post Type for Products', 'wp-shopify'),
      'labels'              => $labels,
      'supports'            => array('title', 'page-attributes', 'editor', 'custom-fields', 'comments'),
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
        'slug' => $permalink
      )
    );

    // Transients::check_rewrite_rules();

    register_post_type('wps_products', $args);

  }


  /*

  CPT: Collections

  */
  public function wps_post_type_collections() {

    if ( post_type_exists( 'wps_collections' ) ) {
      return;
    }

    // If falsey or not an object ...
    if (empty($this->general) || !is_object($this->general)) {
      $permalink = 'collections';

    } else {
      $permalink = $this->general->url_collections;
    }


    $labels = array(
      'name'                => _x('Collections', 'Post Type General Name', 'wp-shopify'),
      'singular_name'       => _x('Collection', 'Post Type Singular Name', 'wp-shopify'),
      'menu_name'           => __('Collections', 'wp-shopify'),
      'parent_item_colon'   => __('Parent Item:', 'wp-shopify'),
      'new_item'            => __('Add New Collection', 'wp-shopify'),
      'edit_item'           => __('Edit Collection', 'wp-shopify'),
      'not_found'           => __('No Collections found', 'wp-shopify'),
      'not_found_in_trash'  => __('No Collections found in trash', 'wp-shopify')
    );

    $args = array(
      'label'               => __('Collections', 'wp-shopify'),
      'description'         => __('Custom Post Type for Collections', 'wp-shopify'),
      'labels'              => $labels,
      'supports'            => array('title', 'page-attributes', 'editor', 'custom-fields', 'comments'),
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
        'slug' => $permalink
      )

    );


    // Transients::check_rewrite_rules();
    register_post_type('wps_collections', $args);

  }



  /*

  Returns a model used to either add or update a product CPT

  */
  public static function wps_get_product_cpt_model($product) {

    return $productModel = array(
      'post_title'    => property_exists($product, 'title') ? __($product->title) : '',
      'post_content'  => property_exists($product, 'body_html') ? __($product->body_html) : '',
      'post_status'   => 'publish',
      'post_type'     => 'wps_products',
      'post_name'			=> property_exists($product, 'handle') ? __($product->handle) : '',
      'meta_input' => array(
        'product_id' => property_exists($product, 'id') ? $product->id : ''
      )
    );

  }


  /*

  Returns a model used to either add or update a collection CPT

  */
  public static function wps_get_collection_cpt_model($collection, $newCollectionID) {

    return array(
      'post_title'    => property_exists($collection, 'title') ? __($collection->title) : '',
      'post_content'  => property_exists($collection, 'body_html') ? __($collection->body_html) : '',
      'post_status'   => 'publish',
      'post_type'     => 'wps_collections',
      'post_name'			=> property_exists($collection, 'handle') ? __($collection->handle) : '',
      'meta_input' => array(
        'collection_id' => $newCollectionID
      )
    );

  }




  /*

  Find Latest Menu Order

  */
  public static function wps_find_latest_menu_order($type) {

    global $post;

    $args = array(
      'post_type'       => 'wps_' . $type,
      'posts_per_page'  => 1,
    );

    $loop = get_posts($args);

    if (is_array($loop) && empty($loop)) {
      return 1;

    } else {
      return $loop[0]->menu_order + 1;
    }

  }


  /*

  Adds New CPT Product into DB
  Don't put expensive operations inside as this function gets called within loops.

  Called in class-db-products.php

  */
  public static function wps_insert_or_update_product($product, $existingProducts, $index = false) {

    $productModel = self::wps_get_product_cpt_model($product);
    $existing_post_id = Utils::wps_find_post_id_from_new_product_or_collection($product, $existingProducts, 'product');

    // If existing CPT found ...
    if (!empty($existing_post_id) && $existing_post_id) {

      $productCPT = get_post($existing_post_id);

      $productModel['ID'] = $existing_post_id;
      $productModel['menu_order'] = $productCPT->menu_order;

    } else {

      if (!empty($index) && $index) {
        $productModel['menu_order'] = $index;
      }

    }

		if ($productModel['post_content'] == null) {
			$productModel['post_content']	= '';
		}

    // Insert post and return the ID or error object if fail
    return wp_insert_post($productModel, true);

  }



  /*

  Insert New Collections
  $product, $existingProducts, $index = false

  */
  public static function wps_insert_or_update_collection($collection, $existingCollections, $index = false) {

    // Sets the collection ID within the meta value
    $newCollectionID = Utils::wps_find_collection_id($collection);
    $newCollectionModel = self::wps_get_collection_cpt_model($collection, $newCollectionID);

    $existing_post_id = Utils::wps_find_post_id_from_new_product_or_collection($collection, $existingCollections,  'collection');

    if (!empty($existing_post_id) && $existing_post_id) {
      $newCollectionModel['ID'] = $existing_post_id;
    }

    /*

    We have access to an $index variable if this function is called
    by a full sync. Otherwise this function is called via a webhook like
    update or add. In this case we need to find the highest index

    */
    if (!empty($index) && $index) {
      $newCollectionModel['menu_order'] = $index;

    } else {

      // Use the current menu order number instead
      $collectionCPT = get_post($existing_post_id);

			if (is_object($collectionCPT) && isset($collectionCPT->menu_order)) {
				$newCollectionModel['menu_order'] = $collectionCPT->menu_order;
			}

    }

		if ($newCollectionModel['post_content'] == null) {
			$newCollectionModel['post_content']	= '';
		}

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
      'post_title'    => property_exists($collection, 'title') ? __($collection->title) : '',
      'post_content'  => property_exists($collection, 'body_html') ? __($collection->body_html) : '',
      'post_status'   => 'publish',
      'post_type'     => 'wps_collections',
      'post_name'			=> property_exists($collection, 'handle') ? __($collection->handle) : '',
      'meta_input' => array(
        'collection_id' => property_exists($collection, 'id') ? $collection->id : ''
      )

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

    $this->wps_post_type_products();
    $this->wps_post_type_collections();

  }


}
