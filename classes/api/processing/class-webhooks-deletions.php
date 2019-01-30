<?php

namespace WPS\API\Processing;

if (!defined('ABSPATH')) {
	exit;
}


class Webhooks_Deletions extends \WPS\API {

	public $Processing_Webhooks_Deletions;


	public function __construct($Processing_Webhooks_Deletions) {
		$this->Processing_Webhooks_Deletions = $Processing_Webhooks_Deletions;
	}


	/*

	Responsible for firing off a background process for smart collections

	*/
	public function process_webhooks($request) {
		$this->Processing_Webhooks_Deletions->process($request);
	}


	/*

	Register route: /process/webhooks

	*/
  public function register_route_process_webhooks() {

		return register_rest_route( WPS_SHOPIFY_API_NAMESPACE, '/process/webhooks_delete', [
			[
				'methods'         => \WP_REST_Server::CREATABLE,
				'callback'        => [$this, 'process_webhooks']
			]
		]);

	}


	/*

	Hooks

	*/
	public function hooks() {
		add_action('rest_api_init', [$this, 'register_route_process_webhooks']);
	}


  /*

  Init

  */
  public function init() {
		$this->hooks();
  }


}
