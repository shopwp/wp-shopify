import {
  activateLicenseKey,
  getLicenseKeyStatus,
  saveLicenseKey,
} from '../ws/ws.js';

import {
  isObject,
  isWordPressError
} from '../utils/utils.js';

import { isLicenseKeyValid, constructLicenseInfoForSaving } from './license.js';


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

    console.log("licenseKeyInfo: ", licenseKeyInfo);

    /*

    Checking if we can activate ...

    */
    try {
      var validKey = await isLicenseKeyValid(licenseKeyInfo);

    } catch(error) {

      return reject(error);

    }

    console.log("validKey: ", validKey);

    /*

    Activating key at wpshop.io

    */
    if (validKey) {

      try {

        licenseKeyActivatedResp = await activateLicenseKey(key);

        console.log("licenseKeyActivatedResp: ", licenseKeyActivatedResp);

        if (!isObject(licenseKeyActivatedResp)) {
          return reject('Error: invalid license key format. Please try again.');
        }

      } catch(error) {
        return reject('Error: unable to activate license key. Please try again.');

      }


      /*

      Saving key locally

      */
      try {

        var newLicenseKeyInfo = constructLicenseInfoForSaving(licenseKeyActivatedResp, key);
        console.log("newLicenseKeyInfo: ", newLicenseKeyInfo);

        var savedKeyResponse = await saveLicenseKey(newLicenseKeyInfo);
        console.log("savedKeyResponse: ", savedKeyResponse);


        if (isWordPressError(savedKeyResponse)) {
          throw savedKeyResponse.data;
        }

        resolve(savedKeyResponse.data);

      } catch(error) {
        return reject(error);

      }

    } else {

      return reject(validKey);

    }

  });

}

export {
  activateKey
}
