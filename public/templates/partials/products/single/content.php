<?php

/*

@description   Description that shows on product single pages

@version       1.0.1
@since         1.0.49
@path          templates/partials/products/single/content.php

@docs          https://wpshop.io/docs/templates/products/single/content

*/

if ( !defined('ABSPATH') ) {
	exit;
}

?>

<div
  itemprop="description"
  class="wps-product-content">

  <?php _e($data->product->details->body_html, WPS_PLUGIN_TEXT_DOMAIN); ?>

</div>
