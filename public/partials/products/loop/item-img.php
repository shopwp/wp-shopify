<?php

use WPS\DB\Images;
$image = Images::get_image_details_from_product($product);

?>

<img
  itemprop="image"
  src="<?php echo esc_url($image['src']); ?>"
  alt="<?php esc_attr_e($image['alt']); ?>"
  class="wps-products-img <?php echo apply_filters( 'wps_products_img_class', '' ); ?>">
