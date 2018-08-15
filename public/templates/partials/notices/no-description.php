<?php

/*

@description   Notice that shows when products / collections do not have descriptions

@version       1.0.1
@since         1.0.49
@path          templates/partials/notices/no-description.php

@docs          https://wpshop.io/docs/templates/notices/no-description

*/

if ( !defined('ABSPATH') ) {
	exit;
}

?>

<div class="wps-notice-inline wps-notice-notice-empty-description wps-notice-warning wps-contain <?= apply_filters('wps_products_no_results_class', ''); ?>" data-wps-description-type="<?= $data->type; ?>">
  <p><?php esc_html_e('No description found', WPS_PLUGIN_TEXT_DOMAIN); ?></p>
</div>
