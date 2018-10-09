<?php

namespace WPS;

use WPS\Utils;
use WPS\CPT;
use WPS\Transients;

use function WPS\Vendor\DeepCopy\deep_copy;

if (!defined('ABSPATH')) {
	exit;
}


class DB {

	public $table_name;
	public $version;
	public $primary_key;


	/*

	Creates database table

	Never called directly. Only used as a method on a DB class such as:

	*/
	public function create_table($network_wide) {

		// Creates custom tables for each blog
		if ( is_multisite() && $network_wide ) {

			$blog_ids = $this->get_network_sites();

			// $site_blog_id is a string!
			foreach ( $blog_ids as $site_blog_id ) {

				switch_to_blog( $site_blog_id );

				$table_name = $this->get_table_name();

				$result = $this->create_table_if_doesnt_exist($table_name);

				restore_current_blog();

			}

		} else {

			$result = $this->create_table_if_doesnt_exist($this->table_name);

		}

		return $result;

	}


	/*

	Creates a table based on table name

	*/
	public function create_table_if_doesnt_exist($table_name) {

		$result = false;

		if ( !$this->table_exists($table_name) ) {

			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

			$result = \dbDelta( $this->create_table_query( $table_name ) );
			set_transient('wp_shopify_table_exists_' . $table_name, 1);

		}

		return $result;

	}


	/*

	Get all blogs in the network and activate plugin on each one

	Could use get_sites() instead, but requires WordPress 4.6 +

	*/
	public function get_network_sites() {

		global $wpdb;

		return $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs");

	}


	/*

	Gets database collate

	*/
	public function collate() {

		global $wpdb;

		$collate = '';

		if ( $wpdb->has_cap('collation') ) {
			$collate = $wpdb->get_charset_collate();
		}

		return $collate;

	}


	/*

	Get Current Columns

	*/
	public function get_columns_current() {

		$table_name = $this->get_table_name();

		if (!$this->table_exists($table_name)) {
			return [];
		}

		global $wpdb;

		return $wpdb->get_col("DESC {$table_name}", 0);

	}


	public function switch_shopify_ids($data, $old_primary_key, $new_primary_key) {

		if ( !Utils::has($data, $old_primary_key) ) {
			return $data;
		}

		$data->$new_primary_key = $data->$old_primary_key;
		unset($data->$old_primary_key);

		return $data;

	}


	public function tables_skip_rename_primary_key() {

		return [
			'settings_connection',
			'settings_general',
			'settings_syncing',
			'settings_license',
			'shop',
			'tag'
		];

	}


	/*

	Maybe renames primary key of data before update / insert

	*/
	public function maybe_rename_to_lookup_key($item) {

		if ( in_array($this->type, $this->tables_skip_rename_primary_key() )) {
			return $item;
		}

		// If item doesnt have the shopify primary key
		// Only proceeds if 'id' is present on data
		if ( !Utils::has($item, WPS_SHOPIFY_PAYLOAD_KEY) ) {
			return $item;
		}

		return $this->rename_to_lookup_key($item, WPS_SHOPIFY_PAYLOAD_KEY, $this->lookup_key);

	}


	/*

	Rename primary key

	$product

	*/
	public function rename_to_lookup_key($data, $old_primary_key, $new_primary_key) {

		$data = Utils::convert_array_to_object($data);

		// If keys have already been changed just return it
		if ( Utils::has($data, $new_primary_key) && !Utils::has($data, $old_primary_key) ) {
			return $data;
		}

		return $this->switch_shopify_ids($data, $old_primary_key, $new_primary_key);

	}


	/*

	Renames primary keys for an array of items

	array_map("show_hindi", $counting, $hindi);

	*/
	public function rename_to_lookup_keys($items, $old_primary_key, $new_primary_key) {

		if ( Utils::object_is_empty($items) ) {
			return [];
		}

		$items = Utils::convert_object_to_array($items);

		return array_map( function($item) use ($old_primary_key, $new_primary_key) {
			return $this->rename_to_lookup_key($item, $old_primary_key, $new_primary_key);
		}, $items );

	}


	/*

	Copy objects

	*/
	public function copy($maybe_object) {
		return deep_copy($maybe_object);
	}


	/*

	Get Column Meta

	*/
	public function get_column_meta() {

		global $wpdb;
		$table_name = $this->get_table_name();

		return $wpdb->get_results("SHOW FULL COLUMNS FROM $table_name");

	}


	/*

	Add Column

	*/
	public function add_column($col_name, $col_meta) {

		global $wpdb;
		$table_name = $this->get_table_name();

		$query = "ALTER TABLE $table_name ADD %s %s";

		$results = $wpdb->query( $wpdb->prepare($query, $col_name, $col_meta) );

		return $results;

	}


	/*

	Get Column Defaults

	*/
	public function get_column_defaults() {
		return [];
	}


	/*

	Get Column Defaults

	*/
	public function get_column_names($columns) {

		$list = [];

		foreach ($columns as $key => $value) {
			$list[] = $key;
		}

		return $list;

	}


	/*

	Construct a string version of the column names

	*/
	public function construct_column_name_string($columns) {

		$string = '';
		$last_column = end($columns);

		foreach ($columns as $column) {

			if ($column == $last_column) {
				$string .= $column;

			} else {
				$string .= $column . ', ';
			}

		}

		return $string;

	}


