<?php

namespace WPS\API\Processing;

if (!defined('ABSPATH')) {
	exit;
}


class Images extends \WPS\API {

	public $Processing_Images;


	public function __construct($Processing_Images) {
		$this->Processing_Images = $Processing_Images;
	}


	/*

	Responsible for firing off a background process for images

	*/
	public function process_images($request) {
		$this->Processing_Images->process($request);
	}


	/*

	Register route: /process/images

	*/
  public function register_route_process_images() {

		return register_rest_route( WPS_SHOPIFY_API_NAMESPACE, '/process/images', [
			[
				'methods'         => 'POST',
				'callback'        => [$this, 'process_images']
			]
		]);

	}


	/*

	Hooks

	*/
	public function hooks() {
		add_action('rest_api_init', [$this, 'register_route_process_images']);
	}


  /*

  Init

  */
  public function init() {
		$this->hooks();
  }


}
