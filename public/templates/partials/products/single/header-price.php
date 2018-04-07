<?php

if ( !defined('ABSPATH') ) {
	exit;
}

?>

<p
  itemprop="offers"
  itemscope
  itemtype="https://schema.org/Offer"
  class="wps-product-price">

  <?php echo do_action('wps_products_price', $data->product); ?>

</p>
