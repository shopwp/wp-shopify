<?php

namespace WPS;

use WPS\AJAX;
use WPS\License;
use WPS\Collections;
use WPS\Products_General;
use WPS\Webhooks;
use WPS\Progress_Bar;
use WPS\WS;
use WPS\Messages;

use WPS\DB\Shop;
use WPS\DB\Settings_Connection;
use WPS\DB\Settings_General;

// If this file is called directly, abort.
if (!defined('ABSPATH')) {
	exit;
}


/*

Backend Class

*/
if ( !class_exists('Backend') ) {

	class Backend {

		protected static $instantiated = null;
		private $Config;
		private $messages;

		/*

		Initialize the class and set its properties.

		*/
		public function __construct($Config) {
			$this->config = $Config;
			$this->connection = $this->config->wps_get_settings_connection();
			$this->messages = new Messages();
			$this->ws = new WS($this->config);
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

		Admin styles

		*/
		public function wps_config_admin_styles() {

			// Only loading styles if we're on the settings page ...
			if('wp-shopify_page_wps-settings' == get_current_screen()->id || get_current_screen()->id === 'wps_products' || get_current_screen()->id === 'wps_collections' || get_current_screen()->id === 'plugins') {

				wp_enqueue_style('wp-color-picker');

				wp_enqueue_style('animate-css', $this->config->plugin_url . 'admin/css/app/vendor/animate.min.css', array());

				wp_enqueue_style('tooltipster-css', $this->config->plugin_url . 'admin/css/app/vendor/tooltipster.min.css', array());

				wp_enqueue_style($this->config->plugin_name, $this->config->plugin_url . 'dist/admin.min.css', array( 'wp-color-picker', 'animate-css', 'tooltipster-css'), $this->config->plugin_version, 'all');

			}

		}


		/*

		Admin scripts

		*/
		public function wps_config_admin_scripts() {

			// Only loading admin script if we're on the settings page ...
			if (get_current_screen()->id === 'wp-shopify_page_wps-settings' || get_current_screen()->id === 'wps_products' || get_current_screen()->id === 'wps_collections' || get_current_screen()->id === 'nav-menus') {

				wp_enqueue_media();

				$DB_Settings_General = new Settings_General();

				if (is_object($DB_Settings_General) && method_exists($DB_Settings_General, 'selective_sync_status') ) {
					$selectiveSyncValue = $DB_Settings_General->selective_sync_status();

				} else {
					$selectiveSyncValue = false;
				}

				wp_enqueue_script('promise-polyfill', $this->config->plugin_url . 'public/js/app/vendor/es6-promise.auto.min.js', array('jquery'), $this->config->plugin_version, true);
				wp_enqueue_script('tooltipster-js', $this->config->plugin_url . 'admin/js/app/vendor/jquery.tooltipster.min.js', array('jquery'), $this->config->plugin_version, false );
				wp_enqueue_script('validate-js', $this->config->plugin_url . 'admin/js/app/vendor/jquery.validate.min.js', array('jquery'), $this->config->plugin_version, false );
				wp_enqueue_script('wps-admin', $this->config->plugin_url . 'dist/admin.min.js', array('jquery', 'promise-polyfill', 'tooltipster-js', 'validate-js'), $this->config->plugin_version, true );

				wp_localize_script('wps-admin', $this->config->plugin_name_js, array(
					'ajax' => __(admin_url('admin-ajax.php')),
					'pluginsPath' => __(plugins_url()),
					'siteUrl' => site_url(),
					'pluginsDirURL' => plugin_dir_url(dirname(__FILE__)),
					'nonce'	=> wp_create_nonce('wp-shopify-backend'),
					'selective_sync' => $selectiveSyncValue
				));

			}

		}


		/*

		Registering the admin menu into the WordPress Dashboard menu.
		Adding a settings page to the Settings menu.

		*/
		public function wps_config_add_plugin_menu() {

			if (current_user_can('manage_options')) {

				global $submenu;

				$icon_svg = 'data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz4KPCEtLSBHZW5lcmF0b3I6IEFkb2JlIElsbHVzdHJhdG9yIDIxLjAuMCwgU1ZHIEV4cG9ydCBQbHVnLUluIC4gU1ZHIFZlcnNpb246IDYuMDAgQnVpbGQgMCkgIC0tPgo8c3ZnIHZlcnNpb249IjEuMSIgaWQ9IkxheWVyXzEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiIHg9IjBweCIgeT0iMHB4IgoJIHZpZXdCb3g9IjAgMCAxOCAxOCIgc3R5bGU9ImVuYWJsZS1iYWNrZ3JvdW5kOm5ldyAwIDAgMTggMTg7IiB4bWw6c3BhY2U9InByZXNlcnZlIj4KPHN0eWxlIHR5cGU9InRleHQvY3NzIj4KCS5Ecm9wX3gwMDIwX1NoYWRvd3tmaWxsOm5vbmU7fQoJLlJvdW5kX3gwMDIwX0Nvcm5lcnNfeDAwMjBfMl94MDAyMF9wdHtmaWxsOiNGRkZGRkY7c3Ryb2tlOiMyMzFGMjA7c3Ryb2tlLW1pdGVybGltaXQ6MTA7fQoJLkxpdmVfeDAwMjBfUmVmbGVjdF94MDAyMF9Ye2ZpbGw6bm9uZTt9CgkuQmV2ZWxfeDAwMjBfU29mdHtmaWxsOnVybCgjU1ZHSURfMV8pO30KCS5EdXNre2ZpbGw6I0ZGRkZGRjt9CgkuRm9saWFnZV9HU3tmaWxsOiNGRkREMDA7fQoJLlBvbXBhZG91cl9HU3tmaWxsLXJ1bGU6ZXZlbm9kZDtjbGlwLXJ1bGU6ZXZlbm9kZDtmaWxsOiM0NEFERTI7fQo8L3N0eWxlPgo8bGluZWFyR3JhZGllbnQgaWQ9IlNWR0lEXzFfIiBncmFkaWVudFVuaXRzPSJ1c2VyU3BhY2VPblVzZSIgeDE9IjAiIHkxPSIwIiB4Mj0iMC43MDcxIiB5Mj0iMC43MDcxIj4KCTxzdG9wICBvZmZzZXQ9IjAiIHN0eWxlPSJzdG9wLWNvbG9yOiNERURGRTMiLz4KCTxzdG9wICBvZmZzZXQ9IjAuMTc4MyIgc3R5bGU9InN0b3AtY29sb3I6I0RBREJERiIvPgoJPHN0b3AgIG9mZnNldD0iMC4zNjExIiBzdHlsZT0ic3RvcC1jb2xvcjojQ0VDRkQzIi8+Cgk8c3RvcCAgb2Zmc2V0PSIwLjU0NiIgc3R5bGU9InN0b3AtY29sb3I6I0I5QkNCRiIvPgoJPHN0b3AgIG9mZnNldD0iMC43MzI0IiBzdHlsZT0ic3RvcC1jb2xvcjojOUNBMEEyIi8+Cgk8c3RvcCAgb2Zmc2V0PSIwLjkxODEiIHN0eWxlPSJzdG9wLWNvbG9yOiM3ODdEN0UiLz4KCTxzdG9wICBvZmZzZXQ9IjEiIHN0eWxlPSJzdG9wLWNvbG9yOiM2NTZCNkMiLz4KPC9saW5lYXJHcmFkaWVudD4KPHBhdGggZD0iTTksMC4yQzQuMSwwLjIsMC4yLDQuMSwwLjIsOXMzLjksOC44LDguOCw4LjhzOC44LTMuOSw4LjgtOC44UzEzLjgsMC4yLDksMC4yeiBNNi4yLDE0LjVjLTAuNCwwLTAuNy0wLjItMC44LTAuNkwzLDUuMgoJYzAtMC4xLDAtMC4xLDAtMC4yYzAtMC4zLDAuMi0wLjQsMC41LTAuNWMwLjEsMCwwLjEsMCwwLjIsMGMwLjIsMCwwLjUsMC4xLDAuNSwwLjRsMS4zLDVsMC4xLDAuNWwwLjYsMi40bDAuNCwxLjcKCUM2LjYsMTQuNSw2LjQsMTQuNSw2LjIsMTQuNXogTTExLjgsMTQuNWMtMC40LDAtMC43LTAuMi0wLjgtMC42YzAsMC0wLjYtMi40LTAuOS00SDguNmwwLjItMC44YzAsMCwwLjEtMC44LDAuNi0wLjgKCWMwLjIsMCwwLjMsMC4xLDAuNCwwLjNDOS42LDguNCw5LjQsOCw5LDhDOC4zLDgsOC4yLDguNyw4LjIsOC43bC0wLjcsMy4yTDYuOSw5LjlsMC4zLTEuMWwwLDAuMWwxLTMuOUM4LjMsNC43LDguNiw0LjUsOSw0LjUKCWMwLjQsMCwwLjcsMC4yLDAuOCwwLjZsMC43LDIuNkwxMC44LDlsMC40LDEuN2wwLjUsMmwwLDAuMWwwLjUsMS42QzEyLjIsMTQuNSwxMiwxNC41LDExLjgsMTQuNXogTTE1LjEsNS4xbC0yLDcuMUwxMi41LDEwbDAuMy0xLjEKCUwxMyw4LjJ2MGwwLjktMy40YzAuMS0wLjMsMC4zLTAuNCwwLjYtMC40YzAuMSwwLDAuMSwwLDAuMiwwYzAuMywwLjEsMC41LDAuMiwwLjUsMC41QzE1LjEsNSwxNS4xLDUuMSwxNS4xLDUuMXoiLz4KPC9zdmc+Cg==';

				// Main menu
				add_menu_page(
					__('WP Shopify', $this->config->plugin_name),
					__('WP Shopify', $this->config->plugin_name),
					'manage_options',
					'wpshopify',
					array($this, 'wps_config_display_setup_page'),
					$icon_svg,
					null
				);

				// Submenu: Settings
				add_submenu_page(
					'wpshopify',
					__('Settings', $this->config->plugin_name),
					__('Settings', $this->config->plugin_name),
					'manage_options',
					'wps-settings',
					array($this, 'wps_config_display_setup_page')
				);

				// Submenu: Products
				add_submenu_page(
					'wpshopify',
					__('Products', $this->config->plugin_name),
					__('Products', $this->config->plugin_name),
					'manage_options',
					'edit.php?post_type=wps_products',
					null
				);

				// Submenu: Collections
				add_submenu_page(
					'wpshopify',
					__('Collections', $this->config->plugin_name),
					__('Collections', $this->config->plugin_name),
					'manage_options',
					'edit.php?post_type=wps_collections',
					null
				);

				remove_submenu_page('wpshopify','wpshopify');

			}


		}


		/*

		Add settings action link to the plugins page.

		*/
		public function wps_config_add_action_links($links) {

			// $this->config = new Config();
			$settings_link = ['<a href="' . esc_url( admin_url('/admin.php?page=' . $this->config->plugin_name) . '-settings' ) . '">' . esc_html__('Settings', 'wp-shopify') . '</a>'];

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

		These credentials do not need to be secured and can be stored on the client-side to
		improve performance.

		*/
	 	public function wps_get_credentials_frontend() {

			if (isset($_GET) && isset($_GET['action']) && !empty($_GET)) {
				$ajax = true;

			} else {
				$ajax = false;
			}


			$shopifyCreds = array();
			$connection = $this->config->wps_get_settings_connection();

			if (is_object($connection) && isset($connection->js_access_token)) {
				$shopifyCreds['js_access_token'] = $connection->js_access_token;
			}

			if (is_object($connection) && isset($connection->app_id)) {
				$shopifyCreds['app_id'] = $connection->app_id;
			}

			if (is_object($connection) && isset($connection->domain)) {
				$shopifyCreds['domain'] = $connection->domain;
			}


			if ($ajax) {
				$this->ws->send_success($shopifyCreds);

			} else {
				return $shopifyCreds;
			}


		}


		/*

		Gets the initial cart session

		*/
		public function wps_get_cart_session() {

			// $cartSession = [];

			// $cartSession['creds'] = $this->wps_get_credentials_frontend(false);


			/*

			Cart Session Steps

			1. wps_get_credentials_frontend
			2. wps_get_cart_cache
			3. wps_has_money_format_changed -- moneyFormatChanged()
			4. wps_get_currency_format
			5. wps_get_money_format_with_currency -- OR -- wps_get_money_format

			*/

			// $this->ws->send_success($shopifyCreds);


		}


		/*

		Inserting authentication modal below settings form

		*/
		public function wps_insert_auth_modal() {

			if (isset($_GET["auth"]) && trim($_GET["auth"]) == 'true') {
				printf(esc_html__('<div class="wps-connector-wrapper wps-is-connected"><div class="wps-connector wps-connector-progress" style="display:block;opacity:1;"><h1 class="wps-connector-heading">Connecting <img class="wps-connector-logo" src="%1" /> to <img class="wps-connector-logo" src="%2" /></h1><div class="wps-l-row"><button type="button" name="button" class="button button-primary wps-btn wps-btn-cancel button button-primary">Cancel</button></div><div class="wps-connector-content"></div></div></div>'), esc_url($this->config->plugin_url . 'admin/imgs/logo-wp.svg'), esc_url($this->config->plugin_url . 'admin/imgs/shopify.svg'));
			}

		}


		/*

		Delete Synced Posts
		- Predicate Function (returns boolean)

		*/
		public function wps_delete_posts($type, $ids = null) {

			$deletions = array();

			$args = array(
				'posts_per_page' => -1,
				'post_type' => $type
			);

			if ($ids !== null) {
				$args['post__in'] = $ids;
			}

			$posts = get_posts($args);

			if (!empty($posts) && is_array($posts)) {

				foreach ($posts as $post) {
					$deletions[] = wp_delete_post( $post->ID, true);
				}

				if (in_array(false, $deletions, true) ) {
					return false;

				} else {
					return true;
				}


			} else {
				return true;

			}

		}


		/*

		Deleting the actual access token from consumer DB
		Returns: Boolean

		*/
		public function wps_delete_connection_setting($setting) {

			// $this->config = new Config();
			$connection = $this->config->wps_get_settings_connection();

			if (isset($setting) && $setting) {

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


		public function init() {

			// $this->config = new Config();

			$AJAX = new AJAX($this->config);
			$License = new License($this->config);

			$Collections = new Collections($this->config);
			$Products_General = new Products_General($this->config);
			$Webhooks = new Webhooks($this->config);
			$WS = new WS($this->config);
			$ProgressBar = new Progress_Bar($this->config);

			$DB_Shop = new Shop();
			$DB_Settings_Connection = new Settings_Connection();


			/*

			TODO: Remove nopriv actions?

			*/
			add_action( 'admin_menu', array($this, 'wps_config_add_plugin_menu') );


			add_action( 'admin_enqueue_scripts', array($this, 'wps_config_admin_styles') );
			add_action( 'admin_enqueue_scripts', array($this, 'wps_config_admin_scripts') );

			add_filter( 'plugin_action_links_' . $this->config->plugin_basename, array($this, 'wps_config_add_action_links') );


			add_action( 'wp_ajax_wps_get_credentials_frontend', array($this, 'wps_get_credentials_frontend'));
			add_action( 'wp_ajax_nopriv_wps_get_credentials_frontend', array($this, 'wps_get_credentials_frontend'));

			add_action( 'wp_ajax_wps_get_cart_session', array($this, 'wps_get_cart_session'));
			add_action( 'wp_ajax_nopriv_wps_get_cart_session', array($this, 'wps_get_cart_session'));

			// Setup / Events
			add_action( 'wp_ajax_wps_notice', array($this, 'wps_notice'));
			add_action( 'wp_ajax_nopriv_wps_notice', array($this, 'wps_notice'));

			add_action( 'wps_after_settings_form', array($this, 'wps_insert_auth_modal'), 1);

			// Custom MetaBoxes for Collections CPT
			// add_action( 'add_meta_boxes', $this, 'wps_meta_collections');

			// Save / Update our plugin options
			add_action( 'admin_init', array($this, 'wps_options_update'));


			// WS
			add_action( 'wp_ajax_wps_sync_with_cpt', array($WS, 'wps_sync_with_cpt'));
			add_action( 'wp_ajax_nopriv_wps_sync_with_cpt', array($WS, 'wps_sync_with_cpt'));

			add_action( 'wp_ajax_wps_clear_cache', array($WS, 'wps_clear_cache'));
			add_action( 'wp_ajax_nopriv_wps_clear_cache', array($WS, 'wps_clear_cache'));

			add_action( 'wp_ajax_wps_ws_set_syncing_indicator', array($WS, 'wps_ws_set_syncing_indicator'));
			add_action( 'wp_ajax_nopriv_wps_ws_set_syncing_indicator', array($WS, 'wps_ws_set_syncing_indicator'));

			add_action( 'wp_ajax_wps_uninstall_consumer', array($WS, 'wps_uninstall_consumer'));
			add_action( 'wp_ajax_nopriv_wps_uninstall_consumer', array($WS, 'wps_uninstall_consumer'));

			add_action( 'wp_ajax_wps_uninstall_product_data', array($WS, 'wps_uninstall_product_data'));
			add_action( 'wp_ajax_nopriv_wps_uninstall_product_data', array($WS, 'wps_uninstall_product_data'));

			add_action( 'wp_ajax_wps_uninstall_all_data', array($WS, 'wps_uninstall_all_data'));
			add_action( 'wp_ajax_nopriv_wps_uninstall_all_data', array($WS, 'wps_uninstall_all_data'));

			add_action( 'wp_ajax_wps_get_progress_count', array($WS, 'wps_get_progress_count'));
			add_action( 'wp_ajax_nopriv_wps_get_progress_count', array($WS, 'wps_get_progress_count'));

			add_action( 'wp_ajax_wps_ws_get_collects_count', array($WS, 'wps_ws_get_collects_count'));
			add_action( 'wp_ajax_nopriv_wps_ws_get_collects_count', array($WS, 'wps_ws_get_collects_count'));

			add_action( 'wp_ajax_wps_ws_get_webhooks_count', array($WS, 'wps_ws_get_webhooks_count'));
			add_action( 'wp_ajax_nopriv_wps_ws_get_webhooks_count', array($WS, 'wps_ws_get_webhooks_count'));

			add_action( 'wp_ajax_wps_ws_get_shop_count', array($WS, 'wps_ws_get_shop_count'));
			add_action( 'wp_ajax_nopriv_wps_ws_get_shop_count', array($WS, 'wps_ws_get_shop_count'));

			add_action( 'wp_ajax_wps_ws_get_smart_collections_count', array($WS, 'wps_ws_get_smart_collections_count'));
			add_action( 'wp_ajax_nopriv_wps_ws_get_smart_collections_count', array($WS, 'wps_ws_get_smart_collections_count'));

			add_action( 'wp_ajax_wps_ws_get_custom_collections_count', array($WS, 'wps_ws_get_custom_collections_count'));
			add_action( 'wp_ajax_nopriv_wps_ws_get_custom_collections_count', array($WS, 'wps_ws_get_custom_collections_count'));

			add_action( 'wp_ajax_wps_insert_collects', array($WS, 'wps_insert_collects'));
			add_action( 'wp_ajax_nopriv_wps_insert_collects', array($WS, 'wps_insert_collects'));

			add_action( 'wp_ajax_wps_ws_get_products_count', array($WS, 'wps_ws_get_products_count'));
			add_action( 'wp_ajax_nopriv_wps_ws_get_products_count', array($WS, 'wps_ws_get_products_count'));

			add_action( 'wp_ajax_wps_ws_get_orders_count', array($WS, 'wps_ws_get_orders_count'));
			add_action( 'wp_ajax_nopriv_wps_ws_get_orders_count', array($WS, 'wps_ws_get_orders_count'));

			add_action( 'wp_ajax_wps_ws_get_customers_count', array($WS, 'wps_ws_get_customers_count'));
			add_action( 'wp_ajax_nopriv_wps_ws_get_customers_count', array($WS, 'wps_ws_get_customers_count'));

			add_action( 'wp_ajax_wps_insert_products_data', array($WS, 'wps_insert_products_data'));
			add_action( 'wp_ajax_nopriv_wps_insert_products_data', array($WS, 'wps_insert_products_data'));

			add_action( 'wp_ajax_wps_insert_alt_text', array($WS, 'wps_insert_alt_text'));
			add_action( 'wp_ajax_nopriv_wps_insert_alt_text', array($WS, 'wps_insert_alt_text'));

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


			add_action( 'wp_ajax_wps_ws_get_webhooks', array($WS, 'wps_ws_get_webhooks'));
			add_action( 'wp_ajax_nopriv_wps_ws_get_webhooks', array($WS, 'wps_ws_get_webhooks'));


			add_action( 'wp_ajax_wps_update_settings_general', array($WS, 'wps_update_settings_general'));
			add_action( 'wp_ajax_nopriv_wps_update_settings_general', array($WS, 'wps_update_settings_general'));


			// Collections
			add_action( 'wp_ajax_wps_insert_collections', array($Collections, 'wps_insert_collections'));
			add_action( 'wp_ajax_nopriv_wps_insert_collections', array($Collections, 'wps_insert_collections'));

			// Orders
			add_action( 'wp_ajax_wps_insert_orders', array($WS, 'wps_insert_orders'));
			add_action( 'wp_ajax_nopriv_wps_insert_orders', array($WS, 'wps_insert_orders'));

			// Customers
			add_action( 'wp_ajax_wps_insert_customers', array($WS, 'wps_insert_customers'));
			add_action( 'wp_ajax_nopriv_wps_insert_customers', array($WS, 'wps_insert_customers'));

			// Shop Data
			add_action( 'wp_ajax_wps_insert_shop', array($WS, 'wps_insert_shop'));
			add_action( 'wp_ajax_nopriv_wps_insert_shop', array($WS, 'wps_insert_shop'));

			// Insert connction data
			add_action( 'wp_ajax_wps_insert_connection', array($WS, 'wps_insert_connection'));
			add_action( 'wp_ajax_nopriv_wps_insert_connection', array($WS, 'wps_insert_connection'));

			// Get connection data
			add_action( 'wp_ajax_wps_get_connection', array($WS, 'wps_get_connection'));
			add_action( 'wp_ajax_nopriv_wps_get_connection', array($WS, 'wps_get_connection'));

			// Remove connection data
			add_action( 'wp_ajax_wps_remove_connection', array($WS, 'wps_remove_connection'));
			add_action( 'wp_ajax_nopriv_wps_remove_connection', array($WS, 'wps_remove_connection'));

			// Get Variants
			add_action( 'wp_ajax_wps_ws_get_variants', array($WS, 'wps_ws_get_variants'));
			add_action( 'wp_ajax_nopriv_wps_ws_get_variants', array($WS, 'wps_ws_get_variants'));

			// Save Counts
			add_action( 'wp_ajax_save_counts', array($WS, 'save_counts'));
			add_action( 'wp_ajax_nopriv_save_counts', array($WS, 'save_counts'));

			// Get Total Counts
			add_action( 'wp_ajax_get_total_counts', array($WS, 'get_total_counts'));
			add_action( 'wp_ajax_nopriv_get_total_counts', array($WS, 'get_total_counts'));

			/*

			Webhook: Products

			*/

			add_action( 'wp_ajax_remove_webhooks', array($Webhooks, 'remove_webhooks'));
			add_action( 'wp_ajax_nopriv_remove_webhooks', array($Webhooks, 'remove_webhooks'));

			add_action( 'wp_ajax_wps_webhooks_register_single', array($Webhooks, 'wps_webhooks_register_single'));
			add_action( 'wp_ajax_nopriv_wps_webhooks_register_single', array($Webhooks, 'wps_webhooks_register_single'));






			// products/create
			add_action( 'wp_ajax_products_create_callback', array($Webhooks, 'products_create_callback'));
			add_action( 'wp_ajax_nopriv_products_create_callback', array($Webhooks, 'products_create_callback'));

			// products/update
			add_action( 'wp_ajax_products_update_callback', array($Webhooks, 'products_update_callback'));
			add_action( 'wp_ajax_nopriv_products_update_callback', array($Webhooks, 'products_update_callback'));

			// products/delete
			add_action( 'wp_ajax_products_delete_callback', array($Webhooks, 'products_delete_callback'));
			add_action( 'wp_ajax_nopriv_products_delete_callback', array($Webhooks, 'products_delete_callback'));



			// collections/create
			add_action( 'wp_ajax_collections_create_callback', array($Webhooks, 'collections_create_callback'));
			add_action( 'wp_ajax_nopriv_collections_create_callback', array($Webhooks, 'collections_create_callback'));

			// collections/update
			add_action( 'wp_ajax_collections_update_callback', array($Webhooks, 'collections_update_callback'));
			add_action( 'wp_ajax_nopriv_collections_update_callback', array($Webhooks, 'collections_update_callback'));

			// collections/delete
			add_action( 'wp_ajax_collections_delete_callback', array($Webhooks, 'collections_delete_callback'));
			add_action( 'wp_ajax_nopriv_collections_delete_callback', array($Webhooks, 'collections_delete_callback'));




			// shop/update
			add_action( 'wp_ajax_shop_update_callback', array($Webhooks, 'shop_update_callback'));
			add_action( 'wp_ajax_nopriv_shop_update_callback', array($Webhooks, 'shop_update_callback'));



			// app/uninstalled
			add_action( 'wp_ajax_app_uninstalled_callback', array($Webhooks, 'app_uninstalled_callback'));
			add_action( 'wp_ajax_nopriv_app_uninstalled_callback', array($Webhooks, 'app_uninstalled_callback'));



			// orders/create (working)
			add_action( 'wp_ajax_orders_create_callback', array($Webhooks, 'orders_create_callback'));
			add_action( 'wp_ajax_nopriv_orders_create_callback', array($Webhooks, 'orders_create_callback'));

			// orders/paid
			add_action( 'wp_ajax_orders_paid_callback', array($Webhooks, 'orders_paid_callback'));
			add_action( 'wp_ajax_nopriv_orders_paid_callback', array($Webhooks, 'orders_paid_callback'));

			// orders/cancelled (working)
			add_action( 'wp_ajax_orders_cancelled_callback', array($Webhooks, 'orders_cancelled_callback'));
			add_action( 'wp_ajax_nopriv_orders_cancelled_callback', array($Webhooks, 'orders_cancelled_callback'));

			// orders/delete (working)
			add_action( 'wp_ajax_orders_delete_callback', array($Webhooks, 'orders_delete_callback'));
			add_action( 'wp_ajax_nopriv_orders_delete_callback', array($Webhooks, 'orders_delete_callback'));

			// orders/fulfilled (working)
			add_action( 'wp_ajax_orders_fulfilled_callback', array($Webhooks, 'orders_fulfilled_callback'));
			add_action( 'wp_ajax_nopriv_orders_fulfilled_callback', array($Webhooks, 'orders_fulfilled_callback'));

			// orders/partially_fulfilled
			add_action( 'wp_ajax_orders_partially_fulfilled_callback', array($Webhooks, 'orders_partially_fulfilled_callback'));
			add_action( 'wp_ajax_nopriv_orders_partially_fulfilled_callback', array($Webhooks, 'orders_partially_fulfilled_callback'));

			// orders/updated (working)
			add_action( 'wp_ajax_orders_updated_callback', array($Webhooks, 'orders_updated_callback'));
			add_action( 'wp_ajax_nopriv_orders_updated_callback', array($Webhooks, 'orders_updated_callback'));

			// draft_orders/create (working)
			add_action( 'wp_ajax_draft_orders_create_callback', array($Webhooks, 'draft_orders_create_callback'));
			add_action( 'wp_ajax_nopriv_draft_orders_create_callback', array($Webhooks, 'draft_orders_create_callback'));

			// draft_orders/delete (working)
			add_action( 'wp_ajax_draft_orders_delete_callback', array($Webhooks, 'draft_orders_delete_callback'));
			add_action( 'wp_ajax_nopriv_draft_orders_delete_callback', array($Webhooks, 'draft_orders_delete_callback'));

			// draft_orders/update (working)
			add_action( 'wp_ajax_draft_orders_update_callback', array($Webhooks, 'draft_orders_update_callback'));
			add_action( 'wp_ajax_nopriv_draft_orders_update_callback', array($Webhooks, 'draft_orders_update_callback'));

			// order_transactions/create (working)
			add_action( 'wp_ajax_order_transactions_create_callback', array($Webhooks, 'order_transactions_create_callback'));
			add_action( 'wp_ajax_nopriv_order_transactions_create_callback', array($Webhooks, 'order_transactions_create_callback'));



			/*

			Webhook: Checkouts

			*/

			// checkouts/create
			add_action( 'wp_ajax_checkouts_create_callback', array($Webhooks, 'checkouts_create_callback'));
			add_action( 'wp_ajax_nopriv_checkouts_create_callback', array($Webhooks, 'checkouts_create_callback'));

			// checkouts/delete
			add_action( 'wp_ajax_checkouts_delete_callback', array($Webhooks, 'checkouts_delete_callback'));
			add_action( 'wp_ajax_nopriv_checkouts_delete_callback', array($Webhooks, 'checkouts_delete_callback'));

			// checkouts/update
			add_action( 'wp_ajax_checkouts_update_callback', array($Webhooks, 'checkouts_update_callback'));
			add_action( 'wp_ajax_nopriv_checkouts_update_callback', array($Webhooks, 'checkouts_update_callback'));


			/*

			Webhook: Customers

			*/

			// customers/create
			add_action( 'wp_ajax_customers_create_callback', array($Webhooks, 'customers_create_callback'));
			add_action( 'wp_ajax_nopriv_customers_create_callback', array($Webhooks, 'customers_create_callback'));

			// customers/delete
			add_action( 'wp_ajax_customers_delete_callback', array($Webhooks, 'customers_delete_callback'));
			add_action( 'wp_ajax_nopriv_customers_delete_callback', array($Webhooks, 'customers_delete_callback'));

			// customers/disable
			add_action( 'wp_ajax_customers_disable_callback', array($Webhooks, 'customers_disable_callback'));
			add_action( 'wp_ajax_nopriv_customers_disable_callback', array($Webhooks, 'customers_disable_callback'));

			// customers/enable
			add_action( 'wp_ajax_customers_enable_callback', array($Webhooks, 'customers_enable_callback'));
			add_action( 'wp_ajax_nopriv_customers_enable_callback', array($Webhooks, 'customers_enable_callback'));

			// customers/update
			add_action( 'wp_ajax_customers_update_callback', array($Webhooks, 'customers_update_callback'));
			add_action( 'wp_ajax_nopriv_customers_update_callback', array($Webhooks, 'customers_update_callback'));





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

			// Webhooks
			add_action( 'wp_ajax_wps_ws_register_all_webhooks', array($WS, 'wps_ws_register_all_webhooks'));
			add_action( 'wp_ajax_nopriv_wps_ws_register_all_webhooks', array($WS, 'wps_ws_register_all_webhooks'));


			// Progress Bar
			add_action( 'wp_ajax_wps_progress_status', array($ProgressBar, 'wps_progress_status'));
			add_action( 'wp_ajax_nopriv_wps_progress_status', array($ProgressBar, 'wps_progress_status'));

			add_action( 'wp_ajax_wps_progress_bar_end', array($ProgressBar, 'wps_progress_bar_end'));
			add_action( 'wp_ajax_nopriv_wps_progress_bar_end', array($ProgressBar, 'wps_progress_bar_end'));


			add_action( 'wp_ajax_wps_progress_session_create', array($ProgressBar, 'wps_progress_session_create'));
			add_action( 'wp_ajax_nopriv_wps_progress_session_create', array($ProgressBar, 'wps_progress_session_create'));


			add_action( 'update_option_wps_settings_general', array($ProgressBar, 'wps_reset_rewrite_rules'), 10, 2 );

		}

	}

}