	/*

	Returns corrosponding table name. Contains prefix.

	*/
	public function get_table_name() {

		global $wpdb;

		return $wpdb->prefix . $this->table_name_suffix;

	}


	/*

	Gets the max packet size

	*/
	public function get_max_packet_size() {

		global $wpdb;

		$results = $wpdb->get_results("SHOW VARIABLES LIKE 'max_allowed_packet'");

		if (!empty($results)) {
			return (int) $results[0]->Value;

		} else {
			return 0;
		}


	}


	public function max_packet_size_reached($query) {

		$postmax_size_in_bytes = $this->get_max_packet_size();

		$query_size_in_bytes = strlen(serialize($query));

		if ($query_size_in_bytes > $postmax_size_in_bytes) {
			return true;

		} else {
			return false;
		}

	}




	/*

	Retrieve a row by the primary key

	*/
	public function get($row_id = 0) {

		global $wpdb;

		$table_name = $this->get_table_name();
		$results = [];

		if ($this->table_exists($table_name)) {

			$get_results_cached = Transients::get('wps_table_' . $table_name . '_row_' . $row_id);

			if ( !empty($get_results_cached) ) {
				return $get_results_cached;
			}

			if (empty($row_id)) {

				$query = "SELECT * FROM $table_name LIMIT 1;";
				$results = $wpdb->get_row($query);


			} else {
				$query = "SELECT * FROM $table_name WHERE $this->primary_key = %s LIMIT 1;";
				$results = $wpdb->get_row( $wpdb->prepare($query, $row_id) );

			}

		}

		Transients::set('wps_table_' . $table_name . '_row_' . $row_id, $results);

		return $results;

	}


	/*

	Retrieve a row by a specific column / value

	*/
	public function get_row_by($column_name, $column_value) {

		global $wpdb;

		$table_name = $this->get_table_name();

		$column_name = esc_sql($column_name);
		$query = "SELECT * FROM $table_name WHERE $column_name = %s LIMIT 1;";

		$prepared = $wpdb->prepare($query, $column_value);

		$results = $wpdb->get_row($prepared);

		return $this->sanitize_db_response($results, 'WP Shopify Error - Unable to get single database row.', 'get_row');

	}


	/*

	Retrieve rows by a specific column / value

	Note: we can't prepare $col_name because doing so converts the sql col into a string.
	E.g., SELECT * FROM wptests_wps_variants WHERE 'product_id'= '1403917533207';

	*/
	public function get_rows($col_name, $col_value) {

		global $wpdb;

		$table_name = $this->get_table_name();

		$col_name = esc_sql($col_name);
		$query = "SELECT * FROM $table_name WHERE $col_name = %s";

		$prepared_query = $wpdb->prepare($query, $col_value);

		return $wpdb->get_results($prepared_query);

	}


	/*

	Retrieve a row by a specific column / value

	*/
	public function get_all_rows() {

		global $wpdb;

		$table_name = $this->get_table_name();

		$query = "SELECT * FROM $table_name";

		return $wpdb->get_results($query);

	}


	/*

	Retrieve a specific column's value by the primary key

	*/
	public function get_column($column_name, $column_value) {

		global $wpdb;

		$table_name = $this->get_table_name();

		$column_name = esc_sql($column_name);
		$query = "SELECT $column_name FROM $table_name WHERE $this->primary_key = %s LIMIT 1;";

		return $wpdb->get_var(
			$wpdb->prepare($query, $column_value)
		);

	}


	public function get_column_single_query($column) {

		$table_name = $this->get_table_name();

		return "SELECT $column FROM $table_name;";

	}


	/*

	Returns true if a database table column exists. Otherwise returns false.

	@link http://stackoverflow.com/a/5943905/2489248

	@param string $table_name Name of table we will check for column existence.
	@param string $column_name Name of column we are checking for.
	@return boolean True if column exists. Else returns false.

	*/
	public function column_exists($column_name) {

		global $wpdb;

		$table_name = $this->get_table_name();

		$query = "SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = %s AND TABLE_NAME = %s AND COLUMN_NAME = %s ";
		$query_prepared = $wpdb->prepare($query, DB_NAME, $table_name, $column_name);

		$column = $wpdb->get_results($query_prepared);

		if ( !empty($column) ) {
			return true;
		}

		return false;

	}


	/*

	Retrieve a specific column's value by the primary key
	TODO: Return the actual value instead of array('col_name' => 'value')

	From Codex: "If no matching rows are found, or if there is a database error, the return value will be an empty array. If your $query string is empty, or you pass an invalid $output_type, NULL will be returned.""

	Can return the following values:

	WP_Error
	False if nothing found or error
	Array of objects


	*/
	public function get_column_single($column) {

		global $wpdb;

		$table_name = $this->get_table_name();

		// If table doesnt exist ...
		if (!$this->table_exists($table_name)) {
			return $this->sanitize_db_response(false, 'WP Shopify Error - Failed to get single database column. Table "' . $table_name . '" doesn\'t exist', 'get_var');
		}

		// If column name is not a string ...
		if (!is_string($column)) {
			return $this->sanitize_db_response(false, 'WP Shopify Error - Database column name is not a string', 'get_var');
		}

		// If argument not apart of schema ...
		if (!array_key_exists($column, $this->get_columns()) ) {
			return $this->sanitize_db_response(false, 'WP Shopify Error - Database column name does not exist. Please try reinstalling the plugin from scratch', 'get_var');
		}

		// Check cache for existing record ...
		$cachedResult = wp_cache_get($column, $this->cache_group);

		/*

		Check to see if the data we want exists already in the cache
		If so, return and exist immediately

		*/
		if ($cachedResult) {
			return $cachedResult;
		}


		// Dont get the column value if it doesnt exist ...
		if ( !$this->column_exists($column) ) {
			return false;
		}

		// Otherwise, construct query and get result ...
		$query = $this->get_column_single_query($column);
		$results = $wpdb->get_results($query);

		// $result will be false if error or if nothing found
		$result = $this->sanitize_db_response($results, 'WP Shopify Error - Database column name is not a string. Please clear the plugin cache and try again.', 'get_results');


		if ($result !== false) {
			wp_cache_add($column, $results, $this->cache_group, 3600);
		}

		return $result;

	}


