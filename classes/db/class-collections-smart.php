<?php

namespace WPS\DB;

use WPS\Utils;
use WPS\CPT;

if (!defined('ABSPATH')) {
	exit;
}


class Collections_Smart extends \WPS\DB {

	public $table_name_suffix;
	public $table_name;
	public $version;
	public $primary_key;
	public $lookup_key;
	public $cache_group;
	public $type;

	public $default_id;
	public $default_collection_id;
	public $default_post_id;
	public $default_title;
	public $default_handle;
	public $default_post_name;
	public $default_body_html;
	public $default_image;
	public $default_rules;
	public $default_disjunctive;
	public $default_sort_order;
	public $default_published_at;
	public $default_updated_at;


	public function __construct() {

		// Table Info
		$this->table_name_suffix  					= WPS_TABLE_NAME_COLLECTIONS_SMART;
		$this->table_name         					= $this->get_table_name();
		$this->version            					= '1.0';
		$this->primary_key        					= 'id';
		$this->lookup_key        						= WPS_COLLECTIONS_LOOKUP_KEY;
		$this->cache_group        					= 'wps_db_collections_smart';
		$this->type													= 'collection';

		// Defaults
		$this->default_id       						= 0;
		$this->default_collection_id      	= 0;
		$this->default_post_id            	= 0;
		$this->default_title              	= '';
		$this->default_handle             	= '';
		$this->default_post_name            = '';
		$this->default_body_html          	= '';
		$this->default_image              	= '';
		$this->default_rules              	= '';
		$this->default_disjunctive        	= '';
		$this->default_sort_order         	= '';
		$this->default_published_at       	= date_i18n( 'Y-m-d H:i:s' );
		$this->default_updated_at         	= date_i18n( 'Y-m-d H:i:s' );

	}


	public function get_columns() {

		return [
			'id'       						=> '%d',
			'collection_id'       => '%d',
			'post_id'             => '%d',
			'title'               => '%s',
			'handle'              => '%s',
			'post_name'           => '%s',
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
			'id'       						=> $this->default_id,
			'collection_id'       => $this->default_collection_id,
			'post_id'             => $this->default_post_id,
			'title'               => $this->default_title,
			'handle'              => $this->default_handle,
			'post_name'           => $this->default_post_name,
			'body_html'           => $this->default_body_html,
			'image'               => $this->default_image,
			'rules'               => $this->default_rules,
			'disjunctive'         => $this->default_disjunctive,
			'sort_order'          => $this->default_sort_order,
			'published_at'        => $this->default_published_at,
			'updated_at'          => $this->default_updated_at
		];

	}


	/*

	Default Smart Collections Query

	*/
	public function get_default_query() {

		global $wpdb;

		return array(
			'where' => '',
			'groupby' => '',
			'join' => ' INNER JOIN ' . $this->table_name . ' smart ON ' . $wpdb->posts . '.ID = smart.post_id',
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

		if ( !$table_name ) {
			$table_name = $this->table_name;
		}

		$collate = $this->collate();

		return "CREATE TABLE $table_name (
			id bigint(100) unsigned NOT NULL AUTO_INCREMENT,
			collection_id bigint(100) unsigned NOT NULL DEFAULT '{$this->default_collection_id}',
			post_id bigint(100) unsigned DEFAULT '{$this->default_post_id}',
			title varchar(255) DEFAULT '{$this->default_title}',
			handle varchar(255) DEFAULT '{$this->default_handle}',
			post_name varchar(255) DEFAULT '{$this->default_post_name}',
			body_html longtext DEFAULT '{$this->default_body_html}',
			image longtext DEFAULT '{$this->default_image}',
			rules longtext DEFAULT '{$this->default_rules}',
			disjunctive varchar(100) DEFAULT '{$this->default_disjunctive}',
			sort_order varchar(100) DEFAULT '{$this->default_sort_order}',
			published_at datetime DEFAULT '{$this->default_published_at}',
			updated_at datetime DEFAULT '{$this->default_updated_at}',
			PRIMARY KEY  (id)
		) ENGINE=InnoDB $collate";

	}


}
