<?php

use WPS\DB\Images;

$image = Images::get_image_details_from_collection($data->collection);

?>

<img
  itemprop="image"
  src="<?php echo esc_url($image->src); ?>"
  alt="<?php esc_attr_e($image->alt); ?>"
  class="wps-collection-img <?php echo apply_filters('wps_collections_single_img_class', ''); ?>">
