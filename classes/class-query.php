<?php

namespace WPS;

use WPS\Utils;
use WPS\Options;

if (!defined('ABSPATH')) {
	exit;
}


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
		return $query->get('context') === 'wp_shopify_products_query';
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

				} else if ($is_collections_query) {

					// If Shortcode has attributes passed in ...
					if ($custom_filters) {
						$clauses = $this->construct_clauses_from_collections_shortcode($custom_filters, $query);

					} else {

						$clauses = $this->DB_Collections->get_default_collections_query($clauses);

					}

				}


				if ( empty($clauses['limits']) ) {

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

			if ( is_singular(WPS_COLLECTIONS_POST_TYPE_SLUG) ) {
				$args['is_single'] = true;

			} else {
				$args['is_single'] = false;

			}



			$collectionsQueryHash = Utils::hash($args, true);

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

	public function get_order_from_args($args) {

		if ( isset($args['orderby']) ) {
			$custom_order_by = $args['orderby'];

		} else if (isset($args['custom']['orderby'])) {
			$custom_order_by = $args['custom']['orderby'];

		} else {
			$custom_order_by = false;
		}

		return $custom_order_by;

	}


	public function add_feat_image_to_products($products) {

		// Adding feature imaged to object
		foreach ($products as $product) {
			$product->feat_image = $this->DB_Images->get_feat_image_by_post_id($product->post_id);
		}

		return $products;

	}


	public function current_product_post_id_matches($product, $current_post) {
		return (int) $product->post_id !== $current_post->ID;
	}

	public function exclude_current_product($products) {

		global $post;

		$products = array_filter($products, function($product, $key) use ($post) {
			return $this->current_product_post_id_matches($product, $post);
		}, ARRAY_FILTER_USE_BOTH);

		return $products;

	}


	public function showing_add_to_cart($args) {
		return isset($args['custom']['add-to-cart']) && $args['custom']['add-to-cart'];
	}






	/*

	Products Display Wrapper

	Responsible for feeding data into all product-related template partials

	*/
	public function wps_products_display($args, $customArgs) {

		if (!is_admin()) {

			$args['context'] = 'wp_shopify_products_query';

			if ( is_singular(WPS_PRODUCTS_POST_TYPE_SLUG) ) {
				$args['is_single'] = true;

			} else {
				$args['is_single'] = false;

			}


			$product_query_hash = Utils::hash($args, true);


			/*

			Here we're caching an entire WP_Query response by hashing the
			argument array. We can safely assume that a given set of args
			will always produce the same list of products if the
			product data doesn't change.

			Therefore it's important that we clear this cache whenever a
			product is updated, created, or deleted. OR whenever the plugin
			settings are updated.

			*/
			$product_query_hash_cache = Options::get('wp_shopify_products_query_hash_cache_' . $product_query_hash);


			if ( !empty($product_query_hash_cache) ) {
				$products_query = $product_query_hash_cache;

			} else {

				$products_query = new \WP_Query($args);

				if ( $this->get_order_from_args($args) !== 'rand' ) {
					Options::update('wp_shopify_products_query_hash_cache_' . $product_query_hash, $products_query);
				}

			}


			$products_cached = Options::get('wp_shopify_products_query_data_hash_' . $product_query_hash);


			if ( empty($products_cached) ) {

				if ( Utils::is_manually_sorted($args) ) {
					$products = Utils::manually_sort_posts_by_title($args['custom']['titles'], $products_query->posts);

				} else {
					$products = $products_query->posts;

				}

				$products = $this->add_feat_image_to_products($products);

				// Used for related products only. Filters products array to exclude the currently shown single product
				if ( is_singular(WPS_PRODUCTS_POST_TYPE_SLUG) ) {
					$products = $this->exclude_current_product($products);
				}

				Options::update('wp_shopify_products_query_data_hash_' . $product_query_hash, $products);


			} else {
				$products = $products_cached;
			}


			// Show add to cart button if add to cart is passed in
			if ( $this->showing_add_to_cart($args) ) {
				add_filter( 'wps_products_show_add_to_cart', function() { return true; });
			}

			$data = [
				'query'								=>	$products_query,
				'args'								=>	Utils::convert_array_to_object($args),
				'custom_args'					=>	isset($args['custom']) ? $args['custom'] : [],
				'amount_of_products'	=>	count($products),
				'products'						=>	$products,
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
	public function construct_slugs_clauses($shortcode_query, $slugs, $table_name) {

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
	public function construct_titles_clauses($shortcode_query, $titles, $table_name) {

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
	public function construct_tags_clauses($shortcode_query, $tags, $table_name) {

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
	public function construct_description_clauses($shortcode_query, $desc, $table_name) {

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
	public function construct_order_clauses($shortcode_query, $attrs) {

		// Defaults to DESC if nothing passed in
		if ( !isset($attrs['order']) ) {
			$shortcode_query['orderby'] .= ' DESC';

		} else {
			$shortcode_query['orderby'] .= ' ' . $attrs['order'];
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







	public function construct_clauses_method_name($type) {
		return 'construct_ ' . $type . ' _clauses';
	}


	public function maybe_filter_query_by($shortcode_query, $attrs, $type) {

		if ( array_key_exists($type, $attrs) ) {

			$type_filter = $this->construct_in_clause($attrs, $type);
			$construct_clauses = $this->construct_clauses_method_name($type);

			$shortcode_query = $this->$construct_clauses($shortcode_query, $type_filter, 'products');

		}

		return $shortcode_query;

	}




	/*

	Construct Clauses From Products Shortcode

	-- Called in related products as well

	*/
	public function construct_clauses_from_products_shortcode($attrs, $query) {

		$query_array = $this->DB_Products->get_default_products_query();

		/*

		Removes the default "menu_order" value if the user
		passes one via shortcode

		*/
		if (array_key_exists('custom', $query->query) && !empty($query->query['custom']['orderby'])) {
			$query_array['orderby'] = '';
		}


		// $query_array = $this->maybe_filter_query_by($query_array, $attrs, 'slugs');
		// $query_array = $this->maybe_filter_query_by($query_array, $attrs, 'titles');
		// $query_array = $this->maybe_filter_query_by($query_array, $attrs, 'tags');
		// $query_array = $this->maybe_filter_query_by($query_array, $attrs, 'vendors');
		// $query_array = $this->maybe_filter_query_by($query_array, $attrs, 'types');
		// $query_array = $this->maybe_filter_query_by($query_array, $attrs, 'ids');
		// $query_array = $this->maybe_filter_query_by($query_array, $attrs, 'options');
		// $query_array = $this->maybe_filter_query_by($query_array, $attrs, 'variants');
		// $query_array = $this->maybe_filter_query_by($query_array, $attrs, 'collections');
		// $query_array = $this->maybe_filter_query_by($query_array, $attrs, 'collection_slugs');
		// $query_array = $this->maybe_filter_query_by($query_array, $attrs, 'desc');


		/*

		Here we have to check all possible shortcode attributes

		*/
		if (array_key_exists('slugs', $attrs)) {
			$slugs = $this->construct_in_clause($attrs, 'slugs');
			$query_array = $this->construct_slugs_clauses($query_array, $slugs, 'products');
		}

		if (array_key_exists('titles', $attrs)) {
			$titles = $this->construct_in_clause($attrs, 'titles');
			$query_array = $this->construct_titles_clauses($query_array, $titles, 'products');
		}

		if (array_key_exists('tags', $attrs)) {
			$tags = $this->construct_in_clause($attrs, 'tags');
			$query_array = $this->construct_tags_clauses($query_array, $tags, 'products');
		}

		if (array_key_exists('vendors', $attrs)) {
			$vendors = $this->construct_in_clause($attrs, 'vendors');
			$query_array = $this->construct_vendors_clauses($query_array, $vendors, 'products');
		}

		if (array_key_exists('types', $attrs)) {
			$types = $this->construct_in_clause($attrs, 'types');
			$query_array = $this->construct_types_clauses($query_array, $types, 'products');
		}

		if (array_key_exists('desc', $attrs)) {
			$query_array = $this->construct_description_clauses($query_array, $attrs['desc'], 'products');
		}

		if (array_key_exists('collection_slugs', $attrs)) {
			$collection_slugs = $this->construct_in_clause($attrs, 'collection_slugs');
			$query_array = $this->construct_collection_slugs_clauses($query_array, $collection_slugs);
		}

		if (array_key_exists('collections', $attrs)) {

			$collections = $this->construct_in_clause($attrs, 'collections');

			$query_array = $this->construct_collections_clauses($query_array, $collections);

		}

		if (array_key_exists('variants', $attrs)) {
			$variants = $this->construct_in_clause($attrs, 'variants');
			$query_array = $this->construct_variants_clauses($query_array, $variants, 'products');
		}

		if (array_key_exists('options', $attrs)) {
			$options = $this->construct_in_clause($attrs, 'options');
			$query_array = $this->construct_options_clauses($query_array, $options, 'products');
		}

		if (array_key_exists('ids', $attrs)) {
			$ids = $this->construct_in_clause($attrs, 'ids');
			$query_array = $this->construct_ids_clauses($query_array, $ids, 'products');
		}



		if (array_key_exists('orderby', $attrs)) {

			// Default to products table
			$table_name_orderby = 'products';

			// Check if price is passed in, use variants table instead
			if ($attrs['orderby'] === 'price') {
				$table_name_orderby = 'variants';
			}

			// If manual is set we order elsewhere
			if ($attrs['orderby'] !== 'manual') {
				$query_array = $this->construct_orderby_clauses($query_array, $attrs['orderby'], $table_name_orderby);
			}

			// Order will always need to be set if orderby is also set
			$query_array = $this->construct_order_clauses($query_array, $attrs);

		}



		if (array_key_exists('limit', $attrs)) {
			$query_array = $this->construct_limit_clauses($query_array, $attrs['limit']);
		}


		return $query_array;


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
	public function construct_clauses_from_collections_shortcode($attrs, $query) {

		$sql = '';

		$shortcode_query = $this->DB_Collections->get_default_collections_query();


		if (array_key_exists('slugs', $attrs)) {
			$slugs = $this->construct_in_clause($attrs, 'slugs');
			$shortcode_query = $this->construct_slugs_clauses($shortcode_query, $slugs, 'collections');
		}


		if (array_key_exists('titles', $attrs)) {
			$titles = $this->construct_in_clause($attrs, 'titles');
			$shortcode_query = $this->construct_titles_clauses($shortcode_query, $titles, 'collections');
		}


		if (array_key_exists('desc', $attrs)) {
			$shortcode_query = $this->construct_description_clauses($shortcode_query, $attrs['desc'], 'collections');
		}


		if (array_key_exists('orderby', $attrs)) {

			if ($attrs['orderby'] !== 'manual') {
				$shortcode_query = $this->construct_orderby_clauses($shortcode_query, $attrs['orderby'], 'collections');
			}

			$shortcode_query = $this->construct_order_clauses($shortcode_query, $attrs);

		}


		if (array_key_exists('limit', $attrs)) {
			$shortcode_query = $this->construct_limit_clauses($shortcode_query, $attrs['limit']);
		}


		if (array_key_exists('ids', $attrs)) {
			$ids = $this->construct_in_clause($attrs, 'ids');
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
