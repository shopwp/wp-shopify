<?php

use WPS\Factories\Shopify_API_Factory;

/*

Tests Shopify_API functions

*/
class Test_Shopify_API extends WP_UnitTestCase {

	protected static $Shopify_API;

	static function wpSetUpBeforeClass() {

	  // Assemble
	  self::$Shopify_API = Shopify_API_Factory::build();

	}


	/*

	Params

	*/

	function test_it_should_return_param_limit() {

		$result = self::$Shopify_API->param_limit(250);

		$this->assertEquals('limit=250', $result);

	}


	function test_it_should_return_param_page() {

		$result = self::$Shopify_API->param_page(4);

		$this->assertEquals('page=4', $result);

	}


	function test_it_should_return_param_product_id() {

		$result = self::$Shopify_API->param_product_id('123456789');

		$this->assertEquals('product_id=123456789', $result);

	}


	function test_it_should_return_param_collection_id() {

		$result = self::$Shopify_API->param_collection_id('123456789');

		$this->assertEquals('collection_id=123456789', $result);

	}


	function test_it_should_return_param_status() {

		$result = self::$Shopify_API->param_status('any');

		$this->assertEquals('status=any', $result);

	}


	function test_it_should_return_param_ids() {

		$result = self::$Shopify_API->param_ids('123,456,789');

		$this->assertEquals('ids=123,456,789', $result);

	}




	/*

	URLs

	*/

	function test_it_should_get_products_per_page_url() {

		add_filter('wps_remote_request_url', function($url) {

			$this->assertEquals('https://wpslitetest10.myshopify.com/admin/products.json?ids=191283,221309,302934,409324,109235&limit=10000', $url);

			return false;

		}, 10, 2);

		self::$Shopify_API->get_products_per_page('191283,221309,302934,409324,109235', 10000);


	}


	/*

	Should return products only from a certain collection

	*/
	function test_it_should_get_products_from_collection_per_page_url() {

		add_filter('wps_remote_request_url', function($url) {

			$this->assertEquals('https://wpslitetest10.myshopify.com/admin/products.json?collection_id=1723981723&limit=250&page=4', $url);

			return false;

		}, 10, 2);

		self::$Shopify_API->get_products_from_collection_per_page(1723981723, 250, 4);

	}


	/*

	Should return collects only from a certain collection

	*/
	function test_it_should_get_collects_from_collection_per_page_url() {

		add_filter('wps_remote_request_url', function($url) {

			$this->assertEquals('https://wpslitetest10.myshopify.com/admin/collects.json?collection_id=12739180928423&limit=250&page=1', $url);

			return false;

		}, 10, 2);

		self::$Shopify_API->get_collects_from_collection_per_page(12739180928423, 250, 1);

	}


	/*

	Should return collects only from a certain collection

	*/
	function test_it_should_get_product_listings_count_url() {

		add_filter('wps_remote_request_url', function($url) {

			$this->assertEquals('https://wpslitetest10.myshopify.com/admin/product_listings/count.json', $url);

			return false;

		}, 10, 2);

		self::$Shopify_API->get_product_listings_count();

	}


	/*

	Should return url for product listing count per colection ids

	*/
	function test_it_should_get_product_listings_count_by_collection_id() {

		add_filter('wps_remote_request_url', function($url) {

			$this->assertEquals('https://wpslitetest10.myshopify.com/admin/product_listings/count.json?collection_id=12345', $url);

			return false;

		}, 10, 2);

		self::$Shopify_API->get_product_listings_count_by_collection_id(12345);

	}


	/*

	Should return url for product listing count per colection ids

	*/
	function test_it_should_get_webhooks_url() {

		add_filter('wps_remote_request_url', function($url) {

			$this->assertEquals('https://wpslitetest10.myshopify.com/admin/webhooks.json', $url);

			return false;

		}, 10, 2);

		self::$Shopify_API->get_webhooks();

	}


	/*

	Should return url for product listing count per colection ids

	*/
	function test_it_should_get_register_webhook_url() {

		add_filter('wps_remote_request_url', function($url, $request_params) {

			$this->assertEquals('https://wpslitetest10.myshopify.com/admin/webhooks.json', $url);
			$this->assertInternalType('array', $request_params);
			$this->assertArrayHasKey('body', $request_params);
			$this->assertArrayHasKey('timeout', $request_params);
			$this->assertArrayHasKey('blocking', $request_params);
			$this->assertArrayHasKey('headers', $request_params);
			$this->assertArrayHasKey('method', $request_params);

			return false;

		}, 10, 2);

		self::$Shopify_API->register_webhook(12345);

	}


	/*

	Should return url for product listing count per colection ids

	*/
	function test_it_should_get_delete_webhook_url() {

		add_filter('wps_remote_request_url', function($url) {

			$this->assertEquals('https://wpslitetest10.myshopify.com/admin/webhooks/12345.json', $url);

			return false;

		}, 10, 2);

		self::$Shopify_API->delete_webhook(12345);

	}


	/*

	Should return url for product listing count per colection ids

	*/
	function test_it_should_get_storefront_access_tokens_url() {

		add_filter('wps_remote_request_url', function($url) {

			$this->assertEquals('https://wpslitetest10.myshopify.com/admin/storefront_access_tokens.json', $url);

			return false;

		}, 10, 2);

		self::$Shopify_API->get_storefront_access_tokens();

	}


	/*

	Should return url for product listing count per colection ids

	*/
	function test_it_should_get_orders_per_page_url() {

		add_filter('wps_remote_request_url', function($url) {

			$this->assertEquals('https://wpslitetest10.myshopify.com/admin/orders.json?limit=250&page=10&status=any', $url);

			return false;

		}, 10, 2);

		self::$Shopify_API->get_orders_per_page(250, 10, 'any');

	}


