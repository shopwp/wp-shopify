<?php

namespace WPS\DB;

use WPS\Utils;

if (!defined('ABSPATH')) {
	exit;
}


if (!class_exists('Shop')) {

	class Shop extends \WPS\DB {

		public $table_name;
		public $version;
		public $primary_key;
		public $cache_group;

		public $domain;
		public $source;
		public $customer_email;
		public $iana_timezone;
		public $taxes_included;
		public $tax_shipping;
		public $county_taxes;
		public $plan_display_name;
		public $plan_name;
		public $has_discounts;
		public $has_gift_cards;
		public $google_apps_domain;
		public $google_apps_login_enabled;
		public $money_in_emails_format;
		public $money_with_currency_in_emails_format;
		public $eligible_for_payments;
		public $requires_extra_payments_agreement;
		public $password_enabled;
		public $has_storefront;
		public $eligible_for_card_reader_giveaway;
		public $finances;
		public $primary_location_id;
		public $checkout_api_supported;
		public $multi_location_enabled;
		public $setup_required;
		public $force_ssl;
		public $pre_launch_enabled;


		public function __construct() {

	    global $wpdb;

			$this->table_name 		 												= WPS_TABLE_NAME_SHOP;

	    $this->primary_key 		 												= 'id';
	    $this->version     		 												= '1.0';
			$this->cache_group     												= 'wps_db_shop';

			$this->id                          						= 0;
			$this->name                        						= '';
			$this->myshopify_domain            						= '';
			$this->shop_owner                  						= '';
			$this->phone                       						= '';
			$this->email                       						= '';
			$this->address1                    						= '';
			$this->address2                    						= '';
			$this->city                        						= '';
			$this->zip                         						= '';
			$this->country                     						= '';
			$this->country_code                						= '';
			$this->country_name                						= '';
			$this->currency                    						= '';
			$this->latitude                    						= 0;
			$this->longitude                   						= 0;
			$this->money_format                						= '';
			$this->money_with_currency_format  						= '';
			$this->weight_unit                 						= '';
			$this->primary_locale              						= '';
			$this->province                    						= '';
			$this->province_code               						= '';
			$this->timezone                    						= '';
			$this->created_at                  						= date_i18n( 'Y-m-d H:i:s' );
			$this->updated_at                  						= date_i18n( 'Y-m-d H:i:s' );
			$this->domain     														= '';
			$this->source																	= '';
			$this->customer_email													= '';
			$this->iana_timezone													= '';
			$this->taxes_included													= 0;
			$this->tax_shipping														= null;
			$this->county_taxes														= null;
			$this->plan_display_name 											= '';
			$this->plan_name															= '';
			$this->has_discounts													= 0;
			$this->has_gift_cards													= 0;
			$this->google_apps_domain											= '';
			$this->google_apps_login_enabled							= 0;
			$this->money_in_emails_format									= '';
			$this->money_with_currency_in_emails_format		= '';
			$this->eligible_for_payments									= 0;
			$this->requires_extra_payments_agreement			= 0;
			$this->password_enabled												= 0;
			$this->has_storefront													= 0;
			$this->eligible_for_card_reader_giveaway			= 0;
			$this->finances																= 0;
			$this->primary_location_id										= 0;
			$this->checkout_api_supported									= 0;
			$this->multi_location_enabled									= 0;
			$this->setup_required													= 0;
			$this->force_ssl															= 0;
			$this->pre_launch_enabled											= 0;

	  }


		public function get_columns() {

			return [
	      'id'                          					=> '%d',
	      'name'                        					=> '%s',
				'myshopify_domain'            					=> '%s',
	      'shop_owner'                  					=> '%s',
	      'phone'                       					=> '%s',
	      'email'                       					=> '%s',
	      'address1'                    					=> '%s',
	      'address2'                    					=> '%s',
	      'city'                        					=> '%s',
	      'zip'                         					=> '%s',
	      'country'                     					=> '%s',
	      'country_code'                					=> '%s',
	      'country_name'                					=> '%s',
	      'currency'                    					=> '%s',
	      'latitude'                    					=> '%f',
	      'longitude'                   					=> '%f',
	      'money_format'                					=> '%s',
	      'money_with_currency_format'  					=> '%s',
	      'weight_unit'                 					=> '%s',
	      'primary_locale'              					=> '%s',
	      'province'                    					=> '%s',
	      'province_code'               					=> '%s',
	      'timezone'                    					=> '%s',
				'created_at'                  					=> '%s',
	      'updated_at'                  					=> '%s',
				'domain' 																=> '%s',
				'source' 																=> '%s',
				'customer_email' 												=> '%s',
				'iana_timezone' 												=> '%s',
				'taxes_included' 												=> '%d',
				'tax_shipping' 													=> '%d',
				'county_taxes' 													=> '%d',
				'plan_display_name' 										=> '%s',
				'plan_name' 														=> '%s',
				'has_discounts' 												=> '%d',
				'has_gift_cards' 												=> '%d',
				'google_apps_domain' 										=> '%s',
				'google_apps_login_enabled' 						=> '%d',
				'money_in_emails_format' 								=> '%s',
				'money_with_currency_in_emails_format' 	=> '%s',
				'eligible_for_payments' 								=> '%d',
				'requires_extra_payments_agreement' 		=> '%d',
				'password_enabled' 											=> '%d',
				'has_storefront' 												=> '%d',
				'eligible_for_card_reader_giveaway' 		=> '%d',
				'finances' 															=> '%d',
				'primary_location_id' 									=> '%d',
				'checkout_api_supported' 								=> '%d',
				'multi_location_enabled' 								=> '%d',
				'setup_required' 												=> '%d',
				'force_ssl' 														=> '%d',
				'pre_launch_enabled' 										=> '%d'
	    ];

	  }


	  /*

	  Get Column Defaults

	  */
		public function get_column_defaults() {

	    return [
	      'id'                          					=> $this->id,
	      'name'                        					=> $this->name,
				'myshopify_domain'            					=> $this->myshopify_domain,
	      'shop_owner'                  					=> $this->shop_owner,
	      'phone'                       					=> $this->phone,
	      'email'                       					=> $this->email,
	      'address1'                    					=> $this->address1,
	      'address2'                    					=> $this->address2,
	      'city'                        					=> $this->city,
	      'zip'                         					=> $this->zip,
	      'country'                     					=> $this->country,
	      'country_code'                					=> $this->country_code,
	      'country_name'                					=> $this->country_name,
	      'currency'                    					=> $this->currency,
	      'latitude'                    					=> $this->latitude,
	      'longitude'                   					=> $this->longitude,
	      'money_format'                					=> $this->money_format,
	      'money_with_currency_format'  					=> $this->money_with_currency_format,
	      'weight_unit'                 					=> $this->weight_unit,
	      'primary_locale'              					=> $this->primary_locale,
	      'province'                    					=> $this->province,
	      'province_code'               					=> $this->province_code,
	      'timezone'                    					=> $this->timezone,
				'created_at'                  					=> $this->created_at,
	      'updated_at'                  					=> $this->updated_at,
				'domain' 																=> $this->domain,
				'source' 																=> $this->source,
				'customer_email' 												=> $this->customer_email,
				'iana_timezone' 												=> $this->iana_timezone,
				'taxes_included' 												=> $this->taxes_included,
				'tax_shipping' 													=> $this->tax_shipping,
				'county_taxes' 													=> $this->county_taxes,
				'plan_display_name' 										=> $this->plan_display_name,
				'plan_name' 														=> $this->plan_name,
				'has_discounts' 												=> $this->has_discounts,
				'has_gift_cards' 												=> $this->has_gift_cards,
				'google_apps_domain' 										=> $this->google_apps_domain,
				'google_apps_login_enabled' 						=> $this->google_apps_login_enabled,
				'money_in_emails_format' 								=> $this->money_in_emails_format,
				'money_with_currency_in_emails_format' 	=> $this->money_with_currency_in_emails_format,
				'eligible_for_payments' 								=> $this->eligible_for_payments,
				'requires_extra_payments_agreement' 		=> $this->requires_extra_payments_agreement,
				'password_enabled' 											=> $this->password_enabled,
				'has_storefront' 												=> $this->has_storefront,
				'eligible_for_card_reader_giveaway' 		=> $this->eligible_for_card_reader_giveaway,
				'finances' 															=> $this->finances,
				'primary_location_id' 									=> $this->primary_location_id,
				'checkout_api_supported' 								=> $this->checkout_api_supported,
				'multi_location_enabled' 								=> $this->multi_location_enabled,
				'setup_required' 												=> $this->setup_required,
				'force_ssl' 														=> $this->force_ssl,
				'pre_launch_enabled' 										=> $this->pre_launch_enabled
	    ];

	  }


	  /*

	  Get single shop info value

	  */
		public function get_shop($column) {

	    global $wpdb;

	    // If not a string ...
	    if (!is_string($column)) {
	      return;
	    }

	    // If argument not apart of schema ...
	    if (!array_key_exists($column, $this->get_columns()) ) {
	      return;
	    }

	    $data = wp_cache_get($column, $this->cache_group);

	    if (!$data) {

	      $query = "SELECT $column FROM {$this->table_name};";
	      $data = $wpdb->get_results($query);

	      // Cache for 1 hour
	      wp_cache_add($column, $data, $this->cache_group, 3600);

	    }

	    return $data;

	  }


		/*

		insert_shop

		*/
		public function insert_shop($shopData) {

			global $wpdb;

			if (is_array($shopData) && isset($shopData['shop']['id']) && $shopData['shop']['id']) {

				if ($this->get_by('id', $shopData['shop']['id'])) {
					$results = $this->update($shopData['shop']['id'], $shopData['shop']);

				} else {
					$results = $this->insert($shopData['shop'], 'shop');
				}

			} else {
				$results = false;

			}

			return $results;

		}


		/*

		Get Money Format

		*/
		public function get_money_format() {

	    $money_format = $this->get_shop('money_format');

			if (Utils::array_not_empty($money_format)) {
				return $money_format[0]->money_format;

			} else {
				return '${{amount}}'; // Default fallback
			}

	  }


		/*

		Get Domain

		*/
		public function domain() {

	    $domain = $this->get_shop('domain');

			if (Utils::array_not_empty($domain)) {
				return $domain[0]->domain;

			} else {
				return false;
			}

	  }


		/*

		Get Money With Currency Format

		*/
		public function get_money_with_currency_format() {

	    $money_with_currency_format = $this->get_shop('money_with_currency_format');

			if (!empty($money_with_currency_format)) {
				return $money_with_currency_format[0]->money_with_currency_format;

			} else {
				return false;
			}

	  }


		/*

	  Insert connection data

	  */
	  public function update_shop($shopData) {
	    return $this->update( $this->get_shop('id')[0]->id, $shopData);
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

			if ( $wpdb->has_cap('collation') ) {
				$collate = $wpdb->get_charset_collate();
			}

			return "CREATE TABLE $table_name (
				id bigint(100) unsigned NOT NULL AUTO_INCREMENT,
				name varchar(255) DEFAULT '{$this->name}',
				myshopify_domain varchar(255) DEFAULT '{$this->myshopify_domain}',
				shop_owner varchar(100) DEFAULT '{$this->shop_owner}',
				phone varchar(100) DEFAULT '{$this->phone}',
				email varchar(100) DEFAULT '{$this->email}',
				address1 varchar(100) DEFAULT '{$this->address1}',
				address2 varchar(100) DEFAULT '{$this->address2}',
				city varchar(50) DEFAULT '{$this->city}',
				zip varchar(50) DEFAULT '{$this->zip}',
				country varchar(50) DEFAULT '{$this->country}',
				country_code varchar(50) DEFAULT '{$this->country_code}',
				country_name varchar(50) DEFAULT '{$this->country_name}',
				currency varchar(50) DEFAULT '{$this->currency}',
				latitude smallint(20) DEFAULT '{$this->latitude}',
				longitude smallint(20) DEFAULT '{$this->longitude}',
				money_format varchar(200) DEFAULT '{$this->money_format}',
				money_with_currency_format varchar(200) DEFAULT '{$this->money_with_currency_format}',
				weight_unit varchar(20) DEFAULT '{$this->weight_unit}',
				primary_locale varchar(20) DEFAULT '{$this->primary_locale}',
				province varchar(20) DEFAULT '{$this->province}',
				province_code varchar(20) DEFAULT '{$this->province_code}',
				timezone varchar(200) DEFAULT '{$this->timezone}',
				created_at datetime,
				updated_at datetime,
				domain varchar(100) DEFAULT '{$this->domain}',
				source varchar(100) DEFAULT '{$this->source}',
				customer_email varchar(100) DEFAULT '{$this->customer_email}',
				iana_timezone varchar(100) DEFAULT '{$this->iana_timezone}',
				taxes_included tinyint(1) DEFAULT '{$this->taxes_included}',
				tax_shipping varchar(100) DEFAULT '{$this->tax_shipping}',
				county_taxes varchar(100) DEFAULT '{$this->county_taxes}',
				plan_display_name varchar(100) DEFAULT '{$this->plan_display_name}',
				plan_name varchar(100) DEFAULT '{$this->plan_name}',
				has_discounts tinyint(1) DEFAULT '{$this->has_discounts}',
				has_gift_cards tinyint(1) DEFAULT '{$this->has_gift_cards}',
				google_apps_domain varchar(100) DEFAULT '{$this->google_apps_domain}',
				google_apps_login_enabled tinyint(1) DEFAULT '{$this->google_apps_login_enabled}',
				money_in_emails_format varchar(100) DEFAULT '{$this->money_in_emails_format}',
				money_with_currency_in_emails_format varchar(100) DEFAULT '{$this->money_with_currency_in_emails_format}',
				eligible_for_payments tinyint(1) DEFAULT '{$this->eligible_for_payments}',
				requires_extra_payments_agreement tinyint(1) DEFAULT '{$this->requires_extra_payments_agreement}',
				password_enabled tinyint(1) DEFAULT '{$this->password_enabled}',
				has_storefront tinyint(1) DEFAULT '{$this->has_storefront}',
				eligible_for_card_reader_giveaway tinyint(1) DEFAULT '{$this->eligible_for_card_reader_giveaway}',
				finances tinyint(1) DEFAULT '{$this->finances}',
				primary_location_id tinyint(1) DEFAULT '{$this->primary_location_id}',
				checkout_api_supported tinyint(1) DEFAULT '{$this->checkout_api_supported}',
				multi_location_enabled tinyint(1) DEFAULT '{$this->multi_location_enabled}',
				setup_required tinyint(1) DEFAULT '{$this->setup_required}',
				force_ssl tinyint(1) DEFAULT '{$this->force_ssl}',
				pre_launch_enabled tinyint(1) DEFAULT '{$this->pre_launch_enabled}',
				PRIMARY KEY  (id)
			) ENGINE=InnoDB $collate";

		}


		/*

		Migrate insert into query

		*/
		public function migration_insert_into_query() {

			return $this->query('INSERT INTO ' . $this->table_name . WPS_TABLE_MIGRATION_SUFFIX . '(`id`, `name`, `myshopify_domain`, `shop_owner`, `phone`, `email`, `address1`, `address2`, `city`, `zip`, `country`, `country_code`, `country_name`, `currency`, `latitude`, `longitude`, `money_format`, `money_with_currency_format`, `weight_unit`, `primary_locale`, `province`, `province_code`, `timezone`, `created_at`, `updated_at`, `domain`, `source`, `customer_email`, `iana_timezone`, `taxes_included`, `tax_shipping`, `county_taxes`, `plan_display_name`, `plan_name`, `has_discounts`, `has_gift_cards`, `google_apps_domain`, `google_apps_login_enabled`, `money_in_emails_format`, `money_with_currency_in_emails_format`, `eligible_for_payments`, `requires_extra_payments_agreement`, `password_enabled`, `has_storefront`, `eligible_for_card_reader_giveaway`, `finances`, `primary_location_id`, `checkout_api_supported`, `multi_location_enabled`, `setup_required`, `force_ssl`, `pre_launch_enabled`) SELECT `id`, `name`, `myshopify_domain`, `shop_owner`, `phone`, `email`, `address1`, `address2`, `city`, `zip`, `country`, `country_code`, `country_name`, `currency`, `latitude`, `longitude`, `money_format`, `money_with_currency_format`, `weight_unit`, `primary_locale`, `province`, `province_code`, `timezone`, `created_at`, `updated_at`, `domain`, `source`, `customer_email`, `iana_timezone`, `taxes_included`, `tax_shipping`, `county_taxes`, `plan_display_name`, `plan_name`, `has_discounts`, `has_gift_cards`, `google_apps_domain`, `google_apps_login_enabled`, `money_in_emails_format`, `money_with_currency_in_emails_format`, `eligible_for_payments`, `requires_extra_payments_agreement`, `password_enabled`, `has_storefront`, `eligible_for_card_reader_giveaway`, `finances`, `primary_location_id`, `checkout_api_supported`, `multi_location_enabled`, `setup_required`, `force_ssl`, `pre_launch_enabled` FROM ' . $this->table_name);

		}


	  /*

	  Creates database table

	  */
		public function create_table() {

			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

			if ( !$this->table_exists($this->table_name) ) {
				dbDelta( $this->create_table_query() );
				set_transient('wp_shopify_table_exists_' . $this->table_name, 1);
			}

	  }


	}

}
