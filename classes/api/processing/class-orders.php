<?php

namespace WPS\API\Processing;

if (!defined('ABSPATH')) {
	exit;
}


class Orders extends \WPS\API {

	public $Processing_Orders;


	public function __construct($Processing_Orders) {
		$this->Processing_Orders = $Processing_Orders;
	}


	/*

	Responsible for firing off a background process for smart collections

	*/
	public function process_orders($request) {
		$this->Processing_Orders->process($request);
	}


	/*

	Register route: /process/orders

	*/
  public function register_route_process_orders() {

		return register_rest_route( WPS_SHOPIFY_API_NAMESPACE, '/process/orders', [
			[
				'methods'         => \WP_REST_Server::CREATABLE,
				'callback'        => [$this, 'process_orders']
			]
		]);

	}


	/*

	Hooks

	*/
	public function hooks() {
		add_action('rest_api_init', [$this, 'register_route_process_orders']);
	}


  /*

  Init

  */
  public function init() {
		$this->hooks();
  }


}
