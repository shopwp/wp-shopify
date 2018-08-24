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



if ( !class_exists('Bootstrap') ) {

	class Bootstrap {

		public static $instantiated;


		/*

		The init() methods on each classes are responsible for registering
		the necessary hooks, so don't need to do them here.

		*/
		public function __construct() {
			$this->plugin_build();
		}


		public static function run() {

			if (is_null(self::$instantiated)) {
 	      self::$instantiated = new self();
 	    }

 	    return self::$instantiated;

 	  }

		public static function Hooks() {
			return Hooks_Factory::build();
		}


		public function plugin_build() {

			$Config 																										= Config_Factory::build();
			$Activator 																									= Activator_Factory::build();
			$Async_Processing_Database 																	= Async_Processing_Database_Factory::build();
			$Deactivator 																								= Deactivator_Factory::build();
			$License 																										= License_Factory::build();
			$I18N 																											= I18N_Factory::build();
			$Backend 																										= Backend_Factory::build();
			$Frontend 																									= Frontend_Factory::build();
			$Checkouts 																									= Checkouts_Factory::build();
			$Admin_Menus 																								= Admin_Menus_Factory::build();
			$Admin_Notices 																							= Admin_Notices_Factory::build();
			$Templates 																									= Templates_Factory::build();
			$CPT 																												= CPT_Factory::build();
			$Hooks 																											= Hooks_Factory::build();
			$Tools 																											= WS_Tools_Factory::build();
			$WS_CPT 																										= WS_CPT_Factory::build();
			$WS_Settings_Connection 																		= WS_Settings_Connection_Factory::build();
			$WS_Settings_General 																				= WS_Settings_General_Factory::build();
			$WS_Settings_License 																				= WS_Settings_License_Factory::build();
			$WS_Collections 																						= WS_Collections_Factory::build();
			$WS_Collections_Custom 																			= WS_Collections_Custom_Factory::build();
			$WS_Collections_Smart 																			= WS_Collections_Smart_Factory::build();
			$WS_Collects 																								= WS_Collects_Factory::build();
			$WS_Images 																									= WS_Images_Factory::build();
			$WS_Options 																								= WS_Options_Factory::build();
			$WS_Products 																								= WS_Products_Factory::build();
			$WS_Shop 																										= WS_Shop_Factory::build();
			$WS_Tags 																										= WS_Tags_Factory::build();
			$WS_Variants 																								= WS_Variants_Factory::build();


			$WS_Syncing 																								= WS_Syncing_Factory::build();
			$Cart 																											= Cart_Factory::build();
			$Progress_Bar 																							= Progress_Bar_Factory::build();
			$Query 																											= Query_Factory::build();
			$Migrations_122 																						= Migrations_122_Factory::build();


			$Activator->init(); // Registers register_activation_hook
			$Async_Processing_Database->init();
			$Deactivator->init();
			$License->init();
			$I18N->init();
			$Backend->init();
			$Frontend->init();
			$Checkouts->init();
			$Admin_Menus->init();
			$Admin_Notices->init();
			$Templates->init();
			$WS_CPT->init();
			$WS_Settings_Connection->init();
			$WS_Settings_General->init();
			$WS_Settings_License->init();
			$WS_Collections->init();
			$WS_Collections_Custom->init();
			$WS_Collections_Smart->init();
			$WS_Products->init();
			$WS_Collects->init();


			$WS_Images->init();
			$WS_Options->init();
			$WS_Shop->init();
			$WS_Tags->init();
			$WS_Variants->init();
			$WS_Syncing->init();
			$CPT->init();
			$Hooks->init();
			$Tools->init();
			$Cart->init();
			$Progress_Bar->init();
			$Query->init();

			$Migrations_122->init();

		}

	}

}
