<!--

Products per page

-->
<div class="wps-form-group wps-form-group-tight">

  <table class="form-table">
    <tbody>
      <tr valign="top">

        <th scope="row" class="titledesc">
          <?php esc_html_e( 'Products per page', WPS_PLUGIN_TEXT_DOMAIN ); ?>
          <span class="wps-help-tip" title="<?php esc_attr_e( 'Defaults to the standard WordPress post count set within Settings - Reading. Can be overwritten here.', WPS_PLUGIN_TEXT_DOMAIN ); ?>"></span>
        </th>

        <td class="forminp forminp-text">
          <input type="number" class="small-text" id="<?= WPS_SETTINGS_GENERAL_OPTION_NAME; ?>_num_posts" name="<?= WPS_SETTINGS_GENERAL_OPTION_NAME; ?>[wps_general_num_posts]" value="<?php echo !empty($general->num_posts) ? $general->num_posts : get_option('posts_per_page'); ?>" placeholder="">
        </td>

      </tr>

    </tbody>
  </table>

</div>
