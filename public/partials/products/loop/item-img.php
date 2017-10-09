<?php

if (empty($product->feat_image[0]->alt)) {
  $altText = $product->title;

} else {
  $altText = $product->feat_image[0]->alt;
}

?>

<img
  itemprop="image"
  src="<?php echo $product->feat_image[0]->src; ?>"
  class="wps-products-img <?php echo apply_filters( 'wps_products_img_class', '' ); ?>"
  alt="<?php echo $altText; ?>">
