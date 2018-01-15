<?php

namespace WPS;
require plugin_dir_path( __FILE__ ) . '../vendor/autoload.php';


use WPS\DB\Settings_License;
use WPS\Messages;
use WPS\WS;
use GuzzleHttp\Client as Guzzle;

// If this file is called directly, abort.
if (!defined('ABSPATH')) {
	exit;
}

/*

Class Utils

*/
class License {

	protected static $instantiated = null;
	private $Config;
	private $messages;

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
		$this->messages = new Messages();
		$this->WS = new WS($this->config);
	}


	/*

	Creates a new class if one hasn't already been created.
	Ensures only one instance is used.

	*/
	public static function instance($Config) {

		if (is_null(self::$instantiated)) {
			self::$instantiated = new self($Config);
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

		Utils::valid_backend_nonce($_POST['nonce']) ?: wp_send_json_error($this->messages->message_nonce_invalid . ' (code: #1053a)');

		$Settings_License = new Settings_License();

		$newLicenseData = array(
    	'key'                   => isset($_POST['key']) ? $_POST['key'] : '',
    	'is_local'              => isset($_POST['is_local']) && $_POST['is_local'] ? 1 : 0,
    	'expires'               => isset($_POST['expires']) ? date_i18n("Y-m-d H:i:s", strtotime($_POST['expires'])) : '',
			'lifetime'							=> isset($_POST['lifetime']) ? $_POST['lifetime'] : '',
    	'site_count'            => isset($_POST['site_count']) ? $_POST['site_count'] : '',
    	'checksum'              => isset($_POST['checksum']) ? $_POST['checksum'] : '',
    	'customer_email'        => isset($_POST['customer_email']) ? $_POST['customer_email'] : '',
    	'customer_name'         => isset($_POST['customer_name']) ? $_POST['customer_name'] : '',
    	'item_name'             => isset($_POST['item_name']) ? $_POST['item_name'] : '',
    	'license'               => isset($_POST['license']) ? $_POST['license'] : '',
    	'license_limit'         => isset($_POST['license_limit']) ? $_POST['license_limit'] : '',
    	'payment_id'            => isset($_POST['payment_id']) ? $_POST['payment_id'] : '',
			'activations_left'      => isset($_POST['activations_left']) ? $_POST['activations_left'] : '',
    	'success'               => isset($_POST['success']) && $_POST['success'] ? 1 : 0
    );

		$result = $Settings_License->insert_license($newLicenseData);

		if ($result) {
			wp_send_json_success($newLicenseData);

		} else {
			wp_send_json_success(false);

		}

  }


  /*

  Delete License Key

  */
  public function wps_license_delete() {

		Utils::valid_backend_nonce($_POST['nonce']) ?: wp_send_json_error($this->messages->message_nonce_invalid . ' (code: #1054a)');

		$Settings_License = new Settings_License();
		$keyDeleted = $Settings_License->delete_license();

		if ($keyDeleted) {
			wp_send_json_success($keyDeleted);

		} else {
			wp_send_json_error($this->messages->message_license_unable_to_delete . ' (code: #1081a)');
		}



  }


	/*

  Save License Key
	TODO: Figure out how to check for valid nonce here

  */
  public function wps_license_get($ajax = true) {

		$Settings_License = new Settings_License();
		$license = $Settings_License->get();

		if ($ajax || isset($_GET['action']) && $_GET['action'] === 'wps_license_get') {

			if (is_object($license) && isset($license->key)) {
				wp_send_json_success($license->key);

			} else {
				wp_send_json_error($this->messages->message_license_invalid_or_missing . ' (code: #1080a)');
			}

		} else {
			return $license;
		}

  }


	/*

	Get the latest plugin version

	*/
	public function wps_get_latest_plugin_version() {

		$WS = new WS($this->config);

		$body = [
			'query' => [
				'edd_action' => 'get_version',
				'item_id'    => 35
			]
		];

		$headers = [
			'Content-type' => 'application/json'
		];

		try {

			$response = $WS->wps_request(
				'POST',
				$this->config->plugin_env,
				$this->WS->get_request_options($headers, $body, false)
			);

			return json_decode($response->getBody()->getContents());

		} catch (\Exception $e) {

			return $e->getMessage() . ' (code: #1056a)';

		}


	}


	/*

  Save License Key
	TODO: Not used?

  */
  public function wps_license_check_valid() {

		$Settings_License = new Settings_License();

		$license = $Settings_License->get();
		$key = $license->key;

		$url = $this->plugin_env . '/edd-sl?edd_action=check_license&item_name=' . $this->plugin_name_full_encoded . '&license=' . $key . '&url=' . home_url();

		try {

			$response = $this->WS->wps_request(
				'GET',
				$url,
				[]
			);

			$data = json_decode($response->getBody()->getContents());

			if ($data->license === 'valid') {
				$this->wps_activate_plugin_license($license);
			}

			return $data->license;

		} catch (\Exception $e) {

			return $e->getMessage() . ' (code: #1057a)';

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

		$Settings_License = new Settings_License();
		$license = $Settings_License->get();

		if (empty($license)) {
			return;

		} else {
			$key = $license->key;

			// Deletes key locally
			$Settings_License->delete_license();

			$url = $this->plugin_env . '/edd-sl?edd_action=deactivate_license&item_name=' . $this->plugin_name_full_encoded . '&license=' . $key . '&url=' . home_url();

			try {

				$promise = $this->WS->wps_request(
					'GET',
					$url,
					[],
					true
				);

				$promise->then(function ($response) {

					$data = json_decode($response->getBody()->getContents());
					return $data;

				});



			} catch (\Exception $e) {

				return new WP_Error('error', $this->messages->message_license_unable_to_delete . ' (code: #1088a)');

			}

		}

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

		// load our custom updater
		if( !class_exists( 'EDD_SL_Plugin_Updater' ) ) {

		  include( $this->plugin_path . 'vendor/EDD/EDD_SL_Plugin_Updater.php' );

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


	/*

	Invalid key notice

	*/
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
		printf(__('Please <a href="%1$sadmin.php?page=wps-settings&tab=updates">activate</a> or <a href="%2$s" target="_blank">purchase</a> a license key to receive plugin updates.', 'wp-shopify'), esc_url(get_admin_url()), esc_url($this->config->plugin_env . '/purchase'));
		echo '</p></div></td></tr>';

	}


	/*

	Check for valid license key
	- Predicate function (returns boolean)

	*/
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

				add_filter( 'site_transient_update_plugins', function ($value) {

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
