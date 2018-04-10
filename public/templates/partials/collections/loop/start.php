<?php

/*

@description   Opening tag for the main collections loop

@version       1.0.0
@since         1.0.49
@path          templates/partials/collections/loop/loop-start.php

@docs          https://wpshop.io/docs/templates/collections/loop/loop-start

*/

if ( !defined('ABSPATH') ) {
	exit;
}

?>

<ul class="wps-row wps-contain wps-row-left wps-collections <?= apply_filters('wps_collections_class', ''); ?>">
