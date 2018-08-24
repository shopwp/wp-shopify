<?php

namespace WPS\DB;

use WPS\Utils;


if (!defined('ABSPATH')) {
	exit;
}


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
		public $title_as_alt;
    public $num_posts;
    public $cache_group;
    public $selective_sync_status;
		public $products_link_to_shopify;
		public $show_breadcrumbs;
		public $hide_pagination;
		public $is_pro;
		public $is_free;

		public $related_products_show;
		public $related_products_sort;
		public $related_products_amount;
		public $allow_insecure_webhooks;

		public $sync_by_collections;
		public $save_connection_only;
		public $app_uninstalled;


  	public function __construct() {

      global $wpdb;

      $this->table_name                     						= WPS_TABLE_NAME_SETTINGS_GENERAL;
      $this->primary_key                    						= 'id';
      $this->version                        						= '1.0';
      $this->webhooks                       						= Utils::convert_to_https_url( get_home_url() );
      $this->plugin_version                 						= WPS_NEW_PLUGIN_VERSION;
      $this->plugin_author                  						= WPS_NEW_PLUGIN_AUTHOR;
      $this->plugin_textdomain              						= WPS_PLUGIN_NAME;
      $this->plugin_name                    						= WPS_PLUGIN_NAME_FULL;
      $this->cache_group                    						= 'wps_db_general';
      $this->num_posts                      						= get_option('posts_per_page');

			$this->title_as_alt                    						= 0;
      $this->cart_loaded                    						= 1;
      $this->price_with_currency            						= 0;

      $this->styles_all                     						= 1;
      $this->styles_core                    						= 0;
      $this->styles_grid                    						= 0;

      $this->selective_sync_all             						= 1;
      $this->selective_sync_products        						= 0;
			$this->sync_by_collections 												= '';
      $this->selective_sync_collections     						= 0;
      $this->selective_sync_customers       						= 0;
      $this->selective_sync_orders          						= 0;
      $this->selective_sync_shop            						= 1;

			$this->products_link_to_shopify       						= 0;
			$this->show_breadcrumbs       										= 0;
			$this->hide_pagination       											= 0;

			$this->is_free        														= 0;
			$this->is_pro        															= 0;

			$this->related_products_show        							= 1;
			$this->related_products_sort        							= 'random';
			$this->related_products_amount        						= 4;

			$this->allow_insecure_webhooks        						= 0;
			$this->save_connection_only        								= 0;
			$this->app_uninstalled        										= 0;

    }


  	public function get_columns() {

      return [
        'id'                            						=> '%d',
        'url_products'                  						=> '%s',
        'url_collections'               						=> '%s',
        'url_webhooks'                  						=> '%s',
        'num_posts'                     						=> '%d',
        'styles_all'                    						=> '%d',
        'styles_core'                   						=> '%d',
        'styles_grid'                   						=> '%d',
        'plugin_name'                   						=> '%s',
        'plugin_textdomain'             						=> '%s',
        'plugin_version'                						=> '%s',
        'plugin_author'                 						=> '%s',
        'price_with_currency'           						=> '%d',
        'cart_loaded'                   						=> '%d',
        'selective_sync_all'            						=> '%d',
        'selective_sync_products'       						=> '%d',
				'sync_by_collections'												=> '%s',
        'selective_sync_collections'    						=> '%d',
        'selective_sync_customers'      						=> '%d',
        'selective_sync_orders'         						=> '%d',
        'selective_sync_shop'           						=> '%d',
				'products_link_to_shopify'      						=> '%d',
				'show_breadcrumbs'      										=> '%d',
				'hide_pagination'      											=> '%d',
				'is_free'              											=> '%d',
				'is_pro'              											=> '%d',
				'related_products_show'											=> '%d',
				'related_products_sort'											=> '%s',
				'related_products_amount'       						=> '%d',
				'allow_insecure_webhooks'       						=> '%d',
				'save_connection_only'       								=> '%d',
				'title_as_alt'       												=> '%d',
				'app_uninstalled'       										=> '%d'
      ];

    }


  	public function get_column_defaults() {

      return [
        'id'                            						=> 1,
        'url_products'                  						=> 'products',
        'url_collections'               						=> 'collections',
        'url_webhooks'                  						=> $this->webhooks,
        'num_posts'                     						=> $this->num_posts,
        'styles_all'                    						=> $this->styles_all,
        'styles_core'                   						=> $this->styles_core,
        'styles_grid'                   						=> $this->styles_grid,
        'plugin_name'                   						=> $this->plugin_name,
        'plugin_textdomain'             						=> $this->plugin_textdomain,
        'plugin_version'                						=> $this->plugin_version,
        'plugin_author'                 						=> $this->plugin_author,
        'price_with_currency'           						=> $this->price_with_currency,
        'cart_loaded'                   						=> $this->cart_loaded,
        'selective_sync_all'            						=> $this->selective_sync_all,
        'selective_sync_products'       						=> $this->selective_sync_products,
				'sync_by_collections'												=> $this->sync_by_collections,
        'selective_sync_collections'    						=> $this->selective_sync_collections,
        'selective_sync_customers'      						=> $this->selective_sync_customers,
        'selective_sync_orders'         						=> $this->selective_sync_orders,
        'selective_sync_shop'           						=> $this->selective_sync_shop,
				'products_link_to_shopify'      						=> $this->products_link_to_shopify,
				'show_breadcrumbs'      										=> $this->show_breadcrumbs,
				'hide_pagination'      											=> $this->hide_pagination,
				'is_free'      															=> $this->is_free,
				'is_pro'      															=> $this->is_pro,
				'related_products_show'											=> $this->related_products_show,
				'related_products_sort'											=> $this->related_products_sort,
				'related_products_amount'       						=> $this->related_products_amount,
				'allow_insecure_webhooks'       						=> $this->allow_insecure_webhooks,
				'save_connection_only'       								=> $this->save_connection_only,
				'title_as_alt'       												=> $this->title_as_alt,
				'app_uninstalled'       										=> $this->app_uninstalled
      ];

    }


    /*

    Runs on plugin activation, sets default row

    */
    public function init() {

      $results = [];

			if ( !$this->table_has_been_initialized('id') ) {
				$results = $this->insert_default_values();
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

      global $wpdb;

      if (get_transient('wps_settings_num_posts')) {
        $results = get_transient('wps_settings_num_posts');

      } else {

        $query = "SELECT num_posts FROM " . WPS_TABLE_NAME_SETTINGS_GENERAL;
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

    Get the current products slug

    */
  	public function products_slug() {

			$url_products = $this->get_column_single('url_products');

			if ( Utils::array_not_empty($url_products) && isset($url_products[0]->url_products) ) {
				return $url_products[0]->url_products;

			} else {
				return false;
			}

    }


		/*

    Get the current collections slug

    */
  	public function collections_slug() {

			$url_collections = $this->get_column_single('url_collections');

			if ( Utils::array_not_empty($url_collections) && isset($url_collections[0]->url_collections) ) {
				return $url_collections[0]->url_collections;

			} else {
				return false;
			}


    }


		/*

		Get the value for whether products link to Shopify or not

		*/
		public function products_link_to_shopify() {

			$products_link_to_shopify = $this->get_column_single('products_link_to_shopify');

			if ( Utils::array_not_empty($products_link_to_shopify) && isset($products_link_to_shopify[0]->products_link_to_shopify) ) {
				return $products_link_to_shopify[0]->products_link_to_shopify;

			} else {
				return false;
			}

		}


		/*

		Get the value for whether products link to Shopify or not

		*/
		public function hide_pagination() {

			$hide_pagination = $this->get_column_single('hide_pagination');

			if ( Utils::array_not_empty($hide_pagination) && isset($hide_pagination[0]->hide_pagination) ) {
				return $hide_pagination[0]->hide_pagination;

			} else {
				return false;
			}

		}


		/*

		Breadcrumbs state

		*/
		public function show_breadcrumbs() {

			$show_breadcrumbs = $this->get_column_single('show_breadcrumbs');

			if ( Utils::array_not_empty($show_breadcrumbs) && isset($show_breadcrumbs[0]->show_breadcrumbs) ) {
				return $show_breadcrumbs[0]->show_breadcrumbs;

			} else {
				return false;
			}

		}


		/*

		Gets free tier status

		*/
		public function is_free_tier() {

			$is_free = $this->get_column_single('is_free');

			if ( Utils::array_not_empty($is_free) && isset($is_free[0]->is_free) ) {
				return $is_free[0]->is_free;

			} else {
				return false;
			}

		}


		/*

		Gets pro tier status

		*/
		public function is_pro_tier() {

			$is_pro = $this->get_column_single('is_pro');

			if ( Utils::array_not_empty($is_pro) && isset($is_pro[0]->is_pro) ) {
				return $is_pro[0]->is_pro;

			} else {
				return false;
			}

		}


		/*

		Get the value for whether products link to Shopify or not
		IMPORTANT: Must pass second param: ['id' => 1]

		Defaults to true

		*/
		public function set_free_tier($value = 1) {
			return $this->update_column_single(['is_free' => $value], ['id' => 1]);
		}


		/*

		Get the value for whether products link to Shopify or not

		*/
		public function set_pro_tier($value = 1) {
			return $this->update_column_single(['is_pro' => $value], ['id' => 1]);
		}


		/*

		Get the value for whether products link to Shopify or not

		*/
		public function related_products_amount() {

			$related_products_amount = $this->get_column_single('related_products_amount');

			if ( Utils::array_not_empty($related_products_amount) && isset($related_products_amount[0]->related_products_amount) ) {
				return $related_products_amount[0]->related_products_amount;

			} else {
				return false;
			}

		}


		/*

		Get the value for whether products link to Shopify or not

		*/
		public function related_products_show() {
			return $this->get_column_single('related_products_show')[0]->related_products_show;

			$related_products_amount = $this->get_column_single('related_products_amount');

			if ( Utils::array_not_empty($related_products_amount) && isset($related_products_amount[0]->related_products_amount) ) {
				return $related_products_amount[0]->related_products_amount;

			} else {
				return false;
			}

		}


		/*

		Get the value for whether products link to Shopify or not

		*/
		public function related_products_sort() {

			$related_products_sort = $this->get_column_single('related_products_sort');

			if ( Utils::array_not_empty($related_products_sort) && isset($related_products_sort[0]->related_products_sort) ) {
				return $related_products_sort[0]->related_products_sort;

			} else {
				return false;
			}

		}


		/*

		Allows for bypassing of the webhooks security check

		*/
		public function allow_insecure_webhooks() {

			$allow_insecure_webhooks = $this->get_column_single('allow_insecure_webhooks');

			if ( Utils::array_not_empty($allow_insecure_webhooks) && isset($allow_insecure_webhooks[0]->allow_insecure_webhooks) ) {
				return $allow_insecure_webhooks[0]->allow_insecure_webhooks;

			} else {
				return false;
			}

		}


		/*

		Allows for syncing products by collection

		*/
		public function sync_by_collections() {

			$sync_by_collections = $this->get_column_single('sync_by_collections');

			if ( Utils::array_not_empty($sync_by_collections) && isset($sync_by_collections[0]->sync_by_collections) ) {
				return $sync_by_collections[0]->sync_by_collections;

			} else {
				return false;
			}

		}


		/*

		Checks whether to save connection only. Useful for using specific settings / tools without syncing data.

		*/
		public function save_connection_only() {

			$save_connection_only = $this->get_column_single('save_connection_only');

			if ( Utils::array_not_empty($save_connection_only) && isset($save_connection_only[0]->save_connection_only) ) {
				return $save_connection_only[0]->save_connection_only;

			} else {
				return false;
			}

		}


		/*

		Get the value for whether products link to Shopify or not

		*/
		public function settings_id() {

			$id = $this->get_column_single('id');

			if ( Utils::array_not_empty($id) && isset($id[0]->id) ) {
				return $id[0]->id;

			} else {
				return false;
			}

		}


		/*

		App Uninstalled

		*/
		public function app_uninstalled() {

			$app_uninstalled = $this->get_column_single('app_uninstalled');

			if ( Utils::array_not_empty($app_uninstalled) && isset($app_uninstalled[0]->app_uninstalled) ) {

				if ($app_uninstalled[0]->app_uninstalled == '1') {
					return true;

				} else {
					return false;
				}

			} else {
				return false;
			}

		}


		/*

		Get app_uninstalled status

		*/
		public function set_app_uninstalled($value = 1) {
			return $this->update_column_single(['app_uninstalled' => $value], ['id' => 1]);
		}


		/*

		Gets the current plugin version number

		*/
		public function get_current_plugin_version() {

			$plugin_version = $this->get_column_single('plugin_version');

			if ( Utils::array_not_empty($plugin_version) && isset($plugin_version[0]->plugin_version) ) {
				return $plugin_version[0]->plugin_version;

			} else {
				return $plugin_version;
			}

		}


    /*

    selective_sync_status

    */
  	public function selective_sync_status() {

      return [
        'all'                 => $this->get_selective_sync_all_status(),
        'products'            => $this->get_selective_sync_products_status(),
        'smart_collections'   => $this->get_selective_sync_collections_status(),
        'custom_collections'  => $this->get_selective_sync_collections_status(),
        'customers'           => $this->get_selective_sync_customers_status(),
        'orders'              => $this->get_selective_sync_orders_status(),
        'shop'                => $this->get_selective_sync_shop_status()
      ];

    }


		/*

		Gets the status of selective_sync_all

		*/
		public function get_selective_sync_all_status() {

			$selective_sync_all = $this->get_column_single('selective_sync_all');

			if ( Utils::array_not_empty($selective_sync_all) && isset($selective_sync_all[0]->selective_sync_all) ) {
				return (int) $selective_sync_all[0]->selective_sync_all;

			} else {
				return 0;
			}

		}


		/*

		Gets the status of selective_sync_products

		*/
		public function get_selective_sync_products_status() {

			$selective_sync_products = $this->get_column_single('selective_sync_products');

			if ( Utils::array_not_empty($selective_sync_products) && isset($selective_sync_products[0]->selective_sync_products) ) {
				return (int) $selective_sync_products[0]->selective_sync_products;

			} else {
				return 0;
			}

		}


		/*

		Gets the status of selective_sync_collections

		*/
		public function get_selective_sync_collections_status() {

			$selective_sync_collections = $this->get_column_single('selective_sync_collections');

			if ( Utils::array_not_empty($selective_sync_collections) && isset($selective_sync_collections[0]->selective_sync_collections) ) {
				return (int) $selective_sync_collections[0]->selective_sync_collections;

			} else {
				return 0;
			}

		}


		/*

		Gets the status of selective_sync_customers

		*/
		public function get_selective_sync_customers_status() {

			$selective_sync_customers = $this->get_column_single('selective_sync_customers');

			if ( Utils::array_not_empty($selective_sync_customers) && isset($selective_sync_customers[0]->selective_sync_customers) ) {
				return (int) $selective_sync_customers[0]->selective_sync_customers;

			} else {
				return 0;
			}

		}


		/*

		Gets the status of selective_sync_orders

		*/
		public function get_selective_sync_orders_status() {

			$selective_sync_orders = $this->get_column_single('selective_sync_orders');

			if ( Utils::array_not_empty($selective_sync_orders) && isset($selective_sync_orders[0]->selective_sync_orders) ) {
				return (int) $selective_sync_orders[0]->selective_sync_orders;

			} else {
				return 0;
			}

		}


		/*

		Gets the status of selective_sync_shop

		*/
		public function get_selective_sync_shop_status() {

			$selective_sync_shop = $this->get_column_single('selective_sync_shop');

			if ( Utils::array_not_empty($selective_sync_shop) && isset($selective_sync_shop[0]->selective_sync_shop) ) {
				return (int) $selective_sync_shop[0]->selective_sync_shop;

			} else {
				return 0;
			}

		}


		/*

		Updates the plugin version

		*/
		public function update_plugin_version($new_version_number) {
			return $this->update_column_single( [ 'plugin_version' => $new_version_number ], [ 'id' => $this->settings_id() ]);
		}


		/*

		Is syncing products by collections?

		*/
		public function is_syncing_by_collection() {

			if ( empty($this->sync_by_collections()) ) {
				return false;

			} else {
				return true;
			}

		}


		/*

		Reset syncing timing

		*/
		public function reset_sync_by_collections() {
			return $this->update_column_single(['sync_by_collections' => false], ['id' => 1]);
		}


		/*

		Reset syncing timing

		*/
		public function update_webhooks_callback_url_to_https() {
			return $this->update_column_single( ['url_webhooks' => Utils::convert_to_https_url( get_home_url() )], ['id' => 1] );
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
        id bigint(100) NOT NULL AUTO_INCREMENT,
  		  url_products varchar(100) NOT NULL DEFAULT 'products',
  		  url_collections varchar(100) NOT NULL DEFAULT 'collections',
        url_webhooks varchar(100) NOT NULL DEFAULT '{$this->webhooks}',
        num_posts bigint(100) DEFAULT NULL,
        styles_all tinyint(1) DEFAULT 1,
        styles_core tinyint(1) DEFAULT 0,
        styles_grid tinyint(1) DEFAULT 0,
        plugin_name varchar(100) NOT NULL DEFAULT '{$this->plugin_name}',
        plugin_textdomain varchar(100) NOT NULL DEFAULT '{$this->plugin_textdomain}',
        plugin_version varchar(100) NOT NULL DEFAULT '{$this->plugin_version}',
        plugin_author varchar(100) NOT NULL DEFAULT '{$this->plugin_author}',
        price_with_currency tinyint(1) DEFAULT 0,
        cart_loaded tinyint(1) DEFAULT '{$this->cart_loaded}',
				title_as_alt tinyint(1) DEFAULT '{$this->title_as_alt}',
        selective_sync_all tinyint(1) DEFAULT '{$this->selective_sync_all}',
        selective_sync_products tinyint(1) DEFAULT '{$this->selective_sync_products}',
				sync_by_collections LONGTEXT DEFAULT '{$this->sync_by_collections}',
        selective_sync_collections tinyint(1) DEFAULT '{$this->selective_sync_collections}',
        selective_sync_customers tinyint(1) DEFAULT '{$this->selective_sync_customers}',
        selective_sync_orders tinyint(1) DEFAULT '{$this->selective_sync_orders}',
        selective_sync_shop tinyint(1) DEFAULT '{$this->selective_sync_shop}',
				products_link_to_shopify tinyint(1) DEFAULT '{$this->products_link_to_shopify}',
				show_breadcrumbs tinyint(1) DEFAULT '{$this->show_breadcrumbs}',
				hide_pagination tinyint(1) DEFAULT '{$this->hide_pagination}',
				is_free tinyint(1) unsigned NOT NULL DEFAULT '{$this->is_free}',
				is_pro tinyint(1) unsigned NOT NULL DEFAULT '{$this->is_pro}',
				related_products_show tinyint(1) unsigned NOT NULL DEFAULT '{$this->related_products_show}',
				related_products_sort varchar(100) NOT NULL DEFAULT '{$this->related_products_sort}',
				related_products_amount tinyint(1) unsigned NOT NULL DEFAULT '{$this->related_products_amount}',
				allow_insecure_webhooks tinyint(1) unsigned NOT NULL DEFAULT '{$this->allow_insecure_webhooks}',
				save_connection_only tinyint(1) unsigned NOT NULL DEFAULT '{$this->save_connection_only}',
				app_uninstalled tinyint(1) unsigned NOT NULL DEFAULT '{$this->app_uninstalled}',
  		  PRIMARY KEY  (id)
  		) ENGINE=InnoDB $collate";

    }


  }

}
