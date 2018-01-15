<?php

namespace WPS\DB;

use WPS\Utils;
use WPS\WS;
use WPS\DB\Collects;
use WPS\DB\Settings_Connection;
use WPS\Config;
use WPS\CPT;
use WPS\Progress_Bar;

class Collections_Smart extends \WPS\DB {

  public $table_name;
	public $version;
	public $primary_key;

  /*

  Construct

  */
	public function __construct() {

    global $wpdb;
    $this->table_name         = $wpdb->prefix . 'wps_collections_smart';
    $this->primary_key        = 'collection_id';
    $this->version            = '1.0';
    $this->cache_group        = 'wps_db_collections_smart';

  }


  /*

  Get Columns

  */
	public function get_columns() {
    return array(
      'collection_id'       => '%d',
      'post_id'             => '%d',
      'title'               => '%s',
      'handle'              => '%s',
      'body_html'           => '%s',
      'image'               => '%s',
      'rules'               => '%s',
      'disjunctive'         => '%s',
      'sort_order'          => '%s',
      'published_at'        => '%s',
      'updated_at'          => '%s'
    );
  }


  /*

  Get Column Defaults

  */
	public function get_column_defaults() {
    return array(
      'collection_id'       => 0,
      'post_id'             => 0,
      'title'               => '',
      'handle'              => '',
      'body_html'           => '',
      'image'               => '',
      'rules'               => '',
      'disjunctive'         => '',
      'sort_order'          => '',
      'published_at'        => date_i18n( 'Y-m-d H:i:s' ),
      'updated_at'          => date_i18n( 'Y-m-d H:i:s' )
    );
  }


  /*

  Insert an array of Smart Collections

  We currently don't need to insert Collects here because the
  only time we're calling this function is during initial sync
  which calls Collects for us. We _may_ run into issues in the future.
  TODO: Revist

  */
	public function insert_smart_collections($smart_collections) {

    // If no smart collections exist to insert, keep moving ...
    if (empty($smart_collections)) {
      return true;
    }

    $results = array();
    $progress = new Progress_Bar(new Config());
    $smart_collections = Utils::flatten_collections_image_prop($smart_collections);
    $index = CPT::wps_find_latest_menu_order('collections');
    $existingCollections = CPT::wps_get_all_cpt_by_type('wps_collections');

    foreach ($smart_collections as $key => $smart_collection) {

      if (!Utils::isStillSyncing()) {
        wp_die();
      }

      if (is_object($smart_collection)) {

        if (property_exists($smart_collection, 'published_at') && $smart_collection->published_at !== null) {

          $customPostTypeID = CPT::wps_insert_or_update_collection($smart_collection, $existingCollections, $index);

          $smart_collection = $this->assign_foreign_key($smart_collection, $customPostTypeID);
          $smart_collection = $this->rename_primary_key($smart_collection);

          $results[$customPostTypeID] = $this->insert($smart_collection, 'smart_collection');

        }

      }

      $progress->increment_current_amount('smart_collections');

      $index++;

    }

    return $results;

  }


  /*

  insert_smart_collection

  */
  public function insert_smart_collection($collection) {

    $WS = new WS(new Config());
    $DB_Collects = new Collects();
    $existingCollections = CPT::wps_get_all_cpt_by_type('wps_collections');
    $newCollectionID = Utils::wps_find_collection_id($collection);

    $collection = Utils::flatten_collections_image_prop($collection);
    $newCollects = $WS->wps_ws_get_collects_from_collection($newCollectionID);

    $customPostTypeID = CPT::wps_insert_or_update_collection($collection, $existingCollections);
    $collection = $this->assign_foreign_key($collection, $customPostTypeID);
    $collection = $this->rename_primary_key($collection);

    if (is_array($newCollects) && $newCollects) {
      $results['smart_collects'] = $DB_Collects->insert_collects($newCollects);
    }

    $results['smart_collection'] = $this->insert($collection, 'smart_collection');

    return $results;

  }


  /*

  update_custom_collection
  @param Object

  */
  public function update_smart_collection($collection) {
    return $this->update_collection($collection);
  }


  /*

  delete_smart_collection
  @param Object

  */
  public function delete_smart_collection($collection) {
    return $this->delete_collection($collection);
  }


  /*

  Rename primary key

  */
  public function rename_primary_key($collection) {

    $collectionCopy = $collection;
    $collectionCopy->collection_id = $collectionCopy->id;
    unset($collectionCopy->id);

    return $collectionCopy;

  }


  /*

	Default Smart Collections Query

	*/
	public function get_default_query() {

		global $wpdb;

		return array(
			'where' => '',
			'groupby' => '',
			'join' => ' INNER JOIN ' . $this->get_table_name() . ' smart ON ' . $wpdb->posts . '.ID = smart.post_id',
			'orderby' => '',
			'distinct' => '',
			'fields' => 'smart .*',
			'limits' => ''
		);

	}


  /*

  Creates a table query string

  */
  public function create_table_query() {

    global $wpdb;

    $collate = '';

    if ($wpdb->has_cap('collation')) {
      $collate = $wpdb->get_charset_collate();
    }

    return "CREATE TABLE `{$this->table_name}` (
      `collection_id` bigint(100) unsigned NOT NULL AUTO_INCREMENT,
      `post_id` bigint(100) unsigned DEFAULT NULL,
      `title` varchar(255) DEFAULT NULL,
      `handle` varchar(255) DEFAULT NULL,
      `body_html` longtext DEFAULT NULL,
      `image` longtext DEFAULT NULL,
      `rules` longtext DEFAULT NULL,
      `disjunctive` varchar(100) DEFAULT NULL,
      `sort_order` varchar(100) DEFAULT NULL,
      `published_at` datetime,
      `updated_at` datetime,
      PRIMARY KEY  (`{$this->primary_key}`)
    ) ENGINE=InnoDB $collate";

	}


  /*

  Creates database table

  */
	public function create_table() {

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

    if (!$this->table_exists($this->table_name)) {
      dbDelta( $this->create_table_query() );
    }

  }

}