	/*

	Retrieve a specific column's value by the the specified column / value

	*/
	public function get_column_by($column, $column_where, $column_value) {

		global $wpdb;

		$table_name = $this->get_table_name();

		$column_where   = esc_sql($column_where);
		$column         = esc_sql($column);
		$query          = "SELECT $column FROM $table_name WHERE $column_where = %s LIMIT 1;";

		return $wpdb->get_var(
			$wpdb->prepare($query, $column_value)
		);

	}


	public function has_existing_record($data) {

		global $wpdb;

		$results = $this->get_row_by($this->lookup_key, $data[$this->lookup_key]);

		if ( empty($results) || is_wp_error($results) ) {
			return false;
		}

		return true;

	}


	/*

	Checks if there was a MySQL error

	*/
	public function has_mysql_error() {

		global $wpdb;

		if ($wpdb->last_error !== '') {
			return true;

		} else {
			return false;
		}

	}


	/*

	Default mod before change just returns

	*/
	public function mod_before_change($item) {
		return $item;
	}


	/*

	Helper method for returning MYSQL errors

	Used only for MySQL operations

	$query: The result of a wpdb operation
	$fallback_message: The message to use if an error occurs
	$type: Name of the wpdb function. Possible values:
		- get_row
		- get_results
		- query
		- update
		- insert


	*/
	public function sanitize_db_response($result, $fallback_message = 'Uncaught error. Please clear the plugin cache and try again.', $type) {

		global $wpdb;


		/*

		If $wpdb->last_error doesnt contain an empty string, then we know the query failed in some capacity. We can safely
		return this wrapped inside a WP_Error.

		*/
		if ( $this->has_mysql_error() ) {
			return Utils::wp_error( __($wpdb->last_error . '. Please clear the plugin cache and try again.', WPS_PLUGIN_TEXT_DOMAIN) );
		}


		/*

		Returns false if errors:

		$wpdb->update
		$wpdb->delete
		$wpdb->insert
		$wpdb->replace

		*/
		if ($result === false) {
			return false;
		}


		/*

		Empty array is returned if no results are found for the following functions:

		$wpdb->get_col					-- Returns an empty array if no result is found.
		$wpdb->get_results			-- If no matching rows are found, or if there is a database error


		NULL will be returned.
		Null is returned for the following functions:

		$wpdb->get_var 				-- Returns NULL if no result is found
		$wpdb->get_row 				-- Returns NULL if no result is found,
		$wpdb->get_results 		-- If your $query string is empty, or you pass an invalid $output_type

		*/

		if ( is_array($result) && Utils::array_is_empty($result) || is_null($result) ) {
			return false;
		}


		/*

		If the $data matches what is already in the database, no rows will be updated, so 0 will be returned.

		No errors occured and nothing was updated.

		$wpdb->update: If nothing was updated
		$wpdb->delete: If nothing was deleted

		*/
		if ($result === 0) {

			if ($type === 'query' || $type === 'update') {
				return true;
			}

		}


		// If execution gets to hear, then we have actual data to work with in the form of a non-empty array
		return $result;

	}


	/*

	Insert a new row

	Returns false if the row could not be inserted. Otherwise, it returns the number of affected rows (which will always be 1).
	https://codex.wordpress.org/Class_Reference/wpdb

	*/
	public function insert($data) {

		global $wpdb;

		$table_name = $this->get_table_name();

		// Only perform an insertion if the table exists ...
		if ( !$this->table_exists($table_name) ) {
			return false;
		}

		// Convert data to array if not one already
		$data = Utils::convert_object_to_array($data);

		// Return immediately, if $data does not exist or if it equals false
		if ( empty($data) ) {
			return false;
		}

		// Gets the table's column names
		$column_formats = $this->get_columns();

		// Performs any needed data structure changes before insert (primary key, adding values, etc)
		$data = $this->mod_before_change($data);

		// Set default values. Requires $data to be array
		$data = wp_parse_args( $data, $this->get_column_defaults() );

		// Shopify sometimes sends date values that don't adhere to the MySQL standard. Here we force it.
		$data = Utils::convert_needed_values_to_datetime($data);

		// Sanitizing nested arrays (serializes nested arrays and objects)
		$data = Utils::serialize_data_for_db($data);

		// Force fields to lower case
		$data = array_change_key_case($data);

		// White list columns
		$data = array_intersect_key($data, $column_formats);

		// Reorder $column_formats to match the order of columns given in $data
		$data_keys = array_keys($data);



		/*

		If data to insert is too big, preemptively throw error. If we don't, $wpdb fails silently

		*/
		if ( Utils::data_values_size_limit_reached($data, $table_name) ) {
			return Utils::wp_error( __('Data size limit reached for table: ' . $table_name, WPS_PLUGIN_TEXT_DOMAIN) );
		}



		$column_formats = array_merge( array_flip($data_keys), $column_formats);

		do_action('wps_before_insert_' . $this->type, $data);


		/*

		Checks whether the item we're inserting into the DB
		already exists to avoid errors. We can do this by first running $wpdb->get_results
		and then cheking the num rows like below:

		*/

		if ( $this->has_existing_record($data) ) {

			$result = $wpdb->update($table_name, $data, $column_formats);

			return $this->sanitize_db_response($result, 'Failed to update database record. Please clear the plugin cache and try again.', 'update');


		} else {

			$result = $wpdb->insert($table_name, $data, $column_formats);

			do_action('wps_after_insert_' . $this->type, $result, $data);

			return $this->sanitize_db_response($result, 'Failed to insert database record. Please clear the plugin cache and try again.', 'insert');

		}


	}




