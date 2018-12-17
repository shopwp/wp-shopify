<?php

namespace WPS;

if (!defined('ABSPATH')) {
	exit;
}

use WPS\Factories;


class Bootstrap {

	public static $instantiated;


	/*

	The init() methods on each classes are responsible for registering
	the necessary hooks, so don't need to do them here.

	*/
	public function __construct() {
		self::plugin_init( self::plugin_build() );
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

		$results['Config'] 																= Factories\Config_Factory::build();
		$results['Activator'] 														= Factories\Activator_Factory::build();
		$results['Deactivator'] 													= Factories\Deactivator_Factory::build();
		$results['Updater'] 															= Factories\Updater_Factory::build();
		$results['I18N'] 																	= Factories\I18N_Factory::build();
		$results['Backend'] 															= Factories\Backend_Factory::build();
		$results['Frontend'] 															= Factories\Frontend_Factory::build();
		$results['Admin_Menus'] 													= Factories\Admin_Menus_Factory::build();
		$results['Templates'] 														= Factories\Templates_Factory::build();
		$results['CPT'] 																	= Factories\CPT_Factory::build();
		$results['Hooks'] 																= Factories\Hooks_Factory::build();



		$results['Query'] 																= Factories\Query_Factory::build();
		$results['Migrations_122'] 												= Factories\Migrations_122_Factory::build();


		/*

		API: Settings

		*/
		$results['API_Settings_Checkout_Factory'] 				= Factories\API\Settings\Checkout_Factory::build();
		$results['API_Settings_Cart_Factory'] 						= Factories\API\Settings\Cart_Factory::build();
		$results['API_Settings_Collections_Factory'] 			= Factories\API\Settings\Collections_Factory::build();
		$results['API_Settings_Layout_Factory'] 					= Factories\API\Settings\Layout_Factory::build();
		$results['API_Settings_Products_Factory'] 				= Factories\API\Settings\Products_Factory::build();
		$results['API_Settings_Related_Products_Factory'] = Factories\API\Settings\Related_Products_Factory::build();
		$results['API_Settings_License_Factory'] 					= Factories\API\Settings\License_Factory::build();
		$results['API_Settings_General_Factory'] 					= Factories\API\Settings\General_Factory::build();
		$results['API_Settings_Connection_Factory'] 			= Factories\API\Settings\Connection_Factory::build();


		/*

		API: Syncing

		*/
		$results['API_Status_Factory'] 										= Factories\API\Syncing\Status_Factory::build();
		$results['API_Indicator_Factory'] 								= Factories\API\Syncing\Indicator_Factory::build();
		$results['API_Counts_Factory'] 										= Factories\API\Syncing\Counts_Factory::build();


		/*

		API: Items

		*/
		$results['API_Items_Collections_Factory']					= Factories\API\Items\Collections_Factory::build();
		$results['API_Items_Shop_Factory']								= Factories\API\Items\Shop_Factory::build();
		$results['API_Items_Products_Factory']						= Factories\API\Items\Products_Factory::build();
		$results['API_Items_Variants_Factory']						= Factories\API\Items\Variants_Factory::build();
		$results['API_Items_Collects_Factory']						= Factories\API\Items\Collects_Factory::build();
		$results['API_Items_Posts_Factory']								= Factories\API\Items\Posts_Factory::build();


		/*

		API: Processing

		*/
		$results['API_Processing_Collections_Factory']		= Factories\API\Processing\Collections_Factory::build();
		$results['API_Processing_Shop_Factory']						= Factories\API\Processing\Shop_Factory::build();
		$results['API_Processing_Products_Factory']				= Factories\API\Processing\Products_Factory::build();
		$results['API_Processing_Collects_Factory']				= Factories\API\Processing\Collects_Factory::build();


		/*

		API: Misc

		*/
		$results['API_Misc_Notices_Factory']							= Factories\API\Misc\Notices_Factory::build();
		$results['API_Misc_Routes_Factory']								= Factories\API\Misc\Routes_Factory::build();


		/*

		API: Tools

		*/
		$results['API_Tools_Cache_Factory']							= Factories\API\Tools\Cache_Factory::build();
		$results['API_Tools_Clear_Factory']							= Factories\API\Tools\Clear_Factory::build();


		return $results;


	}


	public static function plugin_init($classes) {

		foreach ($classes as $class) {

			if ( method_exists($class, 'init') ) {
				$class->init();
			}

		}

		return $classes;

	}

}
