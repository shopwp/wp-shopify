<?php

if ( !defined('ABSPATH') ) {
	exit;
}

?>

<header class="wps-product-header">

  <h1
    itemprop="name"
    class="entry-title wps-product-heading">
      <?php esc_html_e($data->product->details->title, 'wp-shopify'); ?>
  </h1>

</header>
