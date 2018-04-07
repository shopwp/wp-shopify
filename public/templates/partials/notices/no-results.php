<?php

/*

@description   Notice for no products found within main products loop

@version       1.0.0
@since         1.0.49
@path          templates/partials/notices/no-results.php

@docs          https://wpshop.io/docs/templates/partials/notices/no-results

*/

if ( !defined('ABSPATH') ) {
	exit;
}

?>

<div class="wps-notice-inline wps-notice-warning wps-contain <?php echo apply_filters('wps_products_no_results_class', ''); ?>">
  <p><?php esc_html_e('No products found', 'wp-shopify'); ?></p>
</div>
