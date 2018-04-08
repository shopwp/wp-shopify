<?php

/*

@description   Price for each product within the main products loop

@version       1.0.0
@since         1.0.49
@path          templates/partials/products/loop/item-price.php

@docs          https://wpshop.io/docs/templates/partials/products/loop/item-price

*/

if ( !defined('ABSPATH') ) {
	exit;
}

?>

<h3
  itemprop="offers"
  itemscope
  itemtype="https://schema.org/Offer"
  class="wps-products-price <?= apply_filters( 'wps_products_price_class', '' ); ?>">

  <?= do_action('wps_products_price', $data->product); ?>

</h3>
