<?php

namespace WPS\Processing;

use WPS\Options;
use WPS\Utils;
use WPS\Utils\Server;
use WPS\Utils\Data;

/**
 * WP Background Process
 *
 * @package WP-Background-Processing
 */

/**
 * Abstract Vendor_Background_Process class.
 *
 * @abstract
 * @extends WP_Async_Request
 */
abstract class Vendor_Background_Process extends \WPS\Processing\Vendor_Async_Request {

	/**
	 * Action
	 *
	 * (default value: 'background_process')
	 *
	 * @var string
	 * @access protected
	 */
	protected $action = 'background_process';

	/**
	 * Start time of current process.
	 *
	 * (default value: 0)
	 *
	 * @var int
	 * @access protected
	 */
	protected $start_time = 0;

	/**
	 * Cron_hook_identifier
	 *
	 * @var mixed
	 * @access protected
	 */
	protected $cron_hook_identifier;

	/**
	 * Cron_interval_identifier
	 *
	 * @var mixed
	 * @access protected
	 */
	protected $cron_interval_identifier;

	protected $DB_Settings_Syncing;


	/**
	 * Initiate new background process
	 */
	public function __construct($DB_Settings_Syncing) {

		parent::__construct();

		$this->cron_hook_identifier     	= $this->identifier . '_cron';
		$this->cron_interval_identifier 	= $this->identifier . '_cron_interval';
		$this->DB_Settings_Syncing 				= $DB_Settings_Syncing;

		add_action( $this->cron_hook_identifier, [$this, 'handle_cron_healthcheck'] );
		add_filter( 'cron_schedules', [$this, 'schedule_cron_healthcheck'] );

	}

	public function meta_identifier($item) {

		if ( is_array($item) ) {
			$item = $item[0];
		}

		if ( isset($item->increment_name) ) {
			return $this->identifier . '_meta_' . $item->post_type . '_' . $item->increment_name;
		}

		return $this->identifier . '_meta_' . $item->post_type;

	}



	public function add_meta_to_batch($params) {

		if ( isset($params['meta']['increment_name']) ) {
			$option_name = $this->identifier . '_meta_' . $params['meta']['post_type'] . '_' . $params['meta']['increment_name'];

		} else {
			$option_name = $this->identifier . '_meta_' . $params['meta']['post_type'];
		}

		return update_option($option_name, $params['meta']);

	}


	/*

	Checks whether each processing batch will fail

	*/
	public function expired_from_server_issues($items, $method, $line) {

		$expired = false;

		if ( $this->DB_Settings_Syncing->max_packet_size_reached($items) ) {

			$expired = true;

			$this->DB_Settings_Syncing->save_notice_and_expire_sync( Utils::wp_error([
				'message_lookup' 	=> 'max_allowed_packet',
				'call_method' 		=> $method,
				'call_line' 			=> $line
			]));

			$this->complete();

		}

		if ( Server::exceeds_max_post_body_size($items, $method, $line) ) {

			$expired = true;

			$this->DB_Settings_Syncing->save_notice_and_expire_sync( Utils::wp_error([
				'message_lookup' 	=> 'max_post_body_size',
				'call_method' 		=> $method,
				'call_line' 			=> $line
			]));

			$this->complete();

		}

		return $expired;

	}



	public function wp_cron_disabled() {

    global $wp_version;

    if ( defined( 'DISABLE_WP_CRON' ) && DISABLE_WP_CRON ) {

			return Utils::wp_error([
				'message_lookup' 	=> 'wp_cron_disabled'
			]);

    }

    if ( defined( 'ALTERNATE_WP_CRON' ) && ALTERNATE_WP_CRON ) {
    	return new \WP_Error( 'error', sprintf( __( 'The ALTERNATE_WP_CRON constant is set to true as of %s.  This plugin cannot determine the status of your WP-Cron system.', 'wcsc' ), current_time( 'm/d/Y g:i:s a' ) ) );

			return Utils::wp_warning([
				'message_lookup' 	=> 'wp_cron_disabled'
			]);

    }

    $sslverify     = version_compare( $wp_version, 4.0, '<' );
    $doing_wp_cron = sprintf( '%.22F', microtime( true ) );

    $cron_request = apply_filters('cron_request', array(
      'url'  => site_url('wp-cron.php?doing_wp_cron=' . $doing_wp_cron),
      'key'  => $doing_wp_cron,
      'args' => [
        'timeout'   => 3,
        'blocking'  => true,
        'sslverify' => apply_filters( 'https_local_ssl_verify', $sslverify )
      ]
    ));

    $cron_request['args']['blocking'] = true;

    $result = wp_remote_post( $cron_request['url'], $cron_request['args'] );

    if ( is_wp_error( $result ) ) {
			return $result;

    } else if ( wp_remote_retrieve_response_code( $result ) >= 300 ) {

			return new \WP_Error( 'unexpected_http_response_code', sprintf(
				__( 'Unexpected HTTP response code: %s', 'wp-crontrol' ),
				intval( wp_remote_retrieve_response_code( $result ) )
      ));

    }

	}





