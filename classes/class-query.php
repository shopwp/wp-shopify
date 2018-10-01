<?php

namespace WPS;

use WPS\Utils;
use WPS\Transients;

if (!defined('ABSPATH')) {
	exit;
}


if (!class_exists('Query')) {

	class Query {

		private $Template_loader;
		private $DB_Collections;
		private $DB_Settings_General;
		private $DB_Images;
		private $Pagination;
		private $DB_Products;


		public function __construct($Template_loader, $DB_Collections, $DB_Settings_General, $DB_Images, $Pagination, $DB_Products) {

			$this->Template_loader				= $Template_loader;
			$this->DB_Collections					= $DB_Collections;
			$this->DB_Settings_General		= $DB_Settings_General;
			$this->DB_Images							= $DB_Images;
			$this->Pagination							= $Pagination;
			$this->DB_Products						= $DB_Products;

    }


		/*

		Need to get pagination to work

		*/
		public function wps_content_pre_loop($query) {

			if ( is_admin() || $query->get('post_type') !== WPS_PRODUCTS_POST_TYPE_SLUG && $query->get('post_type') !== WPS_COLLECTIONS_POST_TYPE_SLUG ) {
				return;
			}

			/*

			Improves performance of related products query.
			More here -- https://kinsta.com/blog/wp-query/

			*/
			if ( $this->is_related_products_query($query) ) {
				$query->set('category_name', WPS_PLUGIN_TEXT_DOMAIN);
				$query->set('no_found_rows', true);
				$query->set('update_post_meta_cache', false);
				$query->set('update_post_term_cache', false);
			}

			$query->set('posts_per_page', $this->DB_Settings_General->get_num_posts());

			return $query;

		}



		public function is_products_query($query) {
			return $query->get('context') === 'wps_products_query';
		}

		public function is_collections_query($query) {
			return $query->get('context') === 'wps_collections_query';
		}

		public function is_related_products_query($query) {
			return $query->get('wps_related_products');
		}


		public function is_wp_shopify_query($query) {
			return $this->is_products_query($query) && $this->is_collections_query($query);
		}


		/*

		wps_clauses_mod

		*/
		public function wps_clauses_mod($clauses, $query) {

			// Only runs on the front-end
			if ( !is_admin() ) {

				global $post;

				$is_products_query = $this->is_products_query($query);
				$is_collections_query = $this->is_collections_query($query);

				if ($is_products_query || $is_collections_query) {

					$custom_filters = $query->get('custom');

					// If a products query ...
					if ($is_products_query) {

						// If using Shortcode or related products ...
						if ($custom_filters) {
							$clauses = $this->construct_clauses_from_products_shortcode($custom_filters, $query);

						} else {

							$clauses = $this->DB_Products->get_default_products_query();

						}

					}


					// If a collections query ...
					if ($is_collections_query) {

						// If Shortcode has attributes passed in ...
						if ($custom_filters) {
							$clauses = $this->construct_clauses_from_collections_shortcode($custom_filters, $query);

						} else {

							$clauses = $this->DB_Collections->get_default_collections_query($clauses);

						}

					}


					if ( empty($clauses['limits']) ) {

						/*

						This check is needed so as not to override any additional loops on the page.
						TODO: Do research to ensure more additional loops aren't affected

						*/
						if (isset($post->post_content)) {
							$content = $post->post_content;
						}

						$clauses['limits'] = $this->Pagination->construct_pagination_limits($query);

					}


					/*

					This forces the related products to _show_ in random order.
					TODO: Need the ability to allow customers to change.

					*/
					if ( $this->is_related_products_query($query) ) {


						/*

						All we're doing here is adding the order and orderby values to the query again.
						Since wps_clauses_mod runs last, it will override our previous settings.

						TODO: We should restrucutre this so we don't have add it twice

						*/
						if (isset($custom_filters['orderby']) && !empty($custom_filters['orderby'])) {

							if ($custom_filters['orderby'] === 'price') {
								$custom_filters['orderby'] = 'variants.price';
							}

							if (isset($custom_filters['order']) && $custom_filters['order']) {
								$custom_filters['orderby'] = $custom_filters['orderby'] . ' ' . $custom_filters['order'];
							}

							$clauses['orderby'] = $custom_filters['orderby'];


						} else {

							// If the user didn't set a custom orderby then use random by default
							$clauses['orderby'] = 'RAND()';

						}

					}

				}

			}


			return $clauses;


		}


		/*

		Collections Display Wrapper
		TODO: Combine with wps_products_display?

		Fires the wps_clauses_mod during WP_Query

		*/
		public function wps_collections_display($args, $customArgs) {

			if (!is_admin()) {

				$args['context'] = 'wps_collections_query';

				if (is_single()) {
					$args['is_single'] = true;

				} else {
					$args['is_single'] = false;

				}

				$collectionsQueryHash = md5(serialize($args));

				/*

				Here we're caching an entire WP_Query response by hashing the
				argument array. We can safely assume that a given set of args
				will always produce the same list of products assuming the
				product data doesn't change. Therefore it's important that we clear
				this cache whenever a product is updated, created, or deleted.

				*/
				if (get_transient('wps_collections_query_hash_cache_' . $collectionsQueryHash)) {
					$collectionsQuery = get_transient('wps_collections_query_hash_cache_' . $collectionsQueryHash);

				} else {

					$collectionsQuery = new \WP_Query($args);
					set_transient('wps_collections_query_hash_cache_' . $collectionsQueryHash, $collectionsQuery);

				}


				if (Utils::is_manually_sorted($args)) {
					$collections = Utils::manually_sort_posts_by_title($args['custom']['titles'], $collectionsQuery->posts);


				} else {
					$collections = $collectionsQuery->posts;

				}


				// Adding feature imaged to object
				foreach ($collections as $collection) {
					$collection->feat_image = $this->DB_Images->get_feat_image_by_post_id($collection->post_id);
				}


				$data = [
					'query'									=>	$collectionsQuery,
					'args'									=>	Utils::convert_array_to_object($args),
					'custom_args'						=>	isset($args['custom']) ? $args['custom'] : [],
					'amount_of_collections'	=>	count($collections),
					'collections'						=>	$collections,
					'settings'							=>  $this->DB_Settings_General->get()
				];

				return $this->Template_loader->set_template_data($data)->get_template_part( 'collections-all', 'display' );


			}

		}


		/*

		Products Display Wrapper

		Responsible for feeding data into all product-related template partials

		*/
		public function wps_products_display($args, $customArgs) {

			if (!is_admin()) {

				global $wpdb;
				global $post;

				$args['context'] = 'wps_products_query';

				if (is_single()) {
					$args['is_single'] = true;

				} else {
					$args['is_single'] = false;

				}


				$product_query_hash = md5(serialize($args));


				/*

				Here we're caching an entire WP_Query response by hashing the
				argument array. We can safely assume that a given set of args
				will always produce the same list of products if the
				product data doesn't change.

				Therefore it's important that we clear this cache whenever a
				product is updated, created, or deleted. OR whenever the plugin
				settings are updated.

				*/
				$product_query_hash_cache = Transients::get('wps_products_query_hash_cache_' . $product_query_hash);


		    if ( !empty($product_query_hash_cache) ) {

		      $products_query = $product_query_hash_cache;

		    } else {

					$products_query = new \WP_Query($args);

					if ( isset($args['orderby']) ) {
						$custom_order_by = $args['orderby'];

					} else if (isset($args['custom']['orderby'])) {
						$custom_order_by = $args['custom']['orderby'];

					} else {
						$custom_order_by = false;
					}

					if ( $custom_order_by !== 'rand' ) {
						Transients::set('wps_products_query_hash_cache_' . $product_query_hash, $products_query);
					}

		    }



				$wps_products_cached = Transients::get('wps_products_query_data_hash_' . $product_query_hash);


				if ( empty($wps_products_cached) ) {

					if ( Utils::is_collections_sorted($args) ) {

						$wps_products = Utils::sort_posts_by_position($products_query->posts);

					} else if ( Utils::is_manually_sorted($args) ) {
						$wps_products = Utils::manually_sort_posts_by_title($args['custom']['titles'], $products_query->posts);

					} else {
						$wps_products = $products_query->posts;
					}


					// Adding feature imaged to object
					foreach ($wps_products as $wps_product) {
			      $wps_product->feat_image = $this->DB_Images->get_feat_image_by_post_id($wps_product->post_id);
			    }


					/*

					Used for related products only. Filters products array to exclude the currently shown single product

					*/
					if (is_single()) {

						$wps_products = array_filter($wps_products, function($value, $key) use ($post) {
						    return (int) $value->post_id !== $post->ID;
						}, ARRAY_FILTER_USE_BOTH);

					}

					Transients::set('wps_products_query_data_hash_' . $product_query_hash, $wps_products);

				} else {
					$wps_products = $wps_products_cached;
				}

				// Show add to cart button if add to cart is passed in
				if (isset($args['custom']['add-to-cart']) && $args['custom']['add-to-cart']) {
					add_filter( 'wps_products_show_add_to_cart', function() { return true; });
				}


				$data = [
					'query'								=>	$products_query,
					'args'								=>	Utils::convert_array_to_object($args),
					'custom_args'					=>	isset($args['custom']) ? $args['custom'] : [],
					'amount_of_products'	=>	count($wps_products),
					'products'						=>	$wps_products,
					'settings'						=>  $this->DB_Settings_General->get()
				];

				return $this->Template_loader->set_template_data($data)->get_template_part( 'products-all', 'display' );


			}

		}


		/*

		Construct in clause

		*/
		public function construct_in_clause($shortcodeAttrs, $type) {

			$tags = '';
			$items = $shortcodeAttrs[$type];

			if (is_array($items)) {

				foreach ($items as $key => $tag) {
					$tags .= '"' . $tag . '", ';
				}

				$tags = substr($tags, 0, -2);


			} else {

				$tags .= '"' . $shortcodeAttrs[$type] . '"';

			}

			return $tags;

		}


		/*

		Construct Slug Clauses

		Expects $shortcode_query to be an array from posts_clauses

		*/
		public function construct_slug_clauses($shortcode_query, $slugs, $table_name) {

			if (is_array($table_name)) {

				$counter = 0;

				foreach ($table_name as $key => $table) {

					if ($counter === 0) {
						$shortcode_query['where'] .= ' AND ' . $table . '.handle IN (' . $slugs . ')';

					} else {
						$shortcode_query['where'] .= ' OR ' . $table . '.handle IN (' . $slugs . ')';
					}

					$counter++;

				}

			} else {
				$shortcode_query['where'] .= ' AND ' . $table_name . '.handle IN (' . $slugs . ')';
			}

			return $shortcode_query;

		}


		/*

		Construct Title Clauses

		Expects $shortcode_query to be an array from posts_clauses

		*/
		public function construct_title_clauses($shortcode_query, $titles, $table_name) {

			if (is_array($table_name)) {

				$counter = 0;

				foreach ($table_name as $key => $table) {

					if ($counter === 0) {
						$shortcode_query['where'] .= ' AND ' . $table . '.title IN (' . $titles . ')';

					} else {
						$shortcode_query['where'] .= ' OR ' . $table . '.title IN (' . $titles . ')';
					}

					$counter++;

				}

			} else {
				$shortcode_query['where'] .= ' AND ' . $table_name . '.title IN (' . $titles . ')';
			}

			return $shortcode_query;

		}


		/*

		Construct Tag Clauses

		Expects $shortcode_query to be an array from posts_clauses

		*/
		public function construct_tag_clauses($shortcode_query, $tags, $table_name) {

			if ( !empty($tags) ) {

				global $wpdb;

				$shortcode_query['where'] .= ' AND tags.tag IN (' . $tags . ')';
				$shortcode_query['join'] .= ' INNER JOIN ' . $wpdb->prefix . WPS_TABLE_NAME_TAGS . ' tags ON ' . $table_name . '.product_id = tags.product_id';

			}

			return $shortcode_query;

		}


		/*

		Construct Variants Clauses

		*/
		public function construct_variants_clauses($shortcode_query, $variants, $table_name) {

			if ( !empty($variants) ) {

				global $wpdb;

				$shortcode_query['where'] .= ' AND variants.title IN (' . $variants . ')';

				if (!$this->variants_already_joined($shortcode_query['join'])) {
					$shortcode_query['join'] .= ' INNER JOIN ' . $wpdb->prefix . WPS_TABLE_NAME_VARIANTS . ' variants ON ' . $table_name . '.product_id = variants.product_id';
				}

			}

			return $shortcode_query;

		}


		/*

		Construct Options Clauses

		*/
		public function construct_options_clauses($shortcode_query, $options, $table_name) {

			if (!empty($options)) {

				global $wpdb;

				$shortcode_query['where'] .= ' AND options.name IN (' . $options . ')';
				$shortcode_query['join'] .= ' INNER JOIN ' . $wpdb->prefix . WPS_TABLE_NAME_OPTIONS . ' options ON ' . $table_name . '.product_id = options.product_id';
			}

			return $shortcode_query;

		}


		/*

		Construct ids Clauses

		*/
		public function construct_ids_clauses($shortcode_query, $ids, $table_name) {

			if (is_array($table_name)) {

				$counter = 0;

				foreach ($table_name as $key => $table) {

					if ($counter === 0) {
						$shortcode_query['where'] .= ' AND ' . $table . '.collection_id IN (' . $ids . ')';

					} else {
						$shortcode_query['where'] .= ' OR ' . $table . '.collection_id IN (' . $ids . ')';
						$shortcode_query['where'] .= ' OR ' . $table . '.post_id IN (' . $ids . ')';
					}

					$counter++;

				}

			} else {
				$shortcode_query['where'] .= ' AND ' . $table_name . '.collection_id IN (' . $ids . ')';
				$shortcode_query['where'] .= ' OR ' . $table_name . '.post_id IN (' . $ids . ')';
			}

			return $shortcode_query;

		}


		/*

		Construct Vendors Clauses

		*/
		public function construct_vendors_clauses($shortcode_query, $vendors, $table_name) {

			if (!empty($vendors)) {
				$shortcode_query['where'] .= ' AND ' . $table_name . '.vendor IN (' . $vendors . ')';
			}

			return $shortcode_query;

		}


		/*

		Construct Types Clauses

		*/
		public function construct_types_clauses($shortcode_query, $types, $table_name) {

			if (!empty($types)) {
				$shortcode_query['where'] .= ' AND ' . $table_name . '.product_type IN (' . $types . ')';
			}

			return $shortcode_query;

		}


		/*

		Construct Desc Clauses

		*/
		public function construct_desc_clauses($shortcode_query, $desc, $table_name) {

			if (!empty($types)) {
				$shortcode_query['where'] .= ' AND ' . $table_name . '.body_html LIKE "%' . $desc . '%"';
			}

			return $shortcode_query;

		}


		/*

		Construct Collection Slugs Clauses

		*/
		public function construct_collection_slugs_clauses($shortcode_query, $slugs) {

			if (!empty($slugs)) {

				global $wpdb;

				$collections_smart_table_name 	= $wpdb->prefix . WPS_TABLE_NAME_COLLECTIONS_SMART;
				$collections_custom_table_name 	= $wpdb->prefix . WPS_TABLE_NAME_COLLECTIONS_CUSTOM;
				$collects_custom_table_name 		= $wpdb->prefix . WPS_TABLE_NAME_COLLECTS;

				$shortcode_query['where'] .= ' AND collection_id in (
				SELECT
				smart.collection_id
				FROM ' . $collections_smart_table_name . ' smart
				WHERE smart.handle IN (' . $slugs . ')

				UNION

				SELECT
				custom.collection_id
				FROM ' . $collections_custom_table_name  . ' custom
				WHERE custom.handle IN (' . $slugs . ')
				)';

				$shortcode_query['join'] .= ' INNER JOIN ' . $collects_custom_table_name . ' collects ON collects.product_id = products.product_id';
				$shortcode_query['fields'] .= ', collection_id';

			}

			return $shortcode_query;

		}


		/*

		Construct Collections Clauses

		*/
		public function construct_collections_clauses($shortcode_query, $slugs) {

			if ( !empty($slugs) ) {

				global $wpdb;

				$collections_smart_table_name 	= $wpdb->prefix . WPS_TABLE_NAME_COLLECTIONS_SMART;
				$collections_custom_table_name 	= $wpdb->prefix . WPS_TABLE_NAME_COLLECTIONS_CUSTOM;
				$collects_custom_table_name 		= $wpdb->prefix . WPS_TABLE_NAME_COLLECTS;

				$shortcode_query['where'] .= ' AND collection_id in (
				SELECT
				smart.collection_id
				FROM ' . $collections_smart_table_name . ' smart
				WHERE smart.title IN (' . $slugs . ')

				UNION

				SELECT
				custom.collection_id
				FROM ' . $collections_custom_table_name  . ' custom
				WHERE custom.title IN (' . $slugs . ')
				)';

				$shortcode_query['join'] .= ' INNER JOIN ' . $collects_custom_table_name . ' collects ON collects.product_id = products.product_id';
				$shortcode_query['fields'] .= ', collection_id, collects.position';

			}

			return $shortcode_query;

		}


		/*

		Construct Limit Clauses

		*/
		public function construct_limit_clauses($shortcode_query, $limit) {

			if (!empty($limit)) {
				$shortcode_query['limits'] = 'LIMIT 0, ' . $limit;
			}

			return $shortcode_query;

		}


		/*

		Construct Order Clauses

		*/
		public function construct_order_clauses($shortcode_query, $order) {

			if (isset($shortcode_query['orderby']) && $shortcode_query['orderby']) {
				$shortcode_query['orderby'] .= ' ' . $order;
			}

			return $shortcode_query;

		}


		/*

		Construct Order By Clauses

		*/
		public function construct_orderby_clauses($shortcode_query, $orderby, $table_name) {

			if (!empty($orderby)) {
				$shortcode_query['orderby'] .= $table_name . '.' . $orderby;
			}

			return $shortcode_query;

		}


		/*

		Construct Clauses From Products Shortcode

		-- Called in related products as well

		*/
		public function construct_clauses_from_products_shortcode($shortcodeAttrs, $query) {

			$sql = '';

			$shortcode_query = $this->DB_Products->get_default_products_query();

			/*

			Removes the default "menu_order" value if the user
			passes one via shortcode

			*/
			if (array_key_exists('custom', $query->query) && !empty($query->query['custom']['orderby'])) {
				$shortcode_query['orderby'] = '';
			}


			/*

			Here we have to loop through all the shortcode attributes that
			were passed in and check if they exist

			*/
			if (array_key_exists('slugs', $shortcodeAttrs)) {
				$slugs = $this->construct_in_clause($shortcodeAttrs, 'slugs');
				$shortcode_query = $this->construct_slug_clauses($shortcode_query, $slugs, 'products');
			}

			if (array_key_exists('titles', $shortcodeAttrs)) {
				$titles = $this->construct_in_clause($shortcodeAttrs, 'titles');
				$shortcode_query = $this->construct_title_clauses($shortcode_query, $titles, 'products');
			}

			if (array_key_exists('tags', $shortcodeAttrs)) {
				$tags = $this->construct_in_clause($shortcodeAttrs, 'tags');
				$shortcode_query = $this->construct_tag_clauses($shortcode_query, $tags, 'products');
			}

			if (array_key_exists('vendors', $shortcodeAttrs)) {
				$vendors = $this->construct_in_clause($shortcodeAttrs, 'vendors');
				$shortcode_query = $this->construct_vendors_clauses($shortcode_query, $vendors, 'products');
			}

			if (array_key_exists('types', $shortcodeAttrs)) {
				$types = $this->construct_in_clause($shortcodeAttrs, 'types');
				$shortcode_query = $this->construct_types_clauses($shortcode_query, $types, 'products');
			}

			if (array_key_exists('desc', $shortcodeAttrs)) {
				$shortcode_query = $this->construct_desc_clauses($shortcode_query, $shortcodeAttrs['desc'], 'products');
			}

			if (array_key_exists('collection_slugs', $shortcodeAttrs)) {
				$collection_slugs = $this->construct_in_clause($shortcodeAttrs, 'collection_slugs');
				$shortcode_query = $this->construct_collection_slugs_clauses($shortcode_query, $collection_slugs);
			}

			if (array_key_exists('collections', $shortcodeAttrs)) {
				$collections = $this->construct_in_clause($shortcodeAttrs, 'collections');
				$shortcode_query = $this->construct_collections_clauses($shortcode_query, $collections);
			}

			if (array_key_exists('variants', $shortcodeAttrs)) {
				$variants = $this->construct_in_clause($shortcodeAttrs, 'variants');
				$shortcode_query = $this->construct_variants_clauses($shortcode_query, $variants, 'products');
			}

			if (array_key_exists('options', $shortcodeAttrs)) {
				$options = $this->construct_in_clause($shortcodeAttrs, 'options');
				$shortcode_query = $this->construct_options_clauses($shortcode_query, $options, 'products');
			}

			if (array_key_exists('ids', $shortcodeAttrs)) {
				$ids = $this->construct_in_clause($shortcodeAttrs, 'ids');
				$shortcode_query = $this->construct_ids_clauses($shortcode_query, $ids, 'products');
			}

			if (array_key_exists('orderby', $shortcodeAttrs)) {

				// Default to products table
				$table_name_orderby = 'products';

				// Check if price is passed in, use variants table instead
				if ($shortcodeAttrs['orderby'] === 'price') {
					$table_name_orderby = 'variants';
				}

				// If manual is set we order elsewhere
				if ($shortcodeAttrs['orderby'] !== 'manual') {
					$shortcode_query = $this->construct_orderby_clauses($shortcode_query, $shortcodeAttrs['orderby'], $table_name_orderby);
				}

				// Order depends on order by
				if (array_key_exists('order', $shortcodeAttrs)) {
					$shortcode_query = $this->construct_order_clauses($shortcode_query, $shortcodeAttrs['order']);
				}

			}



			if (array_key_exists('limit', $shortcodeAttrs)) {
				$shortcode_query = $this->construct_limit_clauses($shortcode_query, $shortcodeAttrs['limit']);
			}


			return $shortcode_query;


		}


		/*

		Variants already joined

		*/
		public function variants_already_joined($joinStatement) {

			if (strpos($joinStatement, 'variants ON products.product_id = variants.product_id') !== false) {
				return true;

			} else {
				return false;
			}

		}


		/*

		Construct Clauses From Collections Custom Shortcode

		*/
		public function construct_clauses_from_collections_shortcode($shortcodeAttrs, $query) {

			$sql = '';

			$shortcode_query = $this->DB_Collections->get_default_collections_query();


			if (array_key_exists('slugs', $shortcodeAttrs)) {
				$slugs = $this->construct_in_clause($shortcodeAttrs, 'slugs');
				$shortcode_query = $this->construct_slug_clauses($shortcode_query, $slugs, 'collections');
			}


			if (array_key_exists('titles', $shortcodeAttrs)) {
				$titles = $this->construct_in_clause($shortcodeAttrs, 'titles');
				$shortcode_query = $this->construct_title_clauses($shortcode_query, $titles, 'collections');
			}


			if (array_key_exists('desc', $shortcodeAttrs)) {
				$shortcode_query = $this->construct_desc_clauses($shortcode_query, $shortcodeAttrs['desc'], 'collections');
			}


			if (array_key_exists('orderby', $shortcodeAttrs)) {

				if ($shortcodeAttrs['orderby'] !== 'manual') {
					$shortcode_query = $this->construct_orderby_clauses($shortcode_query, $shortcodeAttrs['orderby'], 'collections');
				}

				if (array_key_exists('order', $shortcodeAttrs)) {
					$shortcode_query = $this->construct_order_clauses($shortcode_query, $shortcodeAttrs['order']);
				}

			}


			if (array_key_exists('limit', $shortcodeAttrs)) {
				$shortcode_query = $this->construct_limit_clauses($shortcode_query, $shortcodeAttrs['limit']);
			}


			if (array_key_exists('ids', $shortcodeAttrs)) {
				$ids = $this->construct_in_clause($shortcodeAttrs, 'ids');
				$shortcode_query = $this->construct_ids_clauses($shortcode_query, $ids, 'collections');
			}

			return $shortcode_query;


		}


		/*

		Hooks

		*/
		public function hooks() {

			add_action('pre_get_posts',  [$this, 'wps_content_pre_loop']); // fired when creating posts loop
			add_filter('posts_clauses', [$this, 'wps_clauses_mod'], 10, 2); // fired when creating posts loop
			add_action('wps_collections_display', [$this, 'wps_collections_display'], 10, 2); // fired when creating posts loop
			add_action('wps_products_display', [$this, 'wps_products_display'], 10, 2); // fired when creating posts loop

		}


		/*

		Init

		*/
		public function init() {
			$this->hooks();
		}


	}

}