	/*

	Should return url for product listing count per colection ids

	*/
	function test_it_should_get_orders_count_url() {

		add_filter('wps_remote_request_url', function($url) {

			$this->assertEquals('https://wpslitetest10.myshopify.com/admin/orders/count.json?status=any', $url);

			return false;

		}, 10, 2);

		self::$Shopify_API->get_orders_count('any');

	}


	/*

	Should return url for product listing count per colection ids

	*/
	function test_it_should_get_customers_per_page_url() {

		add_filter('wps_remote_request_url', function($url) {

			$this->assertEquals('https://wpslitetest10.myshopify.com/admin/customers.json?limit=250&page=4&status=any', $url);

			return false;

		}, 10, 2);

		self::$Shopify_API->get_customers_per_page(250, 4, 'any');

	}


	/*

	Should return url for product listing count per colection ids

	*/
	function test_it_should_get_customers_count_url() {

		add_filter('wps_remote_request_url', function($url) {

			$this->assertEquals('https://wpslitetest10.myshopify.com/admin/customers/count.json', $url);

			return false;

		}, 10, 2);

		self::$Shopify_API->get_customers_count();

	}


	/*

	Should return url for product listing count per colection ids

	*/
	function test_it_should_get_collects_count_by_collection_id_url() {

		add_filter('wps_remote_request_url', function($url) {

			$this->assertEquals('https://wpslitetest10.myshopify.com/admin/collects/count.json?collection_id=12345', $url);

			return false;

		}, 10, 2);

		self::$Shopify_API->get_collects_count_by_collection_id(12345);

	}


	/*

	Should return url for product listing count per colection ids

	*/
	function test_it_should_get_smart_collections_count_url() {

		add_filter('wps_remote_request_url', function($url) {

			$this->assertEquals('https://wpslitetest10.myshopify.com/admin/smart_collections/count.json', $url);

			return false;

		}, 10, 2);

		self::$Shopify_API->get_smart_collections_count();

	}


	/*

	Should return url for product listing count per colection ids

	*/
	function test_it_should_get_smart_collections_per_page_url() {

		add_filter('wps_remote_request_url', function($url) {

			$this->assertEquals('https://wpslitetest10.myshopify.com/admin/smart_collections.json?limit=250&page=2', $url);

			return false;

		}, 10, 2);

		self::$Shopify_API->get_smart_collections_per_page(250, 2);

	}


	/*

	Should return url for product listing count per colection ids

	*/
	function test_it_should_get_custom_collections_per_page_url() {

		add_filter('wps_remote_request_url', function($url) {

			$this->assertEquals('https://wpslitetest10.myshopify.com/admin/custom_collections.json?limit=250&page=2', $url);

			return false;

		}, 10, 2);

		self::$Shopify_API->get_custom_collections_per_page(250, 2);

	}


	/*

	Should return url for product listing count per colection ids

	*/
	function test_it_should_get_custom_collections_count_url() {

		add_filter('wps_remote_request_url', function($url) {

			$this->assertEquals('https://wpslitetest10.myshopify.com/admin/custom_collections/count.json', $url);

			return false;

		}, 10, 2);

		self::$Shopify_API->get_custom_collections_count();

	}


	/*

	Should return url for product listing count per colection ids

	*/
	function test_it_should_get_collects_from_collection_id_url() {

		add_filter('wps_remote_request_url', function($url) {

			$this->assertEquals('https://wpslitetest10.myshopify.com/admin/collects.json?collection_id=1234', $url);

			return false;

		}, 10, 2);

		self::$Shopify_API->get_collects_from_collection_id(1234);

	}


	/*

	Should return url for product listing count per colection ids

	*/
	function test_it_should_get_collects_by_product_id_url() {

		add_filter('wps_remote_request_url', function($url) {

			$this->assertEquals('https://wpslitetest10.myshopify.com/admin/collects.json?product_id=1234', $url);

			return false;

		}, 10, 2);

		self::$Shopify_API->get_collects_by_product_id(1234);

	}


	/*

	Should return url for product listing count per colection ids

	*/
	function test_it_should_get_shop() {

		add_filter('wps_remote_request_url', function($url) {

			$this->assertEquals('https://wpslitetest10.myshopify.com/admin/shop.json', $url);

			return false;

		}, 10, 2);

		self::$Shopify_API->get_shop();

	}


	/*

	Should return url for product listing count per colection ids

	*/
	function test_it_should_get_products_listing_product_ids_per_page_url() {

		add_filter('wps_remote_request_url', function($url) {

			$this->assertEquals('https://wpslitetest10.myshopify.com/admin/product_listings/product_ids.json?limit=10000&page=100', $url);

			return false;

		}, 10, 2);

		self::$Shopify_API->get_products_listing_product_ids_per_page(100, 10);

	}


	/*

	Should return url for product listing count per colection ids

	*/
	function test_it_should_get_products_listing_product_ids_by_collection_id_per_page_url() {

		add_filter('wps_remote_request_url', function($url) {

			$this->assertEquals('https://wpslitetest10.myshopify.com/admin/product_listings/product_ids.json?collection_id=128847234&limit=10000&page=1', $url);

			return false;

		}, 10, 2);

		self::$Shopify_API->get_products_listing_product_ids_by_collection_id_per_page(128847234, 1);

	}


	/*

	Should return url for product listing count per colection ids

	*/
	function test_it_should_get_collects_per_page_url() {

		add_filter('wps_remote_request_url', function($url) {

			$this->assertEquals('https://wpslitetest10.myshopify.com/admin/collects.json?limit=250&page=1', $url);

			return false;

		}, 10, 2);

		self::$Shopify_API->get_collects_per_page(250, 1);

	}



}
