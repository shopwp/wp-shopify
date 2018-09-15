<?php

namespace WPS;

use WPS\Messages;

if (!defined('ABSPATH')) {
	exit;
}

if ( !class_exists('Checkouts') ) {

	class Checkouts {

		private $WS;

		/*

		Initialize the class and set its properties.

		*/
		public function __construct($WS) {
			$this->WS = $WS;
		}


		/*

		Checkout Attrs (Fires once the user clicks the checkout button)
		Returns nothing by default

		*/
		public function wps_cart_checkout_attrs($defaultAttrs) {
			return [];
		}


		/*

		Get Checkout Attrs (ajax)

		*/
		public function get_cart_checkout_attrs() {

			if (!Utils::valid_frontend_nonce($_POST['nonce'])) {
				$this->WS->send_error( Messages::get('nonce_invalid') . ' (get_cart_checkout_attrs)');
			}

			$defaultAttrs = [
				'cartID'	=>	$_POST['cartID']
			];

			$this->WS->send_success(apply_filters('wps_cart_checkout_attrs', $defaultAttrs));

		}


		/*

		Get Checkout Attrs (ajax)

		*/
		public function wps_cart_checkout_btn_before() {

		}


		/*

		Checkout button before

		*/
		public function wps_cart_checkout_btn_after() {

		}


		/*

		Before Checkout Hook

		*/
		public function add_checkout_before_hook() {

			if (!Utils::valid_frontend_nonce($_POST['nonce'])) {
				$this->WS->send_error( Messages::get('nonce_invalid') . ' (add_checkout_before_hook)');
			}

			$cart = $_POST['cart'];
			$exploded = explode($cart['domain'], $cart['checkoutUrl']);
			$landing_site = $exploded[1];

			$landing_site_hash = Utils::hash_unique($landing_site);

			$this->WS->send_success();

		}


		/*

		Hooks

		*/
	  public function hooks() {

			add_action('wp_ajax_get_cart_checkout_attrs', [$this, 'get_cart_checkout_attrs']);
			add_action('wp_ajax_nopriv_get_cart_checkout_attrs', [$this, 'get_cart_checkout_attrs']);

			add_action('wps_cart_checkout_btn_before', [$this, 'wps_cart_checkout_btn_before']);
			add_action('wps_cart_checkout_btn_after', [$this, 'wps_cart_checkout_btn_after']);

			add_action('wp_ajax_add_checkout_before_hook', [$this, 'add_checkout_before_hook']);
			add_action('wp_ajax_nopriv_add_checkout_before_hook', [$this, 'add_checkout_before_hook']);

			add_filter('wps_cart_checkout_attrs', [$this, 'wps_cart_checkout_attrs']);

	  }


		/*

		Init

		*/
	  public function init() {
			$this->hooks();
	  }


	}

}
