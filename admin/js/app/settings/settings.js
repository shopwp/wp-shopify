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

      var checkedAttr = jQuery(form).find("#wps_settings_general_styles").attr("checked");

      if (typeof checkedAttr !== typeof undefined && checkedAttr !== false) {
        var styles = 1;

      } else {
        var styles = 0;

      }

      console.log("styles: ", styles);

      var settings = {
        wps_settings_general_products_url: productsURL,
        wps_settings_general_collections_url: collectionsURL,
        wps_settings_general_url_webhooks: webhooksURL,
        wps_settings_general_num_posts: numPosts,
        wps_settings_general_styles: styles
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


/*

Form Events Init

*/
function settingsInit() {
  onSettingsFormSubmit();
}

export { settingsInit };
