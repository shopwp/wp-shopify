<?php

namespace WPS\DB;

use WPS\Utils;
use WPS\DB;
use WPS\DB\Variants;
use WPS\DB\Options;
use WPS\DB\Images;
use WPS\DB\Collects;
use WPS\DB\Tags;
use WPS\CPT;
use WPS\WS;
use WPS\Config;
use WPS\Backend;
use WPS\Transients;
use WPS\DB\Settings_Connection;
use WPS\Progress_Bar;

// Used for mocking HTTP requests
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Exception\RequestException;


// If this file is called directly, abort.
if (!defined('ABSPATH')) {
	exit;
}


/*

Database class for Products

*/
if (!class_exists('Products')) {

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
        'image'                 => '',
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

    Get Products

    */
    public function get_products() {
      return $this->get_all_rows();
    }


    /*

    Get Single Product

    */
    public function get_data($postID = null) {

      $Images = new Images();
      $Variants = new Variants();
      $Options = new Options();
      $Tags = new Tags();
			$DB = new DB();

			$results = new \stdClass;

      $results->details = $this->get_product($postID);

      $results->images = $Images->get_product_images($postID);
      $results->tags = $Tags->get_product_tags($postID);
      $results->variants = $Variants->get_product_variants($postID);
      $results->options = $Options->get_product_options($postID);
      $results->details->tags = $Tags->construct_only_tag_names($results->tags);

			$results->product_id = $results->details->product_id;
			$results->post_id = $results->details->post_id;

			$results->collections = $DB->get_collections_by_product_id($results->details->product_id);

      return $results;

    }


    /*

    Add Image To Product

    */
    public function add_image_to_product($product) {

      // If product has an image
      if (property_exists($product, 'image') && is_object($product->image)) {
        $product->image = $product->image->src;
      }

      return $product;

    }


    /*

    Product Mods Before Insert

    */
    public function modify_product_before_insert($product, $customPostTypeID) {

      $product = $this->add_image_to_product($product);

      return $product;

    }


    /*

    Product Mods Update After CPT

    */
    public function modify_product_after_cpt_insert($product, $customPostTypeID) {

      // Modify's the products model with CPT foreign key
      $product = $this->assign_foreign_key($product, $customPostTypeID);
      $product = $this->rename_primary_key($product);

      return $product;

    }


    /*

    Insert Product

    */
    public function insert_product($product, $customPostTypeID) {

      // If product has an image
      if (property_exists($product, 'image') && is_object($product->image)) {
        $product->image = $product->image->src;
      }

      // If product is published
      if (property_exists($product, 'published_at') && $product->published_at !== null) {

        // Modify's the products model with CPT foreign key
        $product = $this->assign_foreign_key($product, $customPostTypeID);
        $product = $this->rename_primary_key($product);

        return $this->insert($product, 'product');

      } else {

        return false;

      }

    }


    /*

    Insert products

    */
  	public function insert_products($products) {

      $DB_Tags = new Tags();
      $DB_Settings_Connection = new Settings_Connection();

      $progress = new Progress_Bar(new Config());
      $results = array();
      $index = 1;


      foreach ($products as $key => $product) {

        if (!Utils::isStillSyncing()) {
          wp_die();
          break;
        }


        // Inserts CPT
        $customPostTypeID = CPT::wps_insert_or_update_product($product, $index);

        $DB_Tags->insert_product_tags($product, $customPostTypeID);

        // Tested and passed by - test-sync-products.php
        $results[] = $this->insert_product($product, $customPostTypeID);


        $progress->increment_current_amount('products');
        $index++;

      }

      return $results;

    }


    /*

    Fired when product is update at Shopify

    */
    public function update_product($product) {

      $newProductID = Utils::wps_find_product_id($product);
      $results = [];

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
        if (property_exists($product, 'image') && !empty($product->image)) {
          $product->image = $product->image->src;
        }

        $results['variants']    = $DB_Variants->update_variant($product);
        $results['options']     = $DB_Options->update_option($product);
        $results['product']     = $this->update($newProductID, $product);
        $results['image']       = $DB_Images->update_image($product);
        $results['collects']    = $DB_Collects->update_collects($product);
        $results['product_cpt'] = CPT::wps_insert_or_update_product($product);
        $results['tags']        = $DB_Tags->update_tags($product, $results['product_cpt']);

      } else {

        // $results['deleted_product'] = $this->delete_product($product, $newProductID);

      }



			/*

			*Important* Clear product cache and log errors if present

			*/
			$transientSingleProductDeletion = Transients::delete_cached_single_product_by_id($results['product_cpt']);
			$transientProductQueriesDeletion = Transients::delete_cached_product_queries();
			$transientProductPricesDeletion = Transients::delete_cached_prices();

			if (is_wp_error($transientSingleProductDeletion)) {
				error_log($transientSingleProductDeletion->get_error_message());
			}

			if (is_wp_error($transientProductQueriesDeletion)) {
				error_log($transientProductQueriesDeletion->get_error_message());
			}

			if (is_wp_error($transientProductPricesDeletion)) {
				error_log($transientProductPricesDeletion->get_error_message());
			}


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

      } else {
        $postIds = array();
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

    Fired when product is created at Shopify. No need to manually
    created the WP custom post here as this is handled already within
    the insert_products call.

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

    $product current represents the data coming from Shopify or
    the $product object from the products table. Since this could
    vary, we need to check if post_id is available.

    CURRENTLY NOT USED

    */

    public function update_post_content_if_changed($product) {

      if ($this->post_content_has_changed($product)) {
        return $this->update_post_content($product);
      }

    }


    /*

    Update Post Content

    */
    public function update_post_content($product) {

      $postID = $this->get_post_id_from_object($product);

      $response = wp_update_post(array(
        'ID'           => $postID,
        'post_content' => $product->body_html
      ));

      if ($response === 0) {
        return new \WP_Error('error', sprintf(esc_html__('Warning: Unable to update product: %s', 'wp-shopify'), $product->title));

      } else {
        return $response;
      }

      return $response;

    }


    /*

    Post Content Has Changed

    */
    public function post_content_has_changed($product) {

      $productsContent = $this->get_content_hash($product, 'body_html');
      $cptContent = $this->get_content_hash($product, 'post_content', true);

      return $productsContent !== $cptContent;

    }


    /*

    Get Post ID From Object

    */
    public function get_post_id_from_object($post) {

      if (isset($post->post_id)) {
        return $post->post_id;

      } else {

        // get post ID by looking up value manually
        if (isset($post->handle)) {

          return $post->id;

        }

      }

    }


    /*

    Param 1: The product variable (coming from wps_products table)
    Param 2: The column to look up
    Param 3: Whether we should look for a custom post type or not

    */
    public function get_content_hash($product, $content, $cpt = false) {

      $Utils = new Utils();

      if ($cpt) {

        $post = get_post( $this->get_post_id_from_object($product) );
        return $Utils->wps_hash($post->{$content});

      } else {
        return $Utils->wps_hash($product->{$content});
      }

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

      Get the variants / feat image and add them to the products

      */
      foreach ($products as $key => $product) {
        $product->variants = $DB_Variants->get_product_variants($product->post_id);
        $product->feat_image = Utils::get_feat_image_by_id($product->post_id);
      }

      return $products;

    }


    /*

    Rename primary key

    */
    public function get_products_by_page($currentPage) {

      // Create a mock and queue two responses.

      // $mock = new MockHandler([
      //   new Response(504, ['X-Foo' => 'Bar'])
      // ]);
      //
      // $handler = HandlerStack::create($mock);
      // $client = new Client(['handler' => $handler]);
      //
      // return $client->request('GET', '/');



      $WS = new WS(new Config());

      return $WS->wps_request(
        'GET',
        $WS->get_request_url("/admin/products.json", "?limit=250&page=" . $currentPage),
        $WS->get_request_options()
      );


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
        'orderby' => $wpdb->posts . '.menu_order',
        'distinct' => '',
        'fields' => 'products.*, variants.price',
        'limits' => ''
      );

    }


    /*

    Creates a table query string

    */
    public function create_table_query() {

      global $wpdb;

      $collate = '';

      if ( $wpdb->has_cap('collation') ) {
        $collate = $wpdb->get_charset_collate();
      }

      return "CREATE TABLE `{$this->table_name}` (
        `product_id` bigint(255) unsigned DEFAULT NULL AUTO_INCREMENT,
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
      ) ENGINE=InnoDB $collate";

    }


    /*

    Creates database table

    */
  	public function create_table() {

      require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

      // Create the table if it doesnt exist. Where the magic happens.
      if (!$this->table_exists($this->table_name)) {
        dbDelta( $this->create_table_query() );
      }

    }

  }

}
