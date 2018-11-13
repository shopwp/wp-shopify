<?php

namespace WPS\DB;

use WPS\Utils;
use WPS\Transients;
use WPS\CPT;


if (!defined('ABSPATH')) {
	exit;
}


class Orders extends \WPS\DB {

	public $table_name_suffix;
	public $table_name;
	public $version;
	public $primary_key;
	public $lookup_key;
	public $cache_group;
	public $type;

	public $default_order_id;
	public $default_customer_id;
	public $default_email;
	public $default_closed_at;
	public $default_created_at;
	public $default_updated_at;
	public $default_number;
	public $default_note;
	public $default_token;
	public $default_gateway;
	public $default_total_price;
	public $default_subtotal_price;
	public $default_total_weight;
	public $default_total_tax;
	public $default_taxes_included;
	public $default_currency;
	public $default_financial_status;
	public $default_confirmed;
	public $default_total_discounts;
	public $default_total_line_items_price;
	public $default_cart_token;
	public $default_buyer_accepts_marketing;
	public $default_name;
	public $default_referring_site;
	public $default_landing_site;
	public $default_cancelled_at;
	public $default_cancel_reason;
	public $default_total_price_usd;
	public $default_checkout_token;
	public $default_reference;
	public $default_user_id;
	public $default_location_id;
	public $default_source_identifier;
	public $default_source_url;
	public $default_processed_at;
	public $default_device_id;
	public $default_phone;
	public $default_customer_locale;
	public $default_app_id;
	public $default_browser_ip;
	public $default_landing_site_ref;
	public $default_order_number;
	public $default_discount_codes;
	public $default_note_attributes;
	public $default_payment_gateway_names;
	public $default_processing_method;
	public $default_checkout_id;
	public $default_source_name;
	public $default_fulfillment_status;
	public $default_tax_lines;
	public $default_tags;
	public $default_contact_email;
	public $default_order_status_url;
	public $default_line_items;
	public $default_shipping_lines;
	public $default_billing_address;
	public $default_shipping_address;
	public $default_fulfillments;
	public $default_client_details;
	public $default_refunds;
	public $default_customer;
	public $default_test;
	public $default_discount_applications;
	public $default_admin_graphql_api_id;
	public $default_payment_details;


	public function __construct() {

		// Table info
		$this->table_name_suffix  								= WPS_TABLE_NAME_ORDERS;
		$this->table_name         								= $this->get_table_name();
		$this->version            								= '1.0';
		$this->primary_key        								= 'id';
		$this->lookup_key        									= 'order_id';
		$this->cache_group        								= 'wps_db_orders';

		// Used for hook identifiers within low level db methods like insert, update, etc
		$this->type        												= 'order';

		// Defaults
		$this->default_order_id                 	= 0;
		$this->default_customer_id              	= 0;
		$this->default_email                    	= '';
		$this->default_closed_at                	= date_i18n( 'Y-m-d H:i:s' );
		$this->default_created_at               	= date_i18n( 'Y-m-d H:i:s' );
		$this->default_updated_at               	= date_i18n( 'Y-m-d H:i:s' );
		$this->default_number                   	= 0;
		$this->default_note                     	= '';
		$this->default_token                    	= '';
		$this->default_gateway                  	= '';
		$this->default_total_price              	= 0;
		$this->default_subtotal_price           	= 0;
		$this->default_total_weight             	= 0;
		$this->default_total_tax                	= '';
		$this->default_taxes_included           	= 0;
		$this->default_currency                 	= '';
		$this->default_financial_status         	= '';
		$this->default_confirmed                	= 0;
		$this->default_total_discounts          	= '';
		$this->default_total_line_items_price   	= 0;
		$this->default_cart_token               	= '';
		$this->default_buyer_accepts_marketing  	= 0;
		$this->default_name                     	= '';
		$this->default_referring_site           	= '';
		$this->default_landing_site             	= '';
		$this->default_cancelled_at             	= date_i18n( 'Y-m-d H:i:s' );
		$this->default_cancel_reason            	= '';
		$this->default_total_price_usd          	= 0;
		$this->default_checkout_token           	= '';
		$this->default_reference                	= '';
		$this->default_user_id                  	= 0;
		$this->default_location_id              	= 0;
		$this->default_source_identifier        	= '';
		$this->default_source_url               	= '';
		$this->default_processed_at             	= date_i18n( 'Y-m-d H:i:s' );
		$this->default_device_id                	= 0;
		$this->default_phone                    	= '';
		$this->default_customer_locale          	= '';
		$this->default_app_id                   	= 0;
		$this->default_browser_ip               	= '';
		$this->default_landing_site_ref         	= '';
		$this->default_order_number             	= 0;
		$this->default_discount_codes           	= '';
		$this->default_note_attributes          	= '';
		$this->default_payment_gateway_names    	= '';
		$this->default_processing_method        	= '';
		$this->default_checkout_id              	= 0;
		$this->default_source_name              	= '';
		$this->default_fulfillment_status       	= '';
		$this->default_tax_lines                	= '';
		$this->default_tags                     	= '';
		$this->default_contact_email            	= '';
		$this->default_order_status_url         	= '';
		$this->default_line_items               	= '';
		$this->default_shipping_lines           	= '';
		$this->default_billing_address          	= '';
		$this->default_shipping_address         	= '';
		$this->default_fulfillments             	= '';
		$this->default_client_details           	= '';
		$this->default_refunds                  	= '';
		$this->default_customer                 	= '';
		$this->default_test												= '';
		$this->default_discount_applications			= '';
		$this->default_admin_graphql_api_id				= '';
		$this->default_payment_details						= '';

	}




}
