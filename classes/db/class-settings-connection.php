<?php

namespace WPS\DB;

use WPS\Utils;
use WPS\Transients;

if (!defined('ABSPATH')) {
	exit;
}


class Settings_Connection extends \WPS\DB {

	public $table_name_suffix;
	public $table_name;
	public $version;
	public $primary_key;
	public $lookup_key;
	public $cache_group;
	public $type;

	public $default_id;
	public $default_domain;
	public $default_js_access_token;
	public $default_access_token;
	public $default_app_id;
	public $default_webhook_id;
	public $default_nonce;
	public $default_api_key;
	public $default_password;
	public $default_shared_secret;


	public function __construct() {

		$this->table_name_suffix  							= WPS_TABLE_NAME_SETTINGS_CONNECTION;
		$this->table_name         							= $this->get_table_name();
		$this->version         									= '1.0';
		$this->primary_key     									= 'id';
		$this->lookup_key     									= 'id';
		$this->cache_group     									= 'wps_db_connection';
		$this->type     												= 'settings_connection';

		$this->default_id 											= 0;
		$this->default_domain 									= '';
		$this->default_js_access_token 					= ''; // now storefront access token
		$this->default_access_token 						= ''; // soon to be deprecated
		$this->default_app_id 									= 0; 	// soon to be deprecated
		$this->default_webhook_id 							= '';
		$this->default_nonce 										= '';
		$this->default_api_key 									= '';
		$this->default_password 								= '';
		$this->default_shared_secret 						= '';

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
			'id'                        										=> $this->default_id,
			'domain'                    										=> $this->default_domain,
			'js_access_token'           										=> $this->default_js_access_token,
			'access_token'              										=> $this->default_access_token,
			'app_id'                    										=> $this->default_app_id,
			'webhook_id'                										=> $this->default_webhook_id,
			'nonce'                     										=> $this->default_nonce,
			'api_key'                   										=> $this->default_api_key,
			'password'                  										=> $this->default_password,
			'shared_secret'             										=> $this->default_shared_secret
		];

	}


	/*

	Insert connection data

	*/
	public function insert_connection($connectionData) {

		if (isset($connectionData['domain']) && $connectionData['domain']) {

			if ($this->get_row_by('domain', $connectionData['domain'])) {

				$row_id = $this->get_column_by('id', 'domain', $connectionData['domain']);
				$results = $this->update($this->lookup_key, $row_id, $connectionData);

			} else {
				$results = $this->insert($connectionData);
			}

		} else {
			$results = Utils::wp_error( __('Please make sure you\'ve entered your Shopify domain.', WPS_PLUGIN_TEXT_DOMAIN) );

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

	Responsible for building the Basic Auth header value used during requests

	*/
	public function build_auth_token($api_key, $api_password) {
		return base64_encode($api_key . ':' . $api_password);
	}


	/*

	Builds an auth token used for Shopify API requests

	wp_shopify_auth_token Transient cache is cleared after each sync

	*/
	public function get_auth_token() {

		$auth_token = Transients::get('wp_shopify_auth_token');

		if ( !empty($auth_token) ) {
			return $auth_token;
		}


		$connection = $this->get();

		if ( Utils::has($connection, 'api_key') && Utils::has($connection, 'password') ) {

			$built_auth_token = $this->build_auth_token($connection->api_key, $connection->password);

			Transients::set('wp_shopify_auth_token', $built_auth_token);

			return $built_auth_token;

		}

		return false;

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
			domain varchar(100) DEFAULT '{$this->default_domain}',
			js_access_token varchar(100) DEFAULT '{$this->default_js_access_token}',
			access_token varchar(100) DEFAULT '{$this->default_access_token}',
			app_id int(20) unsigned DEFAULT '{$this->default_app_id}',
			webhook_id varchar(100) DEFAULT '{$this->default_webhook_id}',
			nonce varchar(100) DEFAULT '{$this->default_nonce}',
			api_key varchar(100) DEFAULT '{$this->default_api_key}',
			password varchar(100) DEFAULT '{$this->default_password}',
			shared_secret varchar(100) DEFAULT '{$this->default_shared_secret}',
			PRIMARY KEY  (id)
		) ENGINE=InnoDB $collate";

	}


}
