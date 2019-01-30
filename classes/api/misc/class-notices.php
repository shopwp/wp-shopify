<?php

namespace WPS\API\Misc;

use WPS\Options;
use WPS\Messages;
use WPS\Transients;


if (!defined('ABSPATH')) {
	exit;
}


class Notices extends \WPS\API {

	public $DB_Settings_General;
  public $Backend;

	public function __construct($DB_Settings_General, $Backend) {
		$this->DB_Settings_General  = $DB_Settings_General;
    $this->Backend              = $Backend;
	}


  public function delete_notices($request) {

    return $this->handle_response([
			'response' => $this->DB_Settings_General->set_app_uninstalled(0)
		]);

  }


  /*

	Notice: Error

	*/
	public function error($message, $dismiss_name = false) {
		$this->notice('error', $message, $dismiss_name);
	}


	/*

	Notice: Warning

	*/
	public function warning_notice($message, $dismiss_name = false) {
		$this->notice('warning', $message, $dismiss_name);
	}


	/*

	Notice: Success

	*/
	public function success_notice($message, $dismiss_name = false) {
		$this->notice('success', $message, $dismiss_name);
	}


	/*

	Notice: Info

	*/
	public function info_notice($message, $dismiss_name = false) {
		$this->notice('info', $message, $dismiss_name);
	}


	/*

	Notice

	*/
	private function notice($type, $message, $dismiss_name = false) {

		$transientExists = Transients::get("wps_admin_dismissed_{$dismiss_name}");

		if (!$transientExists) {

			?>

			<div class="notice wps-notice notice-<?php echo $type;

				if ( $dismiss_name ) {
						echo ' is-dismissible" data-dismiss-name="' . $dismiss_name;
				} ?>">

				<p><?= $message; ?></p>

			</div>

		<?php }

	}



	public function show_database_migration_needed_notice() {

		if ( Options::get('wp_shopify_migration_needed') ) {
			$this->warning_notice( Messages::get('database_migration_needed'), 'notice_warning_database_migration_needed' );
		}

	}


	/*

	Show admin notices

	*/
	public function show_cpt_data_erase_notice() {

		if ( $this->Backend->is_admin_posts_page( $this->Backend->get_screen_id() ) ) {
			$this->warning_notice( Messages::get('saving_native_cpt_data'), 'notice_warning_post_data_eraser' );
		}

	}


	/*

	Show admin notices

	*/
	public function show_app_uninstalled_notice() {

		$app_uninstalled_status = $this->DB_Settings_General->app_uninstalled();

		if (!$app_uninstalled_status) {
			return;
		}

    $screen_id      = $this->get_screen_id();
    $posts_page     = $this->Backend->is_admin_posts_page( $screen_id );
    $settings_page  = $this->Backend->is_admin_settings_page( $screen_id );

		if ($posts_page || $settings_page) {
			$this->warning_notice( Messages::get('app_uninstalled'), 'notice_warning_app_uninstalled' );
		}

	}


	/*

	Dismiss notices

	*/
	public function dismiss_notice() {

		$notice_name = $request->get_param('dismiss_name');

		if ($notice_name) {
			$notice_dismissed = Transients::set("wps_admin_dismissed_{$notice_name}", true, 0);

		} else {
      $notice_dismissed = false;
    }

    return $this->handle_response([
			'response' => $notice_dismissed
		]);

	}


  /*

	Show admin notices

	*/
	public function show_admin_notices() {

		$this->show_cpt_data_erase_notice();
		$this->show_app_uninstalled_notice();
		// $this->show_database_migration_needed_notice();

	}


	/*

	Register route: collections_heading

	*/
  public function register_route_notices() {

		return register_rest_route( WPS_SHOPIFY_API_NAMESPACE, '/notices', [
			[
				'methods'         => \WP_REST_Server::READABLE,
				'callback'        => [$this, 'get_notices']
			],
			[
				'methods'         => \WP_REST_Server::CREATABLE,
				'callback'        => [$this, 'delete_notices']
			]
		]);

	}


  /*

	Register route: collections_heading

	*/
  public function register_route_notices_dismiss() {

		return register_rest_route( WPS_SHOPIFY_API_NAMESPACE, '/notices/dismiss', [
			[
				'methods'         => \WP_REST_Server::CREATABLE,
				'callback'        => [$this, 'dismiss_notice']
			]
		]);

	}


	/*

	Hooks

	*/
	public function hooks() {

    add_action('rest_api_init', [$this, 'register_route_notices']);
    add_action('rest_api_init', [$this, 'register_route_notices_dismiss']);

		// Shows default notices
		add_action('admin_notices', [$this, 'show_admin_notices']);

	}


  /*

  Init

  */
  public function init() {
		$this->hooks();
  }


}
