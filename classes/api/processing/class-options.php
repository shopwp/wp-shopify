<?php

namespace WPS\API\Processing;

if (!defined('ABSPATH')) {
	exit;
}


class Options extends \WPS\API {

	public $Processing_Options;


	public function __construct($Processing_Options) {
		$this->Processing_Options = $Processing_Options;
	}


	/*

	Responsible for firing off a background process for smart collections

	*/
	public function process_options($request) {
		$this->Processing_Options->process($request);
	}


	/*

	Register route: /process/options

	*/
  public function register_route_process_options() {

		return register_rest_route( WPS_SHOPIFY_API_NAMESPACE, '/process/options', [
			[
				'methods'         => \WP_REST_Server::CREATABLE,
				'callback'        => [$this, 'process_options']
			]
		]);

	}


	/*

	Hooks

	*/
	public function hooks() {
		add_action('rest_api_init', [$this, 'register_route_process_options']);
	}


  /*

  Init

  */
  public function init() {
		$this->hooks();
  }


}
