<?php

namespace WPS;

use WPS\Transients;
use WPS\Messages;
use WPS\WS;
use WPS\Config;

// If this file is called directly, abort.
if (!defined('ABSPATH')) {
	exit;
}

/*

Class Admin_Notices

*/
if ( !class_exists('Admin_Notices') ) {

	class Admin_Notices {

		private $admin_notices;
	  protected static $instantiated = null;

	  /*

	  Initialize the class and set its properties.

	  */
	  public function __construct() {

			$this->admin_notices = new \stdClass();
			$this->admin_notices->error = [];
			$this->admin_notices->warning = [];
			$this->admin_notices->info = [];
			$this->admin_notices->success = [];

			$this->WS = new WS(new Config());

			$this->messages = new Messages();

	  }


	  /*

	  Creates a new class if one hasn't already been created.
	  Ensures only one instance is used.

	  */
	  public static function instance() {

	    if (is_null(self::$instantiated)) {
	      self::$instantiated = new self();
	    }

	    return self::$instantiated;

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

			if (!empty(get_current_screen()) && get_current_screen()->id === 'wps_products' || get_current_screen()->id === 'wps_collections') {
				$this->warning($this->messages->message_saving_native_cpt_data, 'notice_warning_post_data_eraser' );
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

		Init

		*/
		public function init() {

			add_action('wp_ajax_cache_admin_notice_dismissal', [$this, 'cache_admin_notice_dismissal']);
			add_action('wp_ajax_nopriv_cache_admin_notice_dismissal', [$this, 'cache_admin_notice_dismissal']);

			// Shows default notices
			add_action('admin_notices', [$this, 'show_admin_notices']);


		}


	}

}
