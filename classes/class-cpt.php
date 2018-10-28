<?php

namespace WPS;

use WPS\Utils;
use WPS\Transients;

if (!defined('ABSPATH')) {
	exit;
}


class CPT {

	private $DB_Settings_General;
	private $DB_Products;
	private $DB_Collections_Custom;
	private $DB_Collections_Smart;
	private $DB_Collects;
	private $DB_Tags;


	/*

	Initialize the class and set its properties.

	*/
	public function __construct($DB_Settings_General, $DB_Products, $DB_Collections_Custom, $DB_Collections_Smart, $DB_Collects, $DB_Tags) {

		$this->DB_Settings_General						= $DB_Settings_General;
		$this->DB_Products										= $DB_Products;
		$this->DB_Collections_Custom					= $DB_Collections_Custom;
		$this->DB_Collections_Smart						= $DB_Collections_Smart;
		$this->DB_Collects										= $DB_Collects;
		$this->DB_Tags												= $DB_Tags;

	}


	public static function add_meta_to_cpt($posts) {

		return array_map(function($post) {

			$post->post_meta = get_post_meta($post->ID);

			return $post;

		}, $posts);

	}


	public static function get_post_name($data) {

		if ( !empty($data->product->post_name) ) {
			$post_name = $data->product->post_name;

		} else {
			$post_name = $data->product->handle;
		}

		return $post_name;

	}


	public static function get_all_posts($type) {

		return get_posts([
			'posts_per_page' 		=> -1,
			'post_type' 				=> $type
		]);

	}


	public static function get_all_posts_by_type($type) {
		return self::add_meta_to_cpt( self::get_all_posts($type) );
	}


	public static function truncate_post_data($posts) {

		return array_map(function($post) {

			return [
				'ID'					=> $post->ID,
				'post_name'		=> $post->post_name
			];

		}, $posts );

	}


