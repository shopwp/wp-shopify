<?php

use WPS\Factories\Webhooks_Factory;

/*

Tests Webhooks functions

*/
class Test_Webhooks extends WP_UnitTestCase {

	protected static $Webhooks;

  static function wpSetUpBeforeClass() {
		self::$Webhooks = Webhooks_Factory::build();
  }


	function test_it_should_get_callback_name_from_topic_products_create() {
		$this->assertEquals( 'products_create_callback', self::$Webhooks->get_callback_name_from_topic('products/create') );
	}

	function test_it_should_get_callback_name_from_topic_products_update() {
		$this->assertEquals( 'products_update_callback', self::$Webhooks->get_callback_name_from_topic('products/update') );
	}

	function test_it_should_get_callback_name_from_topic_products_delete() {
		$this->assertEquals( 'products_delete_callback', self::$Webhooks->get_callback_name_from_topic('products/delete') );
	}

	function test_it_should_get_callback_name_from_topic_collections_create() {
		$this->assertEquals( 'collections_create_callback', self::$Webhooks->get_callback_name_from_topic('collections/create') );
	}

	function test_it_should_get_callback_name_from_topic_collections_update() {
		$this->assertEquals( 'collections_update_callback', self::$Webhooks->get_callback_name_from_topic('collections/update') );
	}

	function test_it_should_get_callback_name_from_topic_collections_delete() {
		$this->assertEquals( 'collections_delete_callback', self::$Webhooks->get_callback_name_from_topic('collections/delete') );
	}

	function test_it_should_get_callback_name_from_topic_shop_update() {
		$this->assertEquals( 'shop_update_callback', self::$Webhooks->get_callback_name_from_topic('shop/update') );
	}

	function test_it_should_get_callback_name_from_topic_app_uninstalled() {
		$this->assertEquals( 'app_uninstalled_callback', self::$Webhooks->get_callback_name_from_topic('app/uninstalled') );
	}

	function test_it_should_get_callback_name_from_topic_checkouts_create() {
		$this->assertEquals( 'checkouts_create_callback', self::$Webhooks->get_callback_name_from_topic('checkouts/create') );
	}

	function test_it_should_get_callback_name_from_topic_checkouts_delete() {
		$this->assertEquals( 'checkouts_delete_callback', self::$Webhooks->get_callback_name_from_topic('checkouts/delete') );
	}

	function test_it_should_get_callback_name_from_topic_checkouts_update() {
		$this->assertEquals( 'checkouts_update_callback', self::$Webhooks->get_callback_name_from_topic('checkouts/update') );
	}

	function test_it_should_get_callback_name_from_topic_orders_create() {
		$this->assertEquals( 'orders_create_callback', self::$Webhooks->get_callback_name_from_topic('orders/create') );
	}

	function test_it_should_get_callback_name_from_topic_orders_paid() {
		$this->assertEquals( 'orders_paid_callback', self::$Webhooks->get_callback_name_from_topic('orders/paid') );
	}

	function test_it_should_get_callback_name_from_topic_orders_cancelled() {
		$this->assertEquals( 'orders_cancelled_callback', self::$Webhooks->get_callback_name_from_topic('orders/cancelled') );
	}

	function test_it_should_get_callback_name_from_topic_orders_delete() {
		$this->assertEquals( 'orders_delete_callback', self::$Webhooks->get_callback_name_from_topic('orders/delete') );
	}

	function test_it_should_get_callback_name_from_topic_orders_fulfilled() {
		$this->assertEquals( 'orders_fulfilled_callback', self::$Webhooks->get_callback_name_from_topic('orders/fulfilled') );
	}

	function test_it_should_get_callback_name_from_topic_orders_partially_fulfilled() {
		$this->assertEquals( 'orders_partially_fulfilled_callback', self::$Webhooks->get_callback_name_from_topic('orders/partially_fulfilled') );
	}

	function test_it_should_get_callback_name_from_topic_orders_updated() {
		$this->assertEquals( 'orders_updated_callback', self::$Webhooks->get_callback_name_from_topic('orders/updated') );
	}

