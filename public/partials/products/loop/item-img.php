<?php

if (empty($product->feat_image)) {

  $altText = $product->title;
  $src = WP_PLUGIN_URL . '/wp-shopify/public/imgs/placeholder.png';

} else {
  $src = $product->feat_image[0]->src;

  if (empty($product->feat_image[0]->alt)) {
    $altText = $product->title;

  } else {
    $altText = $product->feat_image[0]->alt;
  }

}

?>

<img
  itemprop="image"
  src="<?php echo $src; ?>"
  class="wps-products-img <?php echo apply_filters( 'wps_products_img_class', '' ); ?>"
  alt="<?php echo $altText; ?>">
