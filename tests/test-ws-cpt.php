<?php

use WPS\Factories\WS_CPT_Factory;

/*

Tests Utils functions

*/
class Test_WS_CPT extends WP_UnitTestCase {

	protected static $WS_CPT;
	protected static $mock_product;

  static function setUpBeforeClass() {

		self::$WS_CPT								= WS_CPT_Factory::build();
		self::$mock_product        	= json_decode( file_get_contents( dirname(__FILE__) . "/mock-data/_common/product.json") );

  }


	function test_it_should_delete_posts_by_ids() {

		$post_id = $this->factory->post->create([
			'meta_input' => [
				'product_id' => self::$mock_product->id
			]
		]);

		$delete_result = self::$WS_CPT->delete_posts_by_ids($post_id);

		$this->assertNotWPError($delete_result);
		$this->assertGreaterThan(0, $delete_result);

	}

}
