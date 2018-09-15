<?php

namespace WPS\WS;

use WPS\Utils;
use WPS\Messages;

if (!defined('ABSPATH')) {
	exit;
}


if (!class_exists('Settings_License')) {

  class Settings_License extends \WPS\WS {

		protected $DB_Settings_License;
		protected $HTTP;

  	public function __construct($DB_Settings_License, $HTTP) {

			$this->DB_Settings_License		= $DB_Settings_License;
			$this->HTTP										= $HTTP;

    }


		/*

	  Save License Key

	  */
	  public function license_save() {

			if (!Utils::valid_backend_nonce($_POST['nonce'])) {
				$this->send_error( Messages::get('nonce_invalid') . ' (license_save)' );
			}

			$newLicenseData = [
	    	'license_key'           => isset($_POST['data']['license_key']) ? $_POST['data']['license_key'] : '',
	    	'is_local'              => isset($_POST['data']['is_local']) && $_POST['data']['is_local'] ? 1 : 0,
	    	'expires'               => isset($_POST['data']['expires']) ? date_i18n("Y-m-d H:i:s", strtotime($_POST['data']['expires'])) : '',
				'lifetime'							=> isset($_POST['data']['lifetime']) ? $_POST['data']['lifetime'] : '',
	    	'site_count'            => isset($_POST['data']['site_count']) ? $_POST['data']['site_count'] : '',
	    	'checksum'              => isset($_POST['data']['checksum']) ? $_POST['data']['checksum'] : '',
	    	'customer_email'        => isset($_POST['data']['customer_email']) ? $_POST['data']['customer_email'] : '',
	    	'customer_name'         => isset($_POST['data']['customer_name']) ? $_POST['data']['customer_name'] : '',
	    	'item_name'             => isset($_POST['data']['item_name']) ? $_POST['data']['item_name'] : '',
	    	'license'               => isset($_POST['data']['license']) ? $_POST['data']['license'] : '',
	    	'license_limit'         => isset($_POST['data']['license_limit']) ? $_POST['data']['license_limit'] : '',
	    	'payment_id'            => isset($_POST['data']['payment_id']) ? $_POST['data']['payment_id'] : '',
				'activations_left'      => isset($_POST['data']['activations_left']) ? $_POST['data']['activations_left'] : '',
	    	'success'               => isset($_POST['data']['success']) && $_POST['data']['success'] ? 1 : 0
	    ];

			$result = $this->DB_Settings_License->insert_license($newLicenseData);

			if ($result) {
				$this->send_success($newLicenseData);

			} else {
				$this->send_success(false);

			}

	  }


		/*

		Deactivate License

		*/
		public function deactivate_license($license_key) {

			if (empty($license_key)) {
				return false;
			}

			// Deletes the key locally
			$this->DB_Settings_License->truncate();

			$url = WPS_PLUGIN_ENV . '/edd-sl?edd_action=deactivate_license&item_name=' . WPS_PLUGIN_NAME_ENCODED . '&license=' . $license_key . '&url=' . home_url();

			return $this->HTTP->get($url);

		}


		/*

		Helper method

		*/
		public function delete_and_deactivate_license($license_data) {

			if (!empty($license_data) && property_exists($license_data, 'license_key')) {
				return $this->deactivate_license($license_data->license_key);
			}

		}


		/*

		Delete License Key

		*/
		public function license_delete() {

			if (!Utils::valid_backend_nonce($_POST['nonce'])) {
				$this->send_error( Messages::get('nonce_invalid') . ' (license_delete)' );
			}

			$keyDeleted = $this->delete_and_deactivate_license( $this->DB_Settings_License->get_license() );

			if ($keyDeleted) {
				$this->send_success($keyDeleted);

			} else {
				$this->send_error( Messages::get('license_unable_to_delete') . ' (license_delete)' );
			}

		}


		/*

		Save License Key
		TODO: Figure out how to check for valid nonce here

		*/
		public function license_get($ajax = true) {

			$license = $this->DB_Settings_License->get();

			if ($ajax || isset($_GET['action']) && $_GET['action'] === 'license_get') {

				if (is_object($license) && isset($license->license_key)) {
					$this->send_success($license->license_key);

				} else {
					$this->send_error( Messages::get('license_invalid_or_missing') . ' (license_get)' );
				}

			} else {
				return $license;
			}

		}


		/*

		Check for valid license key
		- Predicate function (returns boolean)

		*/
		public function has_valid_key() {

			$license = $this->license_get(false);

			if (!empty($license->license_key) && $license->license_key) {

				if ($license->license === 'valid') {
					return true;

				} else {
					return false;
				}

			} else {
				return false;

			}

		}


		/*

		Hooks

		*/
		public function hooks() {

			add_action( 'wp_ajax_license_save', [$this, 'license_save']);
			add_action( 'wp_ajax_nopriv_license_save', [$this, 'license_save']);

			add_action( 'wp_ajax_license_delete', [$this, 'license_delete']);
			add_action( 'wp_ajax_nopriv_license_delete', [$this, 'license_delete']);

			add_action( 'wp_ajax_license_get', [$this, 'license_get']);
			add_action( 'wp_ajax_nopriv_license_get', [$this, 'license_get']);

		}


		/*

		Init

		*/
		public function init() {
			$this->hooks();
		}



  }

}
