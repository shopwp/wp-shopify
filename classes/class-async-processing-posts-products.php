<?php

namespace WPS;

use WPS\CPT;

if (!defined('ABSPATH')) {
	exit;
}


class Async_Processing_Posts_Products extends Vendor_Background_Process {

	protected $action = 'wps_background_processing_posts_products';

	protected $DB_Settings_Syncing;
	protected $DB_Products;
	protected $CPT_Query;

	public function __construct($DB_Settings_Syncing, $DB_Products, $CPT_Query) {

		$this->DB_Settings_Syncing 				= $DB_Settings_Syncing;
		$this->DB_Products 								= $DB_Products;
		$this->CPT_Query 									= $CPT_Query;

		parent::__construct();

	}


	/*

	Three scenarios could exists:
		a. Zero posts exist 															-- INSERT only
		b. less posts than data exist (new products)			-- Both UPDATE and INSERT only
		c. the same amount of posts and data exists				-- UPDATE only

	*/
	protected function task($products_from_shopify) {

		// Stops background process if syncing stops
		if ( !$this->DB_Settings_Syncing->is_syncing() ) {
			$this->complete();
			return false;
		}


		if ( !CPT::products_posts_exist() ) {

			// Final Query Results
			$result = $this->CPT_Query->insert_posts( $products_from_shopify, false, WPS_PRODUCTS_POST_TYPE_SLUG );

			if (is_wp_error($result)) {
				$this->DB_Settings_Syncing->save_notice_and_stop_sync($result);
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


			if ($total_products_posts === $total_products_to_sync) {

				$result = $this->CPT_Query->update_posts($products_to_update, WPS_PRODUCTS_POST_TYPE_SLUG);

				if (is_wp_error($result)) {
					$this->DB_Settings_Syncing->save_notice_and_stop_sync($result);
					$this->complete();
					return false;
				}


			} else {

				// Final insert_posts results
				$result_insert = $this->CPT_Query->insert_posts( $products_from_shopify, $existing_products, WPS_PRODUCTS_POST_TYPE_SLUG );

				if (is_wp_error($result_insert)) {
					$this->DB_Settings_Syncing->save_notice_and_stop_sync($result_insert);
					$this->complete();
					return false;
				}

				// Final update_posts results
				$result_update = $this->CPT_Query->update_posts($products_to_update, WPS_PRODUCTS_POST_TYPE_SLUG);

				if (is_wp_error($result_update)) {
					$this->DB_Settings_Syncing->save_notice_and_stop_sync($result_update);
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

		if ( $this->DB_Products->max_packet_size_reached($products) ) {

			$this->DB_Settings_Syncing->save_notice_and_stop_sync( $this->DB_Settings_Syncing->throw_max_allowed_packet() );
			$this->DB_Settings_Syncing->expire_sync();
			$this->complete();

		}


		$this->push_to_queue($products);
		$this->save()->dispatch();

	}


	/*

	When the background process completes ...

	*/
	protected function complete() {

		if (!$this->DB_Settings_Syncing->is_syncing() || $this->DB_Settings_Syncing->all_syncing_complete()) {
			$this->DB_Settings_Syncing->expire_sync();
		}

		parent::complete();

	}

}
