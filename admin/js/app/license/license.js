import to from 'await-to-js';
import isObject from 'lodash/isObject';

import {
  deleteLicense,
  getLicense,
  setLicense
} from '../ws/api/api-license';

import {
  getLicenseKeyStatus,
  activateLicenseKey,
  getProductInfo
} from '../ws/ws';

import {
  enable,
  disable,
  createMask,
  formatExpireDate,
  showLoader,
  hideLoader,
  isWordPressError,
  getJavascriptErrorMessage,
  getWordPressErrorMessage,
  getWordPressErrorType
} from '../utils/utils';

import {
  showAdminNotice,
  toggleActive,
  removeCheckmarks
} from '../utils/utils-dom';

import { rejectedPromise } from '../utils/utils-data';


/*

Activate License Key

*/
function activateKey(key) {

  return new Promise(async (resolve, reject) => {

    var licenseKeyInfo = {};
    var licenseKeyActivatedResp = {};


    /*

    Get License Key Status

    */
    try {

      licenseKeyInfo = await getLicenseKeyStatus(key);

    } catch(error) {

      return reject('Error: unable to get license key. Please try again.');

    }


    /*

    Checking if we can activate ...

    */
    try {
      var validKey = await isLicenseKeyValid(licenseKeyInfo);

    } catch(error) {
      return reject(error);

    }


    /*

    Activating key at wpshop.io

    */
    if (validKey) {

      try {

        licenseKeyActivatedResp = await activateLicenseKey(key);

        if (!isObject(licenseKeyActivatedResp)) {
          return reject('Error: invalid license key format. Please try again.');
        }

      } catch(error) {
        return reject('Error: unable to activate license key. Please try again.');

      }


      var newLicenseKeyInfo = constructLicenseInfoForSaving(licenseKeyActivatedResp, key);


      /*

      Saving key locally

      */

      var [setLicenseError, setLicenseResponse] = await to( setLicense({ license: newLicenseKeyInfo }) );

      if (setLicenseError) {
        showAdminNotice( getJavascriptErrorMessage(setLicenseError) );
        return reject(setLicenseError);
      }

      if (isWordPressError(setLicenseResponse)) {

        showAdminNotice(
          getWordPressErrorMessage(setLicenseResponse),
          getWordPressErrorType(setLicenseResponse)
        );

        return reject(setLicenseError);

      }


      // Important to resolve with this variable since it contains the "license_key" prop
      // for later user in the DOM
      return resolve(newLicenseKeyInfo);


    } else {

      return reject(validKey);

    }

  });

}




