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

    Deleting key locally and remotelly

    */
    try {

      var deletedResponse = await deleteLicenseKey();

      if (isWordPressError(deletedResponse)) {
        throw deletedResponse.data;
      }

      resolve(deletedResponse);

    } catch(error) {

      enable($submitButton);
      return reject(error);

    }

  });

}

export {
  deactivateKey
}
