<?php

namespace WPS\WS;

use WPS\Utils;

if (!defined('ABSPATH')) {
	exit;
}

if (!class_exists('Shop')) {

  class Shop extends \WPS\WS {

		protected $DB_Settings_Connection;
		protected $DB_Settings_General;
		protected $DB_Settings_Syncing;

		protected $DB_Shop;
		protected $Messages;


  	public function __construct($DB_Settings_Connection, $DB_Settings_General, $DB_Settings_Syncing, $DB_Shop, $Messages, $Guzzle) {

			$this->DB_Settings_Connection				= $DB_Settings_Connection;
			$this->DB_Settings_General					= $DB_Settings_General;
			$this->DB_Settings_Syncing					= $DB_Settings_Syncing;

			$this->DB_Shop											= $DB_Shop;
			$this->Messages											= $Messages;

			parent::__construct($Guzzle, $Messages, $DB_Settings_Connection, $DB_Settings_General, $DB_Settings_Syncing);

    }


		/*

		Get Shop Count

		Currently hardcoded to always return 1. WP Shopify only works with one Shop at the moment.

		*/
		public function get_shop_count() {

			$connection = $this->DB_Settings_Connection->get();

			if (!Utils::valid_backend_nonce($_POST['nonce'])) {
				$this->send_error($this->Messages->message_nonce_invalid . ' (get_shop_count)');
			}

			if (Utils::emptyConnection($connection)) {
				$this->send_error($this->Messages->message_connection_not_found . ' (get_shop_count)');
			}

			$this->send_success(['shop' => 1]);

		}


		/*

		Delete Shop Data

		*/
		public function delete_shop() {

			$syncStates = $this->DB_Settings_General->selective_sync_status(); // This property was not set for users ...

			if ($syncStates['all']) {

				if ( !$this->DB_Shop->delete() ) {
					return new \WP_Error('error', $this->Messages->message_delete_shop_error . ' (delete_shop)');

				} else {
					return true;
				}

			} else {

				if ($syncStates['shop']) {

					if ( !$this->DB_Shop->delete() ) {
						return new \WP_Error('error', $this->Messages->message_delete_shop_error . ' (delete_shop)');

					} else {
						return true;
					}

				} else {
					return true;
				}

			}

		}


		/*

		Insert Shop Data

		*/
		public function insert_shop() {

			if (!Utils::valid_backend_nonce($_POST['nonce'])) {
				$this->send_error($this->Messages->message_nonce_invalid . ' (insert_shop)');
			}

			$shopData = $_POST['shopData'];

			// Actual work
			$results = $this->DB_Shop->insert_shop($shopData);
			$this->DB_Settings_Syncing->increment_current_amount('shop');


			if (empty($results)) {
				$this->send_error($this->message_connection_save_error . ' (insert_shop)');

			} else {
				$this->send_success($results);
			}


		}


		/*

	  Get Shop Data

		Doesn't save error to DB -- returns it to client

	  */
	  public function get_shop() {

			// First make sure nonce is valid
			if (!Utils::valid_backend_nonce($_GET['nonce'])) {
				$this->send_error($this->Messages->message_nonce_invalid . ' (get_shop)');
			}

			// Get shop data from Shopify
			$shop = $this->get("/admin/shop.json");

			// Check if error occured during request
			if ( is_wp_error($shop) ) {
				$this->send_error($shop->get_error_message() . ' (get_shop)');
			}

			// Fire off our async processing builds ...
			if (Utils::has($shop, 'shop')) {
				$this->send_success($shop);

			} else {

				// We can't use the plugin withtout the general Shop data so we fail hard here
				$this->send_error($this->Messages->message_missing_shop_for_page . ' (get_shop)');
			}

	  }


		/*

		Hooks

		*/
		public function hooks() {

			add_action('wp_ajax_get_shop', [$this, 'get_shop']);
			add_action('wp_ajax_nopriv_get_shop', [$this, 'get_shop']);

			add_action('wp_ajax_insert_shop', [$this, 'insert_shop']);
			add_action('wp_ajax_nopriv_insert_shop', [$this, 'insert_shop']);

			add_action('wp_ajax_get_shop_count', [$this, 'get_shop_count']);
			add_action('wp_ajax_nopriv_get_shop_count', [$this, 'get_shop_count']);

		}


		/*

		Init

		*/
		public function init() {
			$this->hooks();
		}


  }

}
