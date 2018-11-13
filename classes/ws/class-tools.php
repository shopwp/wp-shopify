<?php

namespace WPS\WS;

use WPS\Utils;
use WPS\Transients;
use WPS\Messages;

if (!defined('ABSPATH')) {
	exit;
}


class Tools extends \WPS\WS {

	protected $DB_Settings_Syncing;

	public function __construct($DB_Settings_Syncing) {
		$this->DB_Settings_Syncing = $DB_Settings_Syncing;
	}


	/*

	Clear Cache

	Once this point is reached, all the data has been synced.
	set_transient allows for /products and /collections permalinks to work

	Does not save errors / warnings to DB. Passes them to client directly.

	*/
	public function clear_cache() {

		if ( !Utils::valid_backend_nonce($_POST['nonce']) ) {
			$this->send_error( Messages::get('nonce_invalid')  . ' (clear_cache)' );
		}

		$results_cache = $this->DB_Settings_Syncing->reset_syncing_cache();
		$results_notices = $this->DB_Settings_Syncing->reset_syncing_notices();

		Transients::delete_long_term_cache();

		if (is_wp_error($results_cache)) {
			$this->send_error(esc_html__($results_cache->get_error_message()  . ' (clear_syncing_cache)', WPS_PLUGIN_TEXT_DOMAIN));
		}

		foreach ($results_notices as $results_notice) {

			if (is_wp_error($results_notice)) {
				$this->send_error(esc_html__($results_notice->get_error_message()  . ' (clear_syncing_notices)', WPS_PLUGIN_TEXT_DOMAIN));
			}

		}

		flush_rewrite_rules();
		$this->send_success();


	}


	/*

	Hooks

	*/
	public function hooks() {

		add_action('wp_ajax_clear_cache', [$this, 'clear_cache']);
		add_action('wp_ajax_nopriv_clear_cache', [$this, 'clear_cache']);

	}


	/*

	Init

	*/
	public function init() {
		$this->hooks();
	}


}
