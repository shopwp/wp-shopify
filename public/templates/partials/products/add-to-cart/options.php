<?php

/*

@description   Product options with dropdown modal

@version       1.0.1
@since         1.0.49
@path          templates/partials/products/add-to-cart/options.php

@docs          https://wpshop.io/docs/templates/partials/products/add-to-cart/options

*/

if ( !defined('ABSPATH') ) {
	exit;
}



?>

<?php

foreach ($data->sorted_options as $option) { ?>

  <div
    class="wps-btn-dropdown wps-col wps-col-<?= $data->button_width; ?> <?= apply_filters('wps_options_class', ''); ?>"
    data-selected="false"
		data-active="false"
    data-open="false"
		data-option-name="<?= $option->name; ?>"
    data-selected-val=""
    data-option="<?= $data->option_number; ?>">

    <a
      href="#!"
      class="wps-btn wps-icon wps-icon-dropdown wps-modal-trigger"
      data-option="<?php esc_attr_e($option->name, WPS_PLUGIN_TEXT_DOMAIN); ?>"
      data-option-id="<?= $option->id; ?>"
			style="<?= !empty($data->button_color) ? 'background-color: ' . $data->button_color . ';' : ''; ?>">
      <?php esc_html_e($option->name, WPS_PLUGIN_TEXT_DOMAIN); ?>
    </a>

    <ul class="wps-modal">

      <?php

			foreach ($option->values as $key => $variant) { ?>

        <li
          itemprop="category"
          class="wps-product-style wps-modal-close-trigger"
          data-option-id="<?= $data->variant_number; ?>"
          data-variant-title="<?php esc_attr_e($variant, WPS_PLUGIN_TEXT_DOMAIN); ?>"
          data-option-position="<?= $option->position; ?>">
            <?php esc_html_e($variant, WPS_PLUGIN_TEXT_DOMAIN); ?>
        </li>

        <?php $data->variant_number++;

        } ?>

    </ul>

  </div>

<?php

  $data->option_number++;

}
