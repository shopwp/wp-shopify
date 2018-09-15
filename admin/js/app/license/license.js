import {
  getLicenseKeyStatus,
  getProductInfo,
  saveLicenseKey,
  deleteLicenseKey,
  getLicenseKey
} from '../ws/ws';

import {
  enable,
  disable,
  createMask,
  formatExpireDate,
  showLoader,
  hideLoader,
  isWordPressError
} from '../utils/utils';

import {
  showAdminNotice,
  toggleActive,
  removeCheckmarks
} from '../utils/utils-dom';

import { rejectedPromise } from '../utils/utils-data';
import { deactivateKey } from './license-deactivate';
import { activateKey } from './license-activate';


/*

Update DOM After License Activation

*/
function updateDOMAfterLicenseActivation(newLicenseKeyInfo) {

  var $form = jQuery('#wps-license'),
      $submitButton = $form.find('input[type="submit"]'),
      $licenseInput = $form.find("#wps_settings_general_license"),
      $licensePostbox = jQuery('.wps-postbox-license-info');

  $submitButton.data('status', 'deactivate');
  $submitButton.attr('data-status', 'deactivate');
  $submitButton.val('Deactivate License');

  $licenseInput.val( createMask(newLicenseKeyInfo.license_key, '•', 4) );
  $licenseInput.removeClass('error');

  updateInfoBox(newLicenseKeyInfo);

  $licensePostbox.removeClass('wps-is-hidden').animateCss('wps-fadeInRight', function() {
    $licensePostbox.removeClass('wps-fadeInRight');
  });

  disable(jQuery('#wps_settings_general_license'));

}


/*

Clear License Form

*/
function clearLicenseForm() {

  var $form = jQuery('#wps-license'),
      $submitButton = $form.find('input[type="submit"]'),
      $licenseInput = $form.find("#wps_settings_general_license"),
      $licensePostbox = jQuery('.wps-postbox-license-info');

  $submitButton.data('status', 'activate');
  $submitButton.attr('data-status', 'activate');
  $submitButton.val('Activate License');

  $licenseInput.val('');
  $licenseInput.attr('disabled', false);
  $licenseInput.prop('disabled', false);
  $licenseInput.removeClass('error valid');

  $licensePostbox.animateCss('wps-fadeOutRight', function() {
    $licensePostbox.addClass('wps-is-hidden');
  });

}


/*

Show License Form

*/
function showLicenseForm() {

  var $forms = jQuery('#wps-license, #wps-plugin-info');

  $forms.addClass('wps-is-ready');
  $forms.find('.wps-is-hidden').removeClass('wps-is-hidden');
}


/*

When License key form is submitted ...

*/
function onLicenseFormSubmit() {

  var $formLicense = jQuery("#wps-license");

  $formLicense.submit(function(e) {

    e.preventDefault();

  }).validate({

    rules: {
      "wps_settings_license[wps_key]": {
        alphaNumeric: true
      }
    },
    errorPlacement: function(error) {
      showAdminNotice(error.text(), 'error');
    },
    submitHandler: async function(form) {

      var $submitButton = jQuery(form).find('input[type="submit"]'),
          $spinner = jQuery(form).find('.spinner'),
          nonce = jQuery("#wps_settings_general_nonce_license_id").val(),
          $licenseInput = jQuery(form).find("#wps_settings_general_license"),
          key = jQuery(form).find("#wps_settings_general_license").val(),
          $licensePostbox = jQuery('.wps-postbox-license-info');


      disable($submitButton);
      toggleActive($spinner);
      showLoader($submitButton);

      if ($submitButton.data('status') === 'activate') {

        // Updates license info box
        try {

          var activatedKeyData = await activateKey(key);

        } catch (errorMsg) {

          hideLoader($submitButton);
          showAdminNotice(errorMsg, 'error');
          return errorMsg;

        }

        // Updates plugin info box
        try {

          var licenseAndProductInfo = await getProductInfo(key);

        } catch (errorMsg) {

          hideLoader($submitButton);
          showAdminNotice(errorMsg, 'error');
          return errorMsg;

        }


        updatePluginInfoDOM({
          productInfo: licenseAndProductInfo
        });

        updateDOMAfterLicenseActivation(activatedKeyData);
        showAdminNotice('Successfully activated license key. Enjoy :)', 'updated');


      } else {

        try {

          var response = await deactivateKey();

          clearLicenseForm();
          showAdminNotice('Successfully deactivated license key', 'updated');


        } catch (errorMsg) {

          hideLoader($submitButton);
          showAdminNotice(errorMsg, 'error');

        }

      }

      enable($submitButton);
      toggleActive($spinner);
      hideLoader($submitButton);

    }

  });

}


