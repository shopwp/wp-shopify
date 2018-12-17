<?php

namespace WPS;

if (!defined('ABSPATH')) {
	exit;
}

use WPS\Options;

class Backend {

	private $DB_Settings_General;
	private $DB_Settings_Connection;

	public function __construct($DB_Settings_General, $DB_Settings_Connection) {
		$this->DB_Settings_General 		= $DB_Settings_General;
		$this->DB_Settings_Connection = $DB_Settings_Connection;
	}


	/*

	Checks for a valid admin page

	*/
	public function is_valid_admin_page() {

		$screen = get_current_screen();

		if ( empty($screen) ) {
			return false;
		}

		if ( !is_admin() ) {
			return false;
		}

		return $screen;

	}


	/*

	Checks for a valid admin page

	*/
	public function get_screen_id() {

		$screen = $this->is_valid_admin_page();

		if ( empty($screen) ) {
			return false;
		}

		return $screen->id;

	}


	/*

	Checks for the correct admin page to load CSS

	*/
	public function should_load_css() {

		if ( !$this->is_valid_admin_page() ) {
			return;
		}

		$screen_id = $this->get_screen_id();

		if ($this->is_admin_settings_page($screen_id) || $this->is_admin_posts_page($screen_id) || $this->is_admin_plugins_page($screen_id)) {
			return true;
		}

		return false;

	}


	/*

	Checks for the correct admin page to load JS

	*/
	public function should_load_js() {

		if ( !$this->is_valid_admin_page() ) {
			return;
		}

		$screen_id = $this->get_screen_id();

		if ($this->is_admin_settings_page($screen_id) || $this->is_admin_posts_page($screen_id) ) {
			return true;
		}

		return false;

	}


	/*

	Is wp posts page

	*/
	public function is_admin_posts_page($current_admin_screen_id) {

		if ($current_admin_screen_id === WPS_COLLECTIONS_POST_TYPE_SLUG || $current_admin_screen_id === WPS_PRODUCTS_POST_TYPE_SLUG) {
			return true;
		}

	}


	/*

	Is wp nav menus page

	*/
	public function is_admin_nav_page($current_admin_screen_id) {

		if ( $current_admin_screen_id === 'nav-menus') {
			return true;
		}

	}


	/*

	Is wp plugins page

	*/
	public function is_admin_plugins_page($current_admin_screen_id) {

		if ( $current_admin_screen_id === 'plugins') {
			return true;
		}

	}


	/*

	Is plugin settings page

	*/
	public function is_admin_settings_page($current_admin_screen_id = false) {

		if ( strpos($current_admin_screen_id, 'wp-shopify') !== false ) {
			return true;
		}

	}


