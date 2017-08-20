<?php

namespace WPS\DB;

class Settings_Connection extends \WPS\DB {

  public $table_name;
	public $version;
	public $primary_key;

  /*

  Construct

  */
	public function __construct() {

    global $wpdb;
    $this->table_name      = $wpdb->prefix . 'wps_settings_connection';
    $this->primary_key     = 'id';
    $this->version         = '1.0';
    $this->cache_group     = 'wps_db_connection';

  }


  /*

  Get Columns

  */
	public function get_columns() {
    return array(
      'id'                        => '%d',
      'domain'                    => '%s',
      'js_access_token'           => '%s',
      'access_token'              => '%s',
      'app_id'                    => '%s',
      'webhook_id'                => '%s',
      'nonce'                     => '%s'
    );
  }


  /*

  Get Column Defaults

  */
	public function get_column_defaults() {
    return array(
      'id'                        => 1,
      'domain'                    => '',
      'js_access_token'           => '',
      'access_token'              => '',
      'app_id'                    => '',
      'webhook_id'                => '',
      'nonce'                     => ''
    );
  }


  /*

  Insert connection data

  */
	public function insert_connection($connectionData) {

    global $wpdb;

    if (isset($connectionData['domain']) && $connectionData['domain']) {

      if ($this->get_by('domain', $connectionData['domain'])) {

        $rowID = $this->get_column_by('id', 'domain', $connectionData['domain']);
        $results = $this->update($rowID, $connectionData);

      } else {
        $results = $this->insert($connectionData, 'connection');

      }

    } else {
      $results = false;
    }

    return $results;

  }


  /*

  check_connection

  */
  public function check_connection() {

    $accessToken = $this->get_column_single('access_token');

    if (is_array($accessToken) && !empty($accessToken)) {
      return true;

    } else {
      return false;
    }

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
      `id` bigint(100) unsigned NOT NULL AUTO_INCREMENT,
      `domain` varchar(100) NOT NULL DEFAULT '',
      `js_access_token` varchar(100) NOT NULL DEFAULT '',
      `access_token` varchar(100) DEFAULT NULL,
      `app_id` int(20) unsigned DEFAULT NULL,
      `webhook_id` varchar(100) DEFAULT NULL,
      `nonce` varchar(100) DEFAULT NULL,
      PRIMARY KEY  (`{$this->primary_key}`)
    ) ENGINE=InnoDB $collate";

    //
    // Create the table if it doesnt exist. Where the magic happens.
    //
    if (!$this->table_exists($this->table_name)) {
      dbDelta($query);
    }

  }

}