	/*

	Update a new row


	By defaut, $row_id refers to the primary key of the table. However if $where is passed in, then
	$row_id will refer to this column instead


	*/
	public function update($column_name = false, $column_value, $data = []) {

		global $wpdb;

		$table_name = $this->get_table_name();

		// Row ID must be positive integer
		$column_value = absint($column_value);

		// Record must already exist already to update
		if ( empty($column_value) ) {
			return false;
		}

		// Sets the column for lookup to the primary key of the table by default
		if ( $column_name === false ) {
			$column_name = $this->primary_key;
		}

		// Performs any needed data structure changes before update (primary key, adding values, etc)
		$data = $this->mod_before_change($data);

		// Forces data to array
		$data = Utils::convert_object_to_array($data);

		// Shopify sometimes sends date values that don't adhere to the MySQL standard. Here we force it.
		$data = Utils::convert_needed_values_to_datetime($data);

		$data = Utils::serialize_data_for_db($data);

		// Initialize column format array
		$column_formats = $this->get_columns();

		// Force fields to lower case
		$data = array_change_key_case( $data );

		// White list columns
		$data = array_intersect_key($data, $column_formats);

		// Reorder $column_formats to match the order of columns given in $data
		$data_keys = array_keys($data);

		$column_formats = array_merge( array_flip($data_keys), $column_formats );

		do_action('wps_before_update_' . $this->type, $data);

		$results = $wpdb->update(
			$table_name,
			$data,
			[ $column_name => $column_value ],
			$column_formats
		);

		if (isset($data['access_token'])) {
			Transients::delete_cached_settings();
		}

		do_action('wps_after_update_' . $this->type, $data);

		return $this->sanitize_db_response($results, 'Failed to update database record. Please clear the plugin cache and try again.', 'update');

	}


	/*

	Update a new row
	Returns boolean

	*/
	public function update_column_single($data = [], $where = [], $formats = false) {

		global $wpdb;

		$table_name = $this->get_table_name();

		/*

		TODO: Currently the below empty check is not working. Will fail silently.
		The correct where format needs to be: ['primary_key_col', 'primary_key_value']

		*/
		if ( empty($where) ) {
			$where = $this->primary_key;
		}


		if ($formats) {

			$column_formats = $this->get_columns();

			// Shopify sometimes sends date values that don't adhere to the MySQL standard. Here we force it.
			$data = Utils::convert_needed_values_to_datetime($data);

			$results = $wpdb->update($table_name, $data, $where, $column_formats);

		} else {

			$results = $wpdb->update($table_name, $data, $where);

		}

		return $this->sanitize_db_response($results, 'Failed to update database record. Please clear the plugin cache and try again.', 'update');

	}


	/*

	Truncates table

	*/
	public function truncate() {

		global $wpdb;

		$table_name = $this->get_table_name();

		if ( !$this->table_exists($table_name) ) {
			return $this->sanitize_db_response(false, 'WP Shopify Error - Tried to truncate table ' . $table_name . ' but table doesn\'t exist.');
		}

		$query = "TRUNCATE TABLE $table_name";
		$query_results = $wpdb->query($query);

		return $this->sanitize_db_response($query_results, 'WP Shopify Error - Failed to truncate table ' . $table_name . '. Please clear the plugin cache and try again.', 'query');

	}


	/*

	Delete a row identified by the primary key

	Used only to delete single rows from a table specified by primary key.

	*/
	public function delete($row_id = 1) {

		global $wpdb;

		$table_name = $this->get_table_name();

		if ( !$this->table_exists($table_name) ) {
			return $this->sanitize_db_response(false, 'WP Shopify Error - Tried to perform deletion on table ' . $table_name . ' but table doesn\'t exist.');
		}

		// Row ID must be positive integer
		$row_id = absint($row_id);

		$query = "DELETE FROM $table_name WHERE $this->primary_key = %d";
		$query_prepared = $wpdb->prepare($query, $row_id);

		do_action('wps_before_delete_' . $this->type, $row_id);

		$query_results = $wpdb->query($query_prepared);

		do_action('wps_after_delete_' . $this->type, $row_id);

		return $this->sanitize_db_response($query_results, 'WP Shopify Error - Failed to delete record(s) on table ' . $table_name . '. Please clear the plugin cache and try again.', 'query');

	}


