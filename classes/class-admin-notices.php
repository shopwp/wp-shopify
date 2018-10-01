<?php

namespace WPS;

use WPS\Transients;
use WPS\WS;
use WPS\Messages;


if (!defined('ABSPATH')) {
	exit;
}

if ( !class_exists('Admin_Notices') ) {

	class Admin_Notices {

		private $WS;
		private $DB_Settings_General;
		private $admin_notices;


	  /*

	  Initialize the class and set its properties.

	  */
	  public function __construct($WS, $DB_Settings_General) {

			// Dependencies
			$this->WS 								 					= $WS;
			$this->DB_Settings_General 					= $DB_Settings_General;

			$this->admin_notices 								= new \stdClass();
			$this->admin_notices->error 				= [];
			$this->admin_notices->warning 			= [];
			$this->admin_notices->info 					= [];
			$this->admin_notices->success 			= [];

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
		public function warning($message, $dismiss_name = false) {
			$this->notice('warning', $message, $dismiss_name);
		}


		/*

		Notice: Success

		*/
		public function success($message, $dismiss_name = false) {
			$this->notice('success', $message, $dismiss_name);
		}


		/*

		Notice: Info

		*/
		public function info($message, $dismiss_name = false) {
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


		/*

		Show admin notices

		*/
		public function show_admin_notices() {

			$this->show_cpt_data_erase_notice();
			$this->show_app_uninstalled_notice();
			$this->show_database_migration_needed_notice();

		}


		public function is_wp_shopify_cpt_page() {

			if (!empty(get_current_screen()) && get_current_screen()->id === WPS_PRODUCTS_POST_TYPE_SLUG || get_current_screen()->id === WPS_COLLECTIONS_POST_TYPE_SLUG) {
				return true;

			} else {
				return false;
			}

		}


		public function is_wp_shopify_settings_page() {

			if (!empty(get_current_screen()) && get_current_screen()->id === 'wp-shopify_page_wps-settings') {
				return true;

			} else {
				return false;
			}

		}


		public function show_database_migration_needed_notice() {

			if ( get_site_option('wp_shopify_migration_needed') ) {
				$this->warning( Messages::get('database_migration_needed'), 'notice_warning_database_migration_needed' );
			}

		}


		/*

		Show admin notices

		*/
		public function show_cpt_data_erase_notice() {

			if ($this->is_wp_shopify_cpt_page()) {
				$this->warning( Messages::get('saving_native_cpt_data'), 'notice_warning_post_data_eraser' );
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

			if ($this->is_wp_shopify_cpt_page() || $this->is_wp_shopify_settings_page()) {
				$this->warning( Messages::get('app_uninstalled'), 'notice_warning_app_uninstalled' );
			}

		}


		/*

		Init

		*/
		public function cache_admin_notice_dismissal() {

			$noticeDismissal = $_POST['dismiss_name'];

			if ($noticeDismissal) {

				$trasnientsSet = Transients::set("wps_admin_dismissed_{$noticeDismissal}", true, 0);
			  $this->WS->send_success($trasnientsSet);

	    }

		}


		/*

		Hooks

		*/
		public function hooks() {

			add_action('wp_ajax_cache_admin_notice_dismissal', [$this, 'cache_admin_notice_dismissal']);
			add_action('wp_ajax_nopriv_cache_admin_notice_dismissal', [$this, 'cache_admin_notice_dismissal']);

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

}
