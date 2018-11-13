<?php

namespace WPS\WS;

use WPS\Utils;
use WPS\Transients;
use WPS\Messages;

if (!defined('ABSPATH')) {
	exit;
}


class Products extends \WPS\WS {

	protected $DB_Settings_General;
	protected $DB_Products;
	protected $DB_Tags;
	protected $DB_Variants;
	protected $DB_Options;
	protected $DB_Images;
	protected $CPT_Model;
	protected $Async_Processing_Posts_Products;
	protected $Async_Processing_Products;
	protected $Async_Processing_Tags;
	protected $Async_Processing_Variants;
	protected $Async_Processing_Options;
	protected $Async_Processing_Images;
	protected $DB_Settings_Syncing;
	protected $Shopify_API;

	public function __construct($DB_Settings_General, $DB_Products, $DB_Tags, $DB_Variants, $DB_Options, $DB_Images, $CPT_Model, $Async_Processing_Posts_Products, $Async_Processing_Products, $Async_Processing_Tags, $Async_Processing_Variants, $Async_Processing_Options, $Async_Processing_Images, $DB_Settings_Syncing, $Shopify_API) {

		$this->DB_Settings_General								= $DB_Settings_General;
		$this->DB_Products												= $DB_Products;
		$this->DB_Tags														= $DB_Tags;
		$this->DB_Variants												= $DB_Variants;
		$this->DB_Options													= $DB_Options;
		$this->DB_Images													= $DB_Images;
		$this->CPT_Model													= $CPT_Model;

		$this->Async_Processing_Posts_Products 		= $Async_Processing_Posts_Products;
		$this->Async_Processing_Products 					= $Async_Processing_Products;
		$this->Async_Processing_Tags 							= $Async_Processing_Tags;
		$this->Async_Processing_Variants 					= $Async_Processing_Variants;
		$this->Async_Processing_Options 					= $Async_Processing_Options;
		$this->Async_Processing_Images 						= $Async_Processing_Images;

		$this->DB_Settings_Syncing								= $DB_Settings_Syncing;
		$this->Shopify_API												= $Shopify_API;
	}




	/*

	Responsible for getting the total product count per an array of collection ids

	*/
	public function get_product_listings_count_by_collection_ids() {

		$products_count = [];
		$collection_ids = $this->DB_Settings_General->get_sync_by_collections_ids();

		foreach ($collection_ids as $collection_id) {

			$response = $this->Shopify_API->get_product_listings_count_by_collection_id($collection_id);

			if (is_wp_error($response)) {
				return $response;
			}

			if ( Utils::has($response, 'count') ) {
				$products_count[] = $response->count;
			}

		}

		return array_sum($products_count);

	}


	/*

	Get Products Count

	get_products_count

	TODO: Do we need to check for all these Exceptions?

	*/
	public function get_products_count() {

		if (!Utils::valid_backend_nonce($_POST['nonce'])) {
			$this->send_error( Messages::get('nonce_invalid') . ' (get_products_count 1)' );
		}

		/*

		If user is syncing by collections, then instead of getting the total
		number of products we need to get the total number of products
		assigned to all selected collections.

		I don't think we need anymore since implementing the recursive fetch

		*/
		if ( $this->DB_Settings_General->is_syncing_by_collection() ) {

			$products_count = $this->get_product_listings_count_by_collection_ids();

			if ( is_wp_error($products_count) ) {
				$this->DB_Settings_Syncing->save_notice_and_stop_sync($products_count);
				$this->send_error($products_count->get_error_message() . ' (get_products_count 2)');
			}

			$this->send_success( ['products' => $products_count] );

		}


		// Getting total products count instead
		$products_count = $this->Shopify_API->get_product_listings_count();


		if ( is_wp_error($products_count) ) {
			$this->DB_Settings_Syncing->save_notice_and_stop_sync($products_count);
			$this->send_error($products_count->get_error_message() . ' (get_products_count 3)');
		}



		if ( Utils::has($products_count, 'count') ) {
			$this->send_success(['products' => $products_count->count]);

		} else {
			$this->send_warning( Messages::get('products_not_found') . ' (get_products_count 4)' );

		}


	}


	/*

	Responsible for assigning the inserted post ID to the following tables:

	Tags
	Products

	*/
	public function attach_post_id_to_custom_tables($postId) {

		// $postId = Utils::wps_convert_array_to_object($_POST['postId']);
		$productId = get_post_meta($postId, 'product_id', true);
		$results = [];

		$results['wps_products'] = $this->DB_Products->update_column_single(['post_id' => $postId], ['product_id' => $productId]);
		$results['wps_tags'] = $this->DB_Tags->update_column_single(['post_id' => $postId], ['product_id' => $productId]);

		return $results;

		// $this->send_success($results);

	}


	public function stringify_ids($chunk) {
		return Utils::remove_spaces_from_string( Utils::convert_to_comma_string( $chunk ) );
	}



