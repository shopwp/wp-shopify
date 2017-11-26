<?php

use WPS\DB\Images;

$image = Images::get_image_details_from_collection($collection);

?>

<img
  itemprop="image"
  src="<?php echo $image['src']; ?>"
  alt="<?php echo $image['alt']; ?>"
  class="<?php echo apply_filters( 'wps_collections_img_class', '' ); ?>" />
