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
    $this->table_name         = $wpdb->prefix . 'wps_collections_custom';
    $this->primary_key        = 'collection_id';
    $this->version            = '1.0';
    $this->cache_group        = 'wps_db_collections_custom';

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
    $index = CPT::wps_find_latest_menu_order('collections');
    $existingCollections = CPT::wps_get_all_cpt_by_type('wps_collections');

    foreach ($custom_collections as $key => $custom_collection) {

      // If product is visible on the Online Stores channel
      if (property_exists($custom_collection, 'published_at') && $custom_collection->published_at !== null) {

        $customPostTypeID = CPT::wps_insert_or_update_collection($custom_collection, $existingCollections, $index);
        $custom_collection = $this->assign_foreign_key($custom_collection, $customPostTypeID);
        $custom_collection = $this->rename_primary_key($custom_collection);

        $results[$customPostTypeID] = $this->insert($custom_collection, 'custom_collection');

      }

      $index++;

    }


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
    $existingCollections = CPT::wps_get_all_cpt_by_type('wps_collections');
    $newCollectionID = Utils::wps_find_collection_id($collection);

    $shopifyCollects = $WS->wps_ws_get_collects_from_collection($newCollectionID);

    $customPostTypeID = CPT::wps_insert_or_update_collection($collection, $existingCollections);

    $collection = $this->assign_foreign_key($collection, $customPostTypeID);
    $collection = $this->rename_primary_key($collection);

    if (is_array($shopifyCollects) && $shopifyCollects) {
      $results['custom_collects'] = $DB_Collects->insert_collects($shopifyCollects);
    }

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
      `body_html` longtext,
      `image` longtext DEFAULT NULL,
      `metafield` longtext DEFAULT NULL,
      `published` varchar(50) DEFAULT NULL,
      `published_scope` varchar(100) DEFAULT NULL,
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
