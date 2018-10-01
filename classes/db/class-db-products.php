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

    public $table_name_suffix;
		public $table_name;
  	public $version;
  	public $primary_key;
		public $lookup_key;
		public $cache_group;
		public $type;


  	public function __construct() {

			$this->table_name_suffix  		= WPS_TABLE_NAME_PRODUCTS;
			$this->table_name         		= $this->get_table_name();
			$this->version            		= '1.0';
      $this->primary_key        		= 'id';
			$this->lookup_key        			= WPS_PRODUCTS_LOOKUP_KEY;
      $this->cache_group        		= 'wps_db_products';
			$this->type        						= 'product';

    }


		/*

		Table column name / formats

		Important: Used to determine when new columns are added

		*/
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


		/*

		Table default values

		*/
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

		The modify options used for inserting / updating / deleting

		*/
		public function modify_options($shopify_item, $item_lookup_key = WPS_PRODUCTS_LOOKUP_KEY) {

			return [
			  'item'									=> $shopify_item,
				'item_lookup_key'				=> $item_lookup_key,
				'item_lookup_value'			=> $shopify_item->id,
			  'prop_to_access'				=> 'products',
			  'change_type'				    => 'product'
			];

		}


		/*

		Mod before change

		*/
		public function mod_before_change($product, $post_id = false) {

			$product_copy = $this->copy($product);
			$product_copy = $this->maybe_rename_to_lookup_key($product_copy);
			$product_copy = Utils::flatten_image_prop($product_copy);

			if ($post_id) {
				$product_copy = CPT::set_post_id($product_copy, $post_id);
			}

			return $product_copy;

		}


		/*

		Insert Product Data

		$product comes directly from Shopify

		*/
		public function insert_product($product) {
			return $this->insert($product);
		}


		/*

		Updates a single variant

		*/
		public function update_product($product) {
			return $this->update($this->lookup_key, $this->get_lookup_value($product), $product);
		}


		/*

		Deletes a single product

		The reason we can use $product->product_id is because the Utils::find_items_to_delete method
		will return the current item data structure if found for deletion, not the shopify item data structure

		*/
		public function delete_product($product) {
			return $this->delete_rows($this->lookup_key, $this->get_lookup_value($product));
		}


		/*

	  Delete products from product ID

	  */
	  public function delete_products_from_product_id($product_id) {
			return $this->delete_rows($this->lookup_key, $product_id);
	  }


		/*

		Gets products based on a Shopify product id

		*/
		public function get_products_from_product_id($product_id) {
			return $this->get_rows($this->lookup_key, $product_id);
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
        $query = "SELECT products.* FROM " . $this->table_name . " as products WHERE products.post_id = %d";
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

      $query = "SELECT products.* FROM " . $this->table_name . " as products WHERE products.handle = %s";
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
				[$this->lookup_key => $product_id],
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

    Find a post ID from a product ID

    */
		public function find_post_id_from_product_id($product_id) {

			$product = $this->get_products_from_product_id($product_id);

			if (empty($product)) {
				return [];
			}

			return $product[0]->post_id;

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
        return Utils::wp_error( sprintf( esc_html__('Warning: Unable to update product: %s', WPS_PLUGIN_TEXT_DOMAIN), $product->title) );

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

		Only needed because the data structure coming from the product single template is
		different from the other templates. We should standardize but need to do it in such
		a way that nothing breaks.

		*/
		public function get_post_id_from_product($product) {

			// If $product is a post object with a post id, just return it
			if ( !empty($product->post_id) ) {
				return $product->post_id;
			}

			if ( !empty($product->details->post_id) ) {
				return $product->details->post_id;
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
        return Utils::hash_unique($post->{$content});

      } else {
        return Utils::hash_unique($product->{$content});

      }

    }


    /*

    Insert connection data

    */
    public function update_products($products) {

      $result = [];

      foreach ($products as $key => $product) {
        $result[] = $this->update($this->lookup_key, $product['id'], $product);
      }

      return $result;

    }


    /*

    Gets all products from a collection by collection id

    */
    public function get_products_by_collection_id($collection_id) {

      global $wpdb;

      $query = "SELECT products.* FROM " . $this->table_name . " products INNER JOIN " . $wpdb->prefix . WPS_TABLE_NAME_COLLECTS . " collects ON products.product_id = collects.product_id WHERE collects.collection_id = %d order by collects.position asc;";

      // Get the products
      $products = $wpdb->get_results(
        $wpdb->prepare($query, $collection_id)
      );

      return $products;

    }


		/*

    Default Products Query

    */
    public function get_default_products_query() {

      global $wpdb;

      return [
        'where' => '',
        'groupby' => '',
        'join' => ' INNER JOIN ' . $this->table_name . ' products ON ' .
           $wpdb->posts . '.ID = products.post_id INNER JOIN ' . $wpdb->prefix . WPS_TABLE_NAME_VARIANTS . ' variants ON products.product_id = variants.product_id AND variants.position = 1',
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
