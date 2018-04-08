<?php

/*

@description   Image gallery contents

@version       1.0.0
@since         1.0.49
@path          templates/partials/products/single/imgs.php

@docs          https://wpshop.io/docs/templates/products/single/imgs

*/

if ( !defined('ABSPATH') ) {
	exit;
}


if ($data->amount_of_thumbs < 1) {

  do_action('wps_product_single_imgs_feat_placeholder', $data);

} else {

  foreach ($data->product->images as $image) {

    if ($data->index === 0) {
      do_action('wps_product_single_imgs_feat', $data, $image);
      do_action('wps_product_single_thumbs_start', $data->product);

    } else {
      do_action('wps_product_single_img', $data, $image);

    }

    if ($data->index === 0) {
      do_action('wps_before_first_product_img');
    }

    $data->index++;

  }

  do_action('wps_product_single_thumbs_end', $data->product);

}
