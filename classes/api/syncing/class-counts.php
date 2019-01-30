<?php

namespace WPS\API\Syncing;

use WPS\Messages;
use WPS\Utils;

if (!defined('ABSPATH')) {
	exit;
}


class Counts extends \WPS\API {

	public $DB_Settings_Syncing;

	public function __construct($DB_Settings_Syncing) {
		$this->DB_Settings_Syncing = $DB_Settings_Syncing;
	}


	/*

	Update setting: add_to_cart_color

	*/
	public function set_syncing_counts($request) {

		$syncing_totals 			= $request->get_param('counts');
		$syncing_exclusions 	= $request->get_param('exclusions');

		if ( empty($syncing_totals) ) {
			$this->send_error('Nothing to sync!');
		}

		if ( empty($syncing_exclusions) ) {
			$syncing_exclusions = [];
		}

		// TODO: Handle errors in here
		return $this->DB_Settings_Syncing->set_syncing_totals(
			Utils::flatten_array($syncing_totals),
			Utils::flatten_array($syncing_exclusions)
		);

	}


	public function set_syncing_count($request) {

		$step_name = $request->get_param('stepName');

		$grand_total_col_name = 'syncing_totals_' . $step_name;
		$current_total_col_name = 'syncing_current_amounts_' . $step_name;

		$grand_total = $this->DB_Settings_Syncing->get_col_value($grand_total_col_name, 'int');
		$current_total = $this->DB_Settings_Syncing->get_col_value($current_total_col_name, 'int');

		if ($current_total !== $grand_total) {

			$this->DB_Settings_Syncing->save_warning( Messages::get('missing_' . $step_name . '_for_page') );

			return $this->DB_Settings_Syncing->update_column_single([$current_total_col_name => $grand_total], ['id' => 1]);
		}

		return false;

	}



	public function get_syncing_grand_totals() {
		return $this->DB_Settings_Syncing->syncing_totals();
	}

	public function get_syncing_current_totals() {
		return $this->DB_Settings_Syncing->syncing_current_amounts();
	}







	public function filter_totals_by_excludes($sync_totals, $excludes) {

		if (empty($excludes)) {
			return $sync_totals;
		}

		foreach ($excludes as $exclude) {

			foreach ($sync_totals as &$syncTotal) {

				if (isset($syncTotal[$exclude])) {
					unset($syncTotal[$exclude]);
				}

			}

		}

		return $sync_totals;

	}



	/*

	Progress: Filter session variables by includes

	*/
	public function filter_totals_by_includes($sync_totals, $includes) {

		if (empty($includes)) {
			return $sync_totals;
		}

		$allowed = [];

		foreach ($includes as $include) {
			$allowed[$include] = 0;
		}

		$sync_totals['wps_syncing_current_amounts'] = $allowed;
		$sync_totals['wps_syncing_totals'] = array_intersect_key($sync_totals['wps_syncing_totals'], $allowed);

		return $sync_totals;

	}


	/*

	Progress: Session creation

	$includes and $excludes are arrays containing the data types like this:

	Array (
		[0] => smart_collections
		[1] => custom_collections
		[2] => customers
		[3] => orders
	)

	*/
	public function get_syncing_total_counts($request) {

		$this->DB_Settings_Syncing->toggle_syncing(1);

		$sync_totals = [];
		$includes = $request->get_param('includes') ? $request->get_param('includes') : [];
		$excludes = $request->get_param('excludes') ? $request->get_param('excludes') : [];

		// TODO: Need to handle errors here
		$sync_totals['wps_syncing_totals'] = $this->get_syncing_grand_totals();
		$sync_totals['wps_syncing_current_amounts'] = $this->get_syncing_current_totals();

		$sync_totals = $this->filter_totals_by_includes($sync_totals, $includes);

		// Removes any sync type excluded by the user
		$sync_totals = $this->filter_totals_by_excludes($sync_totals, $excludes);

		// TODO: Need to handle errors
		return $sync_totals;

	}


	/*

	Register route: cart_icon_color

	*/
  public function register_route_syncing_set_counts() {

		return register_rest_route( WPS_SHOPIFY_API_NAMESPACE, '/syncing/counts', [
			[
				'methods'         => \WP_REST_Server::CREATABLE,
				'callback'        => [$this, 'set_syncing_counts']
			],
			[
				'methods'         => \WP_REST_Server::READABLE,
				'callback'        => [$this, 'get_syncing_total_counts']
			]
		]);

	}


	/*

	Register route: cart_icon_color

	*/
  public function register_route_syncing_set_single_count() {

		return register_rest_route( WPS_SHOPIFY_API_NAMESPACE, '/syncing/count', [
			[
				'methods'         => \WP_REST_Server::CREATABLE,
				'callback'        => [$this, 'set_syncing_count']
			]
		]);

	}


	/*

	Hooks

	*/
	public function hooks() {

		add_action('rest_api_init', [$this, 'register_route_syncing_set_counts']);
		add_action('rest_api_init', [$this, 'register_route_syncing_set_single_count']);

	}


  /*

  Init

  */
  public function init() {
		$this->hooks();
  }


}
