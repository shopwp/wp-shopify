<?php

namespace WPS;

use WPS\Utils;
use WPS\CPT;

if (!defined('ABSPATH')) {
	exit;
}


class CPT_Model {

	private $DB_Settings_General;

	/*

	Initialize the class and set its properties.

	*/
	public function __construct($DB_Settings_General) {
		$this->DB_Settings_General = $DB_Settings_General;
	}


	/*

	Returns a collections post model with collection id assigned as meta_value

	TODO: Can't we just add the collection_id to the meta_input field like this?

	'meta_input' => [
		'collection_id' => property_exists($collection, 'id') ? $collection->id : ''
	]

	*/
	public function set_collection_model_defaults($collection) {

		$collection = Utils::convert_array_to_object($collection);

		return [
			'post_title'    => property_exists($collection, 'title') ? __($collection->title) : '',
			'post_content'  => property_exists($collection, 'body_html') && $collection->body_html !== null  ? __($collection->body_html) : '',
			'post_status'   => 'publish',
			'post_author'		=> CPT::return_author_id(),
			'post_type'     => WPS_COLLECTIONS_POST_TYPE_SLUG,
			'post_name'			=> property_exists($collection, 'handle') ? sanitize_title($collection->handle) : '',
			'meta_input' => [
				'collection_id' => property_exists($collection, 'id') ? $collection->id : ''
			]
		];

	}


	/*

	Returns a model used to either add or update a product CPT

	TODO: Can't we just add the product_id to the meta_input field like this?

	*/
	public function set_product_model_defaults($product) {

		$product = Utils::convert_array_to_object($product);

		return [
			'post_title'    => property_exists($product, 'title') ? __($product->title) : '',
			'post_content'  => property_exists($product, 'body_html') && $product->body_html !== null ? __($product->body_html) : '',
			'post_status'   => 'publish',
			'post_type'     => WPS_PRODUCTS_POST_TYPE_SLUG,
			'post_name'			=> property_exists($product, 'handle') ? sanitize_title($product->handle) : '',
			'meta_input' => [
				'product_id' => property_exists($product, 'id') ? $product->id : ''
			]
		];

	}


	/*

	Finds existing menu order by post ID

	*/
	public function get_existing_menu_order_by_post_id($existing_post_id) {

		// Use the current menu order number instead
		$post = get_post($existing_post_id);

		if (is_object($post) && isset($post->menu_order)) {
			return $post->menu_order;
		}

	}


	/*

	We have access to an $menu_order variable if this function is called
	by a full sync. Otherwise this function is called via a webhook like
	update or add. In this case we need to find the highest index.

	*/
	public function set_menu_order($post_model, $menu_order) {

		if (empty($menu_order)) {
			$post_model['menu_order'] = 0;

		} else {
			$post_model['menu_order'] = $menu_order;

		}

		return $post_model;

	}


	/*

	Checks for an existing collection post ID. If found, the collection post
	will be updated instead of created.

	Can't be 0 or '0' either

	*/
	public function set_existing_collection_post_id($collection_model, $all_collections, $collection) {

		$existing_post_id = CPT::find_existing_post_id_from_collection($all_collections, $collection);

		return CPT::set_post_id_if_exists($collection_model, $existing_post_id);

	}


	/*

	Checks for an existing collection post ID. If found, the collection post
	will be updated instead of created.

	Can't be 0 or '0' either

	*/
	public function set_existing_product_post_id($all_products, $product_model, $product) {

		$existing_post_id = CPT::find_existing_post_id_from_product($all_products, $product);

		return CPT::set_post_id_if_exists($product_model, $existing_post_id);

	}


	/*

	Wrapper function used during a collection post update

	*/
	public function build_collections_model_for_update($all_collections, $collection, $menu_order = 0) {

		$collection_model = $this->set_collection_model_defaults($collection);
		$collection_model = $this->set_existing_collection_post_id($collection_model, $all_collections, $collection);

		return $collection_model;

	}


	/*

	Wrapper function used during a product post update

	*/
	public function build_products_model_for_update($all_products, $product, $menu_order = 0) {

		$product_model = $this->set_product_model_defaults($product);
		$product_model = $this->set_existing_product_post_id($all_products, $product_model, $product);
		// $product_model = $this->set_menu_order($product_model, $menu_order);

		return $product_model;

	}


	/*

	Adds New CPT Product into DB
	Don't put expensive operations inside as this function gets called within loops.

	Called in class-db-products.php

	Requires $productModel to be an array

	Inserts post and returns the ID or error object if fail.

	Only currently used in webhooks

	*/

	public function insert_or_update_product_post($product, $menu_order = 0) {

		$all_products = CPT::get_all_posts_by_type(WPS_PRODUCTS_POST_TYPE_SLUG);

		$model = $this->build_products_model_for_update($all_products, $product, $menu_order);
		return wp_insert_post($model, true);

	}


	/*

	Insert or update collection post
	$product, $existingProducts, $index = false

	*/
	public function insert_or_update_collection_post($collection, $menu_order = 0) {

		$all_collections = CPT::get_all_posts_by_type(WPS_COLLECTIONS_POST_TYPE_SLUG);

		$model = $this->build_collections_model_for_update($all_collections, $collection, $menu_order);
		return wp_insert_post($model, true);

	}


}