	function test_it_should_get_callback_name_from_topic_draft_orders_create() {
		$this->assertEquals( 'draft_orders_create_callback', self::$Webhooks->get_callback_name_from_topic('draft_orders/create') );
	}

	function test_it_should_get_callback_name_from_topic_draft_orders_delete() {
		$this->assertEquals( 'draft_orders_delete_callback', self::$Webhooks->get_callback_name_from_topic('draft_orders/delete') );
	}

	function test_it_should_get_callback_name_from_topic_draft_orders_update() {
		$this->assertEquals( 'draft_orders_update_callback', self::$Webhooks->get_callback_name_from_topic('draft_orders/update') );
	}

	function test_it_should_get_callback_name_from_topic_order_transactions_create() {
		$this->assertEquals( 'order_transactions_create_callback', self::$Webhooks->get_callback_name_from_topic('order_transactions/create') );
	}

	function test_it_should_get_callback_name_from_topic_customers_create() {
		$this->assertEquals( 'customers_create_callback', self::$Webhooks->get_callback_name_from_topic('customers/create') );
	}

	function test_it_should_get_callback_name_from_topic_customers_delete() {
		$this->assertEquals( 'customers_delete_callback', self::$Webhooks->get_callback_name_from_topic('customers/delete') );
	}

	function test_it_should_get_callback_name_from_topic_customers_disable() {
		$this->assertEquals( 'customers_disable_callback', self::$Webhooks->get_callback_name_from_topic('customers/disable') );
	}

	function test_it_should_get_callback_name_from_topic_customers_enable() {
		$this->assertEquals( 'customers_enable_callback', self::$Webhooks->get_callback_name_from_topic('customers/enable') );
	}

	function test_it_should_get_callback_name_from_topic_customers_update() {
		$this->assertEquals( 'customers_update_callback', self::$Webhooks->get_callback_name_from_topic('customers/update') );
	}




	/*

	Webhook callback: When checkout-create is fired ...

	*/
	function test_it_should_run_on_checkouts_create_callback() {

		add_action('wps_on_checkout_create', function($checkout) {
			$this->assertEquals(1, $checkout);
		});

		self::$Webhooks->on_checkout_create(1);

	}


	/*

	Webhook callback: When checkout-update is fired ...

	*/
	function test_it_should_run_on_checkouts_delete_callback() {

		add_action('wps_on_checkout_delete', function($checkout) {
			$this->assertEquals(1, $checkout);
		});

		self::$Webhooks->on_checkout_delete(1);

	}


	/*

	Webhook callback: When checkout-update is fired ...

	*/
	function test_it_should_run_on_checkouts_update_callback() {

		add_action('wps_on_checkout_update', function($checkout) {
			$this->assertEquals(1, $checkout);
		});

		self::$Webhooks->on_checkout_update(1);

	}


	/*

	Webhook callback: When app-uninstall is fired ...

	*/
	function test_it_should_run_on_app_uninstall_callback() {

		add_action('wps_on_app_uninstall', function($shop) {
			$this->assertEquals(1, $shop);
		});

		self::$Webhooks->on_app_uninstall(1);

	}


	/*

	Webhook callback: after app-uninstall work is done

	*/
	function test_it_should_run_after_app_uninstall_callback() {

		add_action('wps_after_app_uninstall', function($shop) {
			$this->assertEquals(1, $shop);
		});

		self::$Webhooks->after_app_uninstall(1);

	}


	/*

	Webhook callback: When collections-create is fired ...

	*/
	function test_it_should_run_on_collections_create_callback() {

		add_action('wps_on_collections_create', function($collection) {
			$this->assertEquals(1, $collection);
		});

		self::$Webhooks->on_collections_create(1);

	}


	/*

	Webhook callback: After collections-create is done ...

	*/
	function test_it_should_run_after_collections_create_callback() {

		add_action('wps_after_collections_create', function($collection) {
			$this->assertEquals(1, $collection);
		});

		self::$Webhooks->after_collections_create(1);

	}


	/*

	Webhook callback: When collections-delete is fired ...

	*/
	function test_it_should_run_on_collections_delete_callback() {

		add_action('wps_on_collections_delete', function($collection) {
			$this->assertEquals(1, $collection);
		});

		self::$Webhooks->on_collections_delete(1);

	}


