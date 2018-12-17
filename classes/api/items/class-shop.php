<?php

namespace WPS\API\Items;

use WPS\Messages;
use WPS\Utils;

if (!defined('ABSPATH')) {
	exit;
}


class Shop extends \WPS\API {

	public function __construct($DB_Settings_General, $Shopify_API, $Processing_Shop) {
		$this->DB_Settings_General 		= $DB_Settings_General;
		$this->Shopify_API 						= $Shopify_API;
		$this->Processing_Shop				= $Processing_Shop;
	}


	/*

	Get Smart Collections Count

	Nonce checks are handled automatically by WordPress

	*/
	public function get_shop_count($request) {

		return [
			'shop' => 1
		];

	}


	/*

	Get smart collections

	Nonce checks are handled automatically by WordPress

	*/
	public function get_shop($request) {

		// Grab smart collections from Shopify
		$response = $this->Shopify_API->get_shop();

		return $this->handle_response([
			'response' 				=> $response,
			'access_prop' 		=> 'shop',
			'return_key' 			=> 'shop',
			'warning_message'	=> 'shop_count_not_found',
			'process_fns' 		=> [
				$this->Processing_Shop
			]
		]);

	}


	/*

	Register route: cart_icon_color

	*/
  public function register_route_shop_count() {

		return register_rest_route( WP_SHOPIFY_API_NAMESPACE, '/shop/count', [
			[
				'methods'         => 'POST',
				'callback'        => [$this, 'get_shop_count']
			]
		]);

	}


	/*

	Register route: cart_icon_color

	*/
  public function register_route_shop() {

		return register_rest_route( WP_SHOPIFY_API_NAMESPACE, '/shop', [
			[
				'methods'         => 'POST',
				'callback'        => [$this, 'get_shop']
			]
		]);

	}


	/*

	Hooks

	*/
	public function hooks() {

		add_action('rest_api_init', [$this, 'register_route_shop_count']);
		add_action('rest_api_init', [$this, 'register_route_shop']);

	}


  /*

  Init

  */
  public function init() {
		$this->hooks();
  }


}
