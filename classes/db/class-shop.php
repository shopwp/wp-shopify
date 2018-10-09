<?php

namespace WPS\DB;

use WPS\Utils;

if (!defined('ABSPATH')) {
	exit;
}


class Shop extends \WPS\DB {

	public $table_name_suffix;
	public $table_name;
	public $version;
	public $primary_key;
	public $lookup_key;
	public $cache_group;
	public $type;

	public $default_id;
	public $default_name;
	public $default_myshopify_domain;
	public $default_shop_owner;
	public $default_phone;
	public $default_email;
	public $default_address1;
	public $default_address2;
	public $default_city;
	public $default_zip;
	public $default_country;
	public $default_country_code;
	public $default_country_name;
	public $default_currency;
	public $default_latitude;
	public $default_longitude;
	public $default_money_format;
	public $default_money_with_currency_format;
	public $default_weight_unit;
	public $default_primary_locale;
	public $default_province;
	public $default_province_code;
	public $default_timezone;
	public $default_created_at;
	public $default_updated_at;
	public $default_domain;
	public $default_source;
	public $default_customer_email;
	public $default_iana_timezone;
	public $default_taxes_included;
	public $default_tax_shipping;
	public $default_county_taxes;
	public $default_plan_display_name;
	public $default_plan_name;
	public $default_has_discounts;
	public $default_has_gift_cards;
	public $default_google_apps_domain;
	public $default_google_apps_login_enabled;
	public $default_money_in_emails_format;
	public $default_money_with_currency_in_emails_format;
	public $default_eligible_for_payments;
	public $default_requires_extra_payments_agreement;
	public $default_password_enabled;
	public $default_has_storefront;
	public $default_eligible_for_card_reader_giveaway;
	public $default_finances;
	public $default_primary_location_id;
	public $default_checkout_api_supported;
	public $default_multi_location_enabled;
	public $default_setup_required;
	public $default_force_ssl;
	public $default_pre_launch_enabled;


