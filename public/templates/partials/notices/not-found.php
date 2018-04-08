<?php

/*

@description   Used for displaying "products not found" notices

@version       1.0.0
@since         1.0.49
@path          templates/partials/notices/notice.php

@docs          https://wpshop.io/docs/templates/partials/notices/notice

*/

if ( !defined('ABSPATH') ) {
	exit;
}

?>

<div class="wps-notice <?= apply_filters('wps_notice_class', ''); ?>"></div>
