<?php

namespace WPS\DB;

use WPS\Config;

class Settings_General extends \WPS\DB {

  public $table_name;
  public $primary_key;
	public $version;
	public $webhooks;
  public $plugin_version;
  public $plugin_author;
  public $plugin_textdomain;
  public $plugin_name;
  public $num_posts;

  /*

  Construct

  */
	public function __construct() {

    $Config = new Config();

    // 
    // $okokok = $this->get_num_posts()[0]->num_posts;
    //
    // error_log('NUM POSTS CUSTOM');
    // error_log(print_r($okokok, true));
    //
    // error_log('NUM POSTS DEFAULT');
    // error_log();



    global $wpdb;
    $this->table_name         = $wpdb->prefix . 'wps_settings_general';
    $this->primary_key        = 'id';
    $this->version            = '1.0';
    $this->webhooks           = get_home_url();
    $this->plugin_version     = $Config->plugin_version;
    $this->plugin_author      = $Config->plugin_author;
    $this->plugin_textdomain  = $Config->plugin_name;
    $this->plugin_name        = $Config->plugin_name_full;
    $this->cache_group        = 'wps_db_general';
    $this->num_posts          = get_option('posts_per_page');

  }


  /*

  Get Columns

  */
	public function get_columns() {
    return array(
      'id'                        => '%d',
      'url_products'              => '%s',
      'url_collections'           => '%s',
      'url_webhooks'              => '%s',
      'num_posts'                 => '%d',
      'styles_all'                => '%d',
      'styles_core'               => '%d',
      'styles_grid'               => '%d',
      'plugin_name'               => '%s',
      'plugin_textdomain'         => '%s',
      'plugin_version'            => '%s',
      'plugin_author'             => '%s'
    );
  }


  /*

  Get Column Defaults

  */
	public function get_column_defaults() {
    return array(
      'id'                        => 1,
      'url_products'              => 'products',
      'url_collections'           => 'collections',
      'url_webhooks'              => $this->webhooks,
      'num_posts'                 => $this->num_posts,
      'styles_all'                => 1,
      'styles_core'               => 0,
      'styles_grid'               => 0,
      'plugin_name'               => $this->plugin_name,
      'plugin_textdomain'         => $this->plugin_textdomain,
      'plugin_version'            => $this->plugin_version,
      'plugin_author'             => $this->plugin_author
    );
  }


  /*

  init_general

  */
  public function init_general() {

    $results = array();

    $data = array(
      'id'                        => 1,
      'url_products'              => 'products',
      'url_collections'           => 'collections',
      'url_webhooks'              => $this->webhooks,
      'num_posts'                 => $this->num_posts,
      'styles_all'                => 1,
      'styles_core'               => 0,
      'styles_grid'               => 0,
      'plugin_name'               => $this->plugin_name,
      'plugin_textdomain'         => $this->plugin_textdomain,
      'plugin_version'            => $this->plugin_version,
      'plugin_author'             => $this->plugin_author
    );

    $row = $this->get_rows('id', 1);

    if (count($row) <= 0) {
      $results = $this->insert($data, 'general');
    }

    return $results;

  }


  /*

  Insert connection data

  */
  public function update_general($generalData) {
    return $this->update(1, $generalData);
  }


  /*

  Get num posts value

  */
  public function get_num_posts() {
    return $this->get_column_single('num_posts');
  }


  /*

  Creates database table

  */
	public function create_table() {

    global $wpdb;

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

		$collate = '';

		if ( $wpdb->has_cap('collation') ) {
			$collate = $wpdb->get_charset_collate();
		}

    $query = "CREATE TABLE `{$this->table_name}` (
      `id` bigint(100) NOT NULL DEFAULT 1,
		  `url_products` varchar(100) NOT NULL DEFAULT 'products',
		  `url_collections` varchar(100) NOT NULL DEFAULT 'collections',
      `url_webhooks` varchar(100) NOT NULL DEFAULT '{$this->webhooks}',
      `num_posts` bigint(100) DEFAULT NULL,
      `styles_all` tinyint(1) DEFAULT 1,
      `styles_core` tinyint(1) DEFAULT 0,
      `styles_grid` tinyint(1) DEFAULT 0,
      `plugin_name` varchar(100) NOT NULL DEFAULT '{$this->plugin_name}',
      `plugin_textdomain` varchar(100) NOT NULL DEFAULT '{$this->plugin_textdomain}',
      `plugin_version` varchar(100) NOT NULL DEFAULT '{$this->plugin_version}',
      `plugin_author` varchar(100) NOT NULL DEFAULT '{$this->plugin_author}',
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
