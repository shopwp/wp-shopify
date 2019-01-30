<?php

namespace WPS\API\Syncing;

use WPS\Messages;


if (!defined('ABSPATH')) {
	exit;
}


class Indicator extends \WPS\API {

	public $DB_Settings_Syncing;

	public function __construct($DB_Settings_Syncing) {
		$this->DB_Settings_Syncing = $DB_Settings_Syncing;
	}


	/*

	Update setting: add_to_cart_color

	*/
	public function set_syncing_indicator($request) {

		$syncing_on = $request->get_param('syncing');

		if ($syncing_on) {
			$this->DB_Settings_Syncing->reset_syncing_notices();
		}

		$toggle_syncing_result = $this->DB_Settings_Syncing->toggle_syncing($syncing_on);

		// If the DB update was successful ...
		if ( !$toggle_syncing_result ) {
			$this->send_error( Messages::get('syncing_status_update_failed'));
		}

		$this->send_success();


	}


	/*

	Register route: cart_icon_color

	*/
  public function register_route_syncing_indicator() {

		return register_rest_route( WPS_SHOPIFY_API_NAMESPACE, '/syncing/indicator', [
			[
				'methods'         => \WP_REST_Server::CREATABLE,
				'callback'        => [$this, 'set_syncing_indicator']
			]
		]);

	}


	/*

	Hooks

	*/
	public function hooks() {
		add_action('rest_api_init', [$this, 'register_route_syncing_indicator']);
	}


  /*

  Init

  */
  public function init() {
		$this->hooks();
  }


}
