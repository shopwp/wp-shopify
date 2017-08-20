<?php

namespace WPS;
require plugin_dir_path( __FILE__ ) . '../vendor/autoload.php';

use WPS\DB\Settings_License;
use GuzzleHttp\Client as Guzzle;

/*

Class Utils

*/
class License {

	protected static $instantiated = null;
	private $Config;

	/*

	Initialize the class and set its properties.

	*/
	public function __construct($Config) {

		$this->config = $Config;
		$this->plugin_version = $this->config->plugin_version;
		$this->plugin_name_full = $this->config->plugin_name_full;
		$this->plugin_name_full_encoded = $this->config->plugin_name_full_encoded;
		$this->plugin_path = $this->config->plugin_path;
		$this->plugin_root_file = $this->config->plugin_root_file;
		$this->plugin_env = $this->config->plugin_env;
		$this->license = $this->config->wps_get_settings_license();
		$this->license_option_name = $this->config->settings_license_option_name;

	}


	/*

	Creates a new class if one hasn't already been created.
	Ensures only one instance is used.

	*/
	public static function instance() {

		if (is_null(self::$instantiated)) {
			self::$instantiated = new self();
		}

		return self::$instantiated;

	}


	/*

	Validate License Key

	*/
	public function wps_license_has_existing($newKey) {

    if(isset($this->license['key']) && $this->license['key'] === $newKey) {
      return true;

    } else {
      return false;
    }

	}


  /*

  Save License Key

  */
  public function wps_license_save() {

		$Settings_License = new Settings_License();

		$isLocal = isset($_POST['is_local']) && $_POST['is_local'] ? 1 : 0;
		$success = isset($_POST['success']) && $_POST['success'] ? 1 : 0;

		$newLicenseData = array(
    	'key'                   => $_POST['key'],
    	'is_local'              => $isLocal,
    	'expires'               => date('Y-m-d H:i:s', strtotime($_POST['expires'])),
			'lifetime'							=> $_POST['lifetime'],
    	'site_count'            => $_POST['site_count'],
    	'checksum'              => $_POST['checksum'],
    	'customer_email'        => $_POST['customer_email'],
    	'customer_name'         => $_POST['customer_name'],
    	'item_name'             => $_POST['item_name'],
    	'license'               => $_POST['license'],
    	'license_limit'         => $_POST['license_limit'],
    	'payment_id'            => $_POST['payment_id'],
    	'success'               => $success
    );

		$result = $Settings_License->insert_license($newLicenseData);

		if ($result) {
			wp_send_json_success(true);

		} else {
			wp_send_json_success(false);

		}

  }


  /*

  Save License Key

  */
  public function wps_license_delete() {

		$Settings_License = new Settings_License();
		$result = $Settings_License->delete_license($_POST['key']);

		wp_send_json_success($result);

  }


	/*

  Save License Key

  */
  public function wps_license_get($ajax = true) {

		$Settings_License = new Settings_License();
		$license = $Settings_License->get();

		if ($ajax) {
			wp_send_json_success($license->key);

		} else {
			return $license;

		}

  }


	public function wps_get_latest_plugin_version() {

		$url = 'https://wpshop.io'; // TODO: Put in config

		$body = array(
			'edd_action' => 'get_version',
			'item_name'  => isset( $this->config->plugin_name ) ? $this->config->plugin_name : false,
			'item_id'    => 35, // TODO: remove hardcode
			'author'     => isset( $this->config->plugin_author ) ? $this->config->plugin_author : 'Andrew Robbins',
			'url'        => home_url(),
			'beta'       => false
		);

		$headers = array(
			'Accept' => 'application/json',
			'Content-type' => 'application/json'
		);


		try {

			$Guzzle = new Guzzle();

			$guzzelResponse = $Guzzle->post($url, [
				'query' => $body,
				'headers' => $headers
			]);

			return json_decode($guzzelResponse->getBody()->getContents());

		} catch (\Exception $e) {
			return $e->getMessage();

		}


	}


