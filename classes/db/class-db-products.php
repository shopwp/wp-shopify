<?php

namespace WPS\DB;

use WPS\Utils;
use WPS\DB\Variants;
use WPS\DB\Options;
use WPS\DB\Images;
use WPS\DB\Collects;
use WPS\DB\Tags;
use WPS\CPT;
use WPS\Config;
use WPS\Backend;
use WPS\Transients;

class Products extends \WPS\DB {

  public $table_name;
	public $version;
	public $primary_key;
  public $product_data;

  /*

  Construct

  */
	public function __construct() {

    global $wpdb;
    $this->table_name         = $wpdb->prefix . 'wps_products';
    $this->primary_key        = 'product_id';
    $this->version            = '1.0';
    $this->cache_group        = 'wps_db_products';

  }


  /*

  Get Columns

  */
	public function get_columns() {

    return array(
      'product_id'            => '%d',
      'post_id'               => '%d',
      'title'                 => '%s',
      'body_html'             => '%s',
      'handle'                => '%s',
      'image'                 => '%s',
      'vendor'                => '%s',
      'product_type'          => '%s',
      'published_scope'       => '%s',
      'published_at'          => '%s',
      'updated_at'            => '%s',
      'created_at'            => '%s'

    );

  }


  /*

  Get Column Defaults

  */
	public function get_column_defaults() {

    return array(
      'product_id'            => 0,
      'post_id'               => 0,
      'title'                 => '',
      'body_html'             => '',
      'handle'                => '',
      'vendor'                => '',
      'product_type'          => '',
      'published_scope'       => '',
      'published_at'          => '',
      'updated_at'            => '',
      'created_at'            => ''
    );

  }


  /*

  Get Single Product
  Without: Images, variants

  */
	public function get_product($postID = null) {

    global $wpdb;

    if ($postID === null) {
      $postID = get_the_ID();
    }

    if (get_transient('wps_product_single_' . $postID)) {
      $results = get_transient('wps_product_single_' . $postID);

    } else {

      $query = "SELECT products.* FROM $this->table_name as products WHERE products.post_id = %d";
      $results = $wpdb->get_row( $wpdb->prepare($query, $postID) );

      set_transient('wps_product_single_' . $postID, $results);

    }

    return $results;

  }


  /*

  Get Single Product
  Without: Images, variants

  */
  public function get_data($postID = null) {

    $Images = new Images();
    $Variants = new Variants();
    $Options = new Options();

    // $product_data->details = $this->get_product();
    // $product_data->images = $Images->get_product_images();
    // $product_data->variants = $Variants->get_product_variants();
    // $product_data->options = $Options->get_product_options();

    $results['details'] = $this->get_product($postID);
    $results['images'] = $Images->get_product_images($postID);
    $results['variants'] = $Variants->get_product_variants($postID);
    $results['options'] = $Options->get_product_options($postID);

    // return $results;

    return json_decode(json_encode($results), true);

  }


  /*

  Insert products

  */
	public function insert_products($products) {

    $DB_Tags = new Tags();
    $results = array();

    foreach ($products as $key => $product) {

      // If product has an image
      if (property_exists($product, 'image') && is_object($product->image)) {
        $product->image = $product->image->src;
      }

      // If product is visible on the Online Stores channel
      if (property_exists($product, 'published_at') && $product->published_at !== null) {

        // Inserts CPT
        $customPostTypeID = CPT::wps_insert_new_product($product);

        // Modify's the products model with CPT foreign key
        $product = $this->assign_foreign_key($product, $customPostTypeID);
        $product = $this->rename_primary_key($product);

        $DB_Tags->insert_tags($product, $customPostTypeID);

        // Inserts Product into WPS table
        $results[] = $this->insert($product, 'product');

      }

    }

    return $results;

  }


  /*

  Fired when product is update at Shopify

  */
  public function update_product($product) {

    /*

    If published_at is null, we know the user turned off the Online Store sales channel.
    TODO: Shopify may implement better sales channel checking in the future API. We should
    then check for Buy Button visibility as-well.

    */
    if (property_exists($product, 'published_at') && $product->published_at !== null) {

      $DB_Variants = new Variants();
      $DB_Options = new Options();
      $DB_Images = new Images();
      $DB_Collects = new Collects();
      $DB_Tags = new Tags();

      /*

      TODO: Move to a Util
      Needed to update 'image' col in products table. Object is returned
      Shopify so need to only save image URL. Rest of images live in
      images table_name

      */
      if (property_exists($product, 'image')) {
        $product->image = $product->image->src;
      }

      $results['variants']    = $DB_Variants->update_variant($product);
      $results['options']     = $DB_Options->update_option($product);
      $results['product']     = $this->update($product->id, $product);
      $results['image']       = $DB_Images->update_image($product);
      $results['collects']    = $DB_Collects->update_collects($product);
      $results['product_cpt'] = CPT::wps_update_existing_product($product);
      $results['tags']        = $DB_Tags->update_tags($product, $results['product_cpt']);


    } else {
      $results['deleted_product'] = $this->delete_product($product, $product->id);

    }

    Transients::delete_cached_prices();
    Transients::delete_cached_variants();
    Transients::delete_cached_product_single();
    Transients::delete_cached_product_queries();

    return $results;

  }


