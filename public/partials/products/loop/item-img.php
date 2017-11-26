<?php

use WPS\DB\Images;
$image = Images::get_image_details_from_product($product);

?>

<img
  itemprop="image"
  src="<?php echo $image['src']; ?>"
  alt="<?php echo $image['alt']; ?>"
  class="wps-products-img <?php echo apply_filters( 'wps_products_img_class', '' ); ?>">
