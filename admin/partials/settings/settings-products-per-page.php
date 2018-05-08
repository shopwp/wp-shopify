<!-- Products per page -->
<div class="wps-form-group wps-form-group-tight">

  <?php $default_posts_per_page = get_option( 'posts_per_page' ); ?>

  <table class="form-table">
    <tbody>
      <tr valign="top">

        <th scope="row" class="titledesc">
          <?php esc_html_e( 'Products per page', 'wp-shopify' ); ?>
          <span class="wps-help-tip" title="<?php esc_attr_e( 'Defaults to the standard WordPress post count set within Settings - Reading. Can be overwritten here.', 'wp-shopify' ); ?>"></span>
        </th>

        <td class="forminp forminp-text">
          <input type="number" class="regular-text" id="<?php echo $this->config->settings_general_option_name; ?>_num_posts" name="<?php echo $this->config->settings_general_option_name; ?>[wps_general_num_posts]" value="<?php echo !empty($general->num_posts) ? $general->num_posts : $default_posts_per_page; ?>" placeholder="">
        </td>

      </tr>

    </tbody>
  </table>

</div>