	/**
	 * Dispatch
	 *
	 * @access public
	 * @return void
	 */
	public function dispatch() {

		// Schedule the cron healthcheck.
		$this->schedule_event();

		// Perform remote post.
		// Always an empty array because 'blocking' is set to false
		return parent::dispatch();

	}

	/**
	 * Push to queue
	 *
	 * @param mixed $data Data.
	 *
	 * @return $this
	 */
	public function push_to_queue( $data ) {

		$this->data[] = $data;

		return $this;

	}


	/*

	WP Shopify addon -- allows for manipulation before saving to options table

	*/
	protected function before_queue_item_save($items) {
		return $items;
	}


	/*

	Save queue

	@return $this

	*/
	public function save() {

		$key = $this->generate_key();

		if ( !empty( $this->data) ) {
			Options::update($key, $this->before_queue_item_save($this->data));
		}

		return $this;

	}


	/**
	 * Update queue
	 *
	 * @param string $key Key.
	 * @param array  $data Data.
	 *
	 * @return $this
	 */
	public function update( $key, $data ) {

		if ( !empty( $data ) ) {
			Options::update( $key, $data );
		}

		return $this;

	}

	/**
	 * Delete queue
	 *
	 * @param string $key Key.
	 *
	 * @return $this
	 */
	public function delete( $key ) {

		Options::delete($key);

		return $this;

	}


	public function generate_unique_string() {
		return md5( microtime() . rand() );
	}


	/**
	 * Generate key
	 *
	 * Generates a unique key based on microtime. Queue items are
	 * given a unique key so that they can be merged upon save.
	 *
	 * @param int $length Length.
	 *
	 * @return string
	 */
	protected function generate_key( $length = 64 ) {

		$unique  = $this->generate_unique_string();
		$prepend = $this->identifier . '_batch_';

		return substr( $prepend . $unique, 0, $length );

	}

	/**
	 * Maybe process queue
	 *
	 * Checks whether data exists within the queue and that
	 * the process is not already running.
	 */
	public function maybe_handle() {

		// Don't lock up other requests while processing
		session_write_close();

		if ( $this->is_process_running() ) {
			// Background process already running.
			wp_die();
		}

		if ( $this->is_queue_empty() ) {
			// No data to process.
			wp_die();
		}

		check_ajax_referer( $this->identifier, 'nonce' );

		$this->handle();

		wp_die();

	}


