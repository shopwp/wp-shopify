<!--

Related Products Sort Type

-->
<div class="wps-admin-section">
  <div class="wps-form-group wps-form-group-tight wps-form-group-align-top">

    <table class="form-table">

      <tbody>

        <tr valign="top">

          <th scope="row" class="titledesc">
            <?php esc_html_e( 'Filter related products by', WPS_PLUGIN_TEXT_DOMAIN ); ?>
            <span class="wps-help-tip" title="<?php esc_attr_e( 'Performs a fuzzy search based on the below selection. For example, when filtering by Tags WP Shopify will locate all products which share at least one tag as the product in question. By default, related products are filtered and ordered randomly. Shortcode filtering will override this setting. ', WPS_PLUGIN_TEXT_DOMAIN ); ?>"></span>
          </th>

          <td class="forminp forminp-text wps-checkbox-wrapper">

            <label for="<?= WPS_SETTINGS_GENERAL_OPTION_NAME; ?>_related_products_sort_random" class="wps-label-block wps-checkbox-all">
              <input id="<?= WPS_SETTINGS_GENERAL_OPTION_NAME; ?>_related_products_sort_random" class="tog" value="random" type="radio" name="related_proudcts_filter" <?php echo $general->related_products_sort === 'random' ? 'checked' : ''; ?>> <?php esc_html_e( 'Random', WPS_PLUGIN_TEXT_DOMAIN ); ?>
            </label>

            <label for="<?= WPS_SETTINGS_GENERAL_OPTION_NAME; ?>_related_products_sort_collections" class="wps-label-block wps-checkbox-all">
              <input id="<?= WPS_SETTINGS_GENERAL_OPTION_NAME; ?>_related_products_sort_collections" class="tog" value="collections" type="radio" name="related_proudcts_filter" <?php echo $general->related_products_sort === 'collections' ? 'checked' : ''; ?>> <?php esc_html_e( 'Collections', WPS_PLUGIN_TEXT_DOMAIN ); ?>
            </label>

            <label for="<?= WPS_SETTINGS_GENERAL_OPTION_NAME; ?>_related_products_sort_tags" class="wps-label-block wps-checkbox-all">
              <input id="<?= WPS_SETTINGS_GENERAL_OPTION_NAME; ?>_related_products_sort_tags" class="tog" value="tags" type="radio" name="related_proudcts_filter" <?php echo $general->related_products_sort === 'tags' ? 'checked' : ''; ?>> <?php esc_html_e( 'Tags', WPS_PLUGIN_TEXT_DOMAIN ); ?>
            </label>

            <label for="<?= WPS_SETTINGS_GENERAL_OPTION_NAME; ?>_related_products_sort_vendors" class="wps-label-block wps-checkbox-all">
              <input id="<?= WPS_SETTINGS_GENERAL_OPTION_NAME; ?>_related_products_sort_vendors" class="tog" value="vendors" type="radio" name="related_proudcts_filter" <?php echo $general->related_products_sort === 'vendors' ? 'checked' : ''; ?>> <?php esc_html_e( 'Vendors', WPS_PLUGIN_TEXT_DOMAIN ); ?>
            </label>

            <label for="<?= WPS_SETTINGS_GENERAL_OPTION_NAME; ?>_related_products_sort_types" class="wps-label-block wps-checkbox-all">
              <input id="<?= WPS_SETTINGS_GENERAL_OPTION_NAME; ?>_related_products_sort_types" class="tog" value="types" type="radio" name="related_proudcts_filter" <?php echo $general->related_products_sort === 'types' ? 'checked' : ''; ?>> <?php esc_html_e( 'Types', WPS_PLUGIN_TEXT_DOMAIN ); ?>
            </label>

          </td>

        </tr>

      </tbody>

    </table>

  </div>
</div>
