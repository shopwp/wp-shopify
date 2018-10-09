<?php

namespace WPS\DB;

use WPS\Utils;

if (!defined('ABSPATH')) {
	exit;
}


class Tags extends \WPS\DB {

	public $table_name_suffix;
	public $table_name;
	public $version;
	public $primary_key;
	public $lookup_key;
	public $cache_group;
	public $type;

	public $default_id;
	public $default_tag_id;
	public $default_product_id;
	public $default_post_id;
	public $default_tag;


	public function __construct() {

		$this->table_name_suffix  		= WPS_TABLE_NAME_TAGS;
		$this->table_name         		= $this->get_table_name();
		$this->version            		= '1.0';
		$this->primary_key        		= 'id';
		$this->lookup_key        			= 'tag_id';
		$this->cache_group        		= 'wps_db_tags';
		$this->type        						= 'tag';

		$this->default_id 						= 0;
		$this->default_tag_id 				= 0;
		$this->default_product_id 		= 0;
		$this->default_post_id 				= 0;
		$this->default_tag 						= '';

	}


	/*

	Table column name / formats

	Important: Used to determine when new columns are added

	*/
	public function get_columns() {

		return [
			'id'                    		=> '%d',
			'tag_id'										=> '%s',
			'product_id'                => '%d',
			'post_id'                   => '%d',
			'tag'                       => '%s'
		];

	}


	/*

	Table default values

	*/
	public function get_column_defaults() {

		return [
			'id'                    		=> $this->default_id,
			'tag_id'										=> $this->default_tag_id,
			'product_id'                => $this->default_product_id,
			'post_id'                   => $this->default_post_id,
			'tag'                       => $this->default_tag
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
			'prop_to_access'				=> 'tags',
			'change_type'				    => 'tag'
		];

	}


	/*

	Insert single tag

	$tag represents a data structure like this:

	{
		"product_id": 1403917533207,
		"post_id": 18353,
		"tag": "ratione"
	}

	*/
	public function insert_tag($tag) {
		return $this->insert($tag);
	}


	/*

	Deletes single tag

	*/
	public function delete_tag($tag) {
		return $this->delete_rows($this->lookup_key, $this->get_lookup_value($tag));
	}


	/*

	Gets all tags associated with a given product id

	*/
	public function get_tags_from_product_id($product_id) {
		return $this->get_rows(WPS_PRODUCTS_LOOKUP_KEY, $product_id);
	}


	/*

	Delete tags from product id

	*/
	public function delete_tags_from_product_id($product_id) {
		return $this->delete_rows(WPS_PRODUCTS_LOOKUP_KEY, $product_id);
	}


	/*

	Gets id from Shopify object

	*/
	public function get_id_from_shopify($product) {

		if (Utils::has($product, 'id')) {
			return $product->id;

		} else {
			return 0;
		}

	}


	/*

	Construct Tag Model

	*/
	public function construct_tag_model($tag, $product = 0, $post_id = 0) {

		return [
			'tag_id' 				=> 0,
			'product_id' 		=> $this->get_id_from_shopify($product),
			'post_id' 			=> $post_id,
			'tag' 					=> $tag
		];

	}


	/*

	Returns $product with modified tags array

	*/
	public function construct_tags_for_insert($product, $post_id = 0) {

		$results = [];
		$tags = Utils::comma_list_to_array($product->tags);

		foreach ($tags as $tag) {
			$results[] = Utils::convert_array_to_object( $this->add_tag_id_to_tag( $this->construct_tag_model($tag, $product, $post_id) ) );
		}

		return $results;

	}



	public function add_tags_to_product($tags, $product) {

		$product->tags = $tags;

		return $product;

	}


	/*

	Creates a unique tag ID by hashing the product ID with the tag value

	*/
	public function create_tag_id($tag) {
		return Utils::hash_static_num( $tag['product_id'] . $tag['tag'] );
	}


	/*

	Returns tag from tag object

	*/
	public function return_tag($tag_obj) {
		return $tag_obj->tag;
	}


	/*

	Returns tag_id from tag object

	*/
	public function return_tag_id($tag_obj) {
		return $tag_obj->tag_id;
	}


	/*

	Returns tag from tag object

	*/
	public function add_tag_id_to_tag($tag) {

		$tag_id_hash = $this->create_tag_id($tag);
		$tag['tag_id'] = $tag_id_hash;

		return $tag;

	}


	/*

	$tags parameter represents an array of arrays modeled from the above 'construct_tag_model'

	*/
	public function construct_only_tag_names($tags) {

		if ( empty($tags) ) {
			return [];
		}

		return array_map( [__CLASS__, 'return_tag'], $tags );

	}


	/*

	Get Product Tags

	*/
	public function get_tags_from_post_id($postID = null) {

		global $wpdb;

		if ($postID === null) {
			$postID = get_the_ID();
		}

		if (get_transient('wps_product_single_tags_' . $postID)) {
			$results = get_transient('wps_product_single_tags_' . $postID);

		} else {

			$query = "SELECT tags.* FROM " . $wpdb->prefix . WPS_TABLE_NAME_PRODUCTS . " as products INNER JOIN " . $wpdb->prefix . WPS_TABLE_NAME_TAGS . " as tags ON products.product_id = tags.product_id WHERE products.post_id = %d";

			$results = $wpdb->get_results( $wpdb->prepare($query, $postID) );

			set_transient('wps_product_single_tags_' . $postID, $results);

		}

		return $results;

	}


	/*

	Gets all unique tags

	*/
	public function get_unique_tags() {

		$tags = $this->get_all_rows();

		return array_values( array_unique( array_map( [__CLASS__, 'return_tag'], $tags) ) );

	}


	/*

	Get Tags

	*/
	public function get_tags() {
		return $this->get_all_rows();
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
			tag_id bigint(100) DEFAULT '{$this->default_tag_id}',
			product_id bigint(100) DEFAULT '{$this->default_product_id}',
			post_id bigint(100) DEFAULT '{$this->default_post_id}',
			tag varchar(255) DEFAULT '{$this->default_tag}',
			PRIMARY KEY  (id)
		) ENGINE=InnoDB $collate";

	}


}
