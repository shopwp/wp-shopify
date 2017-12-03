import {
  activateLicenseKey,
  deactivateLicenseKey,
  getLicenseKeyStatus,
  getProductInfo,
  saveLicenseKey,
  deleteLicenseKey,
  getLicenseKey
} from '../ws/ws.js';

import {
  enable,
  disable,
  createMask,
  formatExpireDate,
  isObject,
  showSpinner,
  hideSpinner,
  showLoader,
  hideLoader,
  isWordPressError
} from '../utils/utils.js';

import {
  showAdminNotice,
  toggleActive,
  removeCheckmarks
} from '../utils/utils-dom.js';

import {
  rejectedPromise
} from '../utils/utils-data.js';


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

      var $submitButton = jQuery(form).find('input[type="submit"]');
      var $spinner = jQuery(form).find('.spinner');
      var nonce = jQuery("#wps_settings_license_nonce_license_id").val();
      var key = jQuery(form).find("#wps_settings_license_license").val();

      disable($submitButton);
      toggleActive($spinner);
      showLoader($submitButton);

      if($submitButton.data('status') === 'activate') {

        try {
          var response = await activateKey(key);
          showAdminNotice('Successfully activated license key. Enjoy :)', 'updated');

        } catch (errorMsg) {

          hideLoader($submitButton);
          showAdminNotice(errorMsg, 'error');

        }

      } else {

        try {
          var response = await deactivateKey();
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

Deactive License Key

*/
async function deactivateKey() {

  var $submitButton = jQuery('#wps-license input[type="submit"]');
  var $licenseInput = jQuery('#wps_settings_license_license');
  var $licensePostbox = jQuery('.wps-postbox-license-info');


  /*

  Getting License

  */
  try {
    var savedLicenseKey = await getLicenseKey();

  } catch(error) {
    enable($submitButton);
    return rejectedPromise('Error: unable to find license key. Please try again.');
  }


  /*

  Deactivating key at wpshop.io

  */
  try {
    var deactivatedstuff = await deactivateLicenseKey(savedLicenseKey);

  } catch(error) {
    enable($submitButton);
    return rejectedPromise('Error: unable to deactive license key. Please try again.');

  }


  /*

  Deleting key locally

  */
  try {

    var deleted = await deleteLicenseKey(savedLicenseKey);

    if (isWordPressError(deleted)) {
      throw deleted.data;
    }

    $submitButton.data('status', 'activate');
    $submitButton.attr('data-status', 'activate');
    $submitButton.val('Activate License');

    $licenseInput.val('');
    $licenseInput.removeClass('error valid');

    $licensePostbox.animateCss('wps-bounceOutLeft', function() {
      $licensePostbox.addClass('wps-is-hidden');
    });

    enable(jQuery('#wps_settings_license_license'));

  } catch(error) {
    enable($submitButton);
    return rejectedPromise(error);
  }

}


/*

Helper function for checking license key validity
Checks for error properties from WPS

*/
async function isLicenseKeyValid(key) {

  var keyStatusObj = await getLicenseKeyStatus(key);

  if (keyStatusObj.license === 'invalid') {
    return rejectedPromise('Error: license key is invalid. Please double check your key and try again.');
  }

  if(keyStatusObj.activations_left <= 0) {
    return rejectedPromise('Error: license key has reached it\'s activation limit. Please upgrade.');

  } else {

    return true;

  }

}


/*

Activate License Key

*/
async function activateKey(key) {

  var licenseKeyInfo = {};
  var licenseKeyActivatedResp = {};
  var $submitButton = jQuery('#wps-license input[type="submit"]');
  var $licenseInput = jQuery('#wps_settings_license_license');
  var $licensePostbox = jQuery('.wps-postbox-license-info');
  var $form = jQuery('#wps-license');

  /*

  Checking if we can activate ...

  */
  try {
    var validKey = await isLicenseKeyValid(key);

  } catch(error) {

    removeCheckmarks($form);
    return rejectedPromise(error);

  }


  /*

  Activating key at wpshop.io

  */
  try {

    licenseKeyActivatedResp = await activateLicenseKey(key);

    if(!isObject(licenseKeyActivatedResp)) {
      enable($submitButton);
      return rejectedPromise('Error: invalid license key format. Please try again.');
    }

  } catch(error) {

    removeCheckmarks($form);
    return rejectedPromise('Error: unable to activate license key. Please try again.');

  }


  /*

  Get License Key Status

  */
  try {
    licenseKeyInfo = await getLicenseKeyStatus(key);

  } catch(error) {

    removeCheckmarks($form);
    enable($submitButton);

    return rejectedPromise('Error: unable to get license key. Please try again.');

  }


  /*

  Saving key locally

  */
  try {

    if (licenseKeyInfo.expires === "lifetime") {
      licenseKeyInfo.expires = new Date('January 1, 1970');
      licenseKeyInfo.lifetime = true;

    } else {
      licenseKeyInfo.lifetime = false;
    }

    licenseKeyInfo.key = key;
    licenseKeyInfo.is_local = licenseKeyActivatedResp.is_local;

    var savedKeyResponse = await saveLicenseKey(licenseKeyInfo);

    if (isWordPressError(savedKeyResponse)) {
      throw savedKeyResponse.data;
    }

    $submitButton.data('status', 'deactivate');
    $submitButton.attr('data-status', 'deactivate');
    $submitButton.val('Deactivate License');

    $licenseInput.val( createMask(key, '•', 4) );
    $licenseInput.removeClass('error');

    $licensePostbox.removeClass('wps-is-hidden').animateCss('wps-fadeInRight', function() {
      $licensePostbox.removeClass('wps-fadeInRight');
    });

    updateInfoBox(licenseKeyInfo);
    disable(jQuery('#wps_settings_license_license'));

  } catch(error) {

    removeCheckmarks($form);
    enable($submitButton);

    return rejectedPromise(error);

  }

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

  if (licenseKeyInfo.is_local) {
    licenseCount = licenseKeyInfo.site_count;

  } else {
    licenseCount = licenseKeyInfo.site_count + 1;

  }

  if (licenseKeyInfo.success) {
    $statusCol.text('Active');
    $statusCol.removeClass('wps-col-license-status-inactive').addClass('wps-col-license-status-active');
  }

  $limitCol.text(licenseCount + ' / ' + licenseLimit);
  $nameCol.text(licenseKeyInfo.customer_name);
  $emailCol.text(licenseKeyInfo.customer_email);

  if (licenseKeyInfo.lifetime) {
    $expireCol.text('Never expires');

  } else {
    $expireCol.text(formatExpireDate(licenseKeyInfo.expires));
  }

  if (licenseKeyInfo.is_local) {
    $limitCol.append('<small class="wps-table-supporting">(Activations on dev environents don\'t add to total)</small>');
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


/*

When user deactivates license key
TODO: Remove, not needed

*/
function onGetLicenseKeyInfo() {

  jQuery(".wps-btn-get-product").on('click', async function(e) {

    e.preventDefault();

    var key = jQuery('.wps-input-license-key').val();
    var stuff = await getProductInfo(key);

  });

}


/*

Form Events Init

*/
function licenseInit() {

  // onCheckLicenseKeyValidity();
  // onGetLicenseKeyInfo();
  onLicenseFormSubmit();

}

export {
  licenseInit
};
