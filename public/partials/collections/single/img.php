<?php

use WPS\DB\Images;
$image = Images::get_image_details_from_collection($collection);

?>

<img
  itemprop="image"
  src="<?php echo $image['src']; ?>"
  alt="<?php echo $image['alt']; ?>"
  class="wps-collection-img <?php echo apply_filters('wps_collections_single_img_class', ''); ?>">