	public function __construct() {

		global $wpdb;

		$this->table_name_suffix  														= WPS_TABLE_NAME_SHOP;
		$this->table_name         														= $this->get_table_name();
		$this->version     		 																= '1.0';
		$this->primary_key 		 																= 'id';
		$this->lookup_key																			= 'id';
		$this->cache_group     																= 'wps_db_shop';
		$this->type     																			= 'shop';

		$this->default_id                          						= 0;
		$this->default_name                        						= '';
		$this->default_myshopify_domain            						= '';
		$this->default_shop_owner                  						= '';
		$this->default_phone                       						= '';
		$this->default_email                       						= '';
		$this->default_address1                    						= '';
		$this->default_address2                    						= '';
		$this->default_city                        						= '';
		$this->default_zip                         						= '';
		$this->default_country                     						= '';
		$this->default_country_code                						= '';
		$this->default_country_name                						= '';
		$this->default_currency                    						= '';
		$this->default_latitude                    						= 0;
		$this->default_longitude                   						= 0;
		$this->default_money_format                						= '';
		$this->default_money_with_currency_format  						= '';
		$this->default_weight_unit                 						= '';
		$this->default_primary_locale              						= '';
		$this->default_province                    						= '';
		$this->default_province_code               						= '';
		$this->default_timezone                    						= '';
		$this->default_created_at                  						= date_i18n( 'Y-m-d H:i:s' );
		$this->default_updated_at                  						= date_i18n( 'Y-m-d H:i:s' );
		$this->default_domain     														= '';
		$this->default_source																	= '';
		$this->default_customer_email													= '';
		$this->default_iana_timezone													= '';
		$this->default_taxes_included													= 0;
		$this->default_tax_shipping														= null;
		$this->default_county_taxes														= null;
		$this->default_plan_display_name 											= '';
		$this->default_plan_name															= '';
		$this->default_has_discounts													= 0;
		$this->default_has_gift_cards													= 0;
		$this->default_google_apps_domain											= '';
		$this->default_google_apps_login_enabled							= 0;
		$this->default_money_in_emails_format									= '';
		$this->default_money_with_currency_in_emails_format		= '';
		$this->default_eligible_for_payments									= 0;
		$this->default_requires_extra_payments_agreement			= 0;
		$this->default_password_enabled												= 0;
		$this->default_has_storefront													= 0;
		$this->default_eligible_for_card_reader_giveaway			= 0;
		$this->default_finances																= 0;
		$this->default_primary_location_id										= 0;
		$this->default_checkout_api_supported									= 0;
		$this->default_multi_location_enabled									= 0;
		$this->default_setup_required													= 0;
		$this->default_force_ssl															= 0;
		$this->default_pre_launch_enabled											= 0;

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
			'id'                          					=> $this->default_id,
			'name'                        					=> $this->default_name,
			'myshopify_domain'            					=> $this->default_myshopify_domain,
			'shop_owner'                  					=> $this->default_shop_owner,
			'phone'                       					=> $this->default_phone,
			'email'                       					=> $this->default_email,
			'address1'                    					=> $this->default_address1,
			'address2'                    					=> $this->default_address2,
			'city'                        					=> $this->default_city,
			'zip'                         					=> $this->default_zip,
			'country'                     					=> $this->default_country,
			'country_code'                					=> $this->default_country_code,
			'country_name'                					=> $this->default_country_name,
			'currency'                    					=> $this->default_currency,
			'latitude'                    					=> $this->default_latitude,
			'longitude'                   					=> $this->default_longitude,
			'money_format'                					=> $this->default_money_format,
			'money_with_currency_format'  					=> $this->default_money_with_currency_format,
			'weight_unit'                 					=> $this->default_weight_unit,
			'primary_locale'              					=> $this->default_primary_locale,
			'province'                    					=> $this->default_province,
			'province_code'               					=> $this->default_province_code,
			'timezone'                    					=> $this->default_timezone,
			'created_at'                  					=> $this->default_created_at,
			'updated_at'                  					=> $this->default_updated_at,
			'domain' 																=> $this->default_domain,
			'source' 																=> $this->default_source,
			'customer_email' 												=> $this->default_customer_email,
			'iana_timezone' 												=> $this->default_iana_timezone,
			'taxes_included' 												=> $this->default_taxes_included,
			'tax_shipping' 													=> $this->default_tax_shipping,
			'county_taxes' 													=> $this->default_county_taxes,
			'plan_display_name' 										=> $this->default_plan_display_name,
			'plan_name' 														=> $this->default_plan_name,
			'has_discounts' 												=> $this->default_has_discounts,
			'has_gift_cards' 												=> $this->default_has_gift_cards,
			'google_apps_domain' 										=> $this->default_google_apps_domain,
			'google_apps_login_enabled' 						=> $this->default_google_apps_login_enabled,
			'money_in_emails_format' 								=> $this->default_money_in_emails_format,
			'money_with_currency_in_emails_format' 	=> $this->default_money_with_currency_in_emails_format,
			'eligible_for_payments' 								=> $this->default_eligible_for_payments,
			'requires_extra_payments_agreement' 		=> $this->default_requires_extra_payments_agreement,
			'password_enabled' 											=> $this->default_password_enabled,
			'has_storefront' 												=> $this->default_has_storefront,
			'eligible_for_card_reader_giveaway' 		=> $this->default_eligible_for_card_reader_giveaway,
			'finances' 															=> $this->default_finances,
			'primary_location_id' 									=> $this->default_primary_location_id,
			'checkout_api_supported' 								=> $this->default_checkout_api_supported,
			'multi_location_enabled' 								=> $this->default_multi_location_enabled,
			'setup_required' 												=> $this->default_setup_required,
			'force_ssl' 														=> $this->default_force_ssl,
			'pre_launch_enabled' 										=> $this->default_pre_launch_enabled
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
	public function insert_shop($shop_data) {

		global $wpdb;

		if (is_array($shop_data) && isset($shop_data['shop']['id']) && $shop_data['shop']['id']) {

			if ($this->get_row_by('id', $shop_data['shop']['id'])) {
				$results = $this->update($this->lookup_key, $shop_data['shop']['id'], $shop_data['shop']);

			} else {
				$results = $this->insert($shop_data['shop']);
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
		return $this->update( $this->lookup_key, $this->get_shop('id')[0]->id, $shopData);
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
			name varchar(255) DEFAULT '{$this->default_name}',
			myshopify_domain varchar(255) DEFAULT '{$this->default_myshopify_domain}',
			shop_owner varchar(200) DEFAULT '{$this->default_shop_owner}',
			phone varchar(100) DEFAULT '{$this->default_phone}',
			email varchar(100) DEFAULT '{$this->default_email}',
			address1 varchar(100) DEFAULT '{$this->default_address1}',
			address2 varchar(100) DEFAULT '{$this->default_address2}',
			city varchar(50) DEFAULT '{$this->default_city}',
			zip varchar(50) DEFAULT '{$this->default_zip}',
			country varchar(50) DEFAULT '{$this->default_country}',
			country_code varchar(50) DEFAULT '{$this->default_country_code}',
			country_name varchar(50) DEFAULT '{$this->default_country_name}',
			currency varchar(50) DEFAULT '{$this->default_currency}',
			latitude smallint(20) DEFAULT '{$this->default_latitude}',
			longitude smallint(20) DEFAULT '{$this->default_longitude}',
			money_format varchar(200) DEFAULT '{$this->default_money_format}',
			money_with_currency_format varchar(200) DEFAULT '{$this->default_money_with_currency_format}',
			weight_unit varchar(20) DEFAULT '{$this->default_weight_unit}',
			primary_locale varchar(20) DEFAULT '{$this->default_primary_locale}',
			province varchar(200) DEFAULT '{$this->default_province}',
			province_code varchar(20) DEFAULT '{$this->default_province_code}',
			timezone varchar(200) DEFAULT '{$this->default_timezone}',
			created_at datetime DEFAULT '{$this->default_created_at}',
			updated_at datetime DEFAULT '{$this->default_updated_at}',
			domain varchar(100) DEFAULT '{$this->default_domain}',
			source varchar(100) DEFAULT '{$this->default_source}',
			customer_email varchar(100) DEFAULT '{$this->default_customer_email}',
			iana_timezone varchar(100) DEFAULT '{$this->default_iana_timezone}',
			taxes_included tinyint(1) DEFAULT '{$this->default_taxes_included}',
			tax_shipping varchar(100) DEFAULT '{$this->default_tax_shipping}',
			county_taxes varchar(100) DEFAULT '{$this->default_county_taxes}',
			plan_display_name varchar(200) DEFAULT '{$this->default_plan_display_name}',
			plan_name varchar(100) DEFAULT '{$this->default_plan_name}',
			has_discounts tinyint(1) DEFAULT '{$this->default_has_discounts}',
			has_gift_cards tinyint(1) DEFAULT '{$this->default_has_gift_cards}',
			google_apps_domain varchar(100) DEFAULT '{$this->default_google_apps_domain}',
			google_apps_login_enabled tinyint(1) DEFAULT '{$this->default_google_apps_login_enabled}',
			money_in_emails_format varchar(100) DEFAULT '{$this->default_money_in_emails_format}',
			money_with_currency_in_emails_format varchar(100) DEFAULT '{$this->default_money_with_currency_in_emails_format}',
			eligible_for_payments tinyint(1) DEFAULT '{$this->default_eligible_for_payments}',
			requires_extra_payments_agreement tinyint(1) DEFAULT '{$this->default_requires_extra_payments_agreement}',
			password_enabled tinyint(1) DEFAULT '{$this->default_password_enabled}',
			has_storefront tinyint(1) DEFAULT '{$this->default_has_storefront}',
			eligible_for_card_reader_giveaway tinyint(1) DEFAULT '{$this->default_eligible_for_card_reader_giveaway}',
			finances tinyint(1) DEFAULT '{$this->default_finances}',
			primary_location_id tinyint(1) DEFAULT '{$this->default_primary_location_id}',
			checkout_api_supported tinyint(1) DEFAULT '{$this->default_checkout_api_supported}',
			multi_location_enabled tinyint(1) DEFAULT '{$this->default_multi_location_enabled}',
			setup_required tinyint(1) DEFAULT '{$this->default_setup_required}',
			force_ssl tinyint(1) DEFAULT '{$this->default_force_ssl}',
			pre_launch_enabled tinyint(1) DEFAULT '{$this->default_pre_launch_enabled}',
			PRIMARY KEY  (id)
		) ENGINE=InnoDB $collate";

	}


}
