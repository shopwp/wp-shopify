<?php

namespace WPS;

use WPS\Utils;
use WPS\AJAX;
use WPS\License;
use WPS\Waypoints;
use WPS\Collections;
use WPS\Products_General;
use WPS\Webhooks;
use WPS\WS;

use WPS\DB\Shop;
use WPS\DB\Settings_Connection;

/*

Admin Class

*/
class Backend {

	protected static $instantiated = null;

	private $Config;

	/*

	Initialize the class and set its properties.

	*/
	public function __construct($Config) {
		$this->config = $Config;
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

	Admin styles

	*/
	public function wps_config_admin_styles() {


		// Only loading styles if we're on the settings page ...
		if('wp-shopify_page_wps-settings' == get_current_screen()->id || get_current_screen()->id === 'wps_products' || get_current_screen()->id === 'wps_collections' || get_current_screen()->id === 'plugins') {

			// Color Picker
			wp_enqueue_style('wp-color-picker');

			// PACE CSS
			// wp_enqueue_style('pace-css', $Config->plugin_path . 'css/app/pace.min.css', array(), $Config->plugin_version, 'all');

			// Animate CSS
			wp_enqueue_style('animate-css', '//cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css', array());

			// WP Shopify Admin styles
			wp_enqueue_style($this->config->plugin_name, $this->config->plugin_url . 'css/admin.min.css', array( 'wp-color-picker'), $this->config->plugin_version, 'all');

		}

	}


	/*

	Admin scripts

	*/
	public function wps_config_admin_scripts() {

		// $this->config = new Config();

		// Only loading admin script if we're on the settings page ...
		if('wp-shopify_page_wps-settings' == get_current_screen()->id || get_current_screen()->id === 'wps_products'  || get_current_screen()->id === 'wps_collections') {

			// setcookie("wps-progress", 0);


			// Media scripts
			wp_enqueue_media();

			// Shopify JS SDK
			wp_enqueue_script('shopify-js-sdk', '//sdks.shopifycdn.com/js-buy-sdk/latest/shopify-buy.polyfilled.globals.min.js', array(), $this->config->plugin_version, true );

			// jQuery Validate
			wp_enqueue_script('validate-js', '//cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.15.0/jquery.validate.min.js', array('jquery'), $this->config->plugin_version, true );


			// // TEST JS
			// wp_enqueue_script( 'test-js', $this->config->plugin_path . 'js/app/vendor/test.js', array(), $this->config->plugin_version, false );

			//
			// // PACE JS
			// wp_enqueue_script( 'pace-js', $this->config->plugin_path . 'js/app/vendor/pace.min.js', array(), $this->config->plugin_version, false );

			// WP Shopify JS Vendor
			// wp_enqueue_script('wps-admin-vendor', $this->config->plugin_url . 'dist/vendor.min.js', array(), $this->config->plugin_version, false );

			// WP Shopify JS Admin
			wp_enqueue_script('wps-admin', $this->config->plugin_url . 'dist/admin.min.js', array('shopify-js-sdk', 'validate-js'), $this->config->plugin_version, true );

			wp_localize_script('wps-admin', 'wps', array(
					'ajax' => admin_url( 'admin-ajax.php' ),
					'pluginsPath' => plugins_url()
				)
			);

		}

	}


	/*

	Registering the admin menu into the WordPress Dashboard menu.
	Adding a settings page to the Settings menu.

	*/
	public function wps_config_add_plugin_menu() {

		global $submenu;

		// Main menu
		add_menu_page( 'WP Shopify', 'WP Shopify', 'manage_options', $this->config->plugin_name, array($this, 'wps_config_display_setup_page' ), '');

		// Submenu
		add_submenu_page( $this->config->plugin_name, 'Settings', 'Settings', 'manage_options', $this->config->plugin_name.'-settings', array($this, 'wps_config_display_setup_page') );

		// Submenu
		add_submenu_page( $this->config->plugin_name, 'Products', 'Products', 'manage_options', 'edit.php?post_type=wps_products', null );

		// Submenu
		add_submenu_page( $this->config->plugin_name, 'Collections', 'Collections', 'manage_options', 'edit.php?post_type=wps_collections', null );

		unset($submenu[$this->config->plugin_name][0]);

	}


	/*

	Add settings action link to the plugins page.

	*/
	public function wps_config_add_action_links($links) {

		// $this->config = new Config();
		$settings_link = ['<a href="' . admin_url('/admin.php?page=' . $this->config->plugin_name) . '-settings">' . __('Settings', $this->config->plugin_name) . '</a>'];

		return array_merge($settings_link, $links);

	}


	/*

	Render the settings page for this plugin.

	*/
	public function wps_config_display_setup_page() {

		include_once($this->config->plugin_path . 'admin/partials/wps-admin-display.php');

	}


	/*

	Register / Update plugin options
	Currently only updating connection form

	*/
	public function wps_options_update() {

    register_setting( $this->config->settings_connection_option_name, $this->config->settings_connection_option_name, array($this, 'wps_connection_form_validate') );

		register_setting( $this->config->settings_general_option_name, $this->config->settings_general_option_name, array($this, 'wps_general_form_validate') );

	}


	/*

	Validate connection form settings

	*/
	public function wps_connection_form_validate($input) {

    // All checkboxes inputs
    $valid = array();

		/*

		JS Access Token

		*/
		$valid['js_access_token'] = isset($input['js_access_token']) && !empty($input['js_access_token']) ? sanitize_text_field($input['js_access_token']) : '';

		/*

		My Shopify Domain

		*/
		$valid['domain'] = isset($input['domain']) && !empty($input['domain']) ? sanitize_text_field($input['domain']) : '';

		/*

		Nonce

		*/
		$valid['nonce'] = isset($input['nonce']) && !empty($input['nonce']) ? sanitize_text_field($input['nonce']) : '';

		/*

		App ID

		*/
		$valid['app_id'] = isset($input['app_id']) && !empty($input['app_id']) ? sanitize_text_field($input['app_id']) : '';

		/*

		Webhook ID

		*/
		$valid['webhook_id'] = isset($input['webhook_id']) && !empty($input['webhook_id']) ? sanitize_text_field($input['webhook_id']) : '';

		/*

		Shopify Access Token

		*/
		$valid['access_token'] = isset($input['access_token']) && !empty($input['access_token']) ? sanitize_text_field($input['access_token']) : '';

    return $valid;

 	}








	/*

	Validate connection form settings

	*/
	public function wps_general_form_validate($input) {

		// All checkboxes inputs
		$valid = array();

		// Products URL
		$valid['wps_general_url_products'] = isset($input['wps_general_url_products']) && !empty($input['wps_general_url_products']) ? sanitize_text_field($input['wps_general_url_products']) : '';

		// Collections URL
		$valid['wps_general_url_collections'] = isset($input['wps_general_url_collections']) && !empty($input['wps_general_url_collections']) ? sanitize_text_field($input['wps_general_url_collections']) : '';

		return $valid;

	}








	/*

	Getting and sending application credentials to front-end.
	TODO: Limit what values get sent over the wire. Less is more secure :)

	*/
 	public function wps_get_credentials() {

		$shopifyCreds = array();
		$connection = $this->config->wps_get_settings_connection();

		$shopifyCreds['js_access_token'] = $connection['js_access_token'];
		$shopifyCreds['app_id'] = $connection['app_id'];
		$shopifyCreds['domain'] = $connection['domain'];

		echo json_encode($shopifyCreds);
		die();

	}


	/*

	Inserting authentication modal below settings form

	*/
	public function wps_insert_auth_modal() {

		if(isset($_GET["auth"]) && trim($_GET["auth"]) == 'true') {
			echo '<div class="wps-connector-wrapper wps-is-connected"><div class="wps-connector wps-connector-progress" style="display:block;opacity:1;"><h1 class="wps-connector-heading">Connecting <img class="wps-connector-logo" src="/content/plugins/wp-shopify/admin/imgs/logo-wp.svg" /> to <img class="wps-connector-logo" src="/content/plugins/wp-shopify/admin/imgs/shopify.svg" /></h1><div class="wps-l-row"><button type="button" name="button" class="button button-primary wps-btn wps-btn-cancel button button-primary">Cancel</button></div><div class="wps-connector-content"></div></div></div>';
		}

	}














	/*

	Delete Synced Posts

	*/
	public function wps_delete_posts($type, $ids = null) {

		$result = array();

		$args = array(
			'numberposts' => -1,
			'post_type' => $type
		);

		if ($ids !== null) {
			$args['post__in'] = $ids;
		}

		$posts = get_posts($args);

		if (is_array($posts)) {

			foreach ($posts as $post) {
				$result = wp_delete_post( $post->ID, true);
			}

		}

	}


	/*

	Deleting the actual access token from consumer DB
	Returns: Boolean

	*/
	public function wps_delete_connection_setting($setting) {

		// $this->config = new Config();
		$connection = $this->config->wps_get_settings_connection();

		if(isset($setting) && $setting) {

			if( array_key_exists($setting, $connection) ) {

				$connection->$setting = null;
				$connection = array_filter($connection);

				update_option($this->config->settings_connection_option_name, $connection);


			}

		}

		// At this point we should expect an array without the webhook prop
		$pluginSettingsNew = $this->config->wps_get_settings_connection();

		return $pluginSettingsNew;

	}


	public function wps_backend_hooks() {

		// $this->config = new Config();

		$AJAX = new AJAX($this->config);
		$License = new License($this->config);
		$Waypoints = new Waypoints($this->config);
		$Collections = new Collections($this->config);
		$Products_General = new Products_General($this->config);
		$Webhooks = new Webhooks($this->config);
		$WS = new WS($this->config);

		$DB_Shop = new Shop();
		$DB_Settings_Connection = new Settings_Connection();


		/*


		TODO: Remove nopriv actions?


		*/

		// Config
		add_action( 'admin_menu', array($this, 'wps_config_add_plugin_menu') );

		add_action( 'admin_enqueue_scripts', array($this, 'wps_config_admin_styles') );
		add_action( 'admin_enqueue_scripts', array($this, 'wps_config_admin_scripts') );

		add_filter( 'plugin_action_links_' . $this->config->plugin_basename, array($this, 'wps_config_add_action_links') );

		// AJAX
		add_action( 'wp_ajax_wps_get_options', array($AJAX, 'wps_get_options'));
		add_action( 'wp_ajax_nopriv_wps_get_options', array($AJAX, 'wps_get_options'));

		add_action( 'wp_ajax_wps_get_credentials', array($this, 'wps_get_credentials'));
		add_action( 'wp_ajax_nopriv_wps_get_credentials', array($this, 'wps_get_credentials'));

		// Setup / Events
		add_action( 'wp_ajax_wps_notice', array($this, 'wps_notice'));
		add_action( 'wp_ajax_nopriv_wps_notice', array($this, 'wps_notice'));

		// add_action( 'wp_ajax_wps_delete_access_token', array($this, 'wps_delete_access_token'));
		// add_action( 'wp_ajax_nopriv_wps_delete_access_token', array($this, 'wps_delete_access_token'));

		add_action( 'wps_after_settings_form', array($this, 'wps_insert_auth_modal'), 1);

		// Custom MetaBoxes for Collections CPT
		// add_action( 'add_meta_boxes', $this, 'wps_meta_collections');

		// Save / Update our plugin options
		add_action( 'admin_init', array($this, 'wps_options_update'));


		// WS
		add_action( 'wp_ajax_wps_uninstall_consumer', array($WS, 'wps_uninstall_consumer'));
		add_action( 'wp_ajax_nopriv_wps_uninstall_consumer', array($WS, 'wps_uninstall_consumer'));

		add_action( 'wp_ajax_wps_get_progress_count', array($WS, 'wps_get_progress_count'));
		add_action( 'wp_ajax_nopriv_wps_get_progress_count', array($WS, 'wps_get_progress_count'));

		add_action( 'wp_ajax_wps_ws_get_collects_count', array($WS, 'wps_ws_get_collects_count'));
		add_action( 'wp_ajax_nopriv_wps_ws_get_collects_count', array($WS, 'wps_ws_get_collects_count'));

		add_action( 'wp_ajax_wps_insert_collects', array($WS, 'wps_insert_collects'));
		add_action( 'wp_ajax_nopriv_wps_insert_collects', array($WS, 'wps_insert_collects'));

		add_action( 'wp_ajax_wps_ws_get_products_count', array($WS, 'wps_ws_get_products_count'));
		add_action( 'wp_ajax_nopriv_wps_ws_get_products_count', array($WS, 'wps_ws_get_products_count'));

		add_action( 'wp_ajax_wps_insert_products_data', array($WS, 'wps_insert_products_data'));
		add_action( 'wp_ajax_nopriv_wps_insert_products_data', array($WS, 'wps_insert_products_data'));

		add_action( 'wp_ajax_wps_insert_custom_collections_data', array($WS, 'wps_insert_custom_collections_data'));
		add_action( 'wp_ajax_nopriv_wps_insert_custom_collections_data', array($WS, 'wps_insert_custom_collections_data'));

		add_action( 'wp_ajax_wps_insert_smart_collections_data', array($WS, 'wps_insert_smart_collections_data'));
		add_action( 'wp_ajax_nopriv_wps_insert_smart_collections_data', array($WS, 'wps_insert_smart_collections_data'));

		add_action( 'wp_ajax_wps_ws_get_products_from_collection', array($WS, 'wps_ws_get_products_from_collection'));
		add_action( 'wp_ajax_nopriv_wps_ws_get_products_from_collection', array($WS, 'wps_ws_get_products_from_collection'));

		add_action( 'wp_ajax_wps_ws_get_collects_from_product', array($WS, 'wps_ws_get_collects_from_product'));
		add_action( 'wp_ajax_nopriv_wps_ws_get_collects_from_product', array($WS, 'wps_ws_get_collects_from_product'));

		add_action( 'wp_ajax_wps_ws_get_collects_from_collection', array($WS, 'wps_ws_get_collects_from_collection'));
		add_action( 'wp_ajax_nopriv_wps_ws_get_collects_from_collection', array($WS, 'wps_ws_get_collects_from_collection'));

		// TODO: Should this be in another hook?
		add_action( 'init', array($WS, 'wps_ws_on_authorization' ));

		add_action( 'wp_ajax_wps_ws_get_webhooks', array($WS, 'wps_ws_get_webhooks'));
		add_action( 'wp_ajax_nopriv_wps_ws_get_webhooks', array($WS, 'wps_ws_get_webhooks'));

		add_action( 'wp_ajax_wps_ws_delete_webhook', array($WS, 'wps_ws_delete_webhook'));
		add_action( 'wp_ajax_nopriv_wps_ws_delete_webhook', array($WS, 'wps_ws_delete_webhook'));

		add_action( 'wp_ajax_wps_update_settings_general', array($WS, 'wps_update_settings_general'));
		add_action( 'wp_ajax_nopriv_wps_update_settings_general', array($WS, 'wps_update_settings_general'));

		// Products
		add_action( 'wp_ajax_wps_insert_products', array($Products_General, 'wps_insert_products'));
		add_action( 'wp_ajax_nopriv_wps_insert_products', array($Products_General, 'wps_insert_products'));

		// Collections
		add_action( 'wp_ajax_wps_insert_collections', array($Collections, 'wps_insert_collections'));
		add_action( 'wp_ajax_nopriv_wps_insert_collections', array($Collections, 'wps_insert_collections'));















		// Shop Data
		add_action( 'wp_ajax_wps_insert_shop', array($WS, 'wps_insert_shop'));
		add_action( 'wp_ajax_nopriv_wps_insert_shop', array($WS, 'wps_insert_shop'));

		// Insert connction data
		add_action( 'wp_ajax_wps_insert_connection', array($WS, 'wps_insert_connection'));
		add_action( 'wp_ajax_nopriv_wps_insert_connection', array($WS, 'wps_insert_connection'));

		// Get connection data
		add_action( 'wp_ajax_wps_get_connection', array($WS, 'wps_get_connection'));
		add_action( 'wp_ajax_nopriv_wps_get_connection', array($WS, 'wps_get_connection'));




		add_action( 'wp_ajax_wps_ws_get_variants', array($WS, 'wps_ws_get_variants'));
		add_action( 'wp_ajax_nopriv_wps_ws_get_variants', array($WS, 'wps_ws_get_variants'));























		// Waypoint
		add_action( 'wp_ajax_wps_waypoint_get_shopify_url', array($Waypoints, 'wps_waypoint_get_shopify_url'));
		add_action( 'wp_ajax_nopriv_wps_waypoint_get_shopify_url', array($Waypoints, 'wps_waypoint_get_shopify_url'));





		// Webhook create product callback
		add_action( 'wp_ajax_wps_webhooks_product_create', array($Webhooks, 'wps_webhooks_product_create'));
		add_action( 'wp_ajax_nopriv_wps_webhooks_product_create', array($Webhooks, 'wps_webhooks_product_create'));

		// Webhook update product callback
		add_action( 'wp_ajax_wps_webhooks_product_update', array($Webhooks, 'wps_webhooks_product_update'));
		add_action( 'wp_ajax_nopriv_wps_webhooks_product_update', array($Webhooks, 'wps_webhooks_product_update'));

		// Webhook delete product callback
		add_action( 'wp_ajax_wps_webhooks_product_delete', array($Webhooks, 'wps_webhooks_product_delete'));
		add_action( 'wp_ajax_nopriv_wps_webhooks_product_delete', array($Webhooks, 'wps_webhooks_product_delete'));


		// Webhook create collection callback
		add_action( 'wp_ajax_wps_webhooks_collections_create', array($Webhooks, 'wps_webhooks_collections_create'));
		add_action( 'wp_ajax_nopriv_wps_webhooks_collections_create', array($Webhooks, 'wps_webhooks_collections_create'));

		// Webhook create collection callback
		add_action( 'wp_ajax_wps_webhooks_collections_update', array($Webhooks, 'wps_webhooks_collections_update'));
		add_action( 'wp_ajax_nopriv_wps_webhooks_collections_update', array($Webhooks, 'wps_webhooks_collections_update'));

		// Webhook create collection callback
		add_action( 'wp_ajax_wps_webhooks_collections_delete', array($Webhooks, 'wps_webhooks_collections_delete'));
		add_action( 'wp_ajax_nopriv_wps_webhooks_collections_delete', array($Webhooks, 'wps_webhooks_collections_delete'));


		// Webhook: shop/update
		add_action( 'wp_ajax_wps_webhooks_shop_update', array($Webhooks, 'wps_webhooks_shop_update'));
		add_action( 'wp_ajax_nopriv_wps_webhooks_shop_update', array($Webhooks, 'wps_webhooks_shop_update'));

		// Webhook: app/uninstalled
		add_action( 'wp_ajax_wps_webhooks_shop_app_uninstalled', array($Webhooks, 'wps_webhooks_shop_app_uninstalled'));
		add_action( 'wp_ajax_nopriv_wps_webhooks_shop_app_uninstalled', array($Webhooks, 'wps_webhooks_shop_app_uninstalled'));






		// License Key
		add_action( 'wp_ajax_wps_license_save', array($License, 'wps_license_save'));
		add_action( 'wp_ajax_nopriv_wps_license_save', array($License, 'wps_license_save'));

		add_action( 'wp_ajax_wps_license_delete', array($License, 'wps_license_delete'));
		add_action( 'wp_ajax_nopriv_wps_license_delete', array($License, 'wps_license_delete'));

		add_action( 'wp_ajax_wps_license_get', array($License, 'wps_license_get'));
		add_action( 'wp_ajax_nopriv_wps_license_get', array($License, 'wps_license_get'));

		// Get Single Collection
		add_action( 'wp_ajax_wps_ws_get_single_collection', array($WS, 'wps_ws_get_single_collection'));
		add_action( 'wp_ajax_nopriv_wps_ws_get_single_collection', array($WS, 'wps_ws_get_single_collection'));






		// Shop data
		add_action( 'wp_ajax_wps_ws_get_shop_data', array($WS, 'wps_ws_get_shop_data'));
		add_action( 'wp_ajax_nopriv_wps_ws_get_shop_data', array($WS, 'wps_ws_get_shop_data'));










		add_action( 'update_option_wps_settings_general', array($WS, 'wps_reset_rewrite_rules'), 10, 2 );







	}


}
