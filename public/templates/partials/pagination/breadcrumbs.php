<?php

/*

@description   Breadcrumbs

@version       1.0.0
@since         1.0.49
@path          templates/partials/pagination/breadcrumbs.php

@docs          https://wpshop.io/docs/templates/pagination/breadcrumbs

*/

use WPS\Utils;

if ( !defined('ABSPATH') ) {
	exit;
}

?>

<div class="wps-breadcrumbs <?php echo apply_filters('wps_breadcrumbs_class', ''); ?> wps-row wps-contain">
  <?php Utils::wps_breadcrumbs(); ?>
</div>
