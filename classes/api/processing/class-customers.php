<?php

namespace WPS\API\Processing;

if (!defined('ABSPATH')) {
	exit;
}


class Customers extends \WPS\API {

	public $Processing_Customers;


	public function __construct($Processing_Customers) {
		$this->Processing_Customers = $Processing_Customers;
	}


	/*

	Responsible for firing off a background process for smart collections

	*/
	public function process_customers($request) {
		$this->Processing_Customers->process($request);
	}


	/*

	Register route: /process/customers

	*/
  public function register_route_process_customers() {

		return register_rest_route( WP_SHOPIFY_API_NAMESPACE, '/process/customers', [
			[
				'methods'         => 'POST',
				'callback'        => [$this, 'process_customers']
			]
		]);

	}


	/*

	Hooks

	*/
	public function hooks() {
		add_action('rest_api_init', [$this, 'register_route_process_customers']);
	}


  /*

  Init

  */
  public function init() {
		$this->hooks();
  }


}
