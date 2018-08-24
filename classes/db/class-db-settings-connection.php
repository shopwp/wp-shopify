<?php

namespace WPS\DB;

use WPS\Utils;
use WPS\Transients;

if (!defined('ABSPATH')) {
	exit;
}

if (!class_exists('Settings_Connection')) {

  class Settings_Connection extends \WPS\DB {

    public $table_name;
		public $primary_key;
  	public $version;
		public $cache_group;

		public $id;
		public $domain;
		public $js_access_token;
		public $access_token;
		public $app_id;
		public $webhook_id;
		public $nonce;
		public $api_key;
		public $password;
		public $shared_secret;


  	public function __construct() {

      global $wpdb;

      $this->table_name      					= WPS_TABLE_NAME_SETTINGS_CONNECTION;
      $this->primary_key     					= 'id';
      $this->version         					= '1.0';
      $this->cache_group     					= 'wps_db_connection';

			$this->id 											= 1;
			$this->domain 									= '';
			$this->js_access_token 					= ''; // now storefront access token
			$this->access_token 						= ''; // soon to be deprecated
			$this->app_id 									= ''; // soon to be deprecated
			$this->webhook_id 							= '';
			$this->nonce 										= '';
			$this->api_key 									= '';
			$this->password 								= '';
			$this->shared_secret 						= '';

    }


  	public function get_columns() {

      return [
        'id'                        										=> '%d',
        'domain'                    										=> '%s',
        'js_access_token'           										=> '%s',
        'access_token'              										=> '%s',
        'app_id'                    										=> '%s',
        'webhook_id'                										=> '%s',
        'nonce'                     										=> '%s',
        'api_key'                   										=> '%s',
        'password'                  										=> '%s',
        'shared_secret'             										=> '%s'
      ];

    }


  	public function get_column_defaults() {

      return [
        'id'                        										=> $this->id,
        'domain'                    										=> $this->domain,
        'js_access_token'           										=> $this->js_access_token,
        'access_token'              										=> $this->access_token,
        'app_id'                    										=> $this->app_id,
        'webhook_id'                										=> $this->webhook_id,
        'nonce'                     										=> $this->nonce,
        'api_key'                   										=> $this->api_key,
        'password'                  										=> $this->password,
        'shared_secret'             										=> $this->shared_secret
      ];

    }


    /*

    Insert connection data

    */
  	public function insert_connection($connectionData) {

      if (isset($connectionData['domain']) && $connectionData['domain']) {

        if ($this->get_by('domain', $connectionData['domain'])) {

          $rowID = $this->get_column_by('id', 'domain', $connectionData['domain']);
          $results = $this->update($rowID, $connectionData);

        } else {
          $results = $this->insert($connectionData, 'connection');
        }

      } else {

				$results = new \WP_Error('error', __('Please make sure you\'ve entered your Shopify domain.', WPS_PLUGIN_TEXT_DOMAIN));

      }

      return $results;

    }


    /*

		Predicate function
    Checks whether an active connection to Shopify exists

    */
    public function has_connection() {

      $accessToken = $this->get_column_single('access_token');

      if ( Utils::array_not_empty($accessToken) && isset($accessToken[0]->access_token) ) {
        return true;

      } else {
        return false;
      }

    }


		/*

		Get the Shopify shared secret. Used to verify Webhooks.

		*/
		public function shared_secret() {

			$setting = $this->get_column_single('shared_secret');

			if ( Utils::array_not_empty($setting) && isset($setting[0]->shared_secret) ) {
				return $setting[0]->shared_secret;

			} else {
				return false;
			}

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
				domain varchar(100) DEFAULT NULL,
				js_access_token varchar(100) DEFAULT NULL,
				access_token varchar(100) DEFAULT NULL,
				app_id int(20) unsigned DEFAULT NULL,
				webhook_id varchar(100) DEFAULT NULL,
				nonce varchar(100) DEFAULT NULL,
				api_key varchar(100) DEFAULT NULL,
				password varchar(100) DEFAULT NULL,
				shared_secret varchar(100) DEFAULT NULL,
				PRIMARY KEY  (id)
			) ENGINE=InnoDB $collate";

		}


  }

}
