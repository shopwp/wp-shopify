<?php

namespace WPS\API\Processing;

if (!defined('ABSPATH')) {
	exit;
}


class Tags extends \WPS\API {

	public $Processing_Tags;


	public function __construct($Processing_Tags) {
		$this->Processing_Tags = $Processing_Tags;
	}


	/*

	Responsible for firing off a background process for smart collections

	*/
	public function process_tags($request) {
		$this->Processing_Tags->process($request);
	}


	/*

	Register route: /process/tags

	*/
  public function register_route_process_tags() {

		return register_rest_route( WPS_SHOPIFY_API_NAMESPACE, '/process/tags', [
			[
				'methods'         => 'POST',
				'callback'        => [$this, 'process_tags']
			]
		]);

	}


	/*

	Hooks

	*/
	public function hooks() {
		add_action('rest_api_init', [$this, 'register_route_process_tags']);
	}


  /*

  Init

  */
  public function init() {
		$this->hooks();
  }


}
