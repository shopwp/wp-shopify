<?php

namespace WPS\API\Processing;

if (!defined('ABSPATH')) {
	exit;
}


class Collects extends \WPS\API {

	public $Processing_Collects;


	public function __construct($Processing_Collects) {
		$this->Processing_Collects = $Processing_Collects;
	}


	/*

	Responsible for firing off a background process for smart collections

	*/
	public function process_collects($request) {
		$this->Processing_Collects->process($request);
	}


	/*

	Register route: /process/collects

	*/
  public function register_route_process_collects() {

		return register_rest_route( WPS_SHOPIFY_API_NAMESPACE, '/process/collects', [
			[
				'methods'         => \WP_REST_Server::CREATABLE,
				'callback'        => [$this, 'process_collects']
			]
		]);

	}


	/*

	Hooks

	*/
	public function hooks() {
		add_action('rest_api_init', [$this, 'register_route_process_collects']);
	}


  /*

  Init

  */
  public function init() {
		$this->hooks();
  }


}
