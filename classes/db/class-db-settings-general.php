<?php

namespace WPS\DB;

use WPS\Config;
use WPS\Transients;

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
  public $cache_group;

  // $this->get_column_single('num_posts');
  // ;

  /*

  Construct

  */
	public function __construct() {

    $Config = new Config();

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
      'plugin_author'             => '%s',
      'price_with_currency'       => '%d'
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
      'plugin_author'             => $this->plugin_author,
      'price_with_currency'       => 0
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
      'plugin_author'             => $this->plugin_author,
      'price_with_currency'       => 0
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

    $Transients = new Transients();
    $Transients->delete_cached_prices();

    return $this->update(1, $generalData);
  }


  /*

  Get num posts value

  */
  public function get_num_posts() {

    global $wpdb;

    $query = "SELECT num_posts FROM " . $wpdb->prefix . "wps_settings_general";
    $data = $wpdb->get_results($query);

    if (isset($data[0]->num_posts) && $data[0]->num_posts) {
      return $data[0]->num_posts;

    } else {
      return get_option('posts_per_page');

    }

    return $data;

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
      `price_with_currency` tinyint(1) DEFAULT 0,
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
