<?php

namespace WPS\DB;

class Settings_General extends \WPS\DB {

  public $table_name;
	public $version;
	public $primary_key;

  /*

  Construct

  */
	public function __construct() {

    global $wpdb;
    $this->table_name  = $wpdb->prefix . 'wps_settings_general';
    $this->primary_key = 'id';
    $this->version     = '1.0';
    $this->webhooks    = get_home_url();

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
      'num_posts'                 => null,
      'plugin_name'               => 'WP Shopify',
      'plugin_textdomain'         => 'wps',
      'plugin_version'            => '0.1.10',
      'plugin_author'             => 'Andrew Robbins'
    );
  }


  /*

  init_general

  */
  public function init_general() {

    $results = array();

    $data = array(
      'id'                => 1,
      'url_products'      => 'products',
      'url_collections'   => 'collections',
      'url_webhooks'      => $this->webhooks,
      'num_posts'         => null,
      'plugin_name'       => 'WP Shopify',
      'plugin_textdomain' => 'wps',
      'plugin_version'    => '0.1.10',
      'plugin_author'     => 'Andrew Robbins'
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
      `plugin_name` varchar(100) NOT NULL DEFAULT 'WP Shopify',
      `plugin_textdomain` varchar(100) NOT NULL DEFAULT 'wps',
      `plugin_version` varchar(100) NOT NULL DEFAULT '0.1.10',
      `plugin_author` varchar(100) NOT NULL DEFAULT 'Andrew Robbins',
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