	/*

	Admin styles

	*/
	public function admin_styles() {

		if ( $this->should_load_css() ) {

			wp_enqueue_style('wp-color-picker');

			//Enqueue the jQuery UI theme css file from google:
			wp_enqueue_style('jquery-ui-css','//cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css', false, "1.9.0", false);

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
				'gutenberg-components-css',
				WPS_PLUGIN_URL . 'dist/gutenberg-components.min.css',
				[],
				filemtime( WPS_PLUGIN_DIR_PATH . 'dist/gutenberg-components.min.css' )
			);

			wp_enqueue_style(
				WPS_PLUGIN_TEXT_DOMAIN . '-styles-backend',
				WPS_PLUGIN_URL . 'dist/admin.min.css',
				['wp-color-picker', 'animate-css', 'tooltipster-css', 'chosen-css'],
				filemtime( WPS_PLUGIN_DIR_PATH . 'dist/admin.min.css' )
			);

		}

	}


	/*

	Admin scripts

	*/
	public function admin_scripts() {

		if ( $this->should_load_js() ) {

			wp_enqueue_script('jquery-ui-slider');

			wp_enqueue_script(
				'promise-polyfill',
				WPS_PLUGIN_URL . 'admin/js/vendor/es6-promise.auto.min.js',
				['jquery'],
				filemtime( WPS_PLUGIN_DIR_PATH . 'admin/js/vendor/es6-promise.auto.min.js' )
			);

			wp_enqueue_script(
				'tooltipster-js',
				WPS_PLUGIN_URL . 'admin/js/vendor/jquery.tooltipster.min.js',
				['jquery'],
				filemtime( WPS_PLUGIN_DIR_PATH . 'admin/js/vendor/jquery.tooltipster.min.js' )
			);

			wp_enqueue_script(
				'validate-js',
				WPS_PLUGIN_URL . 'admin/js/vendor/jquery.validate.min.js',
				['jquery'],
				filemtime( WPS_PLUGIN_DIR_PATH . 'admin/js/vendor/jquery.validate.min.js' )
			);

			wp_enqueue_script(
				'chosen-js',
				WPS_PLUGIN_URL . 'admin/js/vendor/chosen.jquery.min.js',
				['jquery'],
				filemtime( WPS_PLUGIN_DIR_PATH . 'admin/js/vendor/chosen.jquery.min.js' )
			);

			wp_enqueue_script(
				'anime-js',
				WPS_PLUGIN_URL . 'admin/js/vendor/anime.min.js',
				[],
				filemtime( WPS_PLUGIN_DIR_PATH . 'admin/js/vendor/anime.min.js' )
			);


			// Third-party libs first ...
			wp_enqueue_script(
				WPS_PLUGIN_TEXT_DOMAIN . '-scripts-vendors-admin',
				WPS_PLUGIN_URL . 'dist/vendors-admin.min.js',
				[],
				filemtime( WPS_PLUGIN_DIR_PATH . 'dist/vendors-admin.min.js' )
			);

			// Commonly shared third-party libs second ...
			wp_enqueue_script(
				WPS_PLUGIN_TEXT_DOMAIN . '-scripts-vendors-common',
				WPS_PLUGIN_URL . 'dist/vendors-admin-public.min.js',
				[],
				filemtime( WPS_PLUGIN_DIR_PATH . 'dist/vendors-admin-public.min.js' )
			);

			// Commonly shared pub / admin code ...
			// wp_enqueue_script(
			// 	WPS_PLUGIN_TEXT_DOMAIN . '-scripts-admin-public-common',
			// 	WPS_PLUGIN_URL . 'dist/admin-public.min.js',
			// 	[],
			// 	filemtime( WPS_PLUGIN_DIR_PATH . 'dist/admin-public.min.js' )
			// );

			wp_enqueue_script(
				WPS_PLUGIN_TEXT_DOMAIN . '-scripts-backend',
				WPS_PLUGIN_URL . 'dist/admin.min.js',
				[
					'jquery',
					'promise-polyfill',
					'tooltipster-js',
					'validate-js',
					'chosen-js',
					WPS_PLUGIN_TEXT_DOMAIN . '-scripts-vendors-admin',
					WPS_PLUGIN_TEXT_DOMAIN . '-scripts-vendors-common'
				],
				filemtime( WPS_PLUGIN_DIR_PATH . 'dist/admin.min.js' )
			);


			wp_localize_script(
				WPS_PLUGIN_TEXT_DOMAIN . '-scripts-backend',
				WPS_PLUGIN_NAME_JS,
				[
					'ajax' 											=> __(admin_url('admin-ajax.php')),
					'pluginsPath' 							=> __(plugins_url()),
					'siteUrl' 									=> site_url(),
					'pluginsDirURL' 						=> plugin_dir_url(dirname(__FILE__)),
					'nonce'											=> wp_create_nonce(WPS_BACKEND_NONCE_ACTION),
					'nonce_api'									=> wp_create_nonce('wp_rest'),
					'selective_sync' 						=> $this->DB_Settings_General->selective_sync_status(),
					'reconnectingWebhooks' 			=> false,
					'hasConnection' 						=> $this->DB_Settings_Connection->has_connection(),
					'isSyncing' 								=> false,
					'manuallyCanceled' 					=> false,
					'isClearing' 								=> false,
					'isDisconnecting' 					=> false,
					'isConnecting' 							=> false,
					'latestVersion'							=> WPS_NEW_PLUGIN_VERSION,
					'latestVersionCombined'			=> str_replace('.', '', WPS_NEW_PLUGIN_VERSION),
					'migrationNeeded'						=> Options::get('wp_shopify_migration_needed'),
					'itemsPerRequest'						=> $this->DB_Settings_General->get_items_per_request(),
					'maxItemsPerRequest'				=> WPS_MAX_ITEMS_PER_REQUEST,
					'settings'									=> [
						'colorAddToCart' 											=> $this->DB_Settings_General->get_add_to_cart_color(),
						'colorVariant' 												=> $this->DB_Settings_General->get_variant_color(),
						'colorCheckout' 											=> $this->DB_Settings_General->get_checkout_color(),
						'colorCartCounter' 										=> $this->DB_Settings_General->get_cart_counter_color(),
						'colorCartIcon' 											=> $this->DB_Settings_General->get_cart_icon_color(),
						'productsHeading' 										=> $this->DB_Settings_General->get_products_heading(),
						'collectionsHeading' 									=> $this->DB_Settings_General->get_collections_heading(),
						'relatedProductsHeading'							=> $this->DB_Settings_General->get_related_products_heading(),
						'productsHeadingToggle'								=> $this->DB_Settings_General->get_products_heading_toggle(),
						'collectionsHeadingToggle'						=> $this->DB_Settings_General->get_collections_heading_toggle(),
						'relatedProductsHeadingToggle'				=> $this->DB_Settings_General->get_related_products_heading_toggle(),
						'productsImagesSizingToggle'					=> $this->DB_Settings_General->get_products_images_sizing_toggle(),
						'productsImagesSizingWidth'						=> $this->DB_Settings_General->get_products_images_sizing_width(),
						'productsImagesSizingHeight'					=> $this->DB_Settings_General->get_products_images_sizing_height(),
						'productsImagesSizingCrop'						=> $this->DB_Settings_General->get_products_images_sizing_crop(),
						'productsImagesSizingScale'						=> $this->DB_Settings_General->get_products_images_sizing_scale(),
						'collectionsImagesSizingToggle'				=> $this->DB_Settings_General->get_collections_images_sizing_toggle(),
						'collectionsImagesSizingWidth'				=> $this->DB_Settings_General->get_collections_images_sizing_width(),
						'collectionsImagesSizingHeight'				=> $this->DB_Settings_General->get_collections_images_sizing_height(),
						'collectionsImagesSizingCrop'					=> $this->DB_Settings_General->get_collections_images_sizing_crop(),
						'collectionsImagesSizingScale'				=> $this->DB_Settings_General->get_collections_images_sizing_scale(),
						'relatedProductsImagesSizingToggle'		=> $this->DB_Settings_General->get_related_products_images_sizing_toggle(),
						'relatedProductsImagesSizingWidth'		=> $this->DB_Settings_General->get_related_products_images_sizing_width(),
						'relatedProductsImagesSizingHeight'		=> $this->DB_Settings_General->get_related_products_images_sizing_height(),
						'relatedProductsImagesSizingCrop'			=> $this->DB_Settings_General->get_related_products_images_sizing_crop(),
						'relatedProductsImagesSizingScale'		=> $this->DB_Settings_General->get_related_products_images_sizing_scale(),
						'enableCustomCheckoutDomain'					=> $this->DB_Settings_General->get_enable_custom_checkout_domain(),
						'productsCompareAt'										=> $this->DB_Settings_General->get_products_compare_at(),
						'productsShowPriceRange'							=> $this->DB_Settings_General->get_col_value('products_show_price_range', 'bool'),
						'checkoutButtonTarget'								=> $this->DB_Settings_General->get_col_value('checkout_button_target', 'string'),
						'connection'	=> [
							'saveConnectionOnly' => $this->DB_Settings_General->get_col_value('save_connection_only', 'bool')
						]
					],
					'API' => [
						'namespace'			=> WP_SHOPIFY_API_NAMESPACE,
						'baseUrl' 			=> site_url(),
						'urlPrefix'			=> rest_get_url_prefix(),
						'restUrl'				=> get_rest_url()
					],
					'timers' => [
						'syncing'	=> false
					]
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
				__($this->DB_Settings_General->plugin_nice_name(), WPS_PLUGIN_NAME),
				__($this->DB_Settings_General->plugin_nice_name(), WPS_PLUGIN_NAME),
				'manage_options',
				'wpshopify',
				[$this, 'plugin_admin_page'],
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
				[$this, 'plugin_admin_page']
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
	public function plugin_admin_page() {
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


	public function get_active_tab($GET) {

		if (isset($GET['activetab']) && $GET['activetab']) {
	    $active_tab = $GET['activetab'];

	  } else {
	    $active_tab = 'tab-connect';
	  }

		return $active_tab;

	}

	public function get_active_sub_tab($GET) {

		if (isset($GET['activesubnav']) && $GET['activesubnav']) {
			$active_sub_nav = $GET['activesubnav'];

		} else {
			$active_sub_nav = 'wps-admin-section-general'; // default sub nav
		}

		return $active_sub_nav;

	}


	/*

	Hooks

	*/
	public function hooks() {

		add_action('admin_menu', [$this, 'add_dashboard_menus']);
		add_action('admin_enqueue_scripts', [$this, 'admin_styles']);
		add_action('admin_enqueue_scripts', [$this, 'admin_scripts']);
		add_filter('plugin_action_links_' . WPS_PLUGIN_BASENAME, [$this, 'add_action_links']);
		add_action('admin_init', [$this, 'on_options_update']);

	}


	/*

	Init

	*/
	public function init() {
		$this->hooks();
	}

}
