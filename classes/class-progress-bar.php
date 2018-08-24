<?php

namespace WPS;

use WPS\Utils;

if (!defined('ABSPATH')) {
	exit;
}


if (!class_exists('Progress_Bar')) {

	class Progress_Bar {

		protected $DB_Settings_Syncing;
		protected $DB_Settings_General;
		protected $Messages;
		protected $WS;
		protected $WS_Syncing;


		public function __construct($DB_Settings_Syncing, $DB_Settings_General, $Messages, $WS, $WS_Syncing) {

			$this->DB_Settings_Syncing 			= $DB_Settings_Syncing;
			$this->DB_Settings_General 			= $DB_Settings_General;
			$this->Messages 								= $Messages;
			$this->WS 											= $WS;
			$this->WS_Syncing								= $WS_Syncing;

		}


		/*

		Progress: Session creation

		*/
		public function progress_session_create() {

			if (!Utils::valid_backend_nonce($_POST['nonce'])) {
				$this->WS->send_error($this->Messages->message_nonce_invalid . ' (progress_session_create)');
			}

			$this->DB_Settings_Syncing->toggle_syncing(1);

			$startingSyncTotals = [];


			/*

			$includes is an array contain the data types like this:

			Array (
				[0] => smart_collections
				[1] => custom_collections
				[2] => customers
				[3] => orders
			)

			*/
			if (isset($_POST['includes']) && $_POST['includes']) {
				$includes = $_POST['includes']; // array of keys in which to filter from

			} else {
				$includes = [];
			}


			/*

			$excludes is an array contain the data types like this:

			Array (
				[0] => smart_collections
				[1] => custom_collections
				[2] => customers
				[3] => orders
			)

			*/
			if (isset($_POST['excludes']) && $_POST['excludes']) {
				$excludes = $_POST['excludes']; // array of keys in which to filter from

			} else {
				$excludes = [];
			}


			// Totals
			$startingSyncTotals['wps_syncing_totals'] = $this->DB_Settings_Syncing->syncing_totals();

			// Current amounts
			$startingSyncTotals['wps_syncing_current_amounts'] = $this->DB_Settings_Syncing->syncing_current_amounts();

			$startingSyncTotals = $this->filter_session_variables_by_includes($startingSyncTotals, $includes);
			$startingSyncTotals = $this->filter_session_variables_by_excludes($startingSyncTotals, $excludes);

			$this->WS->send_success($startingSyncTotals);

		}



		public function filter_session_variables_by_excludes($startingSyncTotals, $excludes) {

			if (empty($excludes)) {
				return $startingSyncTotals;

			} else {

				$not_allowed = [];

				foreach ($excludes as $exclude) {

					foreach ($startingSyncTotals as &$startingSyncTotal) {

						if (isset($startingSyncTotal[$exclude])) {
							unset($startingSyncTotal[$exclude]);
						}

					}

				}

				return $startingSyncTotals;

			}

		}



		/*

		Progress: Filter session variables by includes

		*/
		public function filter_session_variables_by_includes($sessionVariables, $includes) {

			if (empty($includes)) {
				return $sessionVariables;

			} else {

				$allowed = [];

				foreach ($includes as $include) {
					$allowed[$include] = 0;
				}

				$sessionVariables['wps_syncing_current_amounts'] = $allowed;
				$sessionVariables['wps_syncing_totals'] = array_intersect_key($sessionVariables['wps_syncing_totals'], $allowed);

				return $sessionVariables;

			}

		}


		/*

		Progress: Get Step Current

		*/
		public function wps_progress_step_current() {

			$databaseResponse = $this->DB_Settings_Syncing->get_column_single('syncing_step_current');

			if (Utils::array_not_empty($databaseResponse) && isset($databaseResponse[0]->syncing_step_current)) {
				$syncingStepCurrent = intval($databaseResponse[0]->syncing_step_current);

			} else {
				$syncingStepCurrent = false;
			}

			return $syncingStepCurrent;

		}


		/*

		Progress: Get Syncing Status
		TODO: Combine with the previous two getters above

		*/
		public function wps_progress_syncing_status() {

			$syncingStatus = $this->DB_Settings_Syncing->get_column_single('is_syncing');

			if (Utils::array_not_empty($syncingStatus) && isset($syncingStatus[0]->is_syncing)) {
				$syncing = intval($syncingStatus[0]->is_syncing);

			} else {
				$syncing = false;
			}

			return $syncing;

		}


		/*

		Progress: Get Status

		*/
		public function progress_status() {

			if (!Utils::valid_backend_nonce($_GET['nonce'])) {
				$this->WS->send_error($this->Messages->message_nonce_invalid . ' (progress_status)');
			}

			if ($this->DB_Settings_Syncing->all_syncing_complete()) {
				$this->WS_Syncing->expire_sync();
			}

			$this->WS->send_success([
				'is_syncing' 								=> $this->DB_Settings_Syncing->is_syncing(),
				'syncing_totals'						=> $this->DB_Settings_Syncing->syncing_totals(),
				'syncing_current_amounts'		=> $this->DB_Settings_Syncing->syncing_current_amounts(),
				'has_fatal_errors' 					=> $this->DB_Settings_Syncing->has_fatal_errors()
			]);

		}


		/*

		Fires once the syncing process stops

		*/
		public function get_syncing_notices() {

			if (!Utils::valid_backend_nonce($_POST['nonce'])) {
				$this->WS->send_error($this->Messages->message_nonce_invalid . ' (progress_status)');
			}

			$syncing_notices = $this->DB_Settings_Syncing->syncing_notices();

			$this->WS->send_success( $syncing_notices );

		}


		public function get_webhooks_removal_status() {

			if (!Utils::valid_backend_nonce($_POST['nonce'])) {
				$this->WS->send_error($this->Messages->message_nonce_invalid . ' (progress_status)');
			}

			$this->WS->send_success( $this->DB_Settings_Syncing->webhooks_removal_status() );

		}


		public function get_data_removal_status() {

			if (!Utils::valid_backend_nonce($_POST['nonce'])) {
				$this->WS->send_error($this->Messages->message_nonce_invalid . ' (progress_status)');
			}

			$this->WS->send_success( $this->DB_Settings_Syncing->data_removal_status() );

		}


		public function get_posts_relationships_status() {

			if (!Utils::valid_backend_nonce($_POST['nonce'])) {
				$this->WS->send_error($this->Messages->message_nonce_invalid . ' (progress_status)');
			}

			$this->WS->send_success( $this->DB_Settings_Syncing->posts_relationships_status() );

		}


		/*

		Kills syncing

		*/
		public function kill_syncing() {

			// Clear all caches again for good measure
			$this->DB_Settings_Syncing->reset_syncing_cache();

			wp_die();

		}


		/*

		Hooks

		*/
		public function hooks() {

			add_action( 'wp_ajax_progress_status', [$this, 'progress_status']);
			add_action( 'wp_ajax_nopriv_progress_status', [$this, 'progress_status']);

			add_action( 'wp_ajax_progress_session_create', [$this, 'progress_session_create']);
			add_action( 'wp_ajax_nopriv_progress_session_create', [$this, 'progress_session_create']);

			add_action( 'wp_ajax_get_syncing_notices', [$this, 'get_syncing_notices']);
			add_action( 'wp_ajax_nopriv_get_syncing_notices', [$this, 'get_syncing_notices']);

			add_action( 'wp_ajax_kill_syncing', [$this, 'kill_syncing']);
			add_action( 'wp_ajax_nopriv_kill_syncing', [$this, 'kill_syncing']);

			add_action( 'wp_ajax_get_webhooks_removal_status', [$this, 'get_webhooks_removal_status']);
			add_action( 'wp_ajax_nopriv_get_webhooks_removal_status', [$this, 'get_webhooks_removal_status']);

			add_action( 'wp_ajax_get_data_removal_status', [$this, 'get_data_removal_status']);
			add_action( 'wp_ajax_nopriv_get_data_removal_status', [$this, 'get_data_removal_status']);

			add_action( 'wp_ajax_get_posts_relationships_status', [$this, 'get_posts_relationships_status']);
			add_action( 'wp_ajax_nopriv_get_posts_relationships_status', [$this, 'get_posts_relationships_status']);

		}


		/*

		Init

		*/
		public function init() {
			$this->hooks();
		}


	}

}