	/*

	Webhook callback: After collections-delete work is done ...

	*/
	function test_it_should_run_after_collections_delete_callback() {

		add_action('wps_after_collections_delete', function($collection) {
			$this->assertEquals(1, $collection);
		});

		self::$Webhooks->after_collections_delete(1);

	}


	/*

	Webhook callback: When collections-update is fired ...

	*/
	function test_it_should_run_on_collections_update_callback() {

		add_action('wps_on_collections_update', function($collection) {
			$this->assertEquals(1, $collection);
		});

		self::$Webhooks->on_collections_update(1);

	}


	/*

	Webhook callback: After collections-update work is done

	*/
	function test_it_should_run_after_collection_update_callback() {

		add_action('wps_after_collection_update', function($collection) {
			$this->assertEquals(1, $collection);
		});

		self::$Webhooks->after_collection_update(1);

	}


	/*

	Webhook callback: When customer-create is fired ...

	*/
	function test_it_should_run_on_customer_create_callback() {

		add_action('wps_on_customer_create', function($customer) {
			$this->assertEquals(1, $customer);
		});

		self::$Webhooks->on_customer_create(1);

	}


	/*

	Webhook callback: wps_after_customer_create

	*/
	function test_it_should_run_after_customer_create_callback() {

		add_action('wps_after_customer_create', function($customer) {
			$this->assertEquals(1, $customer);
		});

		self::$Webhooks->after_customer_create(1);

	}


	/*

	Webhook callback: When customer-create is fired ...

	*/
	function test_it_should_run_on_customer_delete_callback() {

		add_action('wps_on_customer_delete', function($customer) {
			$this->assertEquals(1, $customer);
		});

		self::$Webhooks->on_customer_delete(1);

	}


	/*

	Webhook callback: after customer-create work is done

	*/
	function test_it_should_run_after_customer_delete_callback() {

		add_action('wps_after_customer_delete', function($customer) {
			$this->assertEquals(1, $customer);
		});

		self::$Webhooks->after_customer_delete(1);

	}


	/*

	Webhook callback: When customer-create is fired ...

	*/
	function test_it_should_run_on_customer_disable_callback() {

		add_action('wps_on_customer_disable', function($customer) {
			$this->assertEquals(1, $customer);
		});

		self::$Webhooks->on_customer_disable(1);

	}


	/*

	Webhook callback: After customer-create is done

	*/
	function test_it_should_run_after_customer_disable_callback() {

		add_action('wps_after_customer_disable', function($customer) {
			$this->assertEquals(1, $customer);
		});

		self::$Webhooks->after_customer_disable(1);

	}


	/*

	Webhook callback: When customer-create is fired ...

	*/
	function test_it_should_run_on_customer_enable_callback() {

		add_action('wps_on_customer_enable', function($customer) {
			$this->assertEquals(1, $customer);
		});

		self::$Webhooks->on_customer_enable(1);

	}


	/*

	Webhook callback: After customer-create work is done

	*/
	function test_it_should_run_after_customer_enable_callback() {

		add_action('wps_after_customer_enable', function($customer) {
			$this->assertEquals(1, $customer);
		});

		self::$Webhooks->after_customer_enable(1);

	}


	/*

	Webhook callback: When customer-create is fired ...

	*/
	function test_it_should_run_on_customer_update_callback() {

		add_action('wps_on_customer_update', function($customer) {
			$this->assertEquals(1, $customer);
		});

		self::$Webhooks->on_customer_update(1);

	}


	/*

	Webhook callback: After customer-create work is done ...

	*/
	function test_it_should_run_after_customer_update_callback() {

		add_action('wps_after_customer_update', function($customer) {
			$this->assertEquals(1, $customer);
		});

		self::$Webhooks->after_customer_update(1);

	}


	/*

	Webhook callback: When customer-create is fired ...

	*/
	function test_it_should_run_on_order_cancelled_callback() {

		add_action('wps_on_order_cancelled', function($order) {
			$this->assertEquals(1, $order);
		});

		self::$Webhooks->on_order_cancelled(1);

	}


