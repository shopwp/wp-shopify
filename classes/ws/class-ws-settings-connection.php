<?php

namespace WPS\WS;

use WPS\Utils;

if (!defined('ABSPATH')) {
	exit;
}


if (!class_exists('Settings_Connection')) {


  class Settings_Connection extends \WPS\WS {

		protected $DB_Settings_Connection;
		protected $Messages;
		protected $DB_Settings_General;

  	public function __construct($DB_Settings_Connection, $Messages, $Guzzle, $DB_Settings_General) {

			$this->DB_Settings_Connection				= $DB_Settings_Connection;
			$this->Messages											= $Messages;
			$this->Guzzle												= $Guzzle;
			$this->DB_Settings_General					= $DB_Settings_General;

    }


		/*

		Delete the config data
		TODO: Support multiple connections by making connection ID dynamic?

		*/
		public function delete_settings_connection() {

			if ( !$this->DB_Settings_Connection->delete() ) {
				return new \WP_Error('error', $this->Messages->message_delete_connection_error . ' (delete_settings_connection)');

			} else {
				return true;
			}

		}


		/*

	  Reset rewrite rules on CTP url change

	  */
	  public function get_connection() {

			if (!Utils::valid_backend_nonce($_GET['nonce'])) {
			  $this->send_error($this->Messages->message_nonce_invalid . ' (get_connection)');
			}

	    if (get_transient('wps_settings_connection')) {
	      $connectionData = get_transient('wps_settings_connection');

	    } else {

	      $connectionData = $this->DB_Settings_Connection->get();
	      set_transient('wps_settings_connection', $connectionData);

	    }

	    $this->send_success($connectionData);

	  }


		/*

		Reset rewrite rules on CTP url change

		*/
		public function remove_connection() {

			if (!Utils::valid_backend_nonce($_POST['nonce'])) {
			  $this->send_error($this->Messages->message_nonce_invalid . ' (remove_connection)');
			}

			$response_connection_settings = $this->delete_settings_connection();
			$this->DB_Settings_General->reset_sync_by_collections();

			if (is_wp_error($response_connection_settings)) {
				$this->send_error($response_connection_settings->get_error_message()  . ' (remove_connection)');

			} else {
				$this->send_success($response_connection_settings);
			}

		}


	  /*

	  Insert connection data

		Called from

	  */
	  public function save_connection() {

			if (!Utils::valid_backend_nonce($_POST['nonce'])) {
				$this->send_error($this->Messages->message_nonce_invalid . ' (save_connection)');
			}

			$data_to_send_back = [];

	    $connectionData = $_POST['connectionData'];
	    $connectionData = (array) $connectionData;


			// Will always equal WP_Error if something goes wrong here
	    $insert_result = $this->DB_Settings_Connection->insert_connection($connectionData);

			$data_to_send_back['insert_connection'] = $insert_result;
			$data_to_send_back['save_connection_only'] = $this->DB_Settings_General->save_connection_only();


			if (is_wp_error($insert_result)) {
	      $this->send_error($this->Messages->message_connection_save_error . ' (save_connection)');

	    } else {
	      $this->send_success($data_to_send_back);
	    }

	  }





		/*

		Getting and sending application credentials to front-end.

		These credentials do not need to be secured and can be stored on the client-side to
		improve performance.

		*/
	 	public function get_shopify_creds() {

			$connection = $this->DB_Settings_Connection->get();
			$shopifyCreds = [];

			if (is_object($connection) && isset($connection->js_access_token)) {
				$shopifyCreds['js_access_token'] = $connection->js_access_token;
			}

			if (is_object($connection) && isset($connection->app_id)) {
				$shopifyCreds['app_id'] = $connection->app_id;
			}

			if (is_object($connection) && isset($connection->domain)) {
				$shopifyCreds['domain'] = $connection->domain;
			}

			$this->send_success($shopifyCreds);

		}



		public function check_valid_server_connection() {

			$active_connection = $this->has_valid_server_connection();

			if ( is_wp_error($active_connection) ) {
				$this->send_error($active_connection->get_error_message() . ' (check_valid_server_connection)');
			}

			$this->send_success();

		}



		public function check_connection() {

			$storefront_tokens = $this->get('/admin/storefront_access_tokens.json');

			if ( is_wp_error($storefront_tokens) ) {
				$this->send_error($storefront_tokens->get_error_message() . ' (check_connection)');
			}

			$this->send_success();

		}



		/*

		Hooks

		*/
		public function hooks() {

			add_action('wp_ajax_save_connection', [$this, 'save_connection']);
			add_action('wp_ajax_nopriv_save_connection', [$this, 'save_connection']);

			add_action('wp_ajax_get_connection', [$this, 'get_connection']);
			add_action('wp_ajax_nopriv_get_connection', [$this, 'get_connection']);

			add_action('wp_ajax_remove_connection', [$this, 'remove_connection']);
			add_action('wp_ajax_nopriv_remove_connection', [$this, 'remove_connection']);

			add_action('wp_ajax_get_shopify_creds', [$this, 'get_shopify_creds']);
			add_action('wp_ajax_nopriv_get_shopify_creds', [$this, 'get_shopify_creds']);

			add_action('wp_ajax_check_connection', [$this, 'check_connection']);
			add_action('wp_ajax_nopriv_check_connection', [$this, 'check_connection']);

			add_action('wp_ajax_check_valid_server_connection', [$this, 'check_valid_server_connection']);
			add_action('wp_ajax_nopriv_check_valid_server_connection', [$this, 'check_valid_server_connection']);

		}


		/*

		Init

		*/
		public function init() {
			$this->hooks();
		}


  }

}