	/*

	Used for generalized queriers + error handling

	*/
	public function query($query) {

		global $wpdb;

		$results = $wpdb->query($query);

		return $this->sanitize_db_response($results, 'WP Shopify Error - Database query failed when executing: ' . $query, 'query');

	}


	/*

	Deletes a normal WP Shopify table

	TODO: Will only delete table if it exists. We should probably alert the system
	somehow if the table was _expected to exist_ but didn't for some reason.

	*/
	public function delete_table() {

		global $wpdb;

		$table_name = $this->get_table_name();

		// Removes real table if exists ...
		if ( $this->table_exists($table_name) ) {

			$sql = "DROP TABLE IF EXISTS " . $table_name;
			$results = $wpdb->query($sql);

			return $this->sanitize_db_response($results, 'WP Shopify Error - Failed to delete table: ' . $table_name . '. Please clear the plugin cache and try again.', 'query');

		}

	}


	/*

	Deletes a WP Shopify migration table

	*/
	public function delete_migration_table($table_suffix) {

		global $wpdb;

		$table_name = $this->get_table_name();

		// Removes migration table if exists ...
		if ( $this->table_exists($table_name . $table_suffix) ) {

			$sql = "DROP TABLE IF EXISTS " . $table_name . $table_suffix;
			$results = $wpdb->query($sql);

			return $this->sanitize_db_response($results, 'WP Shopify Error - Failed to delete table: ' . $table_name . $table_suffix . '. Please clear the plugin cache and try again.', 'query');

		}

	}




	/*

	Responsible for renaming the migration table name back to the standard table name

	*/
	public function rename_migration_table() {

		global $wpdb;

		$table_name = $this->get_table_name();

		if ( !$this->table_exists( $table_name . WPS_TABLE_MIGRATION_SUFFIX ) ) {
			return true;
		}

		$query = "RENAME TABLE " . $table_name . WPS_TABLE_MIGRATION_SUFFIX . ' TO ' . $table_name;
		$results = $wpdb->get_results($query);

		return $this->sanitize_db_response($results, 'WP Shopify Error - Failed to rename migration table back to: ' . $table_name . '. Please clear the plugin cache and try again.', 'get_results');

	}


	/*

	Responsible for creating a migration table with the '_migrate' suffix

	Returns either a WP_Error or true on success

	For CREATE, ALTER, TRUNCATE and DROP SQL statements, (which affect whole tables instead of specific rows)
	this function returns TRUE on success. If a MySQL error is encountered, the function will return FALSE.

	*/
	public function create_migration_table( $table_suffix ) {

		$table_name = $this->get_table_name();

		if ( !$this->table_exists( $table_name . $table_suffix ) ) {

			global $wpdb;

			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

			$result = $wpdb->query( $this->create_table_query( $table_name . $table_suffix ) );

			if ($result !== true) {

				if ( $this->has_mysql_error() ) {
					return Utils::wp_error( __($wpdb->last_error, WPS_PLUGIN_TEXT_DOMAIN) );

				} else {
					return Utils::wp_error( __('Unable to create migration table. Unknown reason.', WPS_PLUGIN_TEXT_DOMAIN) );
				}

			}

			return $result;

		} else {
			return Utils::wp_error( __('Unable to create migration table. Already exists.', WPS_PLUGIN_TEXT_DOMAIN) );

		}

	}


	/*

	Delete a row(s) identified by column value

	*/
	public function delete_rows($column_name, $column_value) {

		global $wpdb;

		$table_name = $this->get_table_name();

		$column_name = esc_sql($column_name);

		if (gettype($column_value) === 'integer') {
			$query = "DELETE FROM $table_name WHERE $column_name = %d";

		} else if (gettype($column_value) === 'double') {
			$query = "DELETE FROM $table_name WHERE $column_name = %f";

		} else {
			$query = "DELETE FROM $table_name WHERE $column_name = %s";
		}

		$prepared = $wpdb->prepare($query, $column_value);
		$results = $wpdb->query($prepared);

		return $this->sanitize_db_response($results, 'WP Shopify Error - Failed to delete database rows. Please clear the plugin cache and try again.', 'query');

	}


	/*

	Delete a row(s) identified by column value

	$values comes in as an array. We must turn it into a comma
	seperated list.

	*/
	public function delete_rows_in($column, $ids) {

		global $wpdb;

		$table_name = $this->get_table_name();
		$column = esc_sql($column);

		if (gettype($ids) === 'integer') {
			$query = "DELETE FROM $table_name WHERE $column IN (%d)";

		} else if (gettype($ids) === 'double') {
			$query = "DELETE FROM $table_name WHERE $column IN (%f)";

		} else {
			$query = "DELETE FROM $table_name WHERE $column IN (%s)";
		}


		$result = $wpdb->get_results(
			$wpdb->prepare($query, $ids)
		);

		return $this->sanitize_db_response($result, 'WP Shopify Error - Failed to delete database rows by value. Please clear the plugin cache and try again.', 'get_results');

	}


	/*

	Looks to see if table exists by name

	*/
	public function search_for_table($table) {

		global $wpdb;

		$table_sanitized = sanitize_text_field($table);

		$query = $wpdb->prepare("SHOW TABLES LIKE '%s'", $table_sanitized);

		return $wpdb->get_var($query);

	}