/*

Construct License Info For Saving

*/
function constructLicenseInfoForSaving(licenseKeyInfo, key) {

  var newLicenseKeyInfo = licenseKeyInfo;

  if (newLicenseKeyInfo.expires === "lifetime") {
    newLicenseKeyInfo.expires = new Date('January 1, 1970');
    newLicenseKeyInfo.lifetime = true;

  } else {
    newLicenseKeyInfo.lifetime = false;
  }

  newLicenseKeyInfo.license_key = key;

  return newLicenseKeyInfo;

}


/*

Helper function for checking license key validity
Checks for error properties from WPS

*/
function isLicenseKeyValid(key) {

  return new Promise(async (resolve, reject) => {

    if (key.license === 'invalid') {
      return reject('This license key is invalid or disabled. Please double check / re-enter your key.');
    }

    if (key.license === 'expired') {
      return reject('This license key is expired. Please login and renew your key at <a href="https://wpshop.io/account" target="_blank">wpshop.io/account</a>');
    }

    if (key.license === 'revoked') {
      return reject('This license key has been disabled. Please purchase a new key at <a href="https://wpshop.io/purchase" target="_blank">wpshop.io/purchase</a>');
    }

    if (key.license === 'missing') {
      return reject('This license key cannot be found. Please purchase a new key at <a href="https://wpshop.io/purchase" target="_blank">wpshop.io/purchase</a>');
    }

    if (key.license === 'item_name_mismatch') {
      return reject('This appears to be an invalid license key for WP Shopify. Please purchase a new key at <a href="https://wpshop.io/purchase" target="_blank">wpshop.io/purchase</a>');
    }

    if (key.license === 'no_activations_left') {
      // return reject('This license key has reached its activation limit. Please update at <a href="https://wpshop.io/purchase" target="_blank">wpshop.io/purchase</a>');
    }

    if (key.activations_left < 0) {
      return reject('This license key has reached it\'s activation limit. Please upgrade.');

    } else {
      return resolve(true);

    }

  });

}


/*

Update License Key info postbox -- used only upon first activation

*/
function updateInfoBox(licenseKeyInfo) {

  var $limitCol = jQuery('.wps-col-license-limit'),
      $nameCol = jQuery('.wps-col-license-name'),
      $emailCol = jQuery('.wps-col-license-email'),
      $expireCol = jQuery('.wps-col-license-expire'),
      $statusCol = jQuery('.wps-col-license-status'),
      licenseLimit,
      licenseCount;

  if (licenseKeyInfo.license_limit === 0) {
    licenseLimit = 'unlimited';

  } else {
    licenseLimit = licenseKeyInfo.license_limit;

  }

  licenseCount = licenseKeyInfo.site_count;

  if (licenseKeyInfo.success) {
    $statusCol.text('Active');
    $statusCol.removeClass('wps-col-license-status-inactive').addClass('wps-col-license-status-active');
  }

  $limitCol.text(licenseCount + ' / ' + licenseLimit);
  $nameCol.text(licenseKeyInfo.customer_name);
  $emailCol.text(licenseKeyInfo.customer_email);

  if (licenseKeyInfo.lifetime === 'false' || !licenseKeyInfo.lifetime) {
    $expireCol.text(formatExpireDate(licenseKeyInfo.expires));

  } else {
    $expireCol.text('Never expires');
  }

  if (licenseKeyInfo.is_local) {
    $limitCol.append('<small class="wps-table-supporting">(Activations on dev environments don\'t add to total)</small>');
  }

}


