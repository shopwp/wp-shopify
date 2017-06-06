<?php

namespace WPS\DB;

use WPS\Utils;
use WPS\WS;
use WPS\DB\Collects;
use WPS\Config;
use WPS\CPT;

class Collections_Custom extends \WPS\DB {

  public $table_name;
	public $version;
	public $primary_key;

  /*

  Construct

  */
	public function __construct() {

    global $wpdb;
    $this->table_name  = $wpdb->prefix . 'wps_collections_custom';
    $this->primary_key = 'collection_id';
    $this->version     = '1.0';

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
      'metafield'           => '%s',
      'published'           => '%s',
      'published_scope'     => '%s',
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
      'metafield'           => '',
      'published'           => '',
      'published_scope'     => '',
      'sort_order'          => '',
      'published_at'        => date( 'Y-m-d H:i:s' ),
      'updated_at'          => date( 'Y-m-d H:i:s' )
    );
  }


  /*

  Inserts Multiple Collections
  Only used during initial sync so we don't need to Insert
  Collects. Might need to change in future.

  */
	public function insert_custom_collections($custom_collections) {

    $results = array();
    $custom_collections = Utils::flatten_collections_image_prop($custom_collections);


    foreach ($custom_collections as $key => $custom_collection) {

      // If product is visible on the Online Stores channel
      if (property_exists($custom_collection, 'published_at') && $custom_collection->published_at !== null) {
        $customPostTypeID = CPT::wps_insert_new_collection($custom_collection);
        $custom_collection = $this->assign_foreign_key($custom_collection, $customPostTypeID);
        $custom_collection = $this->rename_primary_key($custom_collection);

        $results[$customPostTypeID] = $this->insert($custom_collection, 'custom_collection');

      }

    }


    // error_log('---- $results Flattened Custom Collectionssss -----');
    // error_log(print_r($results, true));

    return $results;

  }


  /*

  Inserts Single Collection + associated Collects
  @param object

  */
	public function insert_custom_collection($collection) {

    $WS = new WS(new Config());
    $DB_Collects = new Collects();

    $collection = Utils::flatten_collections_image_prop($collection);
    $newCollects = $WS->wps_ws_get_collects_from_collection($collection->collection_id);

    $customPostTypeID = CPT::wps_insert_new_collection($collection);
    $collection = $this->assign_foreign_key($collection, $customPostTypeID);
    $collection = $this->rename_primary_key($collection);

    $results['custom_collects'] = $DB_Collects->insert_collects($newCollects->collects);
    $results['custom_collection'] = $this->insert($collection, 'custom_collection');

    return $results;

  }


  /*

  update_custom_collection
  @param Object

  */
  public function update_custom_collection($collection) {
    return $this->update_collection($collection);
  }


  /*

  delete_custom_collection
  @param Object

  */
  public function delete_custom_collection($collection) {
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

  Default Custom Collections Query

  */
  public function get_default_query() {

    global $wpdb;

    return array(
      'where' => '',
      'groupby' => '',
      'join' => ' INNER JOIN ' . $this->get_table_name() . ' custom ON ' . $wpdb->posts . '.ID = custom.post_id',
      'orderby' => '',
      'distinct' => '',
      'fields' => 'custom .*',
      'limits' => ''
    );

  }


  /*

  Creates database table

  */
	public function create_table() {

    global $wpdb;

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

		$collate = '';

		if ($wpdb->has_cap('collation')) {
			$collate = $wpdb->get_charset_collate();
		}

    $query = "CREATE TABLE `{$this->table_name}` (
      `collection_id` bigint(100) unsigned NOT NULL,
      `post_id` bigint(100) unsigned DEFAULT NULL,
      `title` varchar(255) DEFAULT NULL,
      `handle` varchar(255) DEFAULT NULL,
      `body_html` longtext,
      `image` longtext DEFAULT NULL,
      `metafield` longtext DEFAULT NULL,
      `published` varchar(50) DEFAULT NULL,
      `published_scope` varchar(100) DEFAULT NULL,
      `sort_order` varchar(100) DEFAULT NULL,
      `published_at` datetime,
      `updated_at` datetime,
      PRIMARY KEY (`{$this->primary_key}`)
    ) ENGINE=InnoDB DEFAULT CHARSET={$collate};";

    //
    // Create the table if it doesnt exist. Where the magic happens.
    //
    if (!$this->table_exists($this->table_name)) {
      dbDelta($query);
    }

  }


}