	/*

	Check if the given table exists

	*/
	public function table_exists($table_name) {

		if ( get_transient('wp_shopify_table_exists_' . $table_name) ) {
			return true;

		} else {

			$table_name_from_db = $this->search_for_table($table_name);

			// Tables exists
			if ($table_name_from_db === $table_name) {
				set_transient('wp_shopify_table_exists_' . $table_name, 1);
				return true;
			}

			return false;

		}

	}


	/*

	Assigns the "post_id" foreign key to Products and Collections rows

	*/
	public function assign_foreign_key($row, $foreign_key) {

		$row_copy = $row;
		$row_copy->post_id = $foreign_key;

		return $row_copy;

	}


	/*

	Inserts a single custom term. Used by "wps_tags"

	*/
	public function insert_single_term($cpt_id, $term, $taxonomy_name) {

		$results = [];

		if (taxonomy_exists($taxonomy_name)) {

			// Sets the tag to our custom $taxonomy_name taxonomy
			$tag_tax_set_result = wp_set_object_terms($cpt_id, $term, $taxonomy_name, true);

			if ( !is_array($tag_tax_set_result) ) {

				if (is_wp_error($tag_tax_set_result)) {
					$results[] = $tag_tax_set_result->get_error_message();

				} else {
					$results[] = $tag_tax_set_result;
				}

			} else {
				$results = $tag_tax_set_result;
			}

			return $results;

		}

	}


	public function sanitize_update_post_meta_response($response, $post_id, $meta_key, $new_meta_value) {

		if ( !is_wp_error($response) ) {
			return true;
		}

		// Not a real error
		if ( get_post_meta($post_id, $meta_key, true) === $new_meta_value ) {
			return true;

		} else {
			return $result;

		}

	}


	/*

	Wrapper function for updating post meta

	*/
	public function update_post_meta_helper($post_id, $meta_key, $meta_value) {

		$response = $this->sanitize_db_response(
			update_post_meta($post_id, $meta_key, $meta_value),
			'Failed to update post meta table with key value',
			'update_post_meta'
		);

		return $this->sanitize_update_post_meta_response($response, $post_id, $meta_key, $meta_value);

	}


	/*

	Checks if the tables has been initialized or not

	*/
	public function table_has_been_initialized($primary_key = 'id') {

		if ( !$this->table_exists($this->table_name) ) {
			return false;
		}

		$row = $this->get_rows($primary_key, 1);

		if (count($row) <= 0) {
			return false;

		} else {
			return true;
		}

	}


	/*

	Table charset: Get column info

	*/
	public function get_col_info($table) {

		global $wpdb;

		$results = $wpdb->get_results( "SHOW FULL COLUMNS FROM $table" );

		if (!$results) {
			return Utils::wp_error('WP Shopify Error - Unable to get charset for table ' . $table);

		} else {
			return $results;
		}

	}


	/*

	Table charset: Get column data

	*/
	public function construct_column_data($column_info, $columns) {

		foreach ($column_info as $column) {
			$columns[strtolower($column->Field)] = $column;
		}

		return $columns;

	}


	/*

	Table charset: Get charset from count

	*/
	public function construct_charset_from_count($charsets) {

		// Check if we have more than one charset in play.
		$count = count( $charsets );

		if ( 1 === $count ) {
			$charset = key( $charsets );

		} elseif ( 0 === $count ) {

			// No charsets, assume this table can store whatever.
			$charset = false;

		} else {

			// More than one charset. Remove latin1 if present and recalculate.
			unset( $charsets['latin1'] );
			$count = count( $charsets );

			if ( 1 === $count ) {

				// Only one charset (besides latin1).
				$charset = key( $charsets );

			} elseif ( 2 === $count && isset( $charsets['utf8'], $charsets['utf8mb4'] ) ) {

				// Two charsets, but they're utf8 and utf8mb4, use utf8.
				$charset = 'utf8';

			} else {

				// Two mixed character sets. ascii.
				$charset = 'ascii';

			}

		}

		return $charset;

	}


	/*

	Table charset: Check for binary type

	*/
	public function check_for_binary_type($type) {

		// A binary/blob means the whole query gets treated like this.
		if ( in_array( strtoupper( $type ), array( 'BINARY', 'VARBINARY', 'TINYBLOB', 'MEDIUMBLOB', 'BLOB', 'LONGBLOB' ) ) ) {
			return true;

		} else {
			return false;
		}

	}


	/*

	Table charset: Get charsets from collation

	*/
	public function construct_charsets_from_collation($column, $charsets) {

		global $wpdb;

		if (!empty($column->Collation)) {

			list($charset) = explode( '_', $column->Collation );

			// If the current connection can't support utf8mb4 characters, let's only send 3-byte utf8 characters.
			if ('utf8mb4' === $charset && !$wpdb->has_cap('utf8mb4')) {
				$charset = 'utf8';
			}

			$charsets[ strtolower( $charset ) ] = true;

		}

		return $charsets;

	}


	/*

	Table charset: Check for utf8md3

	*/
	public function check_for_utf8md3($charsets) {

		// utf8mb3 is an alias for utf8.
		if (isset( $charsets['utf8mb3'] )) {
			$charsets['utf8'] = true;
			unset( $charsets['utf8mb3'] );
		}

		return $charsets;

	}


