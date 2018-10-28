<?php

namespace WPS\WS;

use WPS\Utils;
use WPS\Messages;


if (!defined('ABSPATH')) {
	exit;
}


class Syncing extends \WPS\WS {

	protected $DB_Settings_Syncing;

	public function __construct($DB_Settings_Syncing) {
		$this->DB_Settings_Syncing = $DB_Settings_Syncing;
	}


	/*

	Returns the number of chunks to split array for safely constructing
	MySQL queries. Ensures we don't breach the "max_packet_size".

	$counter will split array into two chunks by default

	*/
	public function find_amount_to_chunk($data_to_chunk, &$counter = 2) {

		$data_amount = count($data_to_chunk);
		$chunk_size = ($data_amount / $counter);

		$chunked_data = array_chunk($data_to_chunk, $chunk_size, TRUE);

		// If the first index of the chunked array is STILL too large, then split again ...
		if ($this->DB_Settings_Syncing->max_packet_size_reached($chunked_data[0])) {

			$counter++;
			$this->find_amount_to_chunk($chunked_data, $counter);

		} else {
			return $chunk_size;
		}

	}


	/*

	Checks if network request generated an error, if so, expires
	the sync and saves the error to the db.

	Only runs for request esceptions. Does not run for warnings.

	*/
	public function expire_sync_if_error($response) {

		if (is_wp_error($response) && $this->DB_Settings_Syncing->is_syncing() ) {

			$saveResponse = $this->DB_Settings_Syncing->save_notice($response->get_error_message(), 'error');
			return $this->DB_Settings_Syncing->expire_sync();

		} else {
			return $response;
		}

	}


	/*

	Get Total Counts

	*/
	public function get_syncing_totals() {

		if (!Utils::valid_backend_nonce($_POST['nonce'])) {
			$this->send_error( Messages::get('nonce_invalid') . ' (get_syncing_totals)' );
		}

		$this->send_success( $this->DB_Settings_Syncing->syncing_totals() );

	}


	/*

	Save syncing totals

	*/
	public function insert_syncing_totals() {


		if ( !Utils::valid_backend_nonce($_POST['nonce']) ) {
			$this->send_error( Messages::get('nonce_invalid') . ' (insert_syncing_totals)' );
		}


		if (isset($_POST['counts']) && $_POST['counts']) {
			$syncing_totals = Utils::shift_arrays_up($_POST['counts']);

		} else {
			$syncing_totals = [];
		}


		if (isset($_POST['exclusions']) && $_POST['exclusions']) {
			$syncing_exclusions = $_POST['exclusions'];

		} else {
			$syncing_exclusions = [];
		}


		if (!empty($syncing_totals)) {

			// Saves count total to DB
			$this->DB_Settings_Syncing->set_syncing_totals($syncing_totals, $syncing_exclusions);
			$this->send_success($syncing_totals);

		} else {
			$this->send_error('Nothing to sync!');
		}

	}


	/*

	Get Progress Count

	*/
	function get_progress_count() {
		$this->send_success($_SESSION);
	}


	/*

	Set syncing indicator
	Returns the newly set syncing status: 1 or 0

	Returns errors to client-side. Does not store in DB.

	*/
	public function set_syncing_indicator() {

		if (!isset($_POST['syncing'])) {
			$this->send_error( Messages::get('syncing_status_missing') . ' (set_syncing_indicator 1)' );
		}


		$flag = $_POST['syncing'];
		$results = [];

		// Turning syncing on, zero out the previous syncing notices
		if ($flag == 1 || $flag == '1') {
			$this->DB_Settings_Syncing->reset_syncing_notices();
		}

		// If the DB update was successful ...
		if ( !$this->DB_Settings_Syncing->toggle_syncing($flag) ) {
			$this->send_error( Messages::get('syncing_status_missing') . ' (set_syncing_indicator 2)' );
		}

		$this->send_success();

	}



	/*

	Hooks

	*/
	public function hooks() {

		add_action('wp_ajax_get_progress_count', [$this, 'get_progress_count']);
		add_action('wp_ajax_nopriv_get_progress_count', [$this, 'get_progress_count']);

		add_action('wp_ajax_set_syncing_indicator', [$this, 'set_syncing_indicator']);
		add_action('wp_ajax_nopriv_set_syncing_indicator', [$this, 'set_syncing_indicator']);

		add_action('wp_ajax_get_syncing_totals', [$this, 'get_syncing_totals']);
		add_action('wp_ajax_nopriv_get_syncing_totals', [$this, 'get_syncing_totals']);

		add_action('wp_ajax_insert_syncing_totals', [$this, 'insert_syncing_totals']);
		add_action('wp_ajax_nopriv_insert_syncing_totals', [$this, 'insert_syncing_totals']);

	}


	/*

	Init

	*/
	public function init() {
		$this->hooks();
	}


}