/*

Checks license key validity
TODO: Remove, not needed

*/
function onCheckLicenseKeyValidity() {

  jQuery(".wps-btn-check-license").on('click', async function(e) {

    e.preventDefault();

    var key = jQuery('.wps-input-license-key').val();
    var stuff = await getLicenseKeyStatus(key);

  });

}



function updatePluginInfoDOM(licenseAndProductInfo) {

  var $table = jQuery('#wps-plugin-info'),
      $latestVersionCol = $table.find('.wps-col-plugin-version'),
      $updateCol = $table.find('.wps-col-plugin-update-avail'),
      $nameCol = $table.find('.wps-col-plugin-name'),
      $testedUpToCol = $table.find('.wps-col-tested-up-to');

  // If banners property exists then we know we have the plugin info
  if (licenseAndProductInfo && licenseAndProductInfo.productInfo.banners) {

    $nameCol.text(licenseAndProductInfo.productInfo.name);
    $latestVersionCol.text(licenseAndProductInfo.productInfo.new_version);
    $testedUpToCol.text(licenseAndProductInfo.productInfo.tested_up_to);

  }

}





/*

On initial page load

*/
function onLoad() {

  return new Promise(async function(resolve, reject) {

    var $form = jQuery('#wps-license'),
        $loader = jQuery('#wps-license > .spinner'),
        $submitButton = $form.find('input[type="submit"]'),
        $spinner = $form.find('.spinner'),
        $licenseInput = $form.find("#wps_settings_general_license"),
        $licensePostbox = jQuery('.wps-postbox-license-info'),
        $postBox = jQuery('.wps-postbox-license-activation'),
        $pluginInfo = jQuery('#wps-plugin-info'),
        $pluginInfoLoader = jQuery('#wps-plugin-info > .spinner');

    toggleActive($pluginInfoLoader);
    toggleActive($loader);

    /*

    Step 1. Get license from database

    */
    try {

      var licenseKeyData = await getLicenseKey();

      if (isWordPressError(licenseKeyData)) {
        return resolve();

      } else {
        var currentLicenseKey = licenseKeyData.data;
      }

    } catch(error) {
      return reject(error);

    }


    /*

    Step 2. Get license key status from wpshop.io

    */
    try {
      var currentLicenseKeyStatus = await getLicenseKeyStatus(currentLicenseKey);

    } catch (error) {
      return reject(error);

    }


    /*

    Step 3. Check if license key is valid

    */
    try {

      var validKey = await isLicenseKeyValid(currentLicenseKeyStatus);

    } catch (error) {


      /*

      Deleting key locally and remotelly

      */
      try {

        var deletedKey = await deleteLicenseKey();

        if (isWordPressError(deletedKey)) {
          throw deletedKey.data;
        }

      } catch(error) {

        return reject(error);

      }

      return reject(error);

    }


    /*

    Step 4. If invalid key, delete and clear form, otherwise just show form.

    */
    if ( !validKey ) {

      /*

      Deleting key locally

      */
      try {

        var deletedResponse = await deleteLicenseKey();

        if (isWordPressError(deletedResponse)) {
          throw deletedResponse.data;
        }

      } catch(error) {

        return reject(error);

      }

    } else {

      resolve(licenseKeyData.data);

    }

  });

}


/*

Load License Key and WP Shopify Info

*/
function loadLicenseInfo() {

  return new Promise(async (resolve, reject) => {

    /*

    Step 1. Load License Key Info

    */
    try {
      var licenseKey = await onLoad();

    } catch(error) {

      return reject(error);

    }


    /*

    Step 2. Load Get latest WP Shopify info

    */
    try {
      var productInfo = await getProductInfo(licenseKey);

    } catch (error) {
      return reject(error);
    }


    resolve({
      licenseKey: licenseKey,
      productInfo: productInfo
    });


  });

}


/*

Form Events Init

*/
async function licenseInit() {

  onLicenseFormSubmit();

  try {
    var licenseAndProductInfo = await loadLicenseInfo();

  } catch (error) {
    showAdminNotice(error, 'error');
    clearLicenseForm();
  }

  updatePluginInfoDOM(licenseAndProductInfo)
  showLicenseForm();

}


export {
  licenseInit,
  isLicenseKeyValid,
  constructLicenseInfoForSaving,
  updateDOMAfterLicenseActivation
};
