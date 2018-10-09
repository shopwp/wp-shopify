<?php

namespace WPS;

if (!defined('ABSPATH')) {
	exit;
}

use WPS\Factories\Config_Factory;
use WPS\Factories\Activator_Factory;
use WPS\Factories\Deactivator_Factory;
use WPS\Factories\License_Factory;
use WPS\Factories\I18N_Factory;
use WPS\Factories\Backend_Factory;
use WPS\Factories\Frontend_Factory;
use WPS\Factories\Checkouts_Factory;
use WPS\Factories\Admin_Menus_Factory;
use WPS\Factories\Admin_Notices_Factory;
use WPS\Factories\Templates_Factory;
use WPS\Factories\CPT_Factory;
use WPS\Factories\Hooks_Factory;
use WPS\Factories\WS_CPT_Factory;
use WPS\Factories\WS_Tools_Factory;
use WPS\Factories\WS_Settings_Connection_Factory;
use WPS\Factories\WS_Settings_General_Factory;
use WPS\Factories\WS_Settings_License_Factory;
use WPS\Factories\WS_Collections_Factory;
use WPS\Factories\WS_Collections_Custom_Factory;
use WPS\Factories\WS_Collections_Smart_Factory;
use WPS\Factories\WS_Collects_Factory;
use WPS\Factories\WS_Images_Factory;
use WPS\Factories\WS_Options_Factory;
use WPS\Factories\WS_Products_Factory;
use WPS\Factories\WS_Shop_Factory;
use WPS\Factories\WS_Tags_Factory;
use WPS\Factories\WS_Variants_Factory;
use WPS\Factories\WS_Syncing_Factory;
use WPS\Factories\Async_Processing_Database_Factory;
use WPS\Factories\Async_Processing_Products_Factory;
use WPS\Factories\Async_Processing_Collects_Factory;
use WPS\Factories\Async_Processing_Tags_Factory;
use WPS\Factories\Async_Processing_Variants_Factory;
use WPS\Factories\Async_Processing_Options_Factory;
use WPS\Factories\Async_Processing_Images_Factory;
use WPS\Factories\Async_Processing_Collections_Smart_Factory;
use WPS\Factories\Async_Processing_Collections_Custom_Factory;
use WPS\Factories\Async_Processing_Posts_Products_Factory;
use WPS\Factories\Async_Processing_Posts_Products_Relationships_Factory;
use WPS\Factories\Async_Processing_Posts_Collections_Relationships_Factory;
use WPS\Factories\Async_Processing_Posts_Collections_Smart_Factory;
use WPS\Factories\Async_Processing_Posts_Collections_Custom_Factory;
use WPS\Factories\Cart_Factory;
use WPS\Factories\Progress_Bar_Factory;
use WPS\Factories\Query_Factory;
use WPS\Factories\Migrations_122_Factory;
use WPS\Factories\API_Settings_Factory;



class Bootstrap {

	public static $instantiated;


	/*

	The init() methods on each classes are responsible for registering
	the necessary hooks, so don't need to do them here.

	*/
	public function __construct() {
		self::plugin_init();
	}


	public static function run() {

		if (is_null(self::$instantiated)) {
			self::$instantiated = new self();
		}

		return self::$instantiated;

	}

	// Legacy
	public static function Hooks() {
		return Hooks_Factory::build();
	}


	public static function plugin_build() {

		$results = [];

		$results['Config'] 												= Config_Factory::build();
		$results['Activator'] 										= Activator_Factory::build();
		$results['Async_Processing_Database'] 		= Async_Processing_Database_Factory::build();
		$results['Deactivator'] 									= Deactivator_Factory::build();
		$results['License'] 											= License_Factory::build();
		$results['I18N'] 													= I18N_Factory::build();
		$results['Backend'] 											= Backend_Factory::build();
		$results['Frontend'] 											= Frontend_Factory::build();
		$results['Checkouts'] 										= Checkouts_Factory::build();
		$results['Admin_Menus'] 									= Admin_Menus_Factory::build();
		$results['Admin_Notices'] 								= Admin_Notices_Factory::build();
		$results['Templates'] 										= Templates_Factory::build();
		$results['CPT'] 													= CPT_Factory::build();
		$results['Hooks'] 												= Hooks_Factory::build();
		$results['Tools'] 												= WS_Tools_Factory::build();
		$results['WS_CPT'] 												= WS_CPT_Factory::build();
		$results['WS_Settings_Connection'] 				= WS_Settings_Connection_Factory::build();
		$results['WS_Settings_General'] 					= WS_Settings_General_Factory::build();
		$results['WS_Settings_License'] 					= WS_Settings_License_Factory::build();
		$results['WS_Collections'] 								= WS_Collections_Factory::build();
		$results['WS_Collections_Custom'] 				= WS_Collections_Custom_Factory::build();
		$results['WS_Collections_Smart'] 					= WS_Collections_Smart_Factory::build();
		$results['WS_Collects'] 									= WS_Collects_Factory::build();
		$results['WS_Images'] 										= WS_Images_Factory::build();
		$results['WS_Options'] 										= WS_Options_Factory::build();
		$results['WS_Products'] 									= WS_Products_Factory::build();
		$results['WS_Shop'] 											= WS_Shop_Factory::build();
		$results['WS_Tags'] 											= WS_Tags_Factory::build();
		$results['WS_Variants'] 									= WS_Variants_Factory::build();


		$results['WS_Syncing'] 										= WS_Syncing_Factory::build();
		$results['Cart'] 													= Cart_Factory::build();
		$results['Progress_Bar'] 									= Progress_Bar_Factory::build();
		$results['Query'] 												= Query_Factory::build();
		$results['Migrations_122'] 								= Migrations_122_Factory::build();
		$results['API_Settings_Factory'] 					= API_Settings_Factory::build();

		return $results;

	}

	public static function plugin_init() {

		$builds = self::plugin_build();

		$builds['Activator']->init();
		$builds['Async_Processing_Database']->init();
		$builds['Deactivator']->init();
		$builds['License']->init();
		$builds['I18N']->init();
		$builds['Backend']->init();
		$builds['Frontend']->init();
		$builds['Checkouts']->init();
		$builds['Admin_Menus']->init();
		$builds['Admin_Notices']->init();
		$builds['Templates']->init();
		$builds['WS_CPT']->init();
		$builds['WS_Settings_Connection']->init();
		$builds['WS_Settings_General']->init();
		$builds['WS_Settings_License']->init();
		$builds['WS_Collections']->init();
		$builds['WS_Collections_Custom']->init();
		$builds['WS_Collections_Smart']->init();
		$builds['WS_Products']->init();
		$builds['WS_Collects']->init();


		$builds['WS_Images']->init();
		$builds['WS_Options']->init();
		$builds['WS_Shop']->init();
		$builds['WS_Tags']->init();
		$builds['WS_Variants']->init();
		$builds['WS_Syncing']->init();
		$builds['CPT']->init();
		$builds['Hooks']->init();
		$builds['Tools']->init();
		$builds['Cart']->init();
		$builds['Progress_Bar']->init();
		$builds['Query']->init();

		$builds['Migrations_122']->init();
		$builds['API_Settings_Factory']->init();

	}

}
