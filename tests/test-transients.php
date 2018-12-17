<?php

use WPS\Transients;

/*

Tests Transients functions

*/
class Test_Transients extends WP_UnitTestCase {

	/*

	It should delete cached collection queries

	*/
	function test_it_should_delete_cached_collection_queries() {

		set_transient('wps_collections_query_hash_cache_123', ['this' => 'is', ['a' => 'collection']]);
		set_transient('wps_collections_query_hash_cache_456', ['this' => 'is', ['a' => 'collection']]);

		$result = Transients::delete_cached_collection_queries();

		$this->assertEquals(2, $result);

	}


	/*

	It should delete cached single collections

	*/
	function test_it_should_delete_cached_single_collections() {

		set_transient('wps_collection_single_123', ['this' => 'is', ['a' => 'collection']]);
		set_transient('wps_collection_single_456', ['this' => 'is', ['a' => 'collection']]);

		$result = Transients::delete_cached_single_collections();

		$this->assertEquals(2, $result);

	}


	/*

	It should delete delete cached single collection by id

	*/
	function test_it_should_delete_cached_single_collection_by_id() {

		set_transient('wps_collection_single_123', ['this' => 'is', ['a' => 'collection']]);

		$result = Transients::delete_cached_single_collection_by_id(123);

		$this->assertEquals(1, $result);

	}


	/*

	It should delete cached single product by id

	*/
	function test_it_should_delete_cached_single_product_by_id() {

		set_transient('wps_product_single_123', ['this' => 'is', ['a' => 'product']]);
		set_transient('wps_product_single_images_123', ['this' => 'is', ['a' => 'product']]);
		set_transient('wps_product_single_tags_123', ['this' => 'is', ['a' => 'product']]);
		set_transient('wps_product_single_variants_123', ['this' => 'is', ['a' => 'product']]);
		set_transient('wps_product_single_options_123', ['this' => 'is', ['a' => 'product']]);
		set_transient('wps_product_data_123', ['this' => 'is', ['a' => 'product']]);

		$result = Transients::delete_cached_single_product_by_id(123);

		$this->assertEquals(6, array_sum($result));

	}


	/*

	It should delete cached product queries

	*/
	function test_it_should_delete_cached_product_queries() {

		set_transient('wp_shopify_products_query_hash_cache_123', ['this' => 'is', ['a' => 'product']]);
		set_transient('wp_shopify_products_query_hash_cache_456', ['this' => 'is', ['a' => 'product']]);

		$result = Transients::delete_cached_product_queries();

		$this->assertEquals(2, $result);

	}


	/*

	It should delete cached prices

	*/
	function test_it_should_delete_cached_prices() {

		set_transient('wps_product_price_id_123', '<span>$100.00</span>');
		set_transient('wps_product_price_id_456', '<span>$100.00</span>');

		$result = Transients::delete_cached_prices();

		$this->assertEquals(2, $result);

	}


	/*

	It should delete cached variants

	*/
	function test_it_should_delete_cached_variants() {

		set_transient('wps_product_with_variants_123', ['this' => 'is', ['a' => 'product']]);
		set_transient('wps_product_with_variants_456', ['this' => 'is', ['a' => 'product']]);

		$result = Transients::delete_cached_variants();

		$this->assertEquals(2, $result);

	}


	/*

	It should delete cached product single

	*/
	function test_it_should_delete_cached_product_single() {

		set_transient('wps_product_single_123', ['this' => 'is', ['a' => 'product']]);
		set_transient('wps_product_single_456', ['this' => 'is', ['a' => 'product']]);

		$result = Transients::delete_cached_product_single();

		$this->assertEquals(2, $result);

	}


}
