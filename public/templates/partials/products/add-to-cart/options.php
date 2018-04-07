<?php

/*

@description   Product options with dropdown modal

@version       1.0.0
@since         1.0.49
@path          templates/partials/products/add-to-cart/options.php

@docs          https://wpshop.io/docs/templates/partials/products/add-to-cart/options

*/

if ( !defined('ABSPATH') ) {
	exit;
}

?>

<?php foreach ($data->sorted_options as $option) { ?>

  <div
    class="wps-btn-dropdown wps-col wps-col-<?php echo $data->button_width; ?> <?php echo apply_filters('wps_options_class', ''); ?>"
    data-selected="false"
    data-open="false"
    data-selected-val=""
    data-option="<?php echo $data->option_number; ?>">

    <a
      href="#!"
      class="wps-btn wps-icon wps-icon-dropdown wps-modal-trigger"
      data-option="<?php esc_attr_e($option->name, 'wp-shopify'); ?>"
      data-option-id="<?php echo $option->id; ?>">
      <?php esc_html_e($option->name, 'wp-shopify'); ?>
    </a>

    <ul class="wps-modal">

      <?php foreach (unserialize($option->values) as $key => $variant) { ?>

        <li
          itemprop="category"
          class="wps-product-style wps-modal-close-trigger"
          data-option-id="<?php echo $data->variant_number; ?>"
          data-variant-title="<?php esc_attr_e($variant, 'wp-shopify'); ?>"
          data-option-position="<?php echo $option->position; ?>">
            <?php esc_html_e($variant, 'wp-shopify'); ?>
        </li>

        <?php $data->variant_number++;

        } ?>

    </ul>

  </div>

<?php

  $data->option_number++;

}
