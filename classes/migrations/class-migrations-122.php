<?php

namespace WPS\Migrations;

use WPS\Utils;
use WPS\Transients;

if (!defined('ABSPATH')) {
	exit;
}

if (!class_exists('Migrations_122')) {

  class Migrations_122 {

		private $DB_Products;
		private $DB_Variants;
		private $DB_Collects;
		private $DB_Options;
		private $DB_Collections_Custom;
		private $DB_Collections_Smart;
		private $DB_Images;
		private $DB_Tags;
		private $DB_Customers;
		private $DB_Orders;


  	public function __construct($DB_Products, $DB_Variants, $DB_Collects, $DB_Options, $DB_Collections_Custom, $DB_Collections_Smart, $DB_Images, $DB_Tags, $DB_Customers, $DB_Orders) {

			$this->DB_Products 									= $DB_Products;
			$this->DB_Variants 									= $DB_Variants;
			$this->DB_Collects 									= $DB_Collects;
			$this->DB_Options 									= $DB_Options;
			$this->DB_Collections_Custom 				= $DB_Collections_Custom;
			$this->DB_Collections_Smart 				= $DB_Collections_Smart;
			$this->DB_Images 										= $DB_Images;
			$this->DB_Tags 											= $DB_Tags;
			$this->DB_Customers 								= $DB_Customers;
			$this->DB_Orders 										= $DB_Orders;

    }


		/*

		Tested

		*/
    public function create_migration_db_tables($table_suffix) {

      $create_migration_db_tables_results = [];

      $create_migration_db_tables_results['DB_Products'] = $this->DB_Products->create_migration_table($table_suffix);
      $create_migration_db_tables_results['DB_Variants'] = $this->DB_Variants->create_migration_table($table_suffix);
      $create_migration_db_tables_results['DB_Collects'] = $this->DB_Collects->create_migration_table($table_suffix);
      $create_migration_db_tables_results['DB_Options'] = $this->DB_Options->create_migration_table($table_suffix);
      $create_migration_db_tables_results['DB_Collections_Custom'] = $this->DB_Collections_Custom->create_migration_table($table_suffix);
      $create_migration_db_tables_results['DB_Collections_Smart'] = $this->DB_Collections_Smart->create_migration_table($table_suffix);
      $create_migration_db_tables_results['DB_Images'] = $this->DB_Images->create_migration_table($table_suffix);
      $create_migration_db_tables_results['DB_Tags'] = $this->DB_Tags->create_migration_table($table_suffix);


			return Utils::return_only_error_messages( Utils::return_only_errors($create_migration_db_tables_results) );

    }



		public function products_old_cols() {

			return [
				'product_id',
				'post_id',
				'title',
				'body_html',
				'handle',
				'image',
				'images',
				'vendor',
				'product_type',
				'published_scope',
				'published_at',
				'updated_at',
				'created_at',
				'admin_graphql_api_id'
			];

		}


		public function variants_old_cols() {

			return [
				'id',
				'product_id',
				'image_id',
				'title',
				'price',
				'compare_at_price',
				'position',
				'option1',
				'option2',
				'option3',
				'taxable',
				'sku',
				'inventory_policy',
				'inventory_quantity',
				'old_inventory_quantity',
				'inventory_management',
				'fulfillment_service',
				'barcode',
				'weight',
				'weight_unit',
				'requires_shipping',
				'created_at',
				'updated_at',
				'admin_graphql_api_id'
			];

		}


		public function collects_old_cols() {

			return [
				'id',
				'product_id',
				'collection_id',
				'featured',
				'position',
				'sort_value',
				'created_at',
				'updated_at'
			];

		}


		public function options_old_cols() {

			return [
				'id',
				'product_id',
				'name',
				'position',
				'values'
			];

		}


		public function collections_custom_old_cols() {

			return [
				'collection_id',
				'post_id',
				'title',
				'handle',
				'body_html',
				'image',
				'metafield',
				'published',
				'published_scope',
				'sort_order',
				'published_at',
				'updated_at'
			];

		}


		public function collections_smart_old_cols() {

			return [
				'collection_id',
				'post_id',
				'title',
				'handle',
				'body_html',
				'image',
				'rules',
				'disjunctive',
				'sort_order',
				'published_at',
				'updated_at'
			];

		}


		public function images_old_cols() {

			return [
				'id',
				'product_id',
				'variant_ids',
				'src',
				'alt',
				'position',
				'created_at',
				'updated_at'
			];

		}


		public function tags_old_cols() {

			return [
				'tag_id',
				'product_id',
				'post_id',
				'tag'
			];

		}


		public function customers_old_cols() {

			return [
				'id',
				'email',
				'accepts_marketing',
				'created_at',
				'updated_at',
				'first_name',
				'last_name',
				'orders_count',
				'state',
				'total_spent',
				'last_order_id',
				'note',
				'verified_email',
				'multipass_identifier',
				'tax_exempt',
				'phone',
				'tags',
				'last_order_name',
				'default_address',
				'addresses'
			];

		}


		public function orders_old_cols() {

			return [
				'id',
				'customer_id',
				'email',
				'closed_at',
				'created_at',
				'updated_at',
				'number',
				'note',
				'token',
				'gateway',
				'total_price',
				'subtotal_price',
				'total_weight',
				'total_tax',
				'taxes_included',
				'currency',
				'financial_status',
				'confirmed',
				'total_discounts',
				'total_line_items_price',
				'cart_token',
				'buyer_accepts_marketing',
				'name',
				'referring_site',
				'landing_site',
				'cancelled_at',
				'cancel_reason',
				'total_price_usd',
				'checkout_token',
				'reference',
				'user_id',
				'location_id',
				'source_identifier',
				'source_url',
				'processed_at',
				'device_id',
				'phone',
				'customer_locale',
				'app_id',
				'browser_ip',
				'landing_site_ref',
				'order_number',
				'discount_codes',
				'note_attributes',
				'payment_gateway_names',
				'processing_method',
				'checkout_id',
				'source_name',
				'fulfillment_status',
				'tax_lines',
				'tags',
				'contact_email',
				'order_status_url',
				'line_items',
				'shipping_lines',
				'billing_address',
				'shipping_address',
				'fulfillments',
				'client_details',
				'refunds',
				'customer'
			];

		}


    /*

		DB_Products migrate insert into query

		This function returns an integer value indicating the number of
		rows affected/selected for SELECT, INSERT, DELETE, UPDATE, etc.
		If a MySQL error is encountered, the function will return FALSE.

		Note that since both 0 and FALSE may be returned for row queries,
		you should be careful when checking the return value. Use the
		identity operator (===) to check for errors (e.g., false === $result),
		and whether any rows were affected (e.g., 0 === $result).

		We've checked he result of returning 0 and it passes our test.

		*/
		public function products_insert_into_query() {

			return $this->DB_Products->query(
				$this->DB_Products->build_insert_into_query(
					$this->products_old_cols()
				)
			);

		}


    /*

		Migrate insert into query

		*/
		public function variants_insert_into_query() {

			return $this->DB_Variants->query(
				$this->DB_Variants->build_insert_into_query(
					$this->variants_old_cols()
				)
			);

		}


    /*

		Migrate insert into query

		*/
		public function collects_insert_into_query() {

			return $this->DB_Collects->query(
				$this->DB_Collects->build_insert_into_query(
					$this->collects_old_cols()
				)
			);

		}


    /*

    Migrate insert into query

    */
    public function options_insert_into_query() {

      return $this->DB_Options->query(
				$this->DB_Options->build_insert_into_query(
					$this->options_old_cols()
				)
			);

    }


    /*

    Migrate insert into query

    */
    public function collections_custom_insert_into_query() {

      return $this->DB_Collections_Custom->query(
				$this->DB_Collections_Custom->build_insert_into_query(
					$this->collections_custom_old_cols()
				)
			);

    }


    /*

    Migrate insert into query

    */
    public function collections_smart_insert_into_query() {

      return $this->DB_Collections_Smart->query(
				$this->DB_Collections_Smart->build_insert_into_query(
					$this->collections_smart_old_cols()
				)
			);

    }


    /*

    Migrate insert into query

    */
    public function images_insert_into_query() {

      return $this->DB_Images->query(
				$this->DB_Images->build_insert_into_query(
					$this->images_old_cols()
				)
			);

    }


    /*

    Migrate insert into query

    */
    public function tags_insert_into_query() {

      return $this->DB_Tags->query(
				$this->DB_Tags->build_insert_into_query(
					$this->tags_old_cols()
				)
			);

    }


    /*

    Migrate insert into query

    */
    public function customers_insert_into_query() {

      return $this->DB_Customers->query(
				$this->DB_Customers->build_insert_into_query(
					$this->customers_old_cols()
				)
			);

    }


    /*

    Migrate insert into query

    */
    public function orders_insert_into_query() {

      return $this->DB_Orders->query(
				$this->DB_Orders->build_insert_into_query(
					$this->orders_old_cols()
				)
			);

    }


		/*

		If this function returns an empty array, then we know that it succeded.
		Will return an array of errors if any one of these fail.

		Tested

		*/
    public function run_insert_to_queries() {

      $insert_to_queries_results = [];

      $insert_to_queries_results['DB_Products'] = $this->products_insert_into_query();
      $insert_to_queries_results['DB_Variants'] = $this->variants_insert_into_query();
      $insert_to_queries_results['DB_Collects'] = $this->collects_insert_into_query();
      $insert_to_queries_results['DB_Options'] = $this->options_insert_into_query();
      $insert_to_queries_results['DB_Collections_Custom'] = $this->collections_custom_insert_into_query();
      $insert_to_queries_results['DB_Collections_Smart'] = $this->collections_smart_insert_into_query();
      $insert_to_queries_results['DB_Images'] = $this->images_insert_into_query();
      $insert_to_queries_results['DB_Tags'] = $this->tags_insert_into_query();


      return Utils::return_only_errors($insert_to_queries_results);

    }



    public function delete_old_tables() {

      $delete_old_tables_results = [];

      $delete_old_tables_results['DB_Products'] = $this->DB_Products->delete_table();
      $delete_old_tables_results['DB_Variants'] = $this->DB_Variants->delete_table();
      $delete_old_tables_results['DB_Collects'] = $this->DB_Collects->delete_table();
      $delete_old_tables_results['DB_Options'] = $this->DB_Options->delete_table();
      $delete_old_tables_results['DB_Collections_Custom'] = $this->DB_Collections_Custom->delete_table();
      $delete_old_tables_results['DB_Collections_Smart'] = $this->DB_Collections_Smart->delete_table();
      $delete_old_tables_results['DB_Images'] = $this->DB_Images->delete_table();
      $delete_old_tables_results['DB_Tags'] = $this->DB_Tags->delete_table();


      return Utils::return_only_errors($delete_old_tables_results);

    }


    public function rename_migration_tables() {

      $rename_migration_tables_results = [];

      $rename_migration_tables_results['DB_Products'] = $this->DB_Products->rename_migration_table();
      $rename_migration_tables_results['DB_Variants'] = $this->DB_Variants->rename_migration_table();
      $rename_migration_tables_results['DB_Collects'] = $this->DB_Collects->rename_migration_table();
      $rename_migration_tables_results['DB_Options'] = $this->DB_Options->rename_migration_table();
      $rename_migration_tables_results['DB_Collections_Custom'] = $this->DB_Collections_Custom->rename_migration_table();
      $rename_migration_tables_results['DB_Collections_Smart'] = $this->DB_Collections_Smart->rename_migration_table();
      $rename_migration_tables_results['DB_Images'] = $this->DB_Images->rename_migration_table();
      $rename_migration_tables_results['DB_Tags'] = $this->DB_Tags->rename_migration_table();


      return Utils::return_only_error_messages( Utils::return_only_errors($rename_migration_tables_results) );

    }


    public function run_table_migration_122() {

      if (!Utils::valid_backend_nonce($_POST['nonce'])) {
        wp_send_json_error($this->Messages->message_nonce_invalid  . ' (migrate_tables)');
      }


			$create_tables_result = $this->create_migration_db_tables(WPS_TABLE_MIGRATION_SUFFIX);

			if ( Utils::array_not_empty($create_tables_result) ) {
				Transients::delete_all_cache();
				wp_send_json_error($create_tables_result);
			}

			$insert_queries_result = $this->run_insert_to_queries();

			if ( Utils::array_not_empty($insert_queries_result) ) {
				Transients::delete_all_cache();
				wp_send_json_error($insert_queries_result);
			}

			$delete_old_tables_result = $this->delete_old_tables();

			if ( Utils::array_not_empty($delete_old_tables_result) ) {
				Transients::delete_all_cache();
				wp_send_json_error($delete_old_tables_result);
			}

			$rename_tables_result = $this->rename_migration_tables();

			if ( Utils::array_not_empty($rename_tables_result) ) {
				Transients::delete_all_cache();
				wp_send_json_error($rename_tables_result);
			}
			

			Transients::delete_all_cache();
			delete_option('wp_shopify_migration_needed');

			wp_send_json_success();

		}


    public function hooks() {

      add_action('wp_ajax_run_table_migration_122', [$this, 'run_table_migration_122']);
			add_action('wp_ajax_nopriv_run_table_migration_122', [$this, 'run_table_migration_122']);

    }


    public function init() {
      $this->hooks();
    }


  }

}