	/**
	 * Is queue empty
	 *
	 * @return bool
	 */
	protected function is_queue_empty() {

		global $wpdb;

		$table  = $wpdb->options;
		$column = 'option_name';

		$key = $wpdb->esc_like( $this->identifier . '_batch_' ) . '%';

		$count = $wpdb->get_var( $wpdb->prepare( "
		SELECT COUNT(*)
		FROM {$table}
		WHERE {$column} LIKE %s
	", $key ) );

		return ( $count > 0 ) ? false : true;

	}

	/**
	 * Is process running
	 *
	 * Check whether the current process is already running
	 * in a background process.
	 */
	protected function is_process_running() {

		if ( Options::get($this->identifier . '_process_lock') ) {

			// Process already running.
			return true;

		}

		return false;

	}

	/**
	 * Lock process
	 *
	 * Lock the process so that multiple instances can't run simultaneously.
	 * Override if applicable, but the duration should be greater than that
	 * defined in the time_exceeded() method.
	 */
	protected function lock_process() {

		$this->start_time = time(); // Set start time of current process.

		$lock_duration = ( property_exists( $this, 'queue_lock_time' ) ) ? $this->queue_lock_time : 40; // 1 minute
		$lock_duration = apply_filters( $this->identifier . '_queue_lock_time', $lock_duration );

		Options::update( $this->identifier . '_process_lock', microtime(), $lock_duration );

	}

	/**
	 * Unlock process
	 *
	 * Unlock the process so that other instances can spawn.
	 *
	 * @return $this
	 */
	protected function unlock_process() {
		Options::delete($this->identifier . '_process_lock');
		return $this;
	}


	/*

	Get batch

	@return stdClass Return the first batch from the queue

	*/
	protected function get_batch() {

		global $wpdb;

		$table        = $wpdb->options;
		$column       = 'option_name';
		$key_column   = 'option_id';
		$value_column = 'option_value';

		$key = $wpdb->esc_like( $this->identifier . '_batch_' ) . '%';

		$query = $wpdb->get_row( $wpdb->prepare( "
			SELECT *
			FROM {$table}
			WHERE {$column} LIKE %s
			ORDER BY {$key_column} ASC
			LIMIT 1
		", $key ) );


		if ( !is_object($query) || !isset($query, $column) ) {

			$this->DB_Settings_Syncing->save_notice_and_expire_sync( Utils::wp_error([
				'message_lookup' 	=> 'failed_to_find_batch',
				'message_aux'			=> 'Tried searching table ' . $table . ' where column ' . $column . ' like ' . $key,
				'call_method' 		=> __METHOD__,
				'call_line' 			=> __LINE__
			]));

			return false;

		}

		$batch       = new \stdClass();
		$batch->key  = $query->$column;
		$batch->data = maybe_unserialize( $query->$value_column );

		return $batch;

	}


	/*

	After queue item removal

	*/
	protected function after_queue_item_removal($value) {

	}


	/*

	Handles fatal errors

	*/
	public function handle_fatal_errors() {

		$error = error_get_last();

		if ( $error !== NULL && $error["type"] === 1) {

			$this->DB_Settings_Syncing->save_notice_and_expire_sync( Utils::wp_error([
				'message_lookup' 	=> $error["message"],
				'call_method' 		=> $error["file"],
				'call_line' 			=> $error["line"]
			]));

			$this->complete();

		}

	}


	/*

	Handle

	Pass each queue item to the task handler, while remaining
	within server memory and time limit constraints.

	*/
	protected function handle() {

		$this->lock_process();

		register_shutdown_function([$this, 'handle_fatal_errors']);

		do {

			$batch = $this->get_batch();

			foreach ( $batch->data as $key => $value ) {

				/*

				This task function is what we use in our class extension. If we return false, then
				we remove the item from the queue.

				*/
				$task_result = $this->task($value);

				if ( $task_result !== false ) {

					$batch->data[$key] = $task_result;

				} else {

					$this->after_queue_item_removal($value);

					unset( $batch->data[ $key ] );

				}

				// Batch limits reached.
				if ( $this->time_exceeded() || $this->memory_exceeded() ) {
					break;
				}

			}

			// Update or delete current batch.
			if ( !empty($batch->data) ) {
				$this->update( $batch->key, $batch->data );

			} else {
				$this->delete( $batch->key );

			}

		/*

		Run the do-while loop as long as ...
		- Time hasn't exceeded
		- Memory limit hasn't exceeded
		- Items are left to process inside the queue

		*/
		} while ( !$this->time_exceeded() && !$this->memory_exceeded() && !$this->is_queue_empty() );

		$this->unlock_process();

		// Start next batch or complete process.
		if ( ! $this->is_queue_empty() ) {
			$this->dispatch();

		} else {
			$this->complete();
		}

		wp_die();

	}


	/*

	Memory exceeded

	Ensures the batch process never exceeds 90%
	of the maximum WordPress memory.

	@return bool

	*/
	protected function memory_exceeded() {

		$memory_limit   = $this->get_memory_limit() * 0.9; // 90% of max memory
		$current_memory = memory_get_usage( true );
		$return         = false;

		if ( $current_memory >= $memory_limit ) {

			$return = true;

			$this->DB_Settings_Syncing->save_notice_and_expire_sync( Utils::wp_error([
				'message_lookup' 	=> 'max_memory_exceeded',
				'message_aux' 		=> 'Stopped at ' . Data::to_readable_size($current_memory) . ' of a ' . Data::to_readable_size( $this->get_memory_limit() ) . ' limit.',
				'call_method' 		=> __METHOD__,
				'call_line' 			=> __LINE__
			]));

			$this->complete();

		}

		return apply_filters( $this->identifier . '_memory_exceeded', $return );

	}


	/*

	Get memory limit

	always a 40M minimum

	@return int

	*/
	protected function get_memory_limit() {

		if ( function_exists( 'ini_get' ) ) {
			$memory_limit = ini_get( 'memory_limit' );

		} else {

			// Sensible default.
			$memory_limit = '128M';
		}

		if ( ! $memory_limit || -1 === intval( $memory_limit ) ) {

			// Unlimited, set to 32GB.
			$memory_limit = '32000M';
		}

		return intval( $memory_limit ) * 1024 * 1024;

	}


	/*

	Time exceeded.

  Ensures the batch never exceeds a sensible time limit.
  A timeout limit of 30s is common on shared hosting.

  @return bool

	*/
	protected function time_exceeded() {

		$finish = $this->start_time + apply_filters( $this->identifier . '_default_time_limit', 25 ); // 25 seconds
		$return = false;

		if ( time() >= $finish ) {
			$return = true;
		}

		return apply_filters( $this->identifier . '_time_exceeded', $return );

	}


	/*

	Complete.

	Override if applicable, but ensure that the below actions are
	performed, or, call parent::complete().

	*/
	protected function complete() {

		if ( !$this->DB_Settings_Syncing->is_syncing() ) {
			$this->DB_Settings_Syncing->expire_sync();
		}

		// Unschedule the cron healthcheck.
		$this->clear_scheduled_event();

	}


	/*

	Schedule cron healthcheck

	@access public
	@param mixed $schedules Schedules.
	@return mixed

	*/
	public function schedule_cron_healthcheck( $schedules ) {

		$interval = apply_filters( $this->identifier . '_cron_interval', 1 );

		if ( property_exists( $this, 'cron_interval' ) ) {
			$interval = apply_filters( $this->identifier . '_cron_interval', $this->cron_interval );
		}

		// Adds every 5 minutes to the existing schedules.
		$schedules[ $this->identifier . '_cron_interval' ] = array(
			'interval' => MINUTE_IN_SECONDS * $interval,
			'display'  => sprintf( __( 'Every %d Minutes' ), $interval ),
		);

		return $schedules;

	}


	/*

	Handle cron healthcheck

	Restart the background process if not already running
	and data exists in the queue.

	*/
	public function handle_cron_healthcheck() {

		if ( $this->is_process_running() ) {

			// Background process already running.
			exit;

		}

		if ( $this->is_queue_empty() ) {

			// No data to process.
			$this->clear_scheduled_event();
			exit;

		}

		$this->handle();

		exit;

	}


	/*

	Schedule event

	*/
	protected function schedule_event() {

		if ( ! wp_next_scheduled( $this->cron_hook_identifier ) ) {
			wp_schedule_event( time(), $this->cron_interval_identifier, $this->cron_hook_identifier );
		}

	}


	/*

	Clear scheduled event

	*/
	protected function clear_scheduled_event() {

		$timestamp = wp_next_scheduled( $this->cron_hook_identifier );

		if ( $timestamp ) {
			wp_unschedule_event( $timestamp, $this->cron_hook_identifier );
		}

	}


	/*

	Cancel Process

	Stop processing queue items, clear cronjob and delete batch.

	*/
	public function cancel_process() {

		if ( ! $this->is_queue_empty() ) {

			$batch = $this->get_batch();

			$this->delete( $batch->key );

			wp_clear_scheduled_hook( $this->cron_hook_identifier );
		}

	}


	/*

	Takes care of looping through all the items and dispatching them to the queue

	*/
	public function dispatch_items($items, $group = false) {

		if ($group) {
			$this->push_to_queue($items);

		} else {

			if ( is_array($items) && !empty($items) ) {

				foreach ($items as $item) {
					$this->push_to_queue($item);
				}

			} else {
				$this->push_to_queue($items);
			}

		}

		$this->save()->dispatch();

	}


	/*

	Task

  Override this method to perform any actions required on each
  queue item. Return the modified item for further processing
  in the next pass through. Or, return false to remove the
  item from the queue.

  @param mixed $item Queue item to iterate over.

  @return mixed

	*/
	abstract protected function task($item);

}
