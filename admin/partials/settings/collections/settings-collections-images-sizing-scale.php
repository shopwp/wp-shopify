<!--

Crop sizing

-->
<div class="wps-form-group wps-form-group-tight">

  <table class="form-table">
    <tbody>
      <tr valign="top">

        <th scope="row" class="titledesc">
          <?php esc_attr_e( 'Scale', WPS_PLUGIN_TEXT_DOMAIN ); ?>
          <span class="wps-help-tip" title="<?php esc_attr_e( 'Sets a custom scale for all collection images. The number here will be multiplied by the dimensions set above. For example, an image originally 450x450 will return an image 900x900 pixels. Will only scale up if the original image is large enough. If original image is too small, the closest image in size will be returned.', WPS_PLUGIN_TEXT_DOMAIN ); ?>"></span>
        </th>

        <td class="forminp forminp-text">
          <div id="wps-settings-collections-images-sizing-scale"></div>
        </td>

      </tr>

    </tbody>
  </table>

</div>
