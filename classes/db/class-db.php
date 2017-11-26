<?php

namespace WPS;

use WPS\Utils;
use WPS\WS;
use WPS\DB\Products;
use WPS\DB\Variants;
use WPS\DB\Tags;
use WPS\DB\Shop;
use WPS\DB\Options;
use WPS\DB\Inventory;
use WPS\DB\Images;
use WPS\DB\Collects;
use WPS\DB\Collections_Smart;
use WPS\DB\Collections_Custom;
use WPS\DB\Settings_General;
use WPS\DB\Settings_License;
use WPS\DB\Settings_Connection;
use WPS\DB\Orders;
use WPS\DB\Customers;
use WPS\CPT;
use WPS\Transients;
use WPS\Config;
use WPS\Backend;

class DB {

	public $table_name;
	public $version;
	public $primary_key;

  /*

  Construct

  */
	public function __construct() {}


  /*

  Get Current Columns

  */
	public function get_columns_current() {

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
    return array();
  }


	/*

	Returns corrosponding table name per table

	*/
	public function get_table_name() {
		return $this->table_name;
	}


  /*

  Retrieve a row by the primary key

  */
	public function get($row_id = 0) {

    global $wpdb;
		$results = array();

		if ($this->table_exists($this->table_name)) {

			if( empty($row_id) ) {

				// if (get_transient('wps_table_single_row_' . $this->table_name)) {
				// 	$results = get_transient('wps_table_single_row_' . $this->table_name);
				//
				// } else {
				//
				// 	$query = "SELECT * FROM $this->table_name LIMIT 1;";
				// 	$results = $wpdb->get_row($query);
				//
				// 	set_transient('wps_table_single_row_' . $this->table_name, $results);
				//
				// }

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


	/*

  Retrieve a specific column's value by the primary key

  */
	public function get_column_single($column) {

		global $wpdb;

		// If not a string ...
		if (!is_string($column)) {
			return;
		}

		// If argument not apart of schema ...
		if (!array_key_exists($column, $this->get_columns()) ) {
			return;
		}

		$data = wp_cache_get($column, $this->cache_group);

		if (!$data) {

			$query = "SELECT $column FROM $this->table_name;";

			$data = $wpdb->get_results($query);

			// Cache for 1 hour
			wp_cache_add($column, $data, $this->cache_group, 3600);

		}

		return $data;

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

  Insert a new row

  */
  public function insert($data, $type = '') {

    global $wpdb;

		// Only perform an insertion if the table exists ...
		if ($this->table_exists($this->table_name)) {

			// Set default values
	    $data = wp_parse_args($data, $this->get_column_defaults());

	    do_action('wps_pre_insert_' . $type, $data);

			// Sanitizing nested arrays
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

			TODO: We should probably check whether the item we're inserting into the DB
			already exists to avoid errors. We can do this by first running $wpdb->get_results
			and then cheking the num rows like below:

			*/
			if (!$this->has_existing_record($data)) {

				$result = $wpdb->insert($this->table_name, $data, $column_formats);

		    do_action('wps_post_insert_' . $type, $result, $data);

			} else {
				$result = false;

			}

		} else {
			$result = false;

		}

    return $result;

  }


  /*

  Update a new row

  */
	public function update($row_id, $data = array(), $where = '') {

    global $wpdb;

    // Row ID must be positive integer
    $row_id = absint($row_id);

    if (empty($row_id)) {
      return false;
    }

    if (empty($where)) {
      $where = $this->primary_key;
    }

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

	  if ($results === false) {
	    return false;

	  } else {
	    return true;

	  }

  }





	/*

  Update a new row

  */
	public function update_column_single($data = '', $where, $formats = false) {

    global $wpdb;

    if (empty($where)) {
      $where = $this->primary_key;
    }

		if ($formats) {

	    $column_formats = $this->get_columns();

			if ($wpdb->update( $this->table_name, $data, $where, $column_formats ) === false) {
	      return false;
	    }

		} else {

			if ($wpdb->update( $this->table_name, $data, $where ) === false) {
				return false;
			}

		}

    return true;

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

			// Need to strictly check for FALSE since query can return 0 for no change
			if ($results === false) {
				return false;

			} else {
				return true;

			}

		} else {
			$results = false;

		}

  }



	/*

	Delete a table

	*/
	public function delete_table() {

		global $wpdb;

		if ($this->table_exists($this->table_name)) {
			$sql = "DROP TABLE IF EXISTS " . $this->table_name;
			$results = $wpdb->get_results($sql);

		} else {
			$results = array();

		}

		return $results;

	}


	/*

	Find the difference between tables in the database
	and tables in the database schemea. Used during plugin updates
	to dynamically update the database.

	*/
	public function get_table_delta() {

		$tables = array();
		$finalDelta = array();

		$tables[] = new Products();
		$tables[] = new Variants();
		$tables[] = new Tags();
		$tables[] = new Shop();
		$tables[] = new Options();
		$tables[] = new Images();
		$tables[] = new Collects();
		$tables[] = new Collections_Smart();
		$tables[] = new Collections_Custom();
		$tables[] = new Settings_License();
		$tables[] = new Settings_Connection();
		$tables[] = new Settings_General();
		$tables[] = new Orders();
		$tables[] = new Customers();


		foreach($tables as $key => $table) {

			if ( $table->table_exists($table->get_table_name()) ) {

				$columnsNew = $table->get_columns();
				$columnsCurrent = $table->get_columns_current();
				$tableName = $table->get_table_name();

				$delta = array_diff_key($columnsNew, array_flip($columnsCurrent));

				if (!empty($delta)) {
					$finalDelta[$tableName] = $table;
				}

			} else {

				// Create table since it doesn't exist
				$result = $table->create_table();

			}


		}

		return array_filter($finalDelta);

	}


	/*

	Delete a row(s) identified by column value

	*/
	public function delete_rows($column, $column_value) {

		global $wpdb;

		$column = esc_sql($column);
		$query = "DELETE FROM $this->table_name WHERE $column = %s";

		return $wpdb->get_results(
			$wpdb->prepare($query, $column_value)
		);

	}


	/*

	Delete a row(s) identified by column value

	$values comes in as an array. We must turn it into a comma
	seperated list.

	*/
	public function delete_rows_in($column, $ids) {

		global $wpdb;

		$column = esc_sql($column);
		$query = "DELETE FROM $this->table_name WHERE $column IN (%s)";

		return $wpdb->get_results(
			$wpdb->prepare($query, $ids)
		);

	}











  /*

  Check if the given table exists

  */
	public function table_exists($table) {

    global $wpdb;

		if (get_transient('wps_table_exists_' . $table)) {
			return get_transient('wps_table_exists_' . $table);

		} else {

			$table = sanitize_text_field($table);
			$tableResponse = $wpdb->get_var($wpdb->prepare("SHOW TABLES LIKE '%s'", $table)) === $table;

			set_transient('wps_table_exists_' . $table, $tableResponse);

			return $tableResponse;

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

	Used to check the type of collection



	Need to update Collects AND Collections




	*/
	public function update_collection($collection) {

		$WS = new WS(new Config());
		$CPT = new CPT(new Config());
		$DB_Collects = new Collects();
		$DB_Collections_Custom = new Collections_Custom();
		$DB_Collections_Smart = new Collections_Smart();
		$existingCollections = CPT::wps_get_all_cpt_by_type('wps_collections');
		$newCollectionID = Utils::wps_find_collection_id($collection);

		// Collects from Plugin
		$pluginCollects = $DB_Collects->get_rows('collection_id', $newCollectionID);

		$results = array();

		/*

    If published_at is null, we know the user turned off the Online Store sales channel.
    TODO: Shopify may implement better sales channel checking in the future API. We should
    then check for Buy Button visibility as-well.

    */
		if (property_exists($collection, 'published_at') && $collection->published_at !== null) {

			$collection = Utils::flatten_collections_image_prop($collection);

			// Collects from Shopify
			$shopifyCollects = $WS->wps_ws_get_collects_from_collection($newCollectionID);

			if (is_array($shopifyCollects) && $shopifyCollects) {

				$collectsToAdd = Utils::wps_find_items_to_add($pluginCollects, $shopifyCollects, true);
				$collectsToDelete = Utils::wps_find_items_to_delete($pluginCollects, $shopifyCollects, true);

				if (count($collectsToAdd) > 0) {
					foreach ($collectsToAdd as $key => $newCollect) {
						$results['collects_created'][] = $DB_Collects->insert($newCollect, 'collect');
					}
				}

				if (count($collectsToDelete) > 0) {
					foreach ($collectsToDelete as $key => $oldCollect) {
						$results['collects_deleted'][] = $DB_Collects->delete($oldCollect->id);
					}
				}

			}


			if (!isset($collection->image)) {
				$results['collection_image'] = $this->update_column_single(
					array('image' => null),
					array('collection_id' => $newCollectionID)
				);
			}


			/*

			If collection doesn't currently exist, insert it otherwise update it.
			We also update / insert Collects within insert_smart_collection or
			within insert_custom_collection.

			*/

			$collectionID = $this->get($newCollectionID);

			if (empty($collectionID)) {

				/*

				Inserting collections. Takes care of adding content into custom post type.

				*/
				if (isset($collection->rules)) {
					$results['collection'] = $DB_Collections_Smart->insert_smart_collection($collection);

				} else {
					$results['collection'] = $DB_Collections_Custom->insert_custom_collection($collection);
				}

			} else {

				$results['collection_cpt'] = $CPT->wps_insert_or_update_collection($collection, $existingCollections);
				$results['collection'] = $this->update($newCollectionID, $collection);

			}


		} else {

			/*

			Need to also delete any corresponding Collects

			*/
			$results['deleted_collects'] = $DB_Collects->delete_collects_by_ids($pluginCollects);
			$results['deleted_collection'] = $this->delete_collection($collection, $newCollectionID);

		}

		Transients::delete_cached_collection_queries();

		return $results;

	}


  /*

  Fired when product is deleted at Shopify

  */
  public function delete_collection($collection) {

		$collectionData = $this->get($collection->id);

		if (!empty($collectionData)) {

			$DB_Collects = new Collects();
			$Backend = new Backend(new Config());
			$postIds = array($collectionData->post_id);

			$results['collects']  	= $DB_Collects->delete_rows('collection_id', $collection->id);
	    $results['collection']  = $this->delete_rows('collection_id', $collection->id);

			if (!empty($postIds)) {
				$results['cpt'] = $Backend->wps_delete_posts('wps_collections', $postIds);
			}

			Transients::delete_cached_collection_queries();

		} else {
			$results = array();
		}

    return $results;

  }



  /*

  Get Collection

  */
	public function get_collection($postID = null) {

    global $wpdb;
		global $post;

		$DB_Collections_Custom = new Collections_Custom();
		$DB_Collections_Smart = new Collections_Smart();

		$collections_custom_table = $DB_Collections_Custom->get_table_name();
		$collections_smart_table = $DB_Collections_Smart->get_table_name();

    if ($postID === null && is_object($post)) {
      $postID = $post->ID;
    }

    $query = "SELECT
		smart.collection_id,
		smart.post_id,
		smart.title,
		smart.handle,
		smart.body_html,
		smart.image,
		smart.sort_order,
		smart.published_at,
		smart.updated_at,
		smart.rules
		FROM $collections_smart_table smart WHERE smart.post_id = $postID

		UNION

		SELECT
		custom.collection_id,
		custom.post_id,
		custom.title,
		custom.handle,
		custom.body_html,
		custom.image,
		custom.sort_order,
		custom.published_at,
		custom.updated_at,
		NULL as rules
		FROM $collections_custom_table custom WHERE custom.post_id = $postID;";

    return $wpdb->get_results($query);


  }


	/*

	Get Collection

	*/
	public function get_collections() {

		global $wpdb;

		$DB_Collections_Custom = new Collections_Custom();
		$DB_Collections_Smart = new Collections_Smart();

		$collections_custom_table = $DB_Collections_Custom->get_table_name();
		$collections_smart_table = $DB_Collections_Smart->get_table_name();

		$query = "SELECT
		smart.collection_id,
		smart.post_id,
		smart.title,
		smart.handle,
		smart.body_html,
		smart.image,
		smart.sort_order,
		smart.published_at,
		smart.updated_at,
		smart.rules
		FROM $collections_smart_table smart

		UNION

		SELECT
		custom.collection_id,
		custom.post_id,
		custom.title,
		custom.handle,
		custom.body_html,
		custom.image,
		custom.sort_order,
		custom.published_at,
		custom.updated_at,
		NULL as rules
		FROM $collections_custom_table custom";

		return $wpdb->get_results($query);

	}


	/*

	Default Collections Query

	*/
	public function get_default_collections_query($clauses = '') {

		global $wpdb;

		$DB_Collections_Custom = new Collections_Custom();
		$DB_Collections_Smart = new Collections_Smart();

		$collections_custom_table = $DB_Collections_Custom->get_table_name();
		$collections_smart_table = $DB_Collections_Smart->get_table_name();

		return array(
			'where' => '',
			'groupby' => '',
			'join' => ' INNER JOIN (

			SELECT
			smart.collection_id,
			smart.post_id,
			smart.title,
			smart.handle,
			smart.body_html,
			smart.image,
			smart.sort_order,
			smart.published_at,
			smart.updated_at
			FROM ' . $wpdb->prefix . 'wps_collections_smart smart

			UNION ALL

			SELECT
			custom.collection_id,
			custom.post_id,
			custom.title,
			custom.handle,
			custom.body_html,
			custom.image,
			custom.sort_order,
			custom.published_at,
			custom.updated_at
			FROM ' . $wpdb->prefix . 'wps_collections_custom custom

		) as collections ON ' . $wpdb->prefix . 'posts.ID = collections.post_id',
			'orderby' => $wpdb->posts . '.menu_order',
			'distinct' => '',
			'fields' => 'collections.*',
			'limits' => ''
		);

	}

}
