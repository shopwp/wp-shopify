<?php

namespace WPS;

use WPS\Utils;


if (!defined('ABSPATH')) {
	exit;
}

class Activator {

	private $DB_Settings_Connection;
	private $DB_Settings_General;
	private $DB_Settings_License;
	private $DB_Shop;
	private $DB_Products;
	private $DB_Variants;
	private $DB_Collects;
	private $DB_Options;
	private $DB_Collections_Custom;
	private $DB_Collections_Smart;
	private $DB_Images;
	private $DB_Tags;
	private $CPT;
	private $DB_Customers;
	private $DB_Orders;
	private $DB_Settings_Syncing;


	public function __construct($DB_Settings_Connection, $DB_Settings_General, $DB_Settings_License, $DB_Shop, $DB_Products, $DB_Variants, $DB_Collects, $DB_Options, $DB_Collections_Custom, $DB_Collections_Smart, $DB_Images, $DB_Tags, $CPT, $DB_Customers, $DB_Orders, $DB_Settings_Syncing) {

		$this->DB_Settings_Connection 			= $DB_Settings_Connection;
		$this->DB_Settings_General 					= $DB_Settings_General;
		$this->DB_Settings_License 					= $DB_Settings_License;
		$this->DB_Shop 											= $DB_Shop;
		$this->DB_Products 									= $DB_Products;
		$this->DB_Variants 									= $DB_Variants;
		$this->DB_Collects 									= $DB_Collects;
		$this->DB_Options 									= $DB_Options;
		$this->DB_Collections_Custom 				= $DB_Collections_Custom;
		$this->DB_Collections_Smart 				= $DB_Collections_Smart;
		$this->DB_Images 										= $DB_Images;
		$this->DB_Tags 											= $DB_Tags;
		$this->CPT 													= $CPT;

		// Pro only
		$this->DB_Customers 								= $DB_Customers;
		$this->DB_Orders 										= $DB_Orders;

		$this->DB_Settings_Syncing					= $DB_Settings_Syncing;

	}


	/*

	Create DB Tables

	*/
	public function create_db_tables($network_wide) {

		$results = [];

		$results['DB_Settings_Connection'] 		= $this->DB_Settings_Connection->create_table($network_wide);
		$results['DB_Settings_General'] 			= $this->DB_Settings_General->create_table($network_wide);
		$results['DB_Settings_License'] 			= $this->DB_Settings_License->create_table($network_wide);
		$results['DB_Shop'] 									= $this->DB_Shop->create_table($network_wide);
		$results['DB_Products'] 							= $this->DB_Products->create_table($network_wide);
		$results['DB_Variants'] 							= $this->DB_Variants->create_table($network_wide);
		$results['DB_Collects'] 							= $this->DB_Collects->create_table($network_wide);
		$results['DB_Options'] 								= $this->DB_Options->create_table($network_wide);
		$results['DB_Collections_Custom'] 		= $this->DB_Collections_Custom->create_table($network_wide);
		$results['DB_Collections_Smart'] 			= $this->DB_Collections_Smart->create_table($network_wide);
		$results['DB_Images'] 								= $this->DB_Images->create_table($network_wide);
		$results['DB_Tags'] 									= $this->DB_Tags->create_table($network_wide);
		$results['DB_Settings_Syncing'] 			= $this->DB_Settings_Syncing->create_table($network_wide);


		return $results;

	}


	/*

	Sets default plugin settings and inserts default rows

	*/
	public function set_default_table_values($network_wide) {

		$results = [];

		$results['DB_Settings_General'] = $this->DB_Settings_General->init($network_wide);
		$results['DB_Settings_Syncing'] = $this->DB_Settings_Syncing->init($network_wide);

		return $results;

	}


