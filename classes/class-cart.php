<?php

namespace WPS;

use WPS\Transients;
use WPS\Messages;
use WPS\Utils;


if (!defined('ABSPATH')) {
	exit;
}

if ( !class_exists('Cart') ) {

	class Cart {

		private $WS;

	  /*

	  Initialize the class and set its properties.

	  */
	  public function __construct($WS) {
			$this->WS = $WS;
	  }


		/*

		Get cart cache

		*/
		public function get_checkout_cache() {

			if (!Utils::valid_frontend_nonce($_POST['nonce'])) {
				$this->WS->send_error( Messages::get('nonce_invalid') . ' (get_checkout_cache)');
			}

			if (isset($_POST['checkoutID']) && $_POST['checkoutID']) {

				$checkoutName = 'wps_checkout_' . $_POST['checkoutID'];

				if (Transients::get($checkoutName)) {
					$this->WS->send_success();

				} else {
					$this->WS->send_error();
				}

			} else {
				$this->WS->send_error();
			}


		}


		/*

		Set checkout cache in transient

		*/
		public function set_checkout_cache() {

			if (!Utils::valid_frontend_nonce($_POST['nonce'])) {
				$this->WS->send_error( Messages::get('nonce_invalid') . ' (set_checkout_cache)');
			}

			$checkoutName = 'wps_cart_' . $_POST['checkoutID'];

			if (isset($checkoutName) && $checkoutName) {

				// Checkout is already cached, return
				if ( Transients::get($checkoutName) ) {
					$this->WS->send_success($checkoutName);
				}

				// Cache the checkout id for three days
				$cache_result = Transients::set($checkoutName, true, WPS_CART_CACHE_EXPIRATION);

				if ($cache_result) {
					$this->WS->send_success($checkoutName);

				} else {
					$this->WS->send_error( Messages::get('unable_to_cache_checkout') . ' (set_checkout_cache)' );
				}


			} else {
				$this->WS->send_error( Messages::get('missing_checkout_id') . ' (set_checkout_cache)' );

			}

		}


		/*

		Hooks

		*/
		public function hooks() {

			add_action('wp_ajax_get_checkout_cache', [$this, 'get_checkout_cache']);
			add_action('wp_ajax_nopriv_get_checkout_cache', [$this, 'get_checkout_cache']);

			add_action('wp_ajax_set_checkout_cache', [$this, 'set_checkout_cache']);
			add_action('wp_ajax_nopriv_set_checkout_cache', [$this, 'set_checkout_cache']);

		}


		public function init() {
			$this->hooks();
		}


	}

}
