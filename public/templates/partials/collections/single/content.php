<?php

/*

@description   Description that shows on collection single pages

@version       1.0.0
@since         1.0.49
@path          templates/partials/collections/single/content.php

@docs          https://wpshop.io/docs/templates/collections/single/content

*/

if ( !defined('ABSPATH') ) {
	exit;
}

?>

<div
	itemprop="description"
	class="wps-collection-content <?= apply_filters('wps_collections_single_content_class', ''); ?>">
		<?php _e($data->collection->body_html, 'wp-shopify'); ?>
</div>