	/*

	Table charset: Get table charset

	*/
	public function get_table_charset($table) {

		global $wpdb;

		$table = strtolower($table);

		if (get_transient('wp_shopify_table_charset_' . $table)) {
			return get_transient('wp_shopify_table_charset_' . $table);
		}

		$charsets = [];
		$columns = [];


		$column_info = $this->get_col_info($table);

		if (is_wp_error($column_info)) {
			return $column_info;
		}


		$columns = $this->construct_column_data($column_info, $columns);


		foreach ( $columns as $column ) {

			$charsets = $this->construct_charsets_from_collation($column, $charsets);

			list($type) = explode( '(', $column->Type );

			if ($this->check_for_binary_type($type)) {
				return 'binary';
			}

		}

		$charsets = $this->check_for_utf8md3($charsets);

		$charset = $this->construct_charset_from_count($charsets);

		set_transient('wp_shopify_table_charset_' . $table, $charset);

		return $charset;

	}


	/*

	utf8mb4 was introduced in MySQL 5.5.3. As of WordPress 4.2, utf8mb4 is used by default on supported MySQL versions

	Issues can arise when a user syncs utf8mb4 content into a utf8 WP database. Therefore we need to check whether WP is
	using a non-utf8mb4 character set and encode our content properly using wp_encode_emoji().

	WordPress has supported utf8mb4 encoded data since version 4.2, and automatically updates the database tables to use utf8mb4
	rather than utf8 during the first upgrade to 4.2+ or when installing 4.2+ from scratch. This only happens if the version of
	MySQL being used supports utf8mb4 (5.5.3+).

	https://deliciousbrains.com/wp-migrate-db-pro/doc/the-source-site-supports-utf8mb4-data-but-the-target-does-not-and-unknown-collation-errors/

	*/
	public function has_compatible_charset($table_name) {

		$charset = $this->get_table_charset($table_name);

		if (is_wp_error($charset)) {
			return false;
		}

		if ($charset !== 'utf8mb4') {
			return false;
		}

		return true;

	}


	/*

	Checks whether $charsets are compaible

	$charsets is an array of table name strings
	Predicate function

	*/
	public function has_compatible_charsets($charsets) {

		foreach ($charsets as $charset) {

			if (!$this->has_compatible_charset($charset)) {
				return false;
			}

		}

		return true;

	}


	/*

	Wrapper function for encoding utf8 charset content into utf8mb4

	*/
	public function maybe_encode_emoji_content($content) {

		if ( function_exists('wp_encode_emoji') && function_exists( 'mb_convert_encoding' ) ) {
			$content = wp_encode_emoji($content);
		}

		return $content;

	}



	/*

	$items: Always represents an array of objects

	*/
	public function encode_data($items) {

		if (empty($items)) {
			return $items;
		}

		// If one big string is passed in, just encode and return it
		if (is_string($items)) {

			return $this->maybe_encode_emoji_content($items);

		}


		foreach ($items as $key => $value) {

			if (empty($value)) {
				return;
			}

			if (is_array($value) || is_object($value)) {

				$this->encode_data($value);

			} else {

				if (is_string($value)) {

					if (is_array($items)) {
						$items[$key] = $this->maybe_encode_emoji_content($value);
					}

					if (is_object($items)) {
						$items->{$key} = $this->maybe_encode_emoji_content($value);
					}

				}

			}

		}

		return $items;

	}




	public function get_only_matching_cols($old_cols) {

		$new_col_names = array_keys( $this->get_columns() );

		$final_cols = array_values( array_intersect($new_col_names, $old_cols) );

		return $final_cols;

	}

	public function build_columns_with_backticks($old_cols) {

		$col_names_string_single_quotes = '`' . Utils::convert_to_comma_string_backticks( $this->get_only_matching_cols($old_cols) ) . '`';

		return str_replace("'", "`", $col_names_string_single_quotes);

	}

	/*

	This is our Unit test:
	array_splice($new_col_names, 3, 0, 'test_col_1');
	array_push($new_col_names, 'test_col_2');
	$matching_col_names === $old_cols

	*/
	public function get_columns_as_insert_string($old_cols) {

		$table_name = $this->get_table_name();

		$col_names_string_backticks = $this->build_columns_with_backticks($old_cols);

		return ' (' . $col_names_string_backticks . ') SELECT ' . $col_names_string_backticks . ' FROM ' . $table_name;

	}

	public function get_values_as_string($values) {
		 return " VALUES (" . Utils::convert_to_comma_string($values) . ")";
	}

	public function get_insert_into_start() {

		$table_name = $this->get_table_name();

		return 'INSERT INTO ' . $table_name . WPS_TABLE_MIGRATION_SUFFIX;

	}

	public function build_insert_into_query($old_cols) {
		return $this->get_insert_into_start() . $this->get_columns_as_insert_string($old_cols);
	}

	public function build_insert_into_values_query($values) {
		return $this->get_insert_into_start() . $this->get_values_as_string($values);
	}


	public function insert_default_values() {
		return $this->insert( $this->get_column_defaults() );
	}







	public function get_lookup_value($item) {

		// product_id, variant_id, image_id, etc
		if (Utils::has($item, $this->lookup_key)) {
			return $item->{$this->lookup_key};
		}

		// id
		if (Utils::has($item, WPS_SHOPIFY_PAYLOAD_KEY)) {
			return $item->{WPS_SHOPIFY_PAYLOAD_KEY};
		}

	}







	public function get_current_items($method_name, $options) {
		return $this->$method_name( $options['item']->{WPS_SHOPIFY_PAYLOAD_KEY} );
	}