	/*

	CPT: Products

	*/
	public function post_type_products() {

		if (post_type_exists(WPS_PRODUCTS_POST_TYPE_SLUG)) {
			return;
		}

		$settings_general = $this->DB_Settings_General->get();

		// If falsey or not an object ...
		if (empty($settings_general) || !is_object($settings_general)) {
			$permalink = 'products';

		} else {
			$permalink = $settings_general->url_products;
		}


		$labels = array(
			'name'                => _x('Products', 'Post Type General Name', WPS_PLUGIN_TEXT_DOMAIN),
			'singular_name'       => _x('Product', 'Post Type Singular Name', WPS_PLUGIN_TEXT_DOMAIN),
			'menu_name'           => __('Products', WPS_PLUGIN_TEXT_DOMAIN),
			'parent_item_colon'   => __('Parent Item:', WPS_PLUGIN_TEXT_DOMAIN),
			'new_item'            => __('Add New Product', WPS_PLUGIN_TEXT_DOMAIN),
			'edit_item'           => __('Edit Product', WPS_PLUGIN_TEXT_DOMAIN),
			'not_found'           => __('No Products found', WPS_PLUGIN_TEXT_DOMAIN),
			'not_found_in_trash'  => __('No Products found in trash', WPS_PLUGIN_TEXT_DOMAIN)
		);

		$args = array(
			'label'               => __('Products', WPS_PLUGIN_TEXT_DOMAIN),
			'description'         => __('Custom Post Type for Products', WPS_PLUGIN_TEXT_DOMAIN),
			'labels'              => $labels,
			'supports'            => array('title', 'page-attributes', 'editor', 'custom-fields', 'comments', 'thumbnail'),
			'taxonomies'					=> ['category'],
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

		register_post_type(WPS_PRODUCTS_POST_TYPE_SLUG, $args);


	}


	/*

	CPT: Collections

	*/
	public function post_type_collections() {

		if ( post_type_exists(WPS_COLLECTIONS_POST_TYPE_SLUG) ) {
			return;
		}

		$settings_general = $this->DB_Settings_General->get();

		// If falsey or not an object ...
		if (empty($settings_general) || !is_object($settings_general)) {
			$permalink = 'collections';

		} else {
			$permalink = $settings_general->url_collections;
		}


		$labels = array(
			'name'                => _x('Collections', 'Post Type General Name', WPS_PLUGIN_TEXT_DOMAIN),
			'singular_name'       => _x('Collection', 'Post Type Singular Name', WPS_PLUGIN_TEXT_DOMAIN),
			'menu_name'           => __('Collections', WPS_PLUGIN_TEXT_DOMAIN),
			'parent_item_colon'   => __('Parent Item:', WPS_PLUGIN_TEXT_DOMAIN),
			'new_item'            => __('Add New Collection', WPS_PLUGIN_TEXT_DOMAIN),
			'edit_item'           => __('Edit Collection', WPS_PLUGIN_TEXT_DOMAIN),
			'not_found'           => __('No Collections found', WPS_PLUGIN_TEXT_DOMAIN),
			'not_found_in_trash'  => __('No Collections found in trash', WPS_PLUGIN_TEXT_DOMAIN)
		);

		$args = array(
			'label'               => __('Collections', WPS_PLUGIN_TEXT_DOMAIN),
			'description'         => __('Custom Post Type for Collections', WPS_PLUGIN_TEXT_DOMAIN),
			'labels'              => $labels,
			'supports'            => array('title', 'page-attributes', 'editor', 'custom-fields', 'comments', 'thumbnail'),
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

		register_post_type(WPS_COLLECTIONS_POST_TYPE_SLUG, $args);

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

		$posts = get_posts($args);

		if (is_array($posts) && empty($posts)) {
			return 1;

		} else {
			return $posts[0]->menu_order + 1;
		}

	}


	/*

	$product_or_collection = Array

	*/
	public static function find_existing_post_id($product_or_collection) {

		if (is_array($product_or_collection) && !empty($product_or_collection)) {

			$product_or_collection = Utils::get_first_array_item($product_or_collection);

			return $product_or_collection->ID;

		} else {
			return false;

		}

	}


	/*

	Returns an array containing only products / collections that match the passed in ID.

	*/
	public static function find_only_existing_posts($existing_items, $item_id, $post_type = '') {

		return array_filter($existing_items, function($existing_item) use ($item_id, $post_type) {

			if (isset($existing_item->post_meta[$post_type . '_id']) && is_array($existing_item->post_meta[$post_type . '_id'])) {
				return $existing_item->post_meta[$post_type . '_id'][0] == $item_id;
			}

		});

	}


	/*

	Adds the post ID if one exists. Used for building the product / collections model

	*/
	public static function set_post_id_if_exists($model, $existing_post_id) {

		if (!empty($existing_post_id)) {
			$model['ID'] = $existing_post_id;
		}

		return $model;

	}


	/*

	Post exists

	*/
	public static function post_exists($posts, $post_handle) {

		if ( in_array($post_handle, array_column($posts, 'post_name')) ) {
			return true;
		}

	}


	/*

	At this point the $savedCollections contains only collection IDs that are NEW.
	We need to now create the proper collects row connection

	*/
	public function add_collects_to_post($saved_collections, $product) {

		$insertion_results = [];

		foreach ($saved_collections as $new_saved_collection_id => $value) {

			$product_id = (int) $product->product_id;
			$number_string_1 = (int) substr(strval($new_saved_collection_id), 0, -4);
			$number_string_2 = (int) substr(strval($product_id), 0, -4);

			$collect = [
				'collect_id'           => $number_string_1 . $number_string_2 . 1111,
				'product_id'           => $product->product_id,
				'collection_id'        => $new_saved_collection_id,
				'featured'             => '',
				'position'             => '',
				'sort_value'           => '',
				'created_at'           => date_i18n( 'Y-m-d H:i:s' ),
				'updated_at'           => date_i18n( 'Y-m-d H:i:s' )
			];

			// Inserts any new collects
			$insertion_results[] = $this->DB_Collects->insert_collect($collect);

		}

		return $insertion_results;

	}


	/*

	At this point the $savedCollections contains only collection IDs that are NEW.
	We need to now create the proper collects row connection

	*/
	public function add_tags_to_post($saved_tags, $product) {

		$insertion_results = [];

		foreach ($saved_tags as $saved_tag => $saved_tag_value) {

			$product_id = (int) $product->product_id;
			$numberString1 = (int) ord($saved_tag);
			$numberString2 = (int) substr(strval($product_id), 0, -4);

			$product->id = $product->product_id;

			$final_tags_to_add = $this->DB_Tags->add_tag_id_to_tag( $this->DB_Tags->construct_tag_model($saved_tag, $product, $product->post_id) );

			// Inserts any new tags
			$insertion_results[] = $this->DB_Tags->insert_tag( $final_tags_to_add );

		}

		return $insertion_results;

	}



	/*

	Removing collects row from post

	*/
	public function remove_collects_from_post($collects_to_remove) {

		$removalResult = [];

		foreach ($collects_to_remove as $collect_id) {
			$removalResult[] = $this->DB_Collects->delete_rows_in($this->DB_Collects->lookup_key, $collect_id);
		}

		return $removalResult;

	}


	/*

	Removing tags row from post

	*/
	public function remove_tags_from_post($tags_to_remove) {

		$removalResult = [];

		foreach ($tags_to_remove as $key => $tag_id) {

			$removalResult[] = $this->DB_Tags->delete_rows_in($this->DB_Tags->lookup_key, $tag_id);

		}

		return $removalResult;

	}


	/*

	Fires when custom post type `wps_products` is saved / updated

	*/
	public function on_save_products($post_id, $post, $update) {

		if (function_exists('get_current_screen') && !empty(get_current_screen()) && get_current_screen()->id === WPS_PRODUCTS_POST_TYPE_SLUG) {

			$product = $this->DB_Products->get_product_from_post_id($post_id);
			$collects_results = $this->save_collects_to_post($product);
			$tags_results = $this->save_tags_to_post($product);


			/*

			Updates the product title and post content

			*/
			$title = $this->DB_Products->update_column_single(['title' => $post->post_title], ['post_id' => $post_id]);
			$body_content = $this->DB_Products->update_column_single(['body_html' => wpautop($post->post_content)], ['post_id' => $post_id]);


			/*

			Clear product cache and log errors if present

			*/
			$transientSingleProductDeletion = Transients::delete_cached_single_product_by_id($post_id);
			$transientProductQueriesDeletion = Transients::delete_cached_product_queries();

			if (is_wp_error($transientSingleProductDeletion)) {
				error_log($transientSingleProductDeletion->get_error_message());
			}

			if (is_wp_error($transientProductQueriesDeletion)) {
				error_log($transientsDeletion->get_error_message());
			}

		}


	}


	/*

	Fires when custom post type `wps_collections` is updated

	*/
	public function on_save_collections($post_id, $post, $update) {

		if (function_exists('get_current_screen') && !empty(get_current_screen()) && get_current_screen()->id === WPS_COLLECTIONS_POST_TYPE_SLUG) {

			// Update custom product table data
			$customTitle = $this->DB_Collections_Custom->update_column_single(['title' => $post->post_title], ['post_id' => $post_id]);
			$customBodyContent = $this->DB_Collections_Custom->update_column_single(['body_html' => wpautop($post->post_content)], ['post_id' => $post_id]);

			$smartTitle = $this->DB_Collections_Smart->update_column_single(['title' => $post->post_title], ['post_id' => $post_id]);
			$smartBodyContent = $this->DB_Collections_Smart->update_column_single(['body_html' => wpautop($post->post_content)], ['post_id' => $post_id]);

			// Clear product cache
			$transients_deletion = Transients::delete_cached_single_collection_by_id($post_id);

			// Log error if one exists
			if (is_wp_error($transients_deletion)) {
				error_log($transients_deletion->get_error_message());
			}

		}

	}


	/*

	Save collects to post

	*/
	public function save_collects_to_post($product) {

		$collects = $this->DB_Collects->get_collects_from_product_id($product->product_id);
		$collections = $this->establish_items_for_post('collections');

		$collects_added = $this->add_collects_to_post( $this->find_items_to_add($collects, $collections['saved']), $product);
		$collects_to_remove = $this->find_items_to_remove($collects, $collections['saved_orig']);

		$collects_removed = $this->remove_collects_from_post($collects_to_remove);

		return [
			'collects_added' => $collects_added,
			'collects_removed' => $collects_removed
		];

	}


	/*

	Creating array of collects to potentially remove

	*/
	public function find_items_to_add($current_items, $saved_items) {

		foreach ($current_items as $key => $current_item) {

			if (isset($current_item->collection_id) && $current_item->collection_id) {

				if (isset($saved_items[$current_item->collection_id])) {
					unset($saved_items[$current_item->collection_id]);
				}

			}

			if (isset($current_item->tag) && $current_item->tag) {
				if (isset($saved_items[$current_item->tag])) {
					unset($saved_items[$current_item->tag]);
				}

			}

		}

		return $saved_items;

	}


	public function found_item_to_remove($current_item_id, $saved_items) {
		return in_array($current_item_id, $saved_items, true);
	}



	/*

	Creating array of collects to potentially remove

	*/
	public function find_items_to_remove($current_items, $saved_items) {

		$current_items_orig = Utils::convert_to_assoc_array($current_items);
		$current_items_to_remove = [];


		foreach ($current_items_orig as $current_item) {

			// Collects
			if ( !empty($current_item['collection_id']) ) {

				if ( !$this->found_item_to_remove($current_item['collection_id'], $saved_items) ) {
					$current_items_to_remove[] = $current_item['collect_id'];
				}

			}

			// Tags
			if ( !empty($current_item['tag_id']) ) {

				if ( !$this->found_item_to_remove($current_item['tag_id'], $saved_items) ) {
					$current_items_to_remove[] = $current_item['tag_id'];
				}

			}

		}


		return $current_items_to_remove;

	}


	/*

	Save tags to post

	*/
	public function save_tags_to_post($product) {

		$current_tags = $this->DB_Tags->get_tags_from_post_id($product->post_id);
		$saved_tags = $this->establish_items_for_post('tags');

		$tags_to_add = $this->find_items_to_add($current_tags, $saved_tags['saved']);
		$tags_added = $this->add_tags_to_post($tags_to_add, $product);
		$tags_to_remove = $this->find_items_to_remove($current_tags, $saved_tags['saved']);


		$tags_removed = $this->remove_tags_from_post($tags_to_remove);

		return [
			'tags_added' => $tags_added,
			'tags_removed' => $tags_removed
		];

	}


	/*

	Gathering the nessesary collections data to work with

	*/
	public function establish_items_for_post($type) {

		if ( isset($_POST[$type]) && $_POST[$type] ) {
			$saved_items = $_POST[$type];
			$saved_items_orig = $_POST[$type];

		} else {

			// Should never be empty, but just incase ...
			$saved_items = [];
			$saved_items_orig = [];

		}

		return [
			'saved' 			=> $saved_items,
			'saved_orig'	=> $saved_items_orig
		];

	}


	/*

	Find the WP Post ID of the product being updated

	*/
	public static function find_existing_post_id_from_collection($existing_collections, $collection) {

		$found_post = self::find_only_existing_posts($existing_collections, $collection->{WPS_SHOPIFY_PAYLOAD_KEY}, 'collection');
		$found_post_id = self::find_existing_post_id($found_post);

		return $found_post_id;

	}


	/*

	Find the WP Post ID of the product being updated

	*/
	public static function find_existing_post_id_from_product($existing_products, $product) {

		$product_id = Utils::find_product_id($product);

		$found_post = self::find_only_existing_posts($existing_products, $product_id, 'product');
		$found_post_id = self::find_existing_post_id($found_post);

		return $found_post_id;

	}


	public static function num_of_posts($type) {

		$amounts = wp_count_posts($type);
		$amounts_total = [];

		$amounts_array = get_object_vars($amounts);
		$amounts_values_array = array_values($amounts_array);

		$total_amounts = array_reduce($amounts_values_array, function($carry, $item) {

			$carry += $item;
			return $carry;

		});

		return $total_amounts;

	}


	public static function collections_posts_exist() {

		if (self::num_of_posts(WPS_COLLECTIONS_POST_TYPE_SLUG) > 0) {
			return true;

		} else {
			return false;
		}

	}


	public static function products_posts_exist() {

		if (self::num_of_posts(WPS_PRODUCTS_POST_TYPE_SLUG) > 0) {
			return true;

		} else {
			return false;
		}

	}












	function get_shopify_featured_image() {

	}

	function has_existing_featured_image() {

	}



	function wp_get_attachment_image_attributes_filter($attr, $attachment, $size) {

		global $post;

		$media = get_attached_media('image', $post->ID);
		$post_type = get_post_type($post->ID);


		if ($post_type !== 'wps_products' && $post_type !== 'wps_collections') {
			return $attr;
		}

		return $attr;

	}


	public function post_thumbnail_html_filter($html, $post_ID, $post_thumbnail_id, $size, $attr) {

		if (!empty($html)) {
			return $html;
		}

		$post_type = get_post_type($post_ID);

		if ($post_type !== 'wps_products' && $post_type !== 'wps_collections') {
			return $html;
		}

	}


	/*

	Grabs the current author ID

	*/
	public static function return_author_id() {

		if (get_current_user_id() === 0) {
			$author_id = 1;

		} else {
			$author_id = get_current_user_id();
		}

		return intval($author_id);

	}


	/*

	Responsible for assigning a post_id to a post

	*/
	public static function set_post_id($post, $post_id) {

		$post->post_id = $post_id;

		return $post;

	}


	/*

	Hooks

	*/
	public function hooks() {

		add_action('init', [$this, 'post_type_products']);
		add_action('init', [$this, 'post_type_collections']);

		add_action('save_post_' . WPS_PRODUCTS_POST_TYPE_SLUG, [$this, 'on_save_products'], 10, 3);
		add_action('save_post_' . WPS_COLLECTIONS_POST_TYPE_SLUG, [$this, 'on_save_collections'], 10, 3);

		// add_filter( 'wp_get_attachment_image_attributes', [$this, 'wp_get_attachment_image_attributes_filter'], 10, 3 );
		add_filter( 'post_thumbnail_html', [$this, 'post_thumbnail_html_filter'], 10, 5 );

	}


	/*

	Register

	*/
	public function init() {
		$this->hooks();
	}


}
