<?php

namespace WPS\DB;

use WPS\Utils;
use WPS\CPT;
use WPS\Transients;


if (!defined('ABSPATH')) {
	exit;
}


if (!class_exists('Products')) {

  class Products extends \WPS\DB {

    public $table_name;
  	public $version;
  	public $primary_key;


  	public function __construct() {

      global $wpdb;

      $this->table_name         				= WPS_TABLE_NAME_PRODUCTS;
      $this->primary_key        				= 'id';
      $this->version            				= '1.0';
      $this->cache_group        				= 'wps_db_products';

    }


  	public function get_columns() {

      return [
				'id'            				=> '%d',
        'product_id'            => '%d',
        'post_id'               => '%d',
        'title'                 => '%s',
        'body_html'             => '%s',
        'handle'                => '%s',
        'image'                 => '%s',
				'images'                => '%s',
        'vendor'                => '%s',
        'product_type'          => '%s',
        'published_scope'       => '%s',
        'published_at'          => '%s',
        'updated_at'            => '%s',
        'created_at'            => '%s',
				'admin_graphql_api_id'	=> '%s'
      ];

    }


  	public function get_column_defaults() {

      return [
				'id'										=> 0,
        'product_id'            => 0,
        'post_id'               => 0,
        'title'                 => '',
        'body_html'             => '',
        'handle'                => '',
        'image'                 => '',
				'images'                => '',
        'vendor'                => '',
        'product_type'          => '',
        'published_scope'       => '',
        'published_at'          => '',
        'updated_at'            => '',
        'created_at'            => '',
				'admin_graphql_api_id'	=> ''
      ];

    }


		/*

		Insert Product Data

		$product comes directly from Shopify

		*/
		public function insert_product($product = false, $cpt_id = false) {

			$insertionResults = [];

			$product = Utils::convert_array_to_object($product);
			$product = $this->rename_primary_key($product, 'product_id');
			$product = $this->add_image_to_product($product);
			$product = $this->add_post_id_to_product($product, $cpt_id);

			return $this->insert($product, 'product');

		}


    /*

    Get Single Product
    Without: Images, variants

    */
  	public function get_product_from_post_id($postID = null) {

      global $wpdb;

      if ($postID === null) {
        $postID = get_the_ID();
      }

      if (get_transient('wps_product_single_' . $postID)) {
        $results = get_transient('wps_product_single_' . $postID);

      } else {
        $query = "SELECT products.* FROM " . WPS_TABLE_NAME_PRODUCTS . " as products WHERE products.post_id = %d";
        $results = $wpdb->get_row( $wpdb->prepare($query, $postID) );

        set_transient('wps_product_single_' . $postID, $results);

      }

      return $results;

    }


		/*

		Finds product row from WordPress post iD

		*/
		public function get_product_from_post_name($post_name = false) {

      global $wpdb;

      if ($post_name === false) {
        return;
      }

      $query = "SELECT products.* FROM " . WPS_TABLE_NAME_PRODUCTS . " as products WHERE products.handle = %s";
			$results = $wpdb->get_row( $wpdb->prepare($query, $post_name) );

      return $results;

    }


    /*

    Get Products

    */
    public function get_products() {
      return $this->get_all_rows();
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

    Add Post ID To Product

    */
    public function add_post_id_to_product($product, $cpt_id) {

      $product->post_id = $cpt_id;

      return $product;

    }


		/*

		Assigns a post id to the product data

		*/
		public function assign_post_id_to_product($post_id, $product_id) {

			global $wpdb;

			return $wpdb->update(
				$this->table_name,
				['post_id' => $post_id],
				['product_id' => $product_id],
				['%d'],
				['%d']
			);

		}



		function product_exists_by_id($product_id) {

			if (empty($product_id)) {
				return false;
			}

			$product_found = $this->get($product_id);

			if (empty($product_found)) {
		    return false;

		  } else {
		    return true;
		  }

		}


		/*

  	Responsible for assigning a post_id to collection_id

  	*/
		public function set_post_id_to_product($post_id, $product_id) {

			$product = Utils::convert_array_to_object($product);

			$update_result = $this->update_column_single(['post_id' => $post_id], ['product_id' => $product_id]);

			return $this->sanitize_db_response($update_result);

		}


		/*

		Returns the image src of a product

		Needed to update 'image' col in products table. Object is returned from Shopify
		so we need to only save image src. Rest of product images live in Images table.

		*/
		public function flatten_product_image($product) {

			if (property_exists($product, 'image') && !empty($product->image)) {
				return $product->image->src;
			}

		}


		/*

    Find a post ID from a product ID

    */
		public function find_post_id_from_product_id($product_id) {

			$product = $this->get($product_id);

			if (empty($product)) {
				return [];
			}

			return [$product->post_id];

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
        return new \WP_Error('error', sprintf(esc_html__('Warning: Unable to update product: %s', WPS_PLUGIN_TEXT_DOMAIN), $product->title));

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

      if ($cpt) {

        $post = get_post( $this->get_post_id_from_object($product) );
        return Utils::wps_hash($post->{$content});

      } else {
        return Utils::wps_hash($product->{$content});

      }

    }


    /*

    Insert connection data

    */
    public function update_products($products) {

      $result = [];

      foreach ($products as $key => $product) {
        $result[] = $this->update($product['id'], $product);
      }

      return $result;

    }


    /*

    Gets all products from a collection by collection id

    */
    public function get_products_by_collection_id($collection_id) {

      global $wpdb;

      $query = "SELECT products.* FROM " . WPS_TABLE_NAME_PRODUCTS ." products INNER JOIN " . WPS_TABLE_NAME_COLLECTS . " collects ON products.product_id = collects.product_id WHERE collects.collection_id = %d order by collects.position asc;";

      /*

      Get the products

      */
      $products = $wpdb->get_results(
        $wpdb->prepare($query, $collection_id)
      );

      return $products;

    }


		/*

	  Delete products from product ID

	  */
	  public function delete_products_from_product_id($product_id) {
			return $this->delete_rows('product_id', $product_id);
	  }


		/*
		Updates products from product ID
		*/
		public function update_products_from_product_id($product_id, $product) {

			if (Utils::is_data_published($product)) {
				$product->image = $this->flatten_product_image($product);
				return $this->update($product_id, $product);
			}

		}


		/*

    Default Products Query

    */
    public function get_default_products_query() {

      global $wpdb;

      return [
        'where' => '',
        'groupby' => '',
        'join' => ' INNER JOIN ' . WPS_TABLE_NAME_PRODUCTS . ' products ON ' .
           $wpdb->posts . '.ID = products.post_id INNER JOIN ' . WPS_TABLE_NAME_VARIANTS . ' variants ON products.product_id = variants.product_id AND variants.position = 1',
        'orderby' => $wpdb->posts . '.menu_order',
        'distinct' => '',
        'fields' => 'products.*, variants.price',
        'limits' => ''
      ];

    }


    /*

    Creates a table query string

    */
    public function create_table_query($table_name = false) {

			if ( !$table_name ) {
				$table_name = $this->table_name;
			}

      $collate = $this->collate();

      return "CREATE TABLE $table_name (
				id bigint(100) unsigned NOT NULL AUTO_INCREMENT,
        product_id bigint(255) unsigned NOT NULL,
        post_id bigint(100) unsigned DEFAULT NULL,
        title varchar(255) DEFAULT NULL,
        body_html longtext,
        handle varchar(255) DEFAULT NULL,
        image longtext,
				images longtext,
        vendor varchar(255),
        product_type varchar(100) DEFAULT NULL,
        published_scope varchar(100) DEFAULT NULL,
        published_at datetime,
        updated_at datetime,
        created_at datetime,
				admin_graphql_api_id longtext DEFAULT NULL,
        PRIMARY KEY  (id)
      ) ENGINE=InnoDB $collate";

    }


  }

}
