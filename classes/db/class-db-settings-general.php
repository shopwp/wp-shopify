<?php

namespace WPS\DB;

use WPS\Config;
use WPS\Transients;


// If this file is called directly, abort.
if (!defined('ABSPATH')) {
	exit;
}


/*

Database class for Settings_General

*/
if (!class_exists('Settings_General')) {

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
    public $title_as_alt;
    public $selective_sync_status;


    /*

    Construct

    */
  	public function __construct() {

      $Config = new Config();

      global $wpdb;

      $this->table_name                     = $wpdb->prefix . 'wps_settings_general';
      $this->primary_key                    = 'id';
      $this->version                        = '1.0';
      $this->webhooks                       = get_home_url();
      $this->plugin_version                 = $Config->plugin_version;
      $this->plugin_author                  = $Config->plugin_author;
      $this->plugin_textdomain              = $Config->plugin_name;
      $this->plugin_name                    = $Config->plugin_name_full;
      $this->cache_group                    = 'wps_db_general';
      $this->num_posts                      = get_option('posts_per_page');

      $this->cart_loaded                    = 1;
      $this->title_as_alt                   = 0;
      $this->price_with_currency            = 0;

      $this->styles_all                     = 1;
      $this->styles_core                    = 0;
      $this->styles_grid                    = 0;

      $this->selective_sync_all             = 1;
      $this->selective_sync_products        = 0;
      $this->selective_sync_collections     = 0;
      $this->selective_sync_customers       = 0;
      $this->selective_sync_orders          = 0;
      $this->selective_sync_shop            = 0;

    }


    /*

    Get Columns

    */
  	public function get_columns() {

      return array(
        'id'                            => '%d',
        'url_products'                  => '%s',
        'url_collections'               => '%s',
        'url_webhooks'                  => '%s',
        'num_posts'                     => '%d',
        'styles_all'                    => '%d',
        'styles_core'                   => '%d',
        'styles_grid'                   => '%d',
        'plugin_name'                   => '%s',
        'plugin_textdomain'             => '%s',
        'plugin_version'                => '%s',
        'plugin_author'                 => '%s',
        'price_with_currency'           => '%d',
        'cart_loaded'                   => '%d',
        'title_as_alt'                  => '%d',
        'selective_sync_all'            => '%d',
        'selective_sync_products'       => '%d',
        'selective_sync_collections'    => '%d',
        'selective_sync_customers'      => '%d',
        'selective_sync_orders'         => '%d',
        'selective_sync_shop'           => '%d'
      );

    }


    /*

    Get Column Defaults

    */
  	public function get_column_defaults() {

      return array(
        'id'                            => 1,
        'url_products'                  => 'products',
        'url_collections'               => 'collections',
        'url_webhooks'                  => $this->webhooks,
        'num_posts'                     => $this->num_posts,
        'styles_all'                    => $this->styles_all,
        'styles_core'                   => $this->styles_core,
        'styles_grid'                   => $this->styles_grid,
        'plugin_name'                   => $this->plugin_name,
        'plugin_textdomain'             => $this->plugin_textdomain,
        'plugin_version'                => $this->plugin_version,
        'plugin_author'                 => $this->plugin_author,
        'price_with_currency'           => $this->price_with_currency,
        'cart_loaded'                   => $this->cart_loaded,
        'title_as_alt'                  => $this->title_as_alt,
        'selective_sync_all'            => $this->selective_sync_all,
        'selective_sync_products'       => $this->selective_sync_products,
        'selective_sync_collections'    => $this->selective_sync_collections,
        'selective_sync_customers'      => $this->selective_sync_customers,
        'selective_sync_orders'         => $this->selective_sync_orders,
        'selective_sync_shop'           => $this->selective_sync_shop
      );

    }


    /*

    init_general

    */
    public function init_general() {

      $results = array();

      $data = array(
        'id'                            => 1,
        'url_products'                  => 'products',
        'url_collections'               => 'collections',
        'url_webhooks'                  => $this->webhooks,
        'num_posts'                     => $this->num_posts,
        'styles_all'                    => $this->styles_all,
        'styles_core'                   => $this->styles_core,
        'styles_grid'                   => $this->styles_grid,
        'plugin_name'                   => $this->plugin_name,
        'plugin_textdomain'             => $this->plugin_textdomain,
        'plugin_version'                => $this->plugin_version,
        'plugin_author'                 => $this->plugin_author,
        'price_with_currency'           => $this->price_with_currency,
        'cart_loaded'                   => $this->cart_loaded,
        'title_as_alt'                  => $this->title_as_alt,
        'selective_sync_all'            => $this->selective_sync_all,
        'selective_sync_products'       => $this->selective_sync_products,
        'selective_sync_collections'    => $this->selective_sync_collections,
        'selective_sync_customers'      => $this->selective_sync_customers,
        'selective_sync_orders'         => $this->selective_sync_orders,
        'selective_sync_shop'           => $this->selective_sync_shop
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

      if (get_transient('wps_settings_num_posts')) {
        $results = get_transient('wps_settings_num_posts');

      } else {

        $query = "SELECT num_posts FROM " . $wpdb->prefix . "wps_settings_general";
        $data = $wpdb->get_results($query);

        if (isset($data[0]->num_posts) && $data[0]->num_posts) {
          $results = $data[0]->num_posts;

        } else {
          $results = get_option('posts_per_page');

        }

        set_transient('wps_settings_num_posts', $results);

      }


      return $results;

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
        `id` bigint(100) NOT NULL AUTO_INCREMENT,
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
        `cart_loaded` tinyint(1) DEFAULT '{$this->cart_loaded}',
        `title_as_alt` tinyint(1) DEFAULT '{$this->title_as_alt}',
        `selective_sync_all` tinyint(1) DEFAULT '{$this->selective_sync_all}',
        `selective_sync_products` tinyint(1) DEFAULT '{$this->selective_sync_products}',
        `selective_sync_collections` tinyint(1) DEFAULT '{$this->selective_sync_collections}',
        `selective_sync_customers` tinyint(1) DEFAULT '{$this->selective_sync_customers}',
        `selective_sync_orders` tinyint(1) DEFAULT '{$this->selective_sync_orders}',
        `selective_sync_shop` tinyint(1) DEFAULT '{$this->selective_sync_shop}',
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


    /*

    Get the current products slug

    */
  	public function products_slug() {
      return $this->get_column_single('url_products');
    }


    /*

    Get the current products slug
    TODO: We should abstract out the logic here into the main db class. We shouldn't need to
    check whether the return value of get_column_single is truthy or not. Then we can simply
    use get_column_single instead of title_as_alt

    */
  	public function title_as_alt() {

      $setting = $this->get_column_single('title_as_alt');

      if (is_array($setting) && isset($setting)) {
        return $setting[0]->title_as_alt;

      } else {
        return false;
      }

    }


    /*

    selective_sync_status

    */
  	public function selective_sync_status() {

      return [
        'all'                 => (int) $this->get_column_single('selective_sync_all')[0]->selective_sync_all,
        'products'            => (int) $this->get_column_single('selective_sync_products')[0]->selective_sync_products,
        'smart_collections'   => (int) $this->get_column_single('selective_sync_collections')[0]->selective_sync_collections,
        'custom_collections'  => (int) $this->get_column_single('selective_sync_collections')[0]->selective_sync_collections,
        'customers'           => (int) $this->get_column_single('selective_sync_customers')[0]->selective_sync_customers,
        'orders'              => (int) $this->get_column_single('selective_sync_orders')[0]->selective_sync_orders,
        'shop'                => (int) $this->get_column_single('selective_sync_shop')[0]->selective_sync_shop
      ];

    }


  }

}
