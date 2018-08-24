<?php

/*

@description   Breadcrumbs

@version       1.0.0
@since         1.0.49
@path          templates/partials/pagination/breadcrumbs.php

@docs          https://wpshop.io/docs/templates/pagination/breadcrumbs

*/

// Settings
$separator          = __( apply_filters('wps_breadcrumbs_separator', '&gt;'), WPS_PLUGIN_TEXT_DOMAIN);
$breadcrums_id      = __( apply_filters('wps_breadcrumbs_id', 'wps-breadcrumbs'), WPS_PLUGIN_TEXT_DOMAIN);
$breadcrums_class   = __( apply_filters('wps_breadcrumbs_inner_class', 'wps-breadcrumbs-inner'), WPS_PLUGIN_TEXT_DOMAIN);
$home_title         = __( apply_filters('wps_breadcrumbs_home_text', 'Home'), WPS_PLUGIN_TEXT_DOMAIN);

// If you have any custom post types with custom taxonomies, put the taxonomy name below (e.g. product_cat)
$custom_taxonomy    = '';

// Get the query & post information
global $post, $wp_query;

// Do not display on the homepage
if ( !is_front_page() ) {

	// Build the breadcrums
	echo '<div class="wps-breadcrumbs ' . apply_filters('wps_breadcrumbs_class', '') . ' wps-row wps-contain"><ul id="' . $breadcrums_id . '" class="' . $breadcrums_class . '" itemscope itemtype="http://schema.org/BreadcrumbList">';

	// Home page
	echo '<li class="wps-breadcrumbs-item-home" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a class="wps-breadcrumbs-link wps-breadcrumbs-home" href="' . get_home_url() . '" title="' . $home_title . '" itemprop="item"><span class="wps-breadcrumbs-name" itemprop="name">' . $home_title . '</span></a></li>';
	echo '<li class="wps-breadcrumbs-separator wps-breadcrumbs-separator-home"> ' . $separator . ' </li>';

	if ( is_archive() && !is_tax() && !is_category() && !is_tag() ) {

		echo '<li class="wps-breadcrumbs-item-current wps-breadcrumbs-item-archive" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><strong class="wps-breadcrumbs-current wps-breadcrumbs-archive" itemprop="name">' . post_type_archive_title('', false) . '</strong></li>';

	} else if ( is_archive() && is_tax() && !is_category() && !is_tag() ) {

		// If post is a custom post type
		$post_type = get_post_type();

		// If it is a custom post type display name and link
		if ($post_type != 'post') {

			$post_type_object = get_post_type_object($post_type);
			$post_type_archive = get_post_type_archive_link($post_type);

			echo '<li class="wps-breadcrumbs-item-cat wps-breadcrumbs-item-custom-post-type-' . $post_type . '" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a class="wps-breadcrumbs-cat wps-breadcrumbs-custom-post-type-' . $post_type . '" href="' . $post_type_archive . '" title="' . $post_type_object->labels->name . '" itemprop="item"><span class="wps-breadcrumbs-name" itemprop="name">' . $post_type_object->labels->name . '</span></a></li>';
			echo '<li class="wps-breadcrumbs-separator"> ' . $separator . ' </li>';

		}

		$custom_tax_name = get_queried_object()->name;
		echo '<li class="wps-breadcrumbs-item-current wps-breadcrumbs-item-archive"><strong class="wps-breadcrumbs-current wps-breadcrumbs-archive" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">' . $custom_tax_name . '</strong></li>';

	} else if ( is_single() ) {

		// If post is a custom post type
		$post_type = get_post_type();

		// If it is a custom post type display name and link
		if ($post_type != 'post') {

			$post_type_object = get_post_type_object($post_type);
			$post_type_archive = get_post_type_archive_link($post_type);

			echo '<li class="wps-breadcrumbs-item-cat wps-breadcrumbs-item-custom-post-type-' . $post_type . '" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a class="wps-breadcrumbs-cat wps-breadcrumbs-custom-post-type-' . $post_type . '" href="' . $post_type_archive . '" title="' . $post_type_object->labels->name . '" itemprop="item"><span class="wps-breadcrumbs-name" itemprop="name">' . $post_type_object->labels->name . '</span></a></li>';
			echo '<li class="wps-breadcrumbs-separator"> ' . $separator . ' </li>';

		}

		echo '<li class="wps-breadcrumbs-item-current wps-breadcrumbs-item-' . $post->ID . '" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><strong class="wps-breadcrumbs-current wps-breadcrumbs-' . $post->ID . '" title="' . get_the_title() . '" itemprop="name">' . get_the_title() . '</strong></li>';

	} else if ( is_category() ) {

		// Category page
		echo '<li class="wps-breadcrumbs-item-current wps-breadcrumbs-item-cat" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><strong class="wps-breadcrumbs-current wps-breadcrumbs-cat" itemprop="name">' . single_cat_title('', false) . '</strong></li>';

	} else if ( is_page() ) {

		// Standard page
		if ( $post->post_parent ){

			// If child page, get parents
			$anc = get_post_ancestors( $post->ID );

			// Get parents in the right order
			$anc = array_reverse($anc);

			// Parent page loop
			if ( !isset( $parents ) ) $parents = null;
			foreach ( $anc as $ancestor ) {
					$parents .= '<li class="wps-breadcrumbs-item-parent wps-breadcrumbs-item-parent-' . $ancestor . '" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a class="wps-breadcrumbs-parent wps-breadcrumbs-parent-' . $ancestor . '" href="' . get_permalink($ancestor) . '" title="' . get_the_title($ancestor) . '" itemprop="item"><span class="wps-breadcrumbs-name" itemprop="name">' . get_the_title($ancestor) . '</span></a></li>';
					$parents .= '<li class="wps-breadcrumbs-separator wps-breadcrumbs-separator-' . $ancestor . '"> ' . $separator . ' </li>';
			}

			// Display parent pages
			echo $parents;

			// Current page
			echo '<li class="wps-breadcrumbs-item-current wps-breadcrumbs-item-' . $post->ID . '" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><strong title="' . get_the_title() . '" itemprop="name"> ' . get_the_title() . '</strong></li>';

		} else {

			// Just display current page if not parents
			echo '<li class="wps-breadcrumbs-item-current wps-breadcrumbs-item-' . $post->ID . '" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><strong class="wps-breadcrumbs-current wps-breadcrumbs-' . $post->ID . '" itemprop="name"> ' . get_the_title() . '</strong></li>';

		}

	} else if ( is_tag() ) {

		// Tag page

		// Get tag information
		$term_id        = get_query_var('tag_id');
		$taxonomy       = 'post_tag';
		$args           = 'include=' . $term_id;
		$terms          = get_terms( $taxonomy, $args );
		$get_term_id    = $terms[0]->term_id;
		$get_term_slug  = $terms[0]->slug;
		$get_term_name  = $terms[0]->name;

		// Display the tag name
		echo '<li class="wps-breadcrumbs-item-current wps-breadcrumbs-item-tag-' . $get_term_id . ' wps-breadcrumbs-item-tag-' . $get_term_slug . '" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><strong class="wps-breadcrumbs-current wps-breadcrumbs-tag-' . $get_term_id . ' wps-breadcrumbs-tag-' . $get_term_slug . '" itemprop="name">' . $get_term_name . '</strong></li>';

	} elseif ( is_day() ) {

		// Day archive

		// Year link
		echo '<li class="wps-breadcrumbs-item-year wps-breadcrumbs-item-year-' . get_the_time('Y') . '" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a class="wps-breadcrumbs-year wps-breadcrumbs-year-' . get_the_time('Y') . '" href="' . get_year_link( get_the_time('Y') ) . '" title="' . get_the_time('Y') . '" itemprop="item"><span class="wps-breadcrumbs-name" itemprop="name">' . get_the_time('Y') . ' Archives</span></a></li>';
		echo '<li class="wps-breadcrumbs-separator wps-breadcrumbs-separator-' . get_the_time('Y') . '"> ' . $separator . ' </li>';

		// Month link
		echo '<li class="wps-breadcrumbs-item-month wps-breadcrumbs-item-month-' . get_the_time('m') . '" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a class="wps-breadcrumbs-month wps-breadcrumbs-month-' . get_the_time('m') . '" href="' . get_month_link( get_the_time('Y'), get_the_time('m') ) . '" title="' . get_the_time('M') . '" itemprop="item"><span class="wps-breadcrumbs-name" itemprop="name">' . get_the_time('M') . ' Archives</span></a></li>';
		echo '<li class="wps-breadcrumbs-separator wps-breadcrumbs-separator-' . get_the_time('m') . '"> ' . $separator . ' </li>';

		// Day display
		echo '<li class="wps-breadcrumbs-item-current wps-breadcrumbs-item-' . get_the_time('j') . '" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><strong class="wps-breadcrumbs-current wps-breadcrumbs-' . get_the_time('j') . '" itemprop="name"> ' . get_the_time('jS') . ' ' . get_the_time('M') . ' Archives</strong></li>';

	} else if ( is_month() ) {

		// Month Archive

		// Year link
		echo '<li class="wps-breadcrumbs-item-year wps-breadcrumbs-item-year-' . get_the_time('Y') . '" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a class="wps-breadcrumbs-year wps-breadcrumbs-year-' . get_the_time('Y') . '" href="' . get_year_link( get_the_time('Y') ) . '" title="' . get_the_time('Y') . '" itemprop="item"><span class="wps-breadcrumbs-name" itemprop="name">' . get_the_time('Y') . ' Archives</span></a></li>';
		echo '<li class="wps-breadcrumbs-separator wps-breadcrumbs-separator-' . get_the_time('Y') . '"> ' . $separator . ' </li>';

		// Month display
		echo '<li class="wps-breadcrumbs-item-month wps-breadcrumbs-item-month-' . get_the_time('m') . '" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><strong class="wps-breadcrumbs-month wps-breadcrumbs-month-' . get_the_time('m') . '" title="' . get_the_time('M') . '" itemprop="name">' . get_the_time('M') . ' Archives</strong></li>';

	} else if ( is_year() ) {

		// Display year archive
		echo '<li class="wps-breadcrumbs-item-current wps-breadcrumbs-item-current-' . get_the_time('Y') . '" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><strong class="wps-breadcrumbs-current wps-breadcrumbs-current-' . get_the_time('Y') . '" title="' . get_the_time('Y') . '" itemprop="name">' . get_the_time('Y') . ' Archives</strong></li>';

	} else if ( get_query_var('paged') ) {

		// Paginated archives
		echo '<li class="wps-breadcrumbs-item-current wps-breadcrumbs-item-current-' . get_query_var('paged') . '" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><strong class="wps-breadcrumbs-current wps-breadcrumbs-current-' . get_query_var('paged') . '" title="Page ' . get_query_var('paged') . '" itemprop="name">'.__('Page') . ' ' . get_query_var('paged') . '</strong></li>';

	} else if ( is_search() ) {

		// Search results page
		echo '<li class="wps-breadcrumbs-item-current wps-breadcrumbs-item-current-' . get_search_query() . '" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><strong class="wps-breadcrumbs-current wps-breadcrumbs-current-' . get_search_query() . '" title="Search results for: ' . get_search_query() . '" itemprop="name">Search results for: ' . get_search_query() . '</strong></li>';

	} elseif ( is_404() ) {

		// 404 page
		echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">' . 'Error 404' . '</li>';

	}

	echo '</ul></div>';

}
