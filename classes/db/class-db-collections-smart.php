<?php

namespace WPS\DB;

use WPS\Utils;
use WPS\CPT;

if (!defined('ABSPATH')) {
	exit;
}


if (!class_exists('Collections_Smart')) {

  class Collections_Smart extends \WPS\DB {

    public $table_name;
  	public $version;
  	public $primary_key;


  	public function __construct() {

      global $wpdb;

      $this->table_name         			= WPS_TABLE_NAME_COLLECTIONS_SMART;
      $this->primary_key        			= 'collection_id';
      $this->version            			= '1.0';
      $this->cache_group        			= 'wps_db_collections_smart';

    }


  	public function get_columns() {

      return [
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
      ];

    }


  	public function get_column_defaults() {

			return [
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
      ];

    }


		public function collection_was_deleted($collection) {

			if (Utils::has($collection, 'published_at') && $collection->published_at !== null) {
				return false;

			} else {
				return true;
			}

		}


    /*

    Only responsible for inserting a smart collection into the wps_collections_smart table

    */
    public function insert_smart_collection($smart_collection) {

			$smart_collection = Utils::flatten_collections_image_prop($smart_collection);
			$smart_collection = $this->rename_primary_key($smart_collection);

			return $this->insert($smart_collection, 'smart_collection');

    }


    /*

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
      return $this->delete($collection->id);
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
  			'join' => ' INNER JOIN ' . WPS_TABLE_NAME_COLLECTIONS_SMART . ' smart ON ' . $wpdb->posts . '.ID = smart.post_id',
  			'orderby' => '',
  			'distinct' => '',
  			'fields' => 'smart .*',
  			'limits' => ''
  		);

  	}


    /*

    Creates a table query string

    */
    public function create_table_query($table_name = false) {

      global $wpdb;

			if (!$table_name) {
				$table_name = $this->table_name;
			}

      $collate = '';

      if ($wpdb->has_cap('collation')) {
        $collate = $wpdb->get_charset_collate();
      }

      return "CREATE TABLE $table_name (
        collection_id bigint(100) unsigned NOT NULL DEFAULT 0,
        post_id bigint(100) unsigned DEFAULT NULL,
        title varchar(255) DEFAULT NULL,
        handle varchar(255) DEFAULT NULL,
        body_html longtext DEFAULT NULL,
        image longtext DEFAULT NULL,
        rules longtext DEFAULT NULL,
        disjunctive varchar(100) DEFAULT NULL,
        sort_order varchar(100) DEFAULT NULL,
        published_at datetime,
        updated_at datetime,
        PRIMARY KEY  (collection_id)
      ) ENGINE=InnoDB $collate";

  	}


		/*

		Migrate insert into query

		*/
		public function migration_insert_into_query() {

			return $this->query('INSERT INTO ' . $this->table_name . WPS_TABLE_MIGRATION_SUFFIX . '(`collection_id`, `post_id`, `title`, `handle`, `body_html`, `image`, `rules`, `disjunctive`, `sort_order`, `published_at`, `updated_at`) SELECT `collection_id`, `post_id`, `title`, `handle`, `body_html`, `image`, `rules`, `disjunctive`, `sort_order`, `published_at`, `updated_at` FROM ' . $this->table_name);

		}


    /*

    Creates database table

    */
  	public function create_table() {

      require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

      if (!$this->table_exists($this->table_name)) {
        dbDelta( $this->create_table_query($this->table_name) );
				set_transient('wp_shopify_table_exists_' . $this->table_name, 1);
      }

    }

  }

}
