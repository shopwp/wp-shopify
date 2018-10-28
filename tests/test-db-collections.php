<?php

use WPS\Factories\DB_Collections_Factory;

class Test_DB_Collections extends WP_UnitTestCase {

  protected static $DB_Collections;
  protected static $mock_posts_collections;
  protected static $mock_collection_to_delete;

  static function wpSetUpBeforeClass() {

    // Assemble
    self::$DB_Collections             = DB_Collections_Factory::build();
    self::$mock_posts_collections     = json_decode( file_get_contents( dirname(__FILE__) . "/mock-data/_common/posts-collections.json") );
    self::$mock_collection_to_delete  = json_decode( file_get_contents( dirname(__FILE__) . "/mock-data/collections/collection-to-delete.json") );

  }


  /*

  Should rename payload key to lookup key

  */
  function test_it_should_get_collections_from_posts() {

    $results = self::$DB_Collections->get_collections_from_posts(self::$mock_posts_collections);

    $results = array_filter($results, function($value, $key) {

      if (!empty($value['collection_id']) && !empty($value['post_id'])) {
        return $value;
      }

    }, ARRAY_FILTER_USE_BOTH);

    $this->assertCount(10, $results);

  }


  function test_it_should_find_post_id_from_collection_id() {

    $post_id = self::$DB_Collections->find_post_id_from_collection_id(self::$mock_collection_to_delete);

    $this->assertEquals(18367, $post_id);

  }


}