	/*

  Save License Key

  */
  public function wps_license_check_valid() {

		$Settings_License = new Settings_License();

		$license = $Settings_License->get();
		$key = $license->key;

		$api_url = $this->plugin_env . '/edd-sl?edd_action=check_license&item_name=' . $this->plugin_name_full_encoded . '&license=' . $key . '&url=' . home_url();


		try {

			$Guzzle = new Guzzle();
			$guzzelResponse = $Guzzle->get($api_url);
			$data = json_decode($guzzelResponse->getBody()->getContents());

			if ($data->license === 'valid') {
				$this->wps_activate_plugin_license($license);

			} else {
				$this->wps_deactivate_plugin_license($license);

			}

			return $data->license;


		} catch (\Exception $e) {

			return $e->getMessage();

		}


  }


	/*

	wps_activate_plugin_license

	*/
	public function wps_activate_plugin_license() {

	}


	/*

	wps_deactivate_plugin_license

	*/
	public function wps_deactivate_plugin_license() {

	}


	/*

	wps_check_for_updates

	*/
	public function wps_check_for_updates() {

		$Settings_License = new Settings_License();

		$license = $Settings_License->get();

		if (empty($license)) {
		  return;
		}


		/*

		This is the URL our updater / license checker pings. This should be the URL of the site with EDD installed
		you should use your own CONSTANT name, and be sure to replace it throughout this file

		*/
		if(!defined('EDD_SL_STORE_URL')) {
  		define( 'EDD_SL_STORE_URL', $this->plugin_env );
		}


		// The name of your product. This should match the download name in EDD exactly
		if(!defined('EDD_SAMPLE_ITEM_ID')) {
  		define( 'EDD_SAMPLE_ITEM_ID', 35 );
		}


		if( !class_exists( 'EDD_SL_Plugin_Updater' ) ) {

		  // load our custom updater
		  include( $this->plugin_path . 'vendor/EDD/EDD_SL_Plugin_Updater.php' );

		} else {

		}

		// Setup the updater
		// Calls the init() function within the constructor
		$edd_updater = new \EDD_SL_Plugin_Updater( EDD_SL_STORE_URL, $this->plugin_root_file, array(
		    'version' 			=> $this->plugin_version,
		    'license' 			=> $license->key,
		    'item_name'     => $this->plugin_name_full,
		    'item_id'     	=> EDD_SAMPLE_ITEM_ID,
		    'author' 				=> $this->plugin_name_full,
		    'url'           => home_url(),
		    'beta'          => false
		  )
		);

		return $edd_updater;

	}


	public function wps_invalid_key_notice($plugin_file, $plugin_data, $status) {

		$allowed_tags = array(
			'a' => array(
				'class' => array(),
				'href'  => array(),
				'rel'   => array(),
				'title' => array(),
			)
		);

		echo '<tr class="plugin-update-tr active update">';
		echo '<td colspan="3" class="plugin-update colspanchange">';
		echo '<div class="update-message notice inline notice-warning notice-alt">';
		echo '<p>';

		echo __(wp_kses('Please <a href="' . get_admin_url() . 'admin.php?page=wps-settings&tab=updates">activate</a> or <a href="' . $this->config->plugin_env . '">purchase</a> a license key to receive plugin updates.', $allowed_tags), $this->config->plugin_name);

		echo '</p></div></td></tr>';


	}



	public function has_valid_key() {

		$license = $this->wps_license_get(false);

		if (!empty($license->key) && $license->key) {

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

  Init

  */
	public function init() {

		/*

		Important to check this. Otherwise it can conflict with other plugins that also use EDD and
		result in a fatal error.

		*/
		if( !class_exists( 'EDD_SL_Plugin_Updater' ) ) {
			include( $this->plugin_path . 'vendor/EDD/EDD_SL_Plugin_Updater.php' );
		}

		if (is_admin()) {

			if ($this->has_valid_key()) {

				$EDD_Updater = $this->wps_check_for_updates();

			} else {

				add_filter( 'site_transient_update_plugins', function ( $value ) {

					if (is_object($value)) {
						unset( $value->response[$this->config->plugin_file] );
						return $value;
					}

				});

				/*

				TODO: Remove and modify the outputted plugin <tr> eventually

				*/
				add_filter('admin_body_class', function($classes) {
					return $classes . ' wps-is-notifying';
				});

				add_action( "after_plugin_row_" . $this->config->plugin_file, array($this, 'wps_invalid_key_notice'), 999, 3 );

			}

		}

	}

}
