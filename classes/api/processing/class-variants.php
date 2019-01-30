<?php

namespace WPS\API\Processing;

if (!defined('ABSPATH')) {
	exit;
}


class Variants extends \WPS\API {

	public $Processing_Variants;


	public function __construct($Processing_Variants) {
		$this->Processing_Variants = $Processing_Variants;
	}


	/*

	Responsible for firing off a background process for smart collections

	*/
	public function process_variants($request) {
		$this->Processing_Variants->process($request);
	}


	/*

	Register route: /process/variants

	*/
  public function register_route_process_variants() {

		return register_rest_route( WPS_SHOPIFY_API_NAMESPACE, '/process/variants', [
			[
				'methods'         => \WP_REST_Server::CREATABLE,
				'callback'        => [$this, 'process_variants']
			]
		]);

	}


	/*

	Hooks

	*/
	public function hooks() {
		add_action('rest_api_init', [$this, 'register_route_process_variants']);
	}


  /*

  Init

  */
  public function init() {
		$this->hooks();
  }


}