	public function chunk_published_product_ids($published_product_ids) {

		// TODO: Put an empty check in here
		// Essentially asks, is 'published_product_ids' col empty?
		return array_chunk($published_product_ids, WPS_MAX_ITEMS_PER_REQUEST);

	}


	// Need to subtract by 1 since current page is NOT zero indexed
	public function get_current_page_chunk($chunks, $current_page) {

		if ( isset($chunks[$current_page - 1]) ) {
			return $chunks[$current_page - 1];
		}

	}


	public function get_published_product_ids_as_param($current_page) {

		$product_id_chunks = $this->chunk_published_product_ids( $this->DB_Settings_Syncing->get_published_product_ids() );

		return $this->stringify_ids( $this->get_current_page_chunk($product_id_chunks, $current_page) );

	}




	/*

	Gets products by page

	*/
	public function get_products_per_page($current_page) {

		$param_limit = $this->DB_Settings_General->get_items_per_request();
		$param_product_ids = $this->get_published_product_ids_as_param($current_page);

		$response = $this->Shopify_API->get_products_per_page($param_product_ids, $param_limit);

		return $this->normalize_products_response($response);

	}


	/*

	Normalize the product API responses

	*/
	public function normalize_products_response($response) {

		if ( is_array($response) ) {
			return $response;
		}

		if ( is_object($response) && property_exists($response, 'products') ) {
			return $response->products;
		}

		if ( is_object($response) && property_exists($response, 'product_listings') ) {
			return $response->product_listings;
		}

	}


	/*

	Responsible for normalizing product total

	*/
	public function normalize_product_total($total_product_amount) {

		if ( is_null($total_product_amount) ) {
			// error_log('WP Shopify Warning: Total product amount is of type NULL and not Int. Returning 1 instead.');
			return 1;
		}

		if ( is_array($total_product_amount) ) {
			// error_log('WP Shopify Warning: Total product amount is of type Array and not Int. Returning 1 instead.');
			return 1;
		}

		if ( is_object($total_product_amount) ) {
			// error_log('WP Shopify Warning: Total product amount is of type Object and not Int. Returning 1 instead.');
			return 1;
		};

		if ( is_string($total_product_amount) ) {
			// error_log('WP Shopify Warning: Total product amount is of type String and not Int. Casting to Int.');
			return (int) $total_product_amount;
		}

		return $total_product_amount;

	}


	/*

	Responsible for dividing the product amount with the request limit

	*/
	public function divide_product_amount_with_limit($total_product_amount) {
		return $this->normalize_product_total($total_product_amount) / WPS_MAX_IDS_PER_REQUEST;
	}


	/*

	Responsible for determining the number of product pages to loop through

	*/
	public function find_total_pages_of_product_ids($total_product_amount) {
		return (int) ceil( $this->divide_product_amount_with_limit($total_product_amount) );
	}


	/*

	Responsible for checking whether any ids are left to fetch

	*/
	public function no_product_ids_left($prev_count) {

		if ( $prev_count < 1 ) {
			return true;
		}

		return false;

	}


	/*

	Responsible for getting an array of product ids from a list of collection ids

	*/
	public function get_product_ids_by_collection_ids() {

		$collection_ids 		= maybe_unserialize( $this->DB_Settings_General->sync_by_collections() );
		$all_product_ids 		= [];

		foreach ($collection_ids as $collection_id) {

			$collection_product_ids = $this->get_product_ids_by_collection_id($collection_id);

			if ( is_wp_error($collection_product_ids) ) {
				return $collection_product_ids;
			}

			$all_product_ids = array_merge($all_product_ids, $collection_product_ids);

		}

		return $all_product_ids;

	}


	/*

	Responsible for getting an array of product ids from a single collection id

	*/
	public function get_product_ids_by_collection_id($collection_id, $current_page = 1, $prev_count = WPS_MAX_IDS_PER_REQUEST, $combined_product_ids = []) {

		// If everything was fetched, return the main list
		if ( $this->no_product_ids_left($prev_count) ) {
			return $combined_product_ids;
		}

		$result = $this->Shopify_API->get_products_listing_product_ids_by_collection_id_per_page($collection_id, $current_page);

		if (is_wp_error($result)) {
			return $result;
		}

		$new_product_ids 					= $result->product_ids;
		$new_product_ids_count 		= count($new_product_ids);
		$new_current_page					= $current_page + 1;

		// Save the result in memory
		$combined_product_ids = array_merge($combined_product_ids, $new_product_ids);

		return $this->get_product_ids_by_collection_id( $collection_id, $new_current_page, $new_product_ids_count, $combined_product_ids);

		// return $combined_product_ids;

	}






