import {
  deactivateLicenseKey,
  deleteLicenseKey,
  getLicenseKey
} from '../ws/ws.js';

import {
  enable,
  isWordPressError
} from '../utils/utils.js';


/*

Deactive License Key

*/
function deactivateKey() {

  return new Promise(async (resolve, reject) => {

    var $submitButton = jQuery('#wps-license input[type="submit"]'),
        $licenseInput = jQuery('#wps_settings_license_license'),
        $licensePostbox = jQuery('.wps-postbox-license-info');

    /*

    Step 1. Get license from database

    */
    try {
      var savedLicenseKey = await getLicenseKey();

    } catch(error) {

      return reject('Unnable to find this license key. Please try again.');

    }


    /*

    Deactivating key at wpshop.io

    */
    try {
      var deactivatedstuff = await deactivateLicenseKey(savedLicenseKey.data);

      if (isWordPressError(deactivatedstuff)) {
        throw deactivatedstuff.license;
      }

    } catch(error) {

      enable($submitButton);
      return reject('Error: unable to deactive license key. Please try again.');

    }


    /*

    Deleting key locally

    */
    try {

      var deleted = await deleteLicenseKey(savedLicenseKey);

      if (isWordPressError(deleted)) {
        throw deleted.data;
      }

      resolve(deleted);

    } catch(error) {

      enable($submitButton);
      return reject(error);

    }

  });

}

export {
  deactivateKey
}
