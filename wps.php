<?php

/*

WP Shopify

@link              https://wpshop.io
@since             0.1.14
@package           WPS

@wordpress-plugin
Plugin Name:       WP Shopify
Plugin URI:        https://wpshop.io
Description:       Sync your Shopify store with WordPress. Designed to be extensible, seamless, and lightweight.
Version:           0.1.14
Author:            Andrew Robbins
Author URI:        https://blog.simpleblend.net
License:           GPL-2.0+
License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
Text Domain:       wps
Domain Path:       /languages

*/
namespace WPS;

if ( !function_exists('version_compare') || version_compare(PHP_VERSION, '5.3.0', '<' )) {
	exit;
}

// If this file is called directly, abort.
if (!defined('ABSPATH')) {
	exit;
}

include_once('lib/autoloader.php');

use WPS\Config;
use WPS\Frontend;
use WPS\Backend;
use WPS\Hooks;
use WPS\Products_General;
use WPS\Collections;
use WPS\Waypoints;
use WPS\AJAX;
use WPS\WS;
use WPS\CPT;
use WPS\Webhooks;
use WPS\License;
use WPS\Deactivator;
use WPS\Activator;

/*

Begins execution of the plugin.

Since everything within the plugin is registered via hooks,
then kicking off the plugin from this point in the file does
not affect the page life cycle.

*/
final class Boot {

	private static $instantiated = null;

	public $Config = null;
	public $Activator = null;
	public $Deactivator = null;
	public $Frontend = null;
	public $Backend = null;
	public $Hooks = null;
	public $Products = null;
	public $Collections = null;
	public $Waypoints = null;
	public $AJAX = null;
	public $WS = null;
	public $Webhooks = null;
	public $License = null;
	public $I18N = null;
	public $CPT = null;


	/*

	Creates a new class if one hasn't already been created.
	Ensures only one instance is used.

	*/
	public static function instance() {

		if (is_null(self::$instantiated)) {
			// error_log('... Creating a new instance ...');
			self::$instantiated = new self();

		} else {
			// error_log('... Already found an instance ...');
		}

		return self::$instantiated;

	}


	/*

	Initialize the class

	*/
	public function __construct() {

		do_action('wps_before_bootstrap');

		$this->Config 				= new Config();
		$this->Activator 			= new Activator($this->Config);
		$this->Deactivator 		= new Deactivator($this->Config);
		$this->Frontend 			= new Frontend($this->Config);
		$this->Backend 				= new Backend($this->Config);
		$this->Hooks 					= new Hooks($this->Config);
		$this->Products 			= new Products_General($this->Config);
		$this->Collections 		= new Collections($this->Config);
		$this->Waypoints 			= new Waypoints($this->Config);
		$this->AJAX 					= new AJAX($this->Config);
		$this->WS 						= new WS($this->Config);
		$this->Webhooks 			= new Webhooks($this->Config);
		$this->License 				= new License($this->Config);
		$this->I18N 					= new I18N($this->Config);
		$this->CPT 						= new CPT($this->Config);

		$this->License->init();
		$this->Activator->init();
		$this->Deactivator->init();
		$this->Frontend->wps_frontend_hooks();
		$this->Backend->wps_backend_hooks();
		$this->Hooks->init();
		$this->I18N->init();
		$this->CPT->init();

		do_action('wps_after_bootstrap');

	}


	/*

	Stop Cloning

	*/
	public function __clone() {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'wpshopify' ), '2.1' );
	}


	/*

	Prevent Unserializing class instances

	*/
	public function __wakeup() {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'wpshopify' ), '2.1' );
	}


	/*

	Get Frontend Class

	*/
	public function Frontend() {
		$Frontend = $this->Frontend;
		return $Frontend::instance();
	}


	/*

	Get Backend Class

	*/
	public function Backend() {
		$Backend = $this->Backend;
		return $Backend::instance();
	}


	/*

	Get Backend Class

	*/
	public function Products() {
		$Products = $this->Products;
		return $Products::instance();
	}


	/*

	Runs everytime the plugin loads ...

	*/
	public function Collections() {
		$Collections = $this->Collections;
		return $Collections::instance();
	}

}


/*

Boots the plugin

*/
function Boot() {
	return Boot::instance();
}

Boot();