	/*

	Webhook callback: When customer-create is fired ...

	*/
	function test_it_should_run_after_order_cancelled_callback() {

		add_action('wps_after_order_cancelled', function($order) {
			$this->assertEquals(1, $order);
		});

		self::$Webhooks->after_order_cancelled(1);

	}


	/*

	Webhook callback: When order-create is fired ...

	*/
	function test_it_should_run_on_order_create_callback() {

		add_action('wps_on_order_create', function($order) {
			$this->assertEquals(1, $order);
		});

		self::$Webhooks->on_order_create(1);

	}


	/*

	Webhook callback: When order-create is fired ...

	*/
	function test_it_should_run_after_order_create_callback() {

		add_action('wps_after_order_create', function($order) {
			$this->assertEquals(1, $order);
		});

		self::$Webhooks->after_order_create(1);

	}


	/*

	Webhook callback: When order-create is fired ...

	*/
	function test_it_should_run_on_order_delete_callback() {

		add_action('wps_on_order_delete', function($order) {
			$this->assertEquals(1, $order);
		});

		self::$Webhooks->on_order_delete(1);

	}


	/*

	Webhook callback: When order-create is fired ...

	*/
	function test_it_should_run_after_order_delete_callback() {

		add_action('wps_after_order_delete', function($order) {
			$this->assertEquals(1, $order);
		});

		self::$Webhooks->after_order_delete(1);

	}


	/*

	Webhook callback: When order-create is fired ...

	*/
	function test_it_should_run_on_order_draft_create_callback() {

		add_action('wps_on_order_draft_create', function($order) {
			$this->assertEquals(1, $order);
		});

		self::$Webhooks->on_order_draft_create(1);

	}


	/*

	Webhook callback:

	*/
	function test_it_should_run_after_order_draft_create_callback() {

		add_action('wps_after_order_draft_create', function($order) {
			$this->assertEquals(1, $order);
		});

		self::$Webhooks->after_order_draft_create(1);

	}


	/*

	Webhook callback: When order-create is fired ...

	*/
	function test_it_should_run_on_order_draft_delete_callback() {

		add_action('wps_on_order_draft_delete', function($order) {
			$this->assertEquals(1, $order);
		});

		self::$Webhooks->on_order_draft_delete(1);

	}


	/*

	Webhook callback: When order-create is fired ...

	*/
	function test_it_should_run_after_order_draft_delete_callback() {

		add_action('wps_after_order_draft_delete', function($order) {
			$this->assertEquals(1, $order);
		});

		self::$Webhooks->after_order_draft_delete(1);

	}


	/*

	Webhook callback: When order-create is fired ...

	*/
	function test_it_should_run_on_order_draft_update_callback() {

		add_action('wps_on_order_draft_update', function($order) {
			$this->assertEquals(1, $order);
		});

		self::$Webhooks->on_order_draft_update(1);

	}


	/*

	Webhook callback: When order-create is fired ...

	*/
	function test_it_should_run_after_order_draft_update_callback() {

		add_action('wps_after_order_draft_update', function($order) {
			$this->assertEquals(1, $order);
		});

		self::$Webhooks->after_order_draft_update(1);

	}


	/*

	Webhook callback: When order-create is fired ...

	*/
	function test_it_should_run_on_order_fulfilled_callback() {

		add_action('wps_on_order_fulfilled', function($order) {
			$this->assertEquals(1, $order);
		});

		self::$Webhooks->on_order_fulfilled(1);

	}


	/*

	Webhook callback: When order-create is fired ...

	*/
	function test_it_should_run_after_order_fulfilled_callback() {

		add_action('wps_after_order_fulfilled', function($order) {
			$this->assertEquals(1, $order);
		});

		self::$Webhooks->after_order_fulfilled(1);

	}


	/*

	Webhook callback: When order-create is fired ...

	*/
	function test_it_should_run_on_order_paid_callback() {

		add_action('wps_on_order_paid', function($order) {
			$this->assertEquals(1, $order);
		});

		self::$Webhooks->on_order_paid(1);

	}


	/*

	Webhook callback: When order-create is fired ...

	*/
	function test_it_should_run_after_order_paid_callback() {

		add_action('wps_after_order_paid', function($order) {
			$this->assertEquals(1, $order);
		});

		self::$Webhooks->after_order_paid(1);

	}