	/*

	Gathers items for modification.

	* Important * WPS_SHOPIFY_PAYLOAD_KEY is used to build the get_{items}_from_product_id

	$options

		- $item 									-- $product, $variant, $image, etc
		- $prop_to_access 				-- prop to fetch on latest items
		- $payload_key 		-- old primary key from payload (id)
		- $lookup_key 				-- primary key to we assign (product_id, variant_id, etc)

	*/
	public function gather_items_for_modification($options) {

		$method_name_get_type_from = 'get_' . $options['prop_to_access'] . '_from_' . $options['item_lookup_key'];

		$current_items = $this->get_current_items($method_name_get_type_from, $options);

		$latest_items = $this->get_latest_items_from_payload($options['item'], $options['prop_to_access']);
		$latest_items = $this->rename_to_lookup_keys($latest_items, WPS_SHOPIFY_PAYLOAD_KEY, $this->lookup_key);

		return [
			'current'		=>	Utils::convert_object_to_array($current_items),
			'latest'		=>	Utils::convert_object_to_array($latest_items)
		];

	}


	public function no_items($options) {
		return empty($options['item']);
	}


	public function get_latest_items_from_payload($payload, $prop) {

		if ( !Utils::has($payload, $prop) ) {
			return [];
		}

		return $payload->{$prop};

	}


	/*

	If no items were returned from Shopify, then that means we need to delete any current
	items that we have based on the item value

	*/
	public function gather_items_for_deletion($options) {

		$items = $this->gather_items_for_modification($options);

		return Utils::find_items_to_delete($items['current'], $items['latest'], true, $this->lookup_key);

	}


	/*

	If no items were returned from Shopify, then we know we don't need to perform an update.

	*/
	public function gather_items_for_insertion($options) {

		if ($this->no_items($options)) {
			return [];
		}

		$items = $this->gather_items_for_modification($options);

		return Utils::find_items_to_add($items['current'], $items['latest'], true, $this->lookup_key);

	}


	/*

	If no items were returned from Shopify, then we know we don't need to perform an update.

	*/
	public function gather_items_for_updating($options) {

		if ($this->no_items($options)) {
			return [];
		}

		return $options['item']->{$options['prop_to_access']};
	}


	/*

	Deletes item if not empty

	*/
	public function maybe_delete($items_to_delete, $type) {
		return $this->delete_items_of_type($items_to_delete, $type);
	}


	/*

	Inserts item if not empty

	*/
	public function maybe_insert($items_to_add, $type) {

		if ( Utils::is_empty($items_to_add) ) {
			return [];
		}

		return $this->insert_items_of_type($items_to_add, $type);

	}


	/*

	Updates item if not empty

	*/
	public function maybe_update($items_to_update, $type) {

		if ( Utils::is_empty($items_to_update) ) {
			return [];
		}

		return $this->update_items_of_type($items_to_update, $type);

	}


	/*

	Performs the actual insert, update, or delete

	*/
	public function change_item($item, $method_name) {

		if ( method_exists($this, $method_name) ) {
			return $this->$method_name($item);
		}

	}


	/*

	change_items_of_type

	Generalized wrapper for:

	- insert_{items}
	- update_{items}
	- delete_{items}

	Returns array of modification results or WP_Error

	*/
	public function change_items_of_type($items, $method_name) {

		$results = [];

		if ( !is_array($items) ) {
			return $this->change_item($items, $method_name);
		}

		foreach ($items as $item) {

			$result = $this->change_item($item, $method_name);

			if ( is_wp_error($result) ) {
				return $result;
			}

			$results[] = $result;

		}

		return $results;

	}


	/*

	Inserts items of a specific type

	*/
	public function insert_items_of_type($items) {
		return $this->change_items_of_type($items, 'insert_' . $this->type);
	}


	/*

	Deletes items of a specific type

	Usually calls delete_rows as last step

	*/
	public function delete_items_of_type($items) {
		return $this->change_items_of_type($items, 'delete_' . $this->type);
	}


	/*

	Updates items of a specific type

	*/
	public function update_items_of_type($items) {
		return $this->change_items_of_type($items, 'update_' . $this->type);
	}


	/*

	Main entry point for webhooks

	Returns WP_Error object on error or the number of rows affected on success

	In order to handle an update being initated by _new_ data (e.g., when a new variant is added),
	we need to compare what's currently in the database with what gets sent back via the
	product/update webhook.

	*/
	public function modify_from_shopify($options) {

		$results 				= [];
		$insert_options = $this->copy($options);
		$update_options = $this->copy($options);
		$delete_options = $this->copy($options);

		$items_to_insert = $this->gather_items_for_insertion($insert_options);
		$items_to_delete = $this->gather_items_for_deletion($update_options);
		$items_to_update = $this->gather_items_for_updating($delete_options);

		// Insertions
		$insert_result = $this->maybe_insert($items_to_insert, $options['change_type'] );

		if ( is_wp_error($insert_result) ) {
			return $insert_result;

		} else {
			$results['created'][] = $insert_result;
		}


		// Deletions
		$delete_result = $this->maybe_delete($items_to_delete, $options['change_type'] );

		if ( is_wp_error($delete_result) ) {
			return $delete_result;

		} else {
			$results['deleted'][] = $delete_result;
		}


		// Updates
		$update_result = $this->maybe_update($items_to_update, $options['change_type'] );

		if ( is_wp_error($update_result) ) {
			return $update_result;

		} else {
			$results['updated'][] = $update_result;
		}

		return $results;

	}


}
