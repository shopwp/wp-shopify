<?php

namespace WPS;

use WPS\Utils;
use WPS\CPT;
use WPS\Transients;


if (!defined('ABSPATH')) {
	exit;
}

if (!class_exists('DB')) {

	class DB {

		public $table_name;
		public $version;
		public $primary_key;


	  /*

	  Get Current Columns

	  */
		public function get_columns_current() {

			if (!$this->table_exists($this->table_name)) {
				return [];
			}

			global $wpdb;

			return $wpdb->get_col("DESC {$this->table_name}", 0);

	  }


		/*

	  Get Column Meta

	  */
		public function get_column_meta() {

			global $wpdb;
			return $wpdb->get_results("SHOW FULL COLUMNS FROM $this->table_name");

	  }


		/*

	  Add Column

	  */
		public function add_column($col_name, $col_meta) {

			global $wpdb;

			$query = "ALTER TABLE $this->table_name ADD %s %s";

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
			return $this->table_name;
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
			$results = [];

			if ($this->table_exists($this->table_name)) {

				if (empty($row_id)) {

					$query = "SELECT * FROM $this->table_name LIMIT 1;";
					$results = $wpdb->get_row($query);


				} else {
					$query = "SELECT * FROM $this->table_name WHERE $this->primary_key = %s LIMIT 1;";
					$results = $wpdb->get_row( $wpdb->prepare($query, $row_id) );

				}

			}

	    return $results;

	  }


	  /*

	  Retrieve a row by a specific column / value

	  */
		public function get_by($column, $row_id) {

	    global $wpdb;

	    $column = esc_sql($column);
	    $query = "SELECT * FROM $this->table_name WHERE $column = %s LIMIT 1;";

	    return $wpdb->get_row(
	      $wpdb->prepare($query, $row_id)
	    );

	  }


		/*

	  Retrieve rows by a specific column / value

	  */
		public function get_rows($column, $row_id) {

	    global $wpdb;

	    $column = esc_sql($column);
	    $query = "SELECT * FROM $this->table_name WHERE $column = %s";

	    return $wpdb->get_results(
	      $wpdb->prepare($query, $row_id)
	    );

	  }


		/*

	  Retrieve a row by a specific column / value

	  */
		public function get_all_rows() {

	    global $wpdb;

	    $query = "SELECT * FROM $this->table_name";

	    return $wpdb->get_results($query);

	  }


	  /*

	  Retrieve a specific column's value by the primary key

	  */
		public function get_column($column, $row_id) {

	    global $wpdb;

	    $column = esc_sql($column);
	    $query = "SELECT $column FROM $this->table_name WHERE $this->primary_key = %s LIMIT 1;";

	    return $wpdb->get_var(
	      $wpdb->prepare($query, $row_id)
	    );

	  }


		public function get_column_single_query($column) {
			return "SELECT $column FROM $this->table_name;";
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

			$query = "SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = %s AND TABLE_NAME = %s AND COLUMN_NAME = %s ";

			$column = $wpdb->get_results( $wpdb->prepare($query, DB_NAME, $this->table_name, $column_name));

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

			// If table doesnt exist ...
			if (!$this->table_exists($this->table_name)) {
				return $this->sanitize_db_response(false, 'WP Shopify Error - Failed to get single database column. Table "' . $this->table_name . '" doesn\'t exist. Please clear the plugin cache and try again.');
			}

			// If column name is not a string ...
			if (!is_string($column)) {
				return $this->sanitize_db_response(false, 'WP Shopify Error - Database column name is not a string. Please clear the plugin cache and try again.');
			}

			// If argument not apart of schema ...
			if (!array_key_exists($column, $this->get_columns()) ) {
				return $this->sanitize_db_response(false, 'WP Shopify Error - Database column name does not exist. Please try reinstalling the plugin from scratch.');
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
			$result = $this->sanitize_db_response($results, 'WP Shopify Error - Database column name is not a string. Please clear the plugin cache and try again.');


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

	    $column_where   = esc_sql($column_where);
	    $column         = esc_sql($column);
	    $query          = "SELECT $column FROM $this->table_name WHERE $column_where = %s LIMIT 1;";

	    return $wpdb->get_var(
	      $wpdb->prepare($query, $column_value)
	    );

	  }


		public function has_existing_record($data) {

			global $wpdb;

			$firstKey = current(array_keys($data));
			$existingResults = $wpdb->get_results("SELECT * FROM " . $this->table_name . " WHERE " . $this->table_name . "." . $this->primary_key . " = " . "'" . $data[$firstKey] . "'");

			return $wpdb->num_rows > 0;

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

		Helper method for returning MYSQL errors

		Used only for MySQL operations

		*/
		public function sanitize_db_response($result, $fallback_message = 'Uncaught error. Please clear the plugin cache and try again.') {


			/*

			If $wpdb->last_error doesnt contain an empty string, then we know the query failed in some capacity. We can safely
			return this error wrapped inside a WP_Error.

			*/
			global $wpdb;



			if ( $this->has_mysql_error() ) {
				return new \WP_Error('error', __($wpdb->last_error . '. Please clear the plugin cache and try again.', WPS_PLUGIN_TEXT_DOMAIN));
			}


			/*

			Returns false if errors:

			$wpdb->update
			$wpdb->delete
			$wpdb->insert
			$wpdb->replace

			*/
			if ($result === false) {
				return new \WP_Error('error', __($fallback_message . '. Please clear the plugin cache and try again.', WPS_PLUGIN_TEXT_DOMAIN));
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
			if (Utils::array_is_empty($result) || is_null($result)) {
				return false;
			}


			/*

			If the $data matches what is already in the database, no rows will be updated, so 0 will be returned.

			No errors occured and nothing was updated.

			$wpdb->update: If nothing was updated
			$wpdb->delete: If nothing was deleted

			*/
			if ($result === 0) {
				return true;
			}


			// If execution gets to hear, then we have actual data to work with in the form of a non-empty array
			return $result;

		}


	  /*

	  Insert a new row

		Returns false if the row could not be inserted. Otherwise, it returns the number of affected rows (which will always be 1).
		https://codex.wordpress.org/Class_Reference/wpdb

	  */
	  public function insert($data, $type = '') {

	    global $wpdb;

			// Return immediately, if $data does not exist or if it equals false
			if (empty($data)) {
				return false;
			}

			// Only perform an insertion if the table exists ...
			if (!$this->table_exists($this->table_name)) {
				return false;
			}

			// Set default values. Requires $data to be array
			$data = wp_parse_args($data, $this->get_column_defaults());

			do_action('wps_pre_insert_' . $type, $data);

			// Shopify sometimes sends date values that don't adhere to the MySQL standard. Here we force it.
			$data = Utils::convert_needed_values_to_datetime($data);

			// Sanitizing nested arrays (serializes nested arrays and objects)
			$data = Utils::wps_serialize_data_for_db($data);

			// Initialise column format array
			$column_formats = $this->get_columns();

			// Force fields to lower case
			$data = array_change_key_case($data);

			// White list columns
			$data = array_intersect_key($data, $column_formats);

			// Reorder $column_formats to match the order of columns given in $data
			$data_keys = array_keys($data);

			$column_formats = array_merge( array_flip($data_keys), $column_formats);

			/*

			Checks whether the item we're inserting into the DB
			already exists to avoid errors. We can do this by first running $wpdb->get_results
			and then cheking the num rows like below:

			*/
			if ($this->has_existing_record($data)) {

				$result = $wpdb->update($this->table_name, $data, $column_formats);
				return $this->sanitize_db_response($result, 'Failed to update database record. Please clear the plugin cache and try again.');

			} else {

				$result = $wpdb->insert($this->table_name, $data, $column_formats);

				do_action('wps_post_insert_' . $type, $result, $data);

				return $this->sanitize_db_response($result, 'Failed to insert database record. Please clear the plugin cache and try again.');

			}


	  }


	  /*

	  Update a new row

	  */
		public function update($row_id, $data = array(), $where = '') {

	    global $wpdb;

	    // Row ID must be positive integer
	    $row_id = absint($row_id);

			// Record must already exist already to update
	    if (empty($row_id)) {
	      return false;
	    }

	    if (empty($where)) {
	      $where = $this->primary_key;
	    }

			// Forces data to array
			$data = Utils::convert_object_to_array($data);

			// Shopify sometimes sends date values that don't adhere to the MySQL standard. Here we force it.
			$data = Utils::convert_needed_values_to_datetime($data);

			$data = Utils::wps_serialize_data_for_db($data);

	    // Initialize column format array
	    $column_formats = $this->get_columns();

	    // Force fields to lower case
	    $data = array_change_key_case( $data );

	    // White list columns
	    $data = array_intersect_key($data, $column_formats);

	    // Reorder $column_formats to match the order of columns given in $data
	    $data_keys = array_keys($data);

	    $column_formats = array_merge( array_flip($data_keys), $column_formats );


			$results = $wpdb->update(
		    $this->table_name,
		    $data,
		    array($where => $row_id),
		    $column_formats
		  );


			if (isset($data['access_token'])) {
				Transients::delete_cached_settings();
			}

			return $this->sanitize_db_response($results, 'Failed to update database record. Please clear the plugin cache and try again.');

	  }


		/*

	  Update a new row
		Returns boolean

	  */
		public function update_column_single($data = [], $where = [], $formats = false) {

	    global $wpdb;

			/*

			TODO: Currently the below empty check is not working. Will fail silently.
			The correct where format needs to be: ['primary_key_col', 'primary_key_value']

			*/
	    if (empty($where)) {
	      $where = $this->primary_key;
	    }

			if ($formats) {

		    $column_formats = $this->get_columns();

				// Shopify sometimes sends date values that don't adhere to the MySQL standard. Here we force it.
				$data = Utils::convert_needed_values_to_datetime($data);

				$results = $wpdb->update($this->table_name, $data, $where, $column_formats);

			} else {

				$results = $wpdb->update($this->table_name, $data, $where);

			}

			return $this->sanitize_db_response($results, 'Failed to update database record. Please clear the plugin cache and try again.');

	  }


	  /*

	  Delete a row identified by the primary key

	  */
	  public function delete($row_id = 0) {

	    global $wpdb;

			if ($this->table_exists($this->table_name)) {

				// Row ID must be positive integer
				$row_id = absint($row_id);

				// If no primary key is passed, delete the entire table
				if (empty($row_id)) {

					$results = $wpdb->query("TRUNCATE TABLE $this->table_name");

				} else {
					$results = $wpdb->query( $wpdb->prepare( "DELETE FROM $this->table_name WHERE $this->primary_key = %d", $row_id ));

				}


			} else {
				$results = false;

			}

			return $this->sanitize_db_response($results, 'WP Shopify Error - Failed to delete database record. Please clear the plugin cache and try again.');

	  }


		/*

		Used for generalized queriers + error handling

		*/
		public function query($query) {

			global $wpdb;

			return $this->sanitize_db_response( $wpdb->query($query), 'WP Shopify Error - General database query failed on table: ' . $this->table_name);

		}


		/*

		Delete a table

		*/
		public function delete_table() {

			global $wpdb;

			if ($this->table_exists($this->table_name)) {

				$sql = "DROP TABLE IF EXISTS " . $this->table_name;
				$results = $wpdb->get_results($sql);

				return $this->sanitize_db_response($results, 'WP Shopify Error - Failed to delete table: ' . $this->table_name . '. Please clear the plugin cache and try again.');

			} else {
				return [];

			}

		}


		/*

		Responsible for renaming the migration table name back to the standard table name

		*/
		public function rename_migration_table() {

			global $wpdb;

			if ( !$this->table_exists( $this->table_name . WPS_TABLE_MIGRATION_SUFFIX ) ) {
				return true;
			}

			$results = $wpdb->get_results("RENAME TABLE " . $this->table_name . WPS_TABLE_MIGRATION_SUFFIX . ' TO ' . $this->table_name);

			return $this->sanitize_db_response($results, 'WP Shopify Error - Failed to rename migration table back to: ' . $this->table_name . '. Please clear the plugin cache and try again.');

		}


		/*

		Responsible for creating a migration table with the '_migrate' suffix

		*/
		public function create_migration_table() {

			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

      if (!$this->table_exists( $this->table_name . WPS_TABLE_MIGRATION_SUFFIX )) {

				global $wpdb;

        $result = dbDelta( $this->create_table_query( $this->table_name . WPS_TABLE_MIGRATION_SUFFIX ) );

				if ($this->has_mysql_error()) {
					return new \WP_Error('error', __($wpdb->last_error, WPS_PLUGIN_TEXT_DOMAIN));
				}

				return true;

      }

		}


		/*

		Delete a row(s) identified by column value

		*/
		public function delete_rows($column, $column_value) {

			global $wpdb;
			$column = esc_sql($column);

			if (gettype($column_value) === 'integer') {
				$query = "DELETE FROM $this->table_name WHERE $column = %d";

			} else if (gettype($column_value) === 'double') {
				$query = "DELETE FROM $this->table_name WHERE $column = %f";

			} else {
				$query = "DELETE FROM $this->table_name WHERE $column = %s";
			}


			$results = $wpdb->query(
				$wpdb->prepare($query, $column_value)
			);

			return $this->sanitize_db_response($results, 'WP Shopify Error - Failed to delete database rows. Please clear the plugin cache and try again.');

		}


		/*

		Delete a row(s) identified by column value

		$values comes in as an array. We must turn it into a comma
		seperated list.

		*/
		public function delete_rows_in($column, $ids) {

			global $wpdb;
			$column = esc_sql($column);

			if (gettype($ids) === 'integer') {
				$query = "DELETE FROM $this->table_name WHERE $column IN (%d)";

			} else if (gettype($ids) === 'double') {
				$query = "DELETE FROM $this->table_name WHERE $column IN (%f)";

			} else {
				$query = "DELETE FROM $this->table_name WHERE $column IN (%s)";
			}


			$result = $wpdb->get_results(
				$wpdb->prepare($query, $ids)
			);

			return $this->sanitize_db_response($result, 'WP Shopify Error - Failed to delete database rows by value. Please clear the plugin cache and try again.');

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

			if (get_transient('wp_shopify_table_exists_' . $table_name)) {
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
		public function assign_foreign_key($row, $foreignKey) {

			$rowCopy = $row;
			$rowCopy->post_id = $foreignKey;

			return $rowCopy;

		}


		/*

		Inserts a single custom term. Used by "wps_tags"

		*/
		public function insert_single_term($cpt_id, $term, $taxonomy_name) {

			$results = [];

			if (taxonomy_exists($taxonomy_name)) {

				// Sets the tag to our custom $taxonomy_name taxonomy
				$tagTaxSetResult = wp_set_object_terms($cpt_id, $term, $taxonomy_name, true);

				if ( !is_array($tagTaxSetResult) ) {

					if (is_wp_error($tagTaxSetResult)) {
						$results[] = $tagTaxSetResult->get_error_message();

					} else {
						$results[] = $tagTaxSetResult;
					}

				} else {
					$results = $tagTaxSetResult;
				}

				return $results;

			}

		}


		/*

		Wrapper function for updating post meta

		*/
		public function update_post_meta($post_id, $meta_key, $meta_value) {

			return $this->sanitize_db_response(
				update_post_meta($post_id, $meta_key, $meta_value)
			);

		}


		public function convert_array_to_in_string($array) {
			return "('" .  implode("', '", $array) . "')";
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
				return new WP_Error('WP Shopify Error - Unable to get charset for table ' . $table);

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

	}

}