function showInvalidLicenseDOM($licenseInput) {
  $licenseInput.removeClass('valid').addClass('error');
}


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

  var $forms = jQuery('#wps-license, #wps-plugin-info, #wps-license-info');

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
          showInvalidLicenseDOM($licenseInput);
          return errorMsg;

        }

        // Updates plugin info box
        try {

          var licenseAndProductInfo = await getProductInfo(key);

        } catch (errorMsg) {

          hideLoader($submitButton);
          showAdminNotice(errorMsg, 'error');
          showInvalidLicenseDOM($licenseInput);
          return errorMsg;

        }


        updatePluginInfoDOM({
          productInfo: licenseAndProductInfo
        });

        updateDOMAfterLicenseActivation(activatedKeyData);
        updateLicenseDetailsDOM(activatedKeyData);
        showAdminNotice('Successfully activated license key. Enjoy :)', 'updated');


      } else {

        var [deleteLicenseError, deleteLicenseResponse] = await to( deleteLicense() );

        if (deleteLicenseError) {
          showAdminNotice( getJavascriptErrorMessage(deleteLicenseError) );
        }

        if (isWordPressError(deleteLicenseResponse)) {

          showAdminNotice(
            getWordPressErrorMessage(deleteLicenseResponse),
            getWordPressErrorType(deleteLicenseResponse)
          );

        }

        clearLicenseForm();
        showAdminNotice('Successfully deactivated license key', 'updated');

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
      return reject('Sorry, this key appears to be valid for a different product. Please purchase a new key at <a href="https://wpshop.io/purchase" target="_blank">wpshop.io/purchase</a>');
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



function updatePluginInfoDOM(licenseAndProductInfo) {

  var $table = jQuery('#wps-plugin-info'),
      $latestVersionCol = $table.find('.wps-col-plugin-version'),
      $updateCol = $table.find('.wps-col-plugin-update-avail'),
      $testedUpToCol = $table.find('.wps-col-tested-up-to');

  // If banners property exists then we know we have the plugin info
  if (licenseAndProductInfo && licenseAndProductInfo.productInfo.banners) {

    $latestVersionCol.text(licenseAndProductInfo.productInfo.new_version);
    $testedUpToCol.text(licenseAndProductInfo.productInfo.tested_up_to);

  }

}


function updateLicenseDetailsDOM(licenseDetails) {

  // Falls here if no license is found
  if (!licenseDetails.success) {
    return;
  }

  var $table = jQuery('#wps-license-info'),
      $status = $table.find('.wps-col-license-status'),
      $name = $table.find('.wps-col-license-name'),
      $email = $table.find('.wps-col-license-email'),
      $expires = $table.find('.wps-col-license-expire'),
      $limit = $table.find('.wps-col-license-limit'),
      $activationsLeft = $table.find('.wps-col-license-activations-left');


  if (licenseDetails.license === 'valid') {
    licenseDetails.license = 'Active';
    $status.removeClass('wps-col-license-status-inactive').addClass('wps-col-license-status-active');
  }

  var options = { year: 'numeric', month: 'long', day: 'numeric' };
  var expires  = new Date(licenseDetails.expires);
  var expiresDate = expires.toLocaleDateString("en-US", options);

  $status.text(licenseDetails.license);
  $name.text(licenseDetails.customer_name);
  $email.text(licenseDetails.customer_email);
  $expires.text(expiresDate);
  $limit.text(licenseDetails.site_count + ' / ' + licenseDetails.license_limit);
  $activationsLeft.text(licenseDetails.activations_left);

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
        $pluginInfoLoader = jQuery('#wps-plugin-info > .spinner'),
        $pluginLicenseDetailsLoader = jQuery('#wps-license-info > .spinner');

    toggleActive($pluginInfoLoader);
    toggleActive($loader);
    toggleActive($pluginLicenseDetailsLoader);



    var [licenseError, licenseResponse] = await to( getLicense() );

    // No license key exists, just return
    if (!licenseResponse.data) {
      return resolve();
    }

    if (licenseError) {
      showAdminNotice( getJavascriptErrorMessage(licenseError) );
      return reject(licenseError);
    }

    if (isWordPressError(licenseResponse)) {

      showAdminNotice(
        getWordPressErrorMessage(licenseResponse),
        getWordPressErrorType(licenseResponse)
      );

      return reject(licenseResponse);

    }



    /*

    Step 2. Get license key status from wpshop.io

    */
    try {
      var currentLicenseKeyStatus = await getLicenseKeyStatus(licenseResponse);

    } catch (error) {
      return reject(error);

    }

    updateLicenseDetailsDOM(licenseResponse.data);


    /*

    Step 3. Check if license key is valid

    */

    var [validKeyErrorMessage, validKey] = await to( isLicenseKeyValid(licenseResponse.data) );

    if (validKey) {
      return resolve(licenseResponse.data);
    }

    var [deleteLicenseError, deleteLicenseResponse] = await to( deleteLicense() );

    return reject(validKeyErrorMessage);

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

  var [licenseError, licenseResponse] = await to( loadLicenseInfo() );

  if (licenseError) {
    showAdminNotice( getJavascriptErrorMessage(licenseError) );
    clearLicenseForm();
  }

  if (isWordPressError(licenseResponse)) {

    showAdminNotice(
      getWordPressErrorMessage(licenseResponse),
      getWordPressErrorType(licenseResponse)
    );

    clearLicenseForm();

  }

  updatePluginInfoDOM(licenseResponse)
  showLicenseForm();

}


export {
  licenseInit,
  isLicenseKeyValid,
  constructLicenseInfoForSaving,
  updateDOMAfterLicenseActivation
};
