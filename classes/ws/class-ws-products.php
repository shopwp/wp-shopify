<?php

namespace WPS\WS;

use WPS\Utils;
use WPS\Transients;
use WPS\CPT as CPT_Main;

use function DeepCopy\deep_copy;


if (!defined('ABSPATH')) {
	exit;
}


if (!class_exists('Products')) {

  class Products extends \WPS\WS {

		protected $DB_Settings_Connection;
		protected $DB_Settings_General;
		protected $DB_Products;
		protected $DB_Tags;
		protected $Messages;
		protected $DB_Variants;
		protected $DB_Options;
		protected $DB_Images;
		protected $CPT_Model;
		protected $WS;


  	public function __construct($DB_Settings_Connection, $DB_Settings_General, $DB_Products, $DB_Tags, $Messages, $DB_Variants, $DB_Options, $DB_Images, $Guzzle, $CPT_Model, $WS, $Async_Processing_Posts_Products, $Async_Processing_Products, $Async_Processing_Tags, $Async_Processing_Variants, $Async_Processing_Options, $Async_Processing_Images, $DB_Settings_Syncing) {

			$this->Messages														= $Messages;
			$this->DB_Settings_Connection							= $DB_Settings_Connection;
			$this->DB_Settings_General								= $DB_Settings_General;
			$this->DB_Settings_Syncing								= $DB_Settings_Syncing;

			$this->DB_Products												= $DB_Products;
			$this->DB_Tags														= $DB_Tags;
			$this->DB_Variants												= $DB_Variants;
			$this->DB_Options													= $DB_Options;
			$this->DB_Images													= $DB_Images;

			$this->CPT_Model													= $CPT_Model;
			$this->WS																	= $WS;


			$this->Async_Processing_Posts_Products 		= $Async_Processing_Posts_Products;
			$this->Async_Processing_Products 					= $Async_Processing_Products;
			$this->Async_Processing_Tags 							= $Async_Processing_Tags;
			$this->Async_Processing_Variants 					= $Async_Processing_Variants;
			$this->Async_Processing_Options 					= $Async_Processing_Options;
			$this->Async_Processing_Images 						= $Async_Processing_Images;



			parent::__construct($Guzzle, $Messages, $DB_Settings_Connection, $DB_Settings_General, $DB_Settings_Syncing);

    }


		/*

	  Delete_products

	  */
	  public function delete_products() {

			$syncStates = $this->DB_Settings_General->selective_sync_status();

			if ($syncStates['all']) {

				if (!$this->DB_Products->delete()) {
					return new \WP_Error('error', $this->Messages->message_delete_products_error . ' (delete_products)');

				} else {
					return true;
				}

			} else {

				if ($syncStates['products']) {

					if (!$this->DB_Products->delete()) {
						return new \WP_Error('error', $this->Messages->message_delete_products_error . ' (delete_products 2)');

					} else {
						return true;
					}

				} else {
					return true;
				}

			}

	  }


		/*

		Get Products Count

		get_products_count

		TODO: Do we need to check for all these Exceptions?

		*/
		public function get_products_count() {

			if (!Utils::valid_backend_nonce($_POST['nonce'])) {
				$this->send_error($this->Messages->message_nonce_invalid . ' (get_products_count)');
			}

			// User is syncing by collection
			if ($this->DB_Settings_General->is_syncing_by_collection()) {

				$urls = $this->construct_sync_by_collections_count_url('products');
				$products_count = $this->get_counts_from_urls($urls);

				$this->send_success(['products' => $products_count]);

			} else {
				
				$products = $this->get("/admin/products/count.json");

				if ( is_wp_error($products) ) {
					$this->WS->save_notice_and_stop_sync($products);
					$this->send_error($products->get_error_message() . ' (get_products_count)');
				}

				if (Utils::has($products, 'count')) {
					$this->send_success(['products' => $products->count]);

				} else {
					$this->send_warning($this->Messages->message_products_not_found . ' (get_products_count)');
				}

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


		/*

		Gets products by page

		*/
		public function get_products_by_page($currentPage) {
			return $this->get("/admin/products.json", "?limit=250&page=" . $currentPage);
		}


		public function get_products_by_collection_and_page($products_url_param) {
			return $this->get("/admin/products.json", $products_url_param);
		}








		public function get_products_by_collections_page($products_url_params) {

			$products = [];

			foreach ($products_url_params as $product_url_param) {

				$result = $this->get_products_by_collection_and_page($product_url_param)->products;

				if (is_wp_error($result)) {
					return $result;

				} else {
					$products[] = $result;
				}

			}

			return $products;

		}


















		/*

		Get Bulk Products

		Runs for each "page" of the Shopify API (250 per page)

		Doesn't save error to DB -- returns to client instead

		*/
		public function get_bulk_products() {

			// First make sure nonce is valid
			if (!Utils::valid_backend_nonce($_POST['nonce'])) {
				$this->send_error($this->Messages->message_nonce_invalid . ' (get_products_count)');
			}

			// Check if user is syncing from collections -- returns proper products
			if ($this->DB_Settings_General->is_syncing_by_collection()) {

				$collection_ids = maybe_unserialize($this->DB_Settings_General->sync_by_collections());
				$products_url_params = $this->construct_sync_by_collections_api_urls($collection_ids, Utils::get_current_page($_POST));

				$products = $this->get_products_by_collections_page($products_url_params);
				$products = $this->flatten_data_from_sync_by_collections($products, 'products');

			} else {
				$products = $this->get_products_by_page( Utils::get_current_page($_POST) );
			}

			// Check if error occured during request
			if ( is_wp_error($products) ) {
				$this->send_error($products->get_error_message() . ' (get_bulk_products)');
			}


			// Fire off our async processing builds ...
			if (Utils::has($products, 'products')) {

				$products_clone = deep_copy($products);
				$variants_clone = deep_copy($products);

				$this->Async_Processing_Products->insert_products_batch($products_clone->products);
				$this->Async_Processing_Posts_Products->insert_posts_products_batch($products->products);
				$this->Async_Processing_Tags->insert_tags_batch($products->products);
				$this->Async_Processing_Variants->insert_variants_batch($variants_clone->products);
				$this->Async_Processing_Options->insert_options_batch($products->products);
				$this->Async_Processing_Images->insert_images_batch($products->products);

				$this->send_success($products->products);

			} else {

				$this->DB_Settings_Syncing->save_notice($this->Messages->message_missing_products_for_page, 'warning');
				$this->send_success();

			}



		}





		public function insert_products_queue_count() {
			$this->send_success( Transients::set('wps_async_processing_products_queue_count', $_POST['queueCount']) );
		}


		/*

		Inserts a product post as CPT. $_POST['index'] used for menu_order

		*/
		public function insert_product_post($all_products, $product = false, $menu_order = false) {
			return $this->CPT_Model->insert_or_update_product($all_products, $product, $menu_order);
		}





		/*

	  Get products from collection

	  */
	  public function get_products_from_collection() {

			if (!Utils::valid_backend_nonce($_POST['nonce'])) {
				$this->send_error($this->Messages->message_nonce_invalid . ' (get_products_from_collection)');
			}

			if (Utils::emptyConnection($connection)) {
				$this->send_error($this->Messages->message_connection_not_found . ' (get_products_from_collection)');
			}


			$products = $this->get("/admin/products.json", "?collection_id=" . $collectionID);

			if ( is_wp_error($products) ) {
				$this->send_error($products->get_error_message() . ' (get_products_from_collection)');
			}

			if (Utils::has($products, 'products')) {
				$this->send_success($products->products);

			} else {
				$this->send_warning($this->Messages->message_products_from_collection_not_found . ' (get_products_from_collection)');
			}


	  }


		/*

		Hooks

		*/
		public function hooks() {

			add_action('wp_ajax_get_products_from_collection', [$this, 'get_products_from_collection']);
			add_action('wp_ajax_nopriv_get_products_from_collection', [$this, 'get_products_from_collection']);

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

		}


		/*

		Init

		*/
		public function init() {
			$this->hooks();
		}


  }


}