	public function set_table_charset_cache() {

		$this->DB_Settings_General->get_table_charset( $this->DB_Settings_Connection->get_table_name() );
		$this->DB_Settings_General->get_table_charset( $this->DB_Settings_General->get_table_name() );
		$this->DB_Settings_General->get_table_charset( $this->DB_Settings_License->get_table_name() );
		$this->DB_Settings_General->get_table_charset( $this->DB_Settings_Syncing->get_table_name() );
		$this->DB_Settings_General->get_table_charset( $this->DB_Shop->get_table_name() );
		$this->DB_Settings_General->get_table_charset( $this->DB_Products->get_table_name() );
		$this->DB_Settings_General->get_table_charset( $this->DB_Variants->get_table_name() );
		$this->DB_Settings_General->get_table_charset( $this->DB_Collects->get_table_name() );
		$this->DB_Settings_General->get_table_charset( $this->DB_Options->get_table_name() );
		$this->DB_Settings_General->get_table_charset( $this->DB_Collections_Custom->get_table_name() );
		$this->DB_Settings_General->get_table_charset( $this->DB_Collections_Smart->get_table_name() );
		$this->DB_Settings_General->get_table_charset( $this->DB_Images->get_table_name() );
		$this->DB_Settings_General->get_table_charset( $this->DB_Tags->get_table_name() );


	}


	public function bootstrap_tables($network_wide) {

		$results = [];

		$results['create_db_tables'] = $this->create_db_tables($network_wide);
		$results['set_default_table_values'] = $this->set_default_table_values($network_wide);

		$this->set_table_charset_cache();

		return $results;

	}





	public function deactivate_free_version() {

		if ( Utils::is_free_active() ) {

			require_once(ABSPATH . 'wp-admin/includes/plugin.php');
			\deactivate_plugins(WPS_FREE_FILE_ROOT);

		}

		// If for some reason is_fre is set but the free version isnt actually active
		if ( !Utils::is_free_active() && $this->DB_Settings_General->is_free_tier() ) {
			$this->DB_Settings_General->set_free_tier(0);
		}

	}


	/*

	Only runs after bootstrapping has occured. Need this check to check whether
	DB_Settings_General actually exists first.

	*/
	public function toggle_activation_flags() {

		if ( Utils::plugin_ready() ) {


			if ( Utils::is_free_active() ) {
				$this->DB_Settings_General->set_free_tier();
			}

		}

	}



	public function get_ready($network_wide) {

		// Builds the custom tables
		$this->bootstrap_tables($network_wide);

		// Registers our CPTs
		$this->CPT->init();

		// Forces WP to check for plugin updates on activation
		delete_option('_site_transient_update_plugins');

		// Ensure out CPTs work as expected
		flush_rewrite_rules();

	}


	/*

	Runs when the plugin is activated as a result of register_activation_hook. Runs for both Free and Pro versions

	*/
	public function on_plugin_activate($network_wide) {

		if ( !Utils::plugin_ready() ) {

			$this->get_ready($network_wide);

			update_option('wp_shopify_is_ready', true);

		}

	}


	/*

	Runs when a new blog is created within a multi-site setup

	*/
	function on_blog_create($blog_id, $user_id, $domain, $path, $site_id, $meta) {

		if ( is_plugin_active_for_network( Utils::get_subdir_and_file() ) ) {

			switch_to_blog($blog_id);

			$this->get_ready(false);

			update_option('wp_shopify_is_ready', true);

			restore_current_blog();

		}

	}


	/*

	Deletes custom tables when blog is deleted

	$tables is an array containing a list of table names in string format

	*/
	function on_blog_delete($tables) {

		$tables[] = $this->DB_Settings_Connection->get_table_name();
		$tables[] = $this->DB_Settings_General->get_table_name();
		$tables[] = $this->DB_Settings_License->get_table_name();
		$tables[] = $this->DB_Shop->get_table_name();
		$tables[] = $this->DB_Products->get_table_name();
		$tables[] = $this->DB_Variants->get_table_name();
		$tables[] = $this->DB_Collects->get_table_name();
		$tables[] = $this->DB_Options->get_table_name();
		$tables[] = $this->DB_Collections_Custom->get_table_name();
		$tables[] = $this->DB_Collections_Smart->get_table_name();
		$tables[] = $this->DB_Images->get_table_name();
		$tables[] = $this->DB_Tags->get_table_name();
		$tables[] = $this->DB_Settings_Syncing->get_table_name();


		return $tables;

	}



	public function hooks() {

		add_action('wps_on_plugin_activate', [$this, 'on_plugin_activate']);
		add_action('wpmu_new_blog', [$this, 'on_blog_create'], 10, 6);
		add_filter('wpmu_drop_tables', [$this, 'on_blog_delete']);
		add_action('admin_init', [$this, 'toggle_activation_flags']);

	}


	public function init() {
		$this->hooks();
	}


}
