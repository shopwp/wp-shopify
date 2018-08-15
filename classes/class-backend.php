<?php

namespace WPS;

if (!defined('ABSPATH')) {
	exit;
}


if ( !class_exists('Backend') ) {

	class Backend {

		private $DB_Settings_General;
		private $DB_Settings_Connection;

		public function __construct($DB_Settings_General, $DB_Settings_Connection) {
			$this->DB_Settings_General 		= $DB_Settings_General;
			$this->DB_Settings_Connection = $DB_Settings_Connection;
		}


		/*

		Admin styles

		*/
		public function admin_styles() {

			// Only loading styles if we're on the settings page ...
			if ( !empty(get_current_screen()) && get_current_screen()->id === 'wp-shopify_page_wps-settings' || get_current_screen()->id === WPS_PRODUCTS_POST_TYPE_SLUG || get_current_screen()->id === WPS_COLLECTIONS_POST_TYPE_SLUG || get_current_screen()->id === 'plugins') {

				wp_enqueue_style('wp-color-picker');

				wp_enqueue_style(
					'animate-css',
					WPS_PLUGIN_URL . 'admin/css/vendor/animate.min.css',
					[],
					filemtime( WPS_PLUGIN_DIR_PATH . 'admin/css/vendor/animate.min.css' )
				);

				wp_enqueue_style(
					'tooltipster-css',
					WPS_PLUGIN_URL . 'admin/css/vendor/tooltipster.min.css',
					[],
					filemtime( WPS_PLUGIN_DIR_PATH . 'admin/css/vendor/tooltipster.min.css' )
				);

				wp_enqueue_style(
					'chosen-css',
					WPS_PLUGIN_URL . 'admin/css/vendor/chosen.min.css',
					[],
					filemtime( WPS_PLUGIN_DIR_PATH . 'admin/css/vendor/chosen.min.css' )
				);

				wp_enqueue_style(
					WPS_PLUGIN_TEXT_DOMAIN . '-styles-backend',
					WPS_PLUGIN_URL . 'dist/admin.min.css',
					['wp-color-picker', 'animate-css', 'tooltipster-css', 'chosen-css'],
					filemtime( WPS_PLUGIN_DIR_PATH . 'dist/admin.min.css' ),
					'all'
				);

			}

		}


		/*

		Admin scripts

		*/
		public function admin_scripts() {

			// Only loading admin script if we're on the settings page ...
			if ( !empty(get_current_screen()) && get_current_screen()->id === 'wp-shopify_page_wps-settings' || get_current_screen()->id === WPS_PRODUCTS_POST_TYPE_SLUG || get_current_screen()->id === WPS_COLLECTIONS_POST_TYPE_SLUG || get_current_screen()->id === 'nav-menus') {

				wp_enqueue_media();

				if (is_object($this->DB_Settings_General) && method_exists($this->DB_Settings_General, 'selective_sync_status') ) {
					$selectiveSyncValue = $this->DB_Settings_General->selective_sync_status();

				} else {
					$selectiveSyncValue = false;
				}


				wp_enqueue_script(
					'promise-polyfill',
					WPS_PLUGIN_URL . 'admin/js/vendor/es6-promise.auto.min.js',
					array('jquery'),
					filemtime( WPS_PLUGIN_DIR_PATH . 'admin/js/vendor/es6-promise.auto.min.js' ),
					true
				);


				wp_enqueue_script(
					'tooltipster-js',
					WPS_PLUGIN_URL . 'admin/js/vendor/jquery.tooltipster.min.js',
					array('jquery'),
					filemtime( WPS_PLUGIN_DIR_PATH . 'admin/js/vendor/jquery.tooltipster.min.js' ),
					false
				);


				wp_enqueue_script(
					'validate-js',
					WPS_PLUGIN_URL . 'admin/js/vendor/jquery.validate.min.js',
					array('jquery'),
					filemtime( WPS_PLUGIN_DIR_PATH . 'admin/js/vendor/jquery.validate.min.js' ),
					false
				);


				wp_enqueue_script(
					'chosen-js',
					WPS_PLUGIN_URL . 'admin/js/vendor/chosen.jquery.min.js',
					array('jquery'),
					filemtime( WPS_PLUGIN_DIR_PATH . 'admin/js/vendor/chosen.jquery.min.js' ),
					false
				);


				wp_enqueue_script(
					'anime-js',
					WPS_PLUGIN_URL . 'admin/js/vendor/anime.min.js',
					[],
					filemtime( WPS_PLUGIN_DIR_PATH . 'admin/js/vendor/anime.min.js' ),
					false
				);


				wp_enqueue_script(
					WPS_PLUGIN_TEXT_DOMAIN . '-scripts-backend',
					WPS_PLUGIN_URL . 'dist/admin.min.js',
					array('jquery', 'promise-polyfill', 'tooltipster-js', 'validate-js', 'chosen-js'),
					filemtime( WPS_PLUGIN_DIR_PATH . 'dist/admin.min.js' ),
					true
				);


				wp_localize_script(
					WPS_PLUGIN_TEXT_DOMAIN . '-scripts-backend',
					WPS_PLUGIN_NAME_JS,
					[
						'ajax' 										=> __(admin_url('admin-ajax.php')),
						'pluginsPath' 						=> __(plugins_url()),
						'siteUrl' 								=> site_url(),
						'pluginsDirURL' 					=> plugin_dir_url(dirname(__FILE__)),
						'nonce'										=> wp_create_nonce(WPS_BACKEND_NONCE_ACTION),
						'selective_sync' 					=> $selectiveSyncValue,
						'reconnectingWebhooks' 		=> false,
						'hasConnection' 					=> $this->DB_Settings_Connection->has_connection(),
						'isSyncing' 							=> false,
						'manuallyCanceled' 				=> false,
						'isClearing' 							=> false,
						'isDisconnecting' 				=> false,
						'isConnecting' 						=> false
					]
				);

			}

		}


		/*

		Registering the admin menu into the WordPress Dashboard menu.
		Adding a settings page to the Settings menu.

		*/
		public function add_dashboard_menus() {

			if (current_user_can('manage_options')) {

				global $submenu;

				$icon_svg = 'data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz4KPCEtLSBHZW5lcmF0b3I6IEFkb2JlIElsbHVzdHJhdG9yIDIxLjAuMCwgU1ZHIEV4cG9ydCBQbHVnLUluIC4gU1ZHIFZlcnNpb246IDYuMDAgQnVpbGQgMCkgIC0tPgo8c3ZnIHZlcnNpb249IjEuMSIgaWQ9IkxheWVyXzEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiIHg9IjBweCIgeT0iMHB4IgoJIHZpZXdCb3g9IjAgMCAxOCAxOCIgc3R5bGU9ImVuYWJsZS1iYWNrZ3JvdW5kOm5ldyAwIDAgMTggMTg7IiB4bWw6c3BhY2U9InByZXNlcnZlIj4KPHN0eWxlIHR5cGU9InRleHQvY3NzIj4KCS5Ecm9wX3gwMDIwX1NoYWRvd3tmaWxsOm5vbmU7fQoJLlJvdW5kX3gwMDIwX0Nvcm5lcnNfeDAwMjBfMl94MDAyMF9wdHtmaWxsOiNGRkZGRkY7c3Ryb2tlOiMyMzFGMjA7c3Ryb2tlLW1pdGVybGltaXQ6MTA7fQoJLkxpdmVfeDAwMjBfUmVmbGVjdF94MDAyMF9Ye2ZpbGw6bm9uZTt9CgkuQmV2ZWxfeDAwMjBfU29mdHtmaWxsOnVybCgjU1ZHSURfMV8pO30KCS5EdXNre2ZpbGw6I0ZGRkZGRjt9CgkuRm9saWFnZV9HU3tmaWxsOiNGRkREMDA7fQoJLlBvbXBhZG91cl9HU3tmaWxsLXJ1bGU6ZXZlbm9kZDtjbGlwLXJ1bGU6ZXZlbm9kZDtmaWxsOiM0NEFERTI7fQo8L3N0eWxlPgo8bGluZWFyR3JhZGllbnQgaWQ9IlNWR0lEXzFfIiBncmFkaWVudFVuaXRzPSJ1c2VyU3BhY2VPblVzZSIgeDE9IjAiIHkxPSIwIiB4Mj0iMC43MDcxIiB5Mj0iMC43MDcxIj4KCTxzdG9wICBvZmZzZXQ9IjAiIHN0eWxlPSJzdG9wLWNvbG9yOiNERURGRTMiLz4KCTxzdG9wICBvZmZzZXQ9IjAuMTc4MyIgc3R5bGU9InN0b3AtY29sb3I6I0RBREJERiIvPgoJPHN0b3AgIG9mZnNldD0iMC4zNjExIiBzdHlsZT0ic3RvcC1jb2xvcjojQ0VDRkQzIi8+Cgk8c3RvcCAgb2Zmc2V0PSIwLjU0NiIgc3R5bGU9InN0b3AtY29sb3I6I0I5QkNCRiIvPgoJPHN0b3AgIG9mZnNldD0iMC43MzI0IiBzdHlsZT0ic3RvcC1jb2xvcjojOUNBMEEyIi8+Cgk8c3RvcCAgb2Zmc2V0PSIwLjkxODEiIHN0eWxlPSJzdG9wLWNvbG9yOiM3ODdEN0UiLz4KCTxzdG9wICBvZmZzZXQ9IjEiIHN0eWxlPSJzdG9wLWNvbG9yOiM2NTZCNkMiLz4KPC9saW5lYXJHcmFkaWVudD4KPHBhdGggZD0iTTksMC4yQzQuMSwwLjIsMC4yLDQuMSwwLjIsOXMzLjksOC44LDguOCw4LjhzOC44LTMuOSw4LjgtOC44UzEzLjgsMC4yLDksMC4yeiBNNi4yLDE0LjVjLTAuNCwwLTAuNy0wLjItMC44LTAuNkwzLDUuMgoJYzAtMC4xLDAtMC4xLDAtMC4yYzAtMC4zLDAuMi0wLjQsMC41LTAuNWMwLjEsMCwwLjEsMCwwLjIsMGMwLjIsMCwwLjUsMC4xLDAuNSwwLjRsMS4zLDVsMC4xLDAuNWwwLjYsMi40bDAuNCwxLjcKCUM2LjYsMTQuNSw2LjQsMTQuNSw2LjIsMTQuNXogTTExLjgsMTQuNWMtMC40LDAtMC43LTAuMi0wLjgtMC42YzAsMC0wLjYtMi40LTAuOS00SDguNmwwLjItMC44YzAsMCwwLjEtMC44LDAuNi0wLjgKCWMwLjIsMCwwLjMsMC4xLDAuNCwwLjNDOS42LDguNCw5LjQsOCw5LDhDOC4zLDgsOC4yLDguNyw4LjIsOC43bC0wLjcsMy4yTDYuOSw5LjlsMC4zLTEuMWwwLDAuMWwxLTMuOUM4LjMsNC43LDguNiw0LjUsOSw0LjUKCWMwLjQsMCwwLjcsMC4yLDAuOCwwLjZsMC43LDIuNkwxMC44LDlsMC40LDEuN2wwLjUsMmwwLDAuMWwwLjUsMS42QzEyLjIsMTQuNSwxMiwxNC41LDExLjgsMTQuNXogTTE1LjEsNS4xbC0yLDcuMUwxMi41LDEwbDAuMy0xLjEKCUwxMyw4LjJ2MGwwLjktMy40YzAuMS0wLjMsMC4zLTAuNCwwLjYtMC40YzAuMSwwLDAuMSwwLDAuMiwwYzAuMywwLjEsMC41LDAuMiwwLjUsMC41QzE1LjEsNSwxNS4xLDUuMSwxNS4xLDUuMXoiLz4KPC9zdmc+Cg==';

				// Main menu
				add_menu_page(
					__('WP Shopify', WPS_PLUGIN_NAME),
					__('WP Shopify', WPS_PLUGIN_NAME),
					'manage_options',
					'wpshopify',
					array($this, 'wps_config_display_setup_page'),
					$icon_svg,
					null
				);

				// Submenu: Settings
				add_submenu_page(
					'wpshopify',
					__('Settings', WPS_PLUGIN_NAME),
					__('Settings', WPS_PLUGIN_NAME),
					'manage_options',
					'wps-settings',
					array($this, 'wps_config_display_setup_page')
				);

				// Submenu: Products
				add_submenu_page(
					'wpshopify',
					__('Products', WPS_PLUGIN_NAME),
					__('Products', WPS_PLUGIN_NAME),
					'manage_options',
					'edit.php?post_type=' . WPS_PRODUCTS_POST_TYPE_SLUG,
					null
				);

				// Submenu: Collections
				add_submenu_page(
					'wpshopify',
					__('Collections', WPS_PLUGIN_NAME),
					__('Collections', WPS_PLUGIN_NAME),
					'manage_options',
					'edit.php?post_type=' . WPS_COLLECTIONS_POST_TYPE_SLUG,
					null
				);

				//
				// // Submenu: Tags
				// add_submenu_page(
				// 	'wpshopify',
				// 	__('Tags', WPS_PLUGIN_NAME),
				// 	__('Tags', WPS_PLUGIN_NAME),
				// 	'manage_options',
				// 	'edit-tags.php?taxonomy=wps_tags&post_type=' . WPS_PRODUCTS_POST_TYPE_SLUG,
				// 	null
				// );

				remove_submenu_page('wpshopify', 'wpshopify');

			}


		}


		/*

		Add settings action link to the plugins page.

		*/
		public function add_action_links($links) {

			$settings_link = ['<a href="' . esc_url( admin_url('/admin.php?page=' . WPS_PLUGIN_NAME) . '-settings' ) . '">' . esc_html__('Settings', WPS_PLUGIN_TEXT_DOMAIN) . '</a>'];

			return array_merge($settings_link, $links);

		}


		/*

		Render the settings page for this plugin.

		*/
		public function wps_config_display_setup_page() {
			include_once(WPS_PLUGIN_DIR_PATH . 'admin/partials/wps-admin-display.php');
		}


		/*

		Register / Update plugin options
		Currently only updating connection form

		*/
		public function on_options_update() {
	    register_setting( WPS_SETTINGS_CONNECTION_OPTION_NAME, WPS_SETTINGS_CONNECTION_OPTION_NAME, [$this, 'connection_form_validate'] );
			register_setting( WPS_SETTINGS_GENERAL_OPTION_NAME, WPS_SETTINGS_GENERAL_OPTION_NAME, [$this, 'general_form_validate'] );
		}


		/*

		Validate connection form settings

		*/
		public function connection_form_validate($input) {


	    // All checkboxes inputs
	    $valid = [];

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
		public function general_form_validate($input) {


			// All checkboxes inputs
			$valid = [];

			// Products URL
			$valid['wps_general_url_products'] = isset($input['wps_general_url_products']) && !empty($input['wps_general_url_products']) ? sanitize_text_field($input['wps_general_url_products']) : '';

			// Collections URL
			$valid['wps_general_url_collections'] = isset($input['wps_general_url_collections']) && !empty($input['wps_general_url_collections']) ? sanitize_text_field($input['wps_general_url_collections']) : '';

			return $valid;

		}


		/*

		Inserting authentication modal below settings form

		*/
		public function insert_auth_modal() {

			if (isset($_GET["auth"]) && trim($_GET["auth"]) == 'true') {
				printf(esc_html__('<div class="wps-connector-wrapper wps-is-connected"><div class="wps-connector wps-connector-progress" style="display:block;opacity:1;"><h1 class="wps-connector-heading">Connecting <img class="wps-connector-logo" src="%1" /> to <img class="wps-connector-logo" src="%2" /></h1><div class="wps-l-row"><button type="button" name="button" class="button button-primary wps-btn wps-btn-cancel button button-primary">Cancel</button></div><div class="wps-connector-content"></div></div></div>'), esc_url(WPS_PLUGIN_URL . 'admin/imgs/logo-wp.svg'), esc_url(WPS_PLUGIN_URL . 'admin/imgs/shopify.svg'));
			}

		}


		/*

		Hooks

		*/
		public function hooks() {

			add_action('admin_menu', [$this, 'add_dashboard_menus']);
			add_action('admin_enqueue_scripts', [$this, 'admin_styles']);
			add_action('admin_enqueue_scripts', [$this, 'admin_scripts']);
			add_filter('plugin_action_links_' . WPS_PLUGIN_BASENAME, [$this, 'add_action_links']);
			add_action('wps_after_settings_form', [$this, 'insert_auth_modal'], 1);
			add_action('admin_init', [$this, 'on_options_update']);

		}


		/*

		Init

		*/
		public function init() {
			$this->hooks();
		}

	}

}
