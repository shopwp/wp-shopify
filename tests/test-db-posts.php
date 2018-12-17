<?php

use WPS\Factories;

/*

Tests DB_Posts

*/
class Test_DB_Posts extends WP_UnitTestCase {

	protected static $DB_Posts;
	protected static $mock_product;

  static function wpSetUpBeforeClass() {

		self::$DB_Posts					= Factories\DB\Posts_Factory::build();
		self::$mock_product     = json_decode( file_get_contents( dirname(__FILE__) . "/mock-data/_common/product.json") );

  }


	function test_it_should_delete_posts_by_ids() {

		$post_id = $this->factory->post->create([
			'meta_input' => [
				'product_id' => self::$mock_product->id
			]
		]);

		$delete_result = self::$DB_Posts->delete_posts_by_ids($post_id);

		$this->assertNotWPError($delete_result);
		$this->assertGreaterThan(0, $delete_result);

	}

}
