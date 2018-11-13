<?php

/*

@description   Multi-price component. Used when product has a range of prices, e.g., Small: $1, Medium: $3, Large: $5 -- $1-5.
							 Used on both product single and product listing pages.

@version       1.0.0
@since         1.0.49
@path          templates/partials/products/add-to-cart/price-multi.php

@docs          https://wpshop.io/docs/templates/partials/products/add-to-cart/price-multi

*/

if ( !defined('ABSPATH') ) {
	exit;
}

?>

<h3
  itemprop="offers"
  itemscope
  itemtype="https://schema.org/Offer"
  class="wps-products-price wps-product-pricing wps-products-price-multi <?= apply_filters( 'wps_products_price_class', '' ); ?>"
	data-compare-at="<?= $data->showing_compare_at; ?>"
	>

  <?= apply_filters('wps_products_price_multi', $data->price, $data->first_price, $data->last_price, $data->product); ?>

</h3>
