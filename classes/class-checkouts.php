<?php

namespace WPS;

use WPS\WS;
use WPS\Messages;

// If this file is called directly, abort.
if (!defined('ABSPATH')) {
	exit;
}

/*

Class Checkouts

*/
class Checkouts {

  protected static $instantiated = null;
  private $Config;


	/*

	Initialize the class and set its properties.

	*/
	public function __construct($Config) {
		$this->config = $Config;
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

  Saving webhook plugin settings
  TODO: Same as function above, combine into utility

  */
  public function wps_on_checkout() {

  }


	/*

	Checkout Attrs

	*/
	public function wps_cart_checkout_attrs() {

		return [
			'mynameis'	=>	'andrew'
		];

	}


	/*

	Get Checkout Attrs (ajax)

	*/
	public function wps_get_cart_checkout_attrs() {

		if (!Utils::valid_frontend_nonce($_GET['nonce'])) {
			$this->ws->send_error($this->messages->message_nonce_invalid . ' (wps_get_cart_checkout_attrs)');
		}

		$defaultAttrs = [];
		$this->ws->send_success(apply_filters('wps_cart_checkout_attrs', $defaultAttrs));

	}


	/*

	Get Checkout Attrs (ajax)

	*/
	public function wps_cart_checkout_btn_before() {
		echo 'checkout before';
	}


	/*

	Checkout button before

	*/
	public function wps_cart_checkout_btn_after() {
		echo 'checkout after';
	}


	/*

	Checkout button after

	*/
	public function wps_cart_checkout_btn() {
		return include($this->config->plugin_path . "public/partials/cart/cart-button-checkout.php");
	}


	/*

	Init

	*/
  public function init() {

		add_action('wp_ajax_wps_get_cart_checkout_attrs', [$this, 'wps_get_cart_checkout_attrs']);
		add_action('wp_ajax_nopriv_wps_get_cart_checkout_attrs', [$this, 'wps_get_cart_checkout_attrs']);

		add_action('wps_cart_checkout_btn', [$this, 'wps_cart_checkout_btn']);
		// add_action('wps_cart_checkout_btn_before', [$this, 'wps_cart_checkout_btn_before']);
		// add_action('wps_cart_checkout_btn_after', [$this, 'wps_cart_checkout_btn_after']);

		add_filter('wps_cart_checkout_attrs', [$this, 'wps_cart_checkout_attrs']);

  }


}