	public function get_product_ids($current_page = 1, $prev_count = WPS_MAX_IDS_PER_REQUEST, $combined_product_ids = []) {

		// If everything was fetched, return the main list
		if ( $this->no_product_ids_left($prev_count) ) {
			return $combined_product_ids;
		}

		$result = $this->Shopify_API->get_products_listing_product_ids_per_page($current_page);


		if (is_wp_error($result)) {
			return $result;
		}

		$new_product_ids 					= $result->product_ids;
		$new_product_ids_count 		= count($new_product_ids);
		$new_current_page					= $current_page + 1;

		// Save the result in memory
		$combined_product_ids = array_merge($combined_product_ids, $new_product_ids);

		return $this->get_product_ids($new_current_page, $new_product_ids_count, $combined_product_ids);

	}









	public function get_published_product_ids_by_page() {

		// If syncing by collections ...
		if ( $this->DB_Settings_General->is_syncing_by_collection() ) {
			$product_ids = $this->get_product_ids_by_collection_ids();

		} else {
			$product_ids = $this->get_product_ids();
		}

		if ( is_wp_error($product_ids) ) {
			$this->send_error( $product_ids->get_error_message() . ' (get_product_ids_by_collection_ids)' );
		}

		$this->DB_Settings_Syncing->set_published_product_ids($product_ids);

		$this->send_success($product_ids);

	}






	public function get_products_from_collections($current_page) {

		$products 				= [];
		$collection_ids 	= maybe_unserialize( $this->DB_Settings_General->sync_by_collections() );
		$limit 						= $this->DB_Settings_General->get_items_per_request();


		foreach ($collection_ids as $collection_id) {

			$result = $this->Shopify_API->get_products_from_collection_per_page($collection_id, $limit, $current_page);
			$result = $this->normalize_products_response($result);

			if (is_wp_error($result)) {
				return $result;
			}

			$products = array_merge($products, $result);

		}

		$products = $this->DB_Variants->maybe_add_product_id_to_variants($products);

		return $products;

	}


	/*

	Get Bulk Products

	Runs for each "page" of the Shopify API

	Doesn't save error to DB -- returns to client instead

	*/
	public function get_bulk_products() {

		// First make sure nonce is valid
		if (!Utils::valid_backend_nonce($_POST['nonce'])) {
			$this->send_error( Messages::get('nonce_invalid') . ' (get_bulk_products)' );
		}

		$current_page = Utils::get_current_page($_POST);

		$products = $this->get_products_per_page( $current_page );


		// Check if error occured during request
		if ( is_wp_error($products) ) {
			$this->send_error( $products->get_error_message() . ' (get_bulk_products)' );
		}


		// Fire off our async processing builds ...
		if ( !empty($products) ) {

			$this->Async_Processing_Products->insert_products_batch($products);
			$this->Async_Processing_Variants->insert_variants_batch($products);
			$this->Async_Processing_Posts_Products->insert_posts_products_batch($products);
			$this->Async_Processing_Tags->insert_tags_batch($products);
			$this->Async_Processing_Options->insert_options_batch($products);
			$this->Async_Processing_Images->insert_images_batch($products);

			$this->send_success($products);

		} else {

			$this->DB_Settings_Syncing->save_notice( Messages::get('missing_products_for_page'), 'warning' );
			$this->send_success();

		}


	}





	public function insert_products_queue_count() {
		$this->send_success( Transients::set('wps_async_processing_products_queue_count', $_POST['queueCount']) );
	}


	/*

	Inserts a product post as CPT. $_POST['index'] used for menu_order

	TODO: Not currently used

	*/
	public function insert_product_post($product = false, $menu_order = false) {
		return $this->CPT_Model->insert_or_update_product_post($product, $menu_order);
	}


	/*

	Hooks

	*/
	public function hooks() {

		add_action('wp_ajax_insert_products_queue_count', [$this, 'insert_products_queue_count']);
		add_action('wp_ajax_nopriv_insert_products_queue_count', [$this, 'insert_products_queue_count']);

		add_action('wp_ajax_insert_product', [$this, 'insert_product']);
		add_action('wp_ajax_nopriv_insert_product', [$this, 'insert_product']);

		add_action('wp_ajax_insert_product_post', [$this, 'insert_product_post']);
		add_action('wp_ajax_nopriv_insert_product_post', [$this, 'insert_product_post']);

		add_action('wp_ajax_attach_post_id_to_custom_tables', [$this, 'attach_post_id_to_custom_tables']);
		add_action('wp_ajax_nopriv_attach_post_id_to_custom_tables', [$this, 'attach_post_id_to_custom_tables']);

		add_action('wp_ajax_get_products_count', [$this, 'get_products_count']);
		add_action('wp_ajax_nopriv_get_products_count', [$this, 'get_products_count']);

		add_action('wp_ajax_get_bulk_products', [$this, 'get_bulk_products']);
		add_action('wp_ajax_nopriv_get_bulk_products', [$this, 'get_bulk_products']);

		add_action('wp_ajax_get_published_product_ids_by_page', [$this, 'get_published_product_ids_by_page']);
		add_action('wp_ajax_nopriv_get_published_product_ids_by_page', [$this, 'get_published_product_ids_by_page']);

	}


	/*

	Init

	*/
	public function init() {
		$this->hooks();
	}


}
