<?php

namespace WPS\API\Tools;

use WPS\Messages;


if (!defined('ABSPATH')) {
	exit;
}


class Cache extends \WPS\API {


	public function __construct($DB_Settings_Syncing) {
		$this->DB_Settings_Syncing = $DB_Settings_Syncing;
	}


	/*

	Clear Cache

	Once this point is reached, all the data has been synced.
	set_transient allows for /products and /collections permalinks to work

	Does not save errors / warnings to DB. Passes them to client directly.

	*/
	public function delete_cache($request) {

		return $this->DB_Settings_Syncing->expire_sync();

	}


	/*

	Register route: cart_icon_color

	*/
  public function register_route_tools_delete_cache() {

		return register_rest_route( WPS_SHOPIFY_API_NAMESPACE, '/cache', [
			[
				'methods'         => 'DELETE',
				'callback'        => [$this, 'delete_cache']
			]
		]);

	}


	/*

	Hooks

	*/
	public function hooks() {
		add_action('rest_api_init', [$this, 'register_route_tools_delete_cache']);
	}


  /*

  Init

  */
  public function init() {
		$this->hooks();
  }


}