  /*

  Fired when product is deleted at Shopify

  */
  public function delete_product($product, $productID = null) {

    $DB_Variants = new Variants();
    $DB_Options = new Options();
    $DB_Images = new Images();
    $DB_Collects = new Collects();
    $DB_Tags = new Tags();
    $Backend = new Backend(new Config());

    if ($productID === null) {

      if (isset($product->product_id)) {
        $productID = $product->product_id;

      } else {
        $productID = $product->id;
      }

    }

    $productData = $this->get($productID);

    if (!empty($productData)) {
      $postIds = array($productData->post_id);
    }

    $results['variants']  = $DB_Variants->delete_rows('product_id', $productID);
    $results['options']   = $DB_Options->delete_rows('product_id', $productID);
    $results['images']    = $DB_Images->delete_rows('product_id', $productID);
    $results['collects']  = $DB_Collects->delete_rows('product_id', $productID);
    $results['tags']      = $DB_Tags->delete_rows('product_id', $productID);
    $results['product']   = $this->delete($productID);
    $results['cpt']       = $Backend->wps_delete_posts('wps_products', $postIds);

    // TODO: Only delete cache of the product that was deleted
    Transients::delete_cached_prices();
    Transients::delete_cached_variants();
    Transients::delete_cached_product_single();
    Transients::delete_cached_product_queries();

    return $results;

  }


  /*

  Fired when product is deleted at Shopify

  */
  public function create_product($product) {

    $DB_Variants = new Variants();
    $DB_Options = new Options();
    $DB_Images = new Images();
    $DB_Collects = new Collects();

    $productWrapped = array();
    $productWrapped[] = $product;
    $results = array();

    /*

    Tags are being inserted by _insert_products because
    we need access to the CPT id.

    */
    $results['products'] = $this->insert_products($productWrapped);
    $results['variants'] = $DB_Variants->insert_variants($productWrapped);
    $results['options'] = $DB_Options->insert_options($productWrapped);
    $results['images'] = $DB_Images->insert_images($productWrapped);
    $results['collects']  = $DB_Collects->update_collects($product);

    Transients::delete_cached_product_queries();
    Transients::delete_cached_product_single();
    
    return $results;

  }


  /*

  Insert connection data

  */
  public function update_products($products) {

    $result = array();

    foreach ($products as $key => $product) {
      $result[] = $this->update($product['id'], $product);
    }

    return $result;

  }


  /*

  Rename primary key

  */
  public function rename_primary_key($product) {

    $productCopy = $product;
    $productCopy->product_id = $productCopy->id;
    unset($productCopy->id);

    return $productCopy;

  }


  /*

  get_products_by_collection_id

  */
  public function get_products_by_collection_id($collection_id) {

    global $wpdb;

    $DB_Collects = new Collects();
    $DB_Variants = new Variants();

    $collects_table_name = $DB_Collects->get_table_name();
    $products_table_name = $this->get_table_name();

    $query = "SELECT products.* FROM $products_table_name products INNER JOIN $collects_table_name collects ON products.product_id = collects.product_id WHERE collects.collection_id = %d;";

    /*

    Get the products

    */
    $products = $wpdb->get_results(
      $wpdb->prepare($query, $collection_id)
    );


    /*

    Get the variants and add them to the products

    */
    foreach ($products as $key => $product) {
      $product->variants = $DB_Variants->get_product_variants($product->post_id);
    }

    return $products;

  }


  /*

  Default Products Query

  */
  public function get_default_query() {

    global $wpdb;

    $DB_Variants = new Variants();
    $table_variants = $DB_Variants->get_table_name();

    return array(
      'where' => '',
      'groupby' => '',
      'join' => ' INNER JOIN ' . $this->get_table_name() . ' products ON ' .
         $wpdb->posts . '.ID = products.post_id INNER JOIN ' . $table_variants . ' variants ON products.product_id = variants.product_id AND variants.position = 1',
      'orderby' => '',
      'distinct' => '',
      'fields' => 'products.*, variants.price',
      'limits' => ''
    );

  }





  /*

  Creates database table

  */
	public function create_table() {

    global $wpdb;

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

		$collate = '';

		if ( $wpdb->has_cap('collation') ) {
			$collate = $wpdb->get_charset_collate();
		}

    $query = "CREATE TABLE `{$this->table_name}` (
      `product_id` bigint(100) unsigned NOT NULL AUTO_INCREMENT,
      `post_id` bigint(100) unsigned DEFAULT NULL,
      `title` varchar(255) DEFAULT NULL,
      `body_html` longtext,
      `handle` varchar(255) DEFAULT NULL,
      `image` longtext,
      `vendor` varchar(255),
      `product_type` varchar(100) DEFAULT NULL,
      `published_scope` varchar(100) DEFAULT NULL,
      `published_at` datetime,
      `updated_at` datetime,
      `created_at` datetime,
      PRIMARY KEY  (`{$this->primary_key}`)
    ) ENGINE=InnoDB DEFAULT CHARSET={$collate};";


    // Create the table if it doesnt exist. Where the magic happens.
    if (!$this->table_exists($this->table_name)) {
      dbDelta($query);
    }

  }

}
