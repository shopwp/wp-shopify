<?php

namespace WPS;

use WPS\CPT;

if (!defined('ABSPATH')) {
	exit;
}

if ( !class_exists('Async_Processing_Posts_Products') ) {

  class Async_Processing_Posts_Products extends WP_Shopify_Background_Process {

		protected $action = 'wps_background_processing_posts_products';

		protected $DB_Settings_Syncing;
		protected $WS;
		protected $DB_Products;


		public function __construct($DB_Settings_Syncing, $WS, $DB_Products, $CPT_Query) {

			$this->DB_Settings_Syncing 				= $DB_Settings_Syncing;
			$this->WS 												= $WS;
			$this->DB_Products 								= $DB_Products;
			$this->CPT_Query 									= $CPT_Query;


			parent::__construct();

		}


		/*

		Override this method to perform any actions required during the async request.

		*/
		protected function task($products_from_shopify) {

			// Stops background process if syncing stops
			if ( !$this->DB_Settings_Syncing->is_syncing() ) {
				$this->complete();
				return false;
			}


			global $wpdb;

			if ( !CPT::products_posts_exist() ) {

				$insert_query = $this->CPT_Query->construct_posts_insert_query($products_from_shopify, false, WPS_PRODUCTS_POST_TYPE_SLUG);

				// Final Query
				$result = $this->CPT_Query->query($insert_query, 'products');

				if (is_wp_error($result)) {
					$this->WS->save_notice_and_stop_sync($result);
					$this->complete();
					return false;
				}

			} else {

				$total_products_posts = CPT::num_of_posts(WPS_PRODUCTS_POST_TYPE_SLUG);

				$total_products_to_sync = $this->DB_Settings_Syncing->syncing_totals_products_actual();


				/*

				Step 2. Find the current post IDs and post_name (slugs)

				*/
				$existing_products = CPT::truncate_post_data( CPT::get_all_posts(WPS_PRODUCTS_POST_TYPE_SLUG) );


				/*

				Step 3. Now we need to filter the list of products from the DB down to only
				reflect the current batch. We do this by filtering the array by the post name

				*/

				$products_to_update = $this->CPT_Query->find_posts_to_update($products_from_shopify, $existing_products);


				/*

				Step 3.

				Three scenarios could exists:
					a. Zero posts exist 															-- INSERT only
					b. less posts than data exist (new products)		-- Both UPDATE and INSERT only
					c. the same amount of posts and data exists				-- UPDATE only

				*/



				/*

				If the program flow falls here, we need only need to perform an update

				*/
				if ($total_products_posts === $total_products_to_sync) {

					$posts_to_update_formated = $this->CPT_Query->format_posts_for_update($products_to_update, WPS_PRODUCTS_POST_TYPE_SLUG);
					$final_update_query = $this->CPT_Query->construct_posts_update_query($posts_to_update_formated);


					// Final Query
					$result = $this->CPT_Query->query($final_update_query, 'products');

					if (is_wp_error($result)) {
						$this->WS->save_notice_and_stop_sync($result);
						$this->complete();
						return false;
					}


				} else {

					/*

					If the program flow falls here, we need to perform both an insert and an update

					*/

					$insert_query = $this->CPT_Query->construct_posts_insert_query($products_from_shopify, $existing_products, WPS_PRODUCTS_POST_TYPE_SLUG);


					// Final Query
					$result_insert = $this->CPT_Query->query($insert_query, 'products');

					if (is_wp_error($result_insert)) {
						$this->WS->save_notice_and_stop_sync($result_insert);
						$this->complete();
						return false;
					}


					$posts_to_update = $this->CPT_Query->format_posts_for_update($products_to_update, WPS_PRODUCTS_POST_TYPE_SLUG);
					$final_update_query = $this->CPT_Query->construct_posts_update_query($posts_to_update);



					// Final Query
					$result_update = $this->CPT_Query->query($final_update_query, 'products');

					if (is_wp_error($result_update)) {
						$this->WS->save_notice_and_stop_sync($result_update);
						$this->complete();
						return false;
					}


				}

			}

			return false;

		}


		protected function after_queue_item_removal($products_from_shopify) {
			$this->DB_Settings_Syncing->increment_current_amount('products', count($products_from_shopify));
		}


		public function insert_posts_products_batch($products) {

			$this->push_to_queue($products);
			$this->save()->dispatch();

		}


		/*

		When the background process completes ...

		*/
		protected function complete() {

			if (!$this->DB_Settings_Syncing->is_syncing() || $this->DB_Settings_Syncing->all_syncing_complete()) {
				$this->WS->expire_sync();
			}

			parent::complete();

		}

  }

}
