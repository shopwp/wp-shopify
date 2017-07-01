import {
  updateSettings
} from '../ws/ws.js';

import {
  enable,
  disable,
  showSpinner,
  hideSpinner,
  showLoader,
  hideLoader,
  containsTrailingForwardSlash,
  removeTrailingForwardSlash
} from '../utils/utils.js';

import {
  showAdminNotice,
  toggleActive
} from '../utils/utils-dom.js';

import {
  rejectedPromise
} from '../utils/utils-data.js';


/*

When License key form is submitted ...

*/
function onSettingsFormSubmit() {

  var $formSettings = jQuery("#wps-settings");

  $formSettings.submit(function(e) {
    e.preventDefault();

  }).validate({

    rules: {
      "wps_settings_general[wps_general_url_products]": {
        alphaNumeric: true
      },
      "wps_settings_general[wps_general_url_collections]": {
        alphaNumeric: true
      },
      "wps_settings_general[wps_general_num_posts]": {
        number: true
      },
      "wps_settings_general[wps_general_webhooks_products]": {
        urlRule: true
      }
    },
    errorPlacement: function(error) {
      showAdminNotice(error.text(), 'error');

    },
    submitHandler: async function(form) {

      var $submitButton = jQuery(form).find('input[type="submit"]');
      var $spinner = jQuery(form).find('.spinner');
      var nonce = jQuery("#wps_settings_general_urls_nonce_id").val();
      var productsURL = jQuery(form).find("#wps_settings_general_url_products").val();
      var collectionsURL = jQuery(form).find("#wps_settings_general_url_collections").val();
      var webhooksURL = jQuery(form).find("#wps_settings_general_url_webhooks").val();
      var numPosts = jQuery(form).find("#wps_settings_general_num_posts").val();
      // var styles = jQuery(form).find("#wps_settings_general_styles").val();

      disable($submitButton);
      toggleActive($spinner);


      // If URL contains a trailing forward slash
      if (containsTrailingForwardSlash(webhooksURL)) {

        webhooksURL = removeTrailingForwardSlash(webhooksURL);

        // Reset the modified string val in form field
        jQuery(form).find("#wps_settings_general_url_webhooks").val(webhooksURL);

      }

      var stylesAllAttr = jQuery(form).find("#wps_settings_general_styles_all").attr("checked");
      var stylesCoreAttr = jQuery(form).find("#wps_settings_general_styles_core").attr("checked");
      var stylesGridAttr = jQuery(form).find("#wps_settings_general_styles_grid").attr("checked");
      var priceFormatAttr = jQuery(form).find("#wps_settings_general_price_with_currency").attr("checked");

      if (typeof stylesAllAttr !== typeof undefined && stylesAllAttr !== false) {
        var stylesAll = 1;

      } else {
        var stylesAll = 0;

      }

      if (typeof stylesCoreAttr !== typeof undefined && stylesCoreAttr !== false) {
        var stylesCore = 1;

      } else {
        var stylesCore = 0;

      }

      if (typeof stylesGridAttr !== typeof undefined && stylesGridAttr !== false) {
        var stylesGrid = 1;

      } else {
        var stylesGrid = 0;

      }

      if (typeof priceFormatAttr !== typeof undefined && priceFormatAttr !== false) {
        var priceFormat = 1;

      } else {
        var priceFormat = 0;

      }

      console.log("priceFormat: ", priceFormat);

      var settings = {
        wps_settings_general_products_url: productsURL,
        wps_settings_general_collections_url: collectionsURL,
        wps_settings_general_url_webhooks: webhooksURL,
        wps_settings_general_num_posts: numPosts,
        wps_settings_general_styles_all: stylesAll,
        wps_settings_general_styles_core: stylesCore,
        wps_settings_general_styles_grid: stylesGrid,
        wps_settings_general_price_with_currency: priceFormat
      }

      try {
        var settingsResponse = await updateSettings(settings);
        showAdminNotice('Successfully updated settings', 'updated');
        toggleActive($spinner);
        enable($submitButton);

      } catch (errorMsg) {

        showAdminNotice(errorMsg, 'error');
        enable($submitButton);
        toggleActive($spinner);

      }

    }

  });

}


function toggleStylesCheckboxes() {

  jQuery('#wps_settings_general_styles_all').on('click', function() {

    if (typeof jQuery(this).attr("checked") !== typeof undefined && jQuery(this).attr("checked") !== false) {
        console.log('checked');

        jQuery('#wps_settings_general_styles_core').attr('checked', false);
        jQuery('#wps_settings_general_styles_grid').attr('checked', false);

        jQuery('#wps_settings_general_styles_core').attr('disabled', true);
        jQuery('#wps_settings_general_styles_grid').attr('disabled', true);

        jQuery('#wps_settings_general_styles_core').parent().addClass('wps-is-disabled');
        jQuery('#wps_settings_general_styles_grid').parent().addClass('wps-is-disabled');

    } else {
      console.log('NOT checked');

      jQuery('#wps_settings_general_styles_core').attr('disabled', false);
      jQuery('#wps_settings_general_styles_grid').attr('disabled', false);

      jQuery('#wps_settings_general_styles_core').parent().removeClass('wps-is-disabled');
      jQuery('#wps_settings_general_styles_grid').parent().removeClass('wps-is-disabled');

    }

  });
}

/*

Form Events Init

*/
function settingsInit() {
  onSettingsFormSubmit();
  toggleStylesCheckboxes();
}

export { settingsInit };
