<?php

/*

@description   Heading for the product single page

@version       1.0.0
@since         1.0.49
@path          templates/partials/products/single/heading.php

@docs          https://wpshop.io/docs/templates/products/single/heading

*/

if ( !defined('ABSPATH') ) {
	exit;
}

?>

<h1 itemprop="name" class="entry-title wps-product-heading">
  <?php esc_html_e($data->product->details->title, 'wp-shopify'); ?> From Plugin
</h1>