	/*

	Webhook callback: When order-create is fired ...

	*/
	function test_it_should_run_on_order_partially_fulfilled_callback() {

		add_action('wps_on_order_partially_fulfilled', function($order) {
			$this->assertEquals(1, $order);
		});

		self::$Webhooks->on_order_partially_fulfilled(1);

	}


	/*

	Webhook callback: wps_after_order_partially_fulfilled

	*/
	function test_it_should_run_after_order_partially_fulfilled_callback() {

		add_action('wps_after_order_partially_fulfilled', function($order) {
			$this->assertEquals(1, $order);
		});

		self::$Webhooks->after_order_partially_fulfilled(1);

	}


	/*

	Webhook callback: When order-create is fired ...

	*/
	function test_it_should_run_on_order_transactions_create_callback() {

		add_action('wps_on_order_transactions_create', function($order) {
			$this->assertEquals(1, $order);
		});

		self::$Webhooks->on_order_transactions_create(1);

	}


	/*

	Webhook callback: wps_after_order_transactions_create

	*/
	function test_it_should_run_after_order_transactions_create_callback() {

		add_action('wps_after_order_transactions_create', function($order) {
			$this->assertEquals(1, $order);
		});

		self::$Webhooks->after_order_transactions_create(1);

	}


	/*

	Webhook callback: wps_on_order_updated

	*/
	function test_it_should_run_on_order_updated_callback() {

		add_action('wps_on_order_updated', function($order) {
			$this->assertEquals(1, $order);
		});

		self::$Webhooks->on_order_updated(1);

	}


	/*

	Webhook callback: wps_after_order_updated

	*/
	function test_it_should_run_after_order_updated_callback() {

		add_action('wps_after_order_updated', function($order) {
			$this->assertEquals(1, $order);
		});

		self::$Webhooks->after_order_updated(1);

	}


	/*

	Webhook callback: When product-create is fired ...

	*/
	function test_it_should_run_on_product_create_callback() {

		add_action('wps_on_product_create', function($product) {
			$this->assertEquals(1, $product);
		});

		self::$Webhooks->on_product_create(1);

	}


	/*

	Webhook callback: After product-create work is done ...

	*/
	function test_it_should_run_after_product_create_callback() {

		add_action('wps_after_product_create', function($product) {
			$this->assertEquals(1, $product);
		});

		self::$Webhooks->after_product_create(1);

	}


	/*

	Webhook callback: When product-delete is fired ...

	*/
	function test_it_should_run_on_product_delete_callback() {

		add_action('wps_on_product_delete', function($product) {
			$this->assertEquals(1, $product);
		});

		self::$Webhooks->on_product_delete(1);

	}


	/*

	Webhook callback: After product-delete work is done ...

	*/
	function test_it_should_run_after_product_delete_callback() {

		add_action('wps_after_product_delete', function($product) {
			$this->assertEquals(1, $product);
		});

		self::$Webhooks->after_product_delete(1);

	}


	/*

	Webhook callback: When product-update is fired ...

	*/
	function test_it_should_run_on_product_update_callback() {

		add_action('wps_on_product_update', function($product) {
			$this->assertEquals(1, $product);
		});

		self::$Webhooks->on_product_update(1);

	}


	/*

	Webhook callback: After product-update

	*/
	function test_it_should_run_after_product_update_callback() {

		add_action('wps_after_product_update', function($product) {
			$this->assertEquals(1, $product);
		});

		self::$Webhooks->after_product_update(1);

	}


	/*

	Webhook callback: When shop-update is fired ...

	*/
	function test_it_should_run_on_shop_update_callback() {

		add_action('wps_on_shop_update', function($shop) {
			$this->assertEquals(1, $shop);
		});

		self::$Webhooks->on_shop_update(1);

	}


	/*

	Webhook callback: wps_after_shop_update

	*/
	function test_it_should_run_after_shop_update_callback() {

		add_action('wps_after_shop_update', function($shop) {
			$this->assertEquals(1, $shop);
		});

		self::$Webhooks->after_shop_update(1);

	}







}
