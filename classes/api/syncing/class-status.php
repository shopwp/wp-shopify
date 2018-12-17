<?php

namespace WPS\API\Syncing;

use WPS\Messages;


if (!defined('ABSPATH')) {
	exit;
}


class Status extends \WPS\API {

	public $DB_Settings_General;
	public $DB_Settings_Syncing;
	public $Routes;

	public function __construct($DB_Settings_General, $DB_Settings_Syncing, $Routes) {
		$this->DB_Settings_General 	= $DB_Settings_General;
		$this->DB_Settings_Syncing 	= $DB_Settings_Syncing;
		$this->Routes 							= $Routes;
	}


	/*

	Update setting: add_to_cart_color

	*/
	public function get_syncing_status($request) {

		return [
			'is_syncing' 								=> $this->DB_Settings_Syncing->is_syncing(),
			'syncing_totals'						=> $this->DB_Settings_Syncing->syncing_totals(),
			'syncing_current_amounts'		=> $this->DB_Settings_Syncing->syncing_current_amounts(),
			'has_fatal_errors' 					=> $this->DB_Settings_Syncing->has_fatal_errors()
		];

	}


	public function get_syncing_status_posts($request) {

		if ( !$this->DB_Settings_Syncing->is_syncing() ) {
			return true;
		}

		return $this->handle_response( $this->DB_Settings_Syncing->posts_relationships_status() );

	}

	public function get_syncing_status_webhooks($request) {

		if ( !$this->DB_Settings_Syncing->is_syncing() ) {
			return true;
		}

		return $this->handle_response( $this->DB_Settings_Syncing->get_col_value('finished_webhooks_deletions', 'bool') );

	}

	public function get_syncing_status_removal($request) {

		if ( !$this->DB_Settings_Syncing->is_syncing() ) {
			return true;
		}

		return $this->handle_response( $this->DB_Settings_Syncing->get_col_value('finished_data_deletions', 'bool') );

	}

	// Fires once the syncing process stops
	public function get_syncing_notices($request) {
		return $this->handle_response( $this->DB_Settings_Syncing->syncing_notices() );
	}


	// Fires once the syncing process stops
	public function delete_syncing_notices($request) {
		return $this->handle_response( $this->DB_Settings_Syncing->reset_syncing_notices() );
	}


	/*

	Register route: cart_icon_color

	*/
  public function register_route_syncing_status() {

		return register_rest_route( WP_SHOPIFY_API_NAMESPACE, '/syncing/status', [
			[
				'methods'         => 'GET',
				'callback'        => [$this, 'get_syncing_status']
			]
		]);

	}


	/*

	Register route: cart_icon_color

	*/
  public function register_route_syncing_status_posts() {

		return register_rest_route( WP_SHOPIFY_API_NAMESPACE, '/syncing/status/posts', [
			[
				'methods'         => 'GET',
				'callback'        => [$this, 'get_syncing_status_posts']
			]
		]);

	}


	/*

	Register route: cart_icon_color

	*/
  public function register_route_syncing_status_webhooks() {

		return register_rest_route( WP_SHOPIFY_API_NAMESPACE, '/syncing/status/webhooks', [
			[
				'methods'         => 'GET',
				'callback'        => [$this, 'get_syncing_status_webhooks']
			]
		]);

	}


	/*

	Register route: cart_icon_color

	*/
  public function register_route_syncing_status_removal() {

		return register_rest_route( WP_SHOPIFY_API_NAMESPACE, '/syncing/status/removal', [
			[
				'methods'         => 'GET',
				'callback'        => [$this, 'get_syncing_status_removal']
			]
		]);

	}


	/*

	Register route: cart_icon_color

	*/
  public function register_route_syncing_stop() {

		return register_rest_route( WP_SHOPIFY_API_NAMESPACE, '/syncing/stop', [
			[
				'methods'         => 'POST',
				'callback'        => [$this, 'expire_sync']
			]
		]);

	}


	/*

	Register route: cart_icon_color

	*/
  public function register_route_syncing_notices() {

		return register_rest_route( WP_SHOPIFY_API_NAMESPACE, '/syncing/notices', [
			[
				'methods'         => 'GET',
				'callback'        => [$this, 'get_syncing_notices']
			],
			[
				'methods'         => 'DELETE',
				'callback'        => [$this, 'delete_syncing_notices']
			]
		]);

	}


	/*

	Stops syncing

	*/
	public function expire_sync() {

		$this->Routes->flush_routes();

		return $this->handle_response([
			'response' => $this->DB_Settings_Syncing->expire_sync()
		]);

	}


	/*

	Hooks

	*/
	public function hooks() {

		add_action('rest_api_init', [$this, 'register_route_syncing_status']);
		add_action('rest_api_init', [$this, 'register_route_syncing_status_posts']);
		add_action('rest_api_init', [$this, 'register_route_syncing_status_webhooks']);
		add_action('rest_api_init', [$this, 'register_route_syncing_status_removal']);
		add_action('rest_api_init', [$this, 'register_route_syncing_stop']);
		add_action('rest_api_init', [$this, 'register_route_syncing_notices']);

	}


  /*

  Init

  */
  public function init() {
		$this->hooks();
  }


}
