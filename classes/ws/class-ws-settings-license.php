<?php

namespace WPS\WS;

use WPS\Utils;

if (!defined('ABSPATH')) {
	exit;
}


if (!class_exists('Settings_License')) {


  class Settings_License extends \WPS\WS {

		protected $DB_Settings_License;
		protected $Messages;


  	public function __construct($DB_Settings_License, $Messages) {
			$this->DB_Settings_License		= $DB_Settings_License;
			$this->Messages								= $Messages;
    }


		/*

	  Save License Key

	  */
	  public function license_save() {

			if (!Utils::valid_backend_nonce($_POST['nonce'])) {
				$this->send_error($this->Messages->message_nonce_invalid . ' (license_save)');
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

		Delete License Key

		*/
		public function license_delete() {

			if (!Utils::valid_backend_nonce($_POST['nonce'])) {
				$this->send_error($this->Messages->message_nonce_invalid . ' (license_delete)');
			}

			$keyDeleted = $this->DB_Settings_License->delete_license();

			if ($keyDeleted) {
				$this->send_success($keyDeleted);

			} else {
				$this->send_error($this->Messages->message_license_unable_to_delete . ' (license_delete)');
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
					$this->send_error($this->Messages->message_license_invalid_or_missing . ' (license_get)');
				}

			} else {
				return $license;
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
