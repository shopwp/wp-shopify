import forEach from 'lodash/forEach';

import {
  onModalClose
} from '../forms/events';

import {
  unbindDisconnectForm,
  unbindConnectForm
} from '../forms/forms';

import {
  disable,
  setNonce,
  showSpinner,
  removeTrueAndTransformToArray,
  isWordPressError
} from '../utils/utils';

import {
  resetSyncingURL
} from '../utils/utils-data';

import {
  createConnectorModal,
  injectConnectorModal,
  ejectConnectorModal,
  showConnectorModal,
  setConnectionStepMessage,
  updateModalButtonText,
  updateModalHeadingText,
  updateCurrentConnectionStepText,
  insertXMark,
  initCloseModalEvents,
  insertCheckmark,
  updateConnectStatusHeading,
  clearConnectInputs,
  resetConnectSubmit
} from '../utils/utils-dom';

import {
  uninstallPlugin
} from '../ws/ws.js';

import {
  setConnectionProgress,
  clearLocalstorageCache,
  removeConnectionProgress
} from '../ws/localstorage.js';

import {
  connectInit
} from '../connect/connect.js';

import {
  clearAllCache
} from '../tools/cache.js';


/*

Construct Error List

*/
function constructErrorList(errors, currentErrorList, errorCode = '') {

  var newErrorList = currentErrorList;

  if (Array.isArray(newErrorList)) {

    var errorModified = removeTrueAndTransformToArray(errors) + ' ' + errorCode;

    newErrorList.push(errorModified);

  } else {

    newErrorList = removeTrueAndTransformToArray(errors) + ' ' + errorCode;

  }

  return newErrorList;

}


/*

On connection uninstall ...

*/
async function uninstallPluginData(options = false, reconnect = true) {

  /*

  Setting Default options

  */
  if (options === false) {

    options = {
      headingText: 'Canceled',
      stepText: 'Unable to finish operation',
      buttonText: 'Exit Sync',
      xMark: true,
      errorList: 'Failed to finish operation at unknown step',
      errorCode: ' (Error code: #000)',
      clearInputs: true
    }

  }

  // if (!options.headingText) {
  //   options.headingText = 'Canceled';
  // }
  //
  // if (!options.stepText) {
  //   options.stepText = 'Unable to finish operation';
  // }
  //
  // if (!options.buttonText) {
  //   options.buttonText = 'Exit Sync';
  // }
  //
  // if (!options.errorList) {
  //
  //   if (options.xMark) {
  //     options.errorList = 'Failed to finish operation at unknown step';
  //
  //   } else {
  //     options.errorList = false;
  //   }
  //
  // }


  /*

  Step 1. Uninstall current plugin data

  */
  try {

    var uninstallData = await uninstallPlugin();

    if (isWordPressError(uninstallData)) {
      options.errorList = constructErrorList(uninstallData.data, options.errorList, options.errorCode);
    }

  } catch (error) {
    options.errorList = constructErrorList(error, options.errorList, options.errorCode);

  }


  /*

  Step 2. Clear all plugin cache

  */
  try {

    var clearAllCacheResponse = await clearAllCache();

    if (isWordPressError(uninstallData)) {
      options.errorList = constructErrorList(uninstallData.data, options.errorList, options.errorCode);
    }

  } catch(error) {
    options.errorList = constructErrorList(error, options.errorList, options.errorCode);

  }


  updateDomAfterDisconnect(options);

  // Safe to reconnect again
  if (reconnect) {
    connectInit();
  }


}


/*

Disconnecting

*/
function onDisconnectionFormSubmit() {

  var $formConnect = jQuery("#wps-connect");
  var $submitButton = $formConnect.find('input[type="submit"]');

  unbindConnectForm();

  $formConnect.on('submit.disconnect', async function(e) {

    e.preventDefault();

    // Remove previous connector modal if exists
    ejectConnectorModal();

    var $formInputNonce = jQuery("#wps_settings_connection_nonce_id");
    var $connectorModal = createConnectorModal();

    setConnectionProgress("true");

    disable($submitButton);

    forEach(showSpinner, $submitButton);

    injectConnectorModal($connectorModal);

    // Close Listenter
    onModalClose();

    updateModalHeadingText('Disconnecting ...');
    updateModalButtonText('Stop disconnecting');
    showConnectorModal($connectorModal);
    setNonce( $formInputNonce.val() );
    setConnectionStepMessage('Disconnecting ...', '(Please wait, this may take up to 5 minutes depending on the size of your store and speed of your internet connection.)');

    /*

    Disconnecting ..

    */
    try {

      await uninstallPluginData({
        headingText: 'Disconnected',
        stepText: 'Disconnected Shopify store',
        buttonText: 'Exit Connection',
        xMark: false,
        errorCode: ' (Error code: #111)',
        clearInputs: true
      });

    } catch (error) {

      return error;

    }


  });

}


/*

updateDomAfterDisconnect

*/
function updateDomAfterDisconnect(options) {

  console.log("updateDomAfterDisconnect: ", options);

  updateModalHeadingText(options.headingText);
  updateModalButtonText(options.buttonText);
  updateCurrentConnectionStepText(options.stepText);
  updateConnectStatusHeading('is-disconnected');

  if (options.clearInputs) {
    clearConnectInputs();
  }

  setConnectionProgress("false");

  if (document.querySelector('.wps-btn-cancel')) {
    document.querySelector('.wps-btn-cancel').disabled = false;
  }

  if(options.xMark) {
    insertXMark();

  } else {
    insertCheckmark();
  }

  if (!options.noticeType) {
    options.noticeType = 'success';
  }


  // TODO: Modularize this, can put in Utils
  if (options.xMark) {

    // Showing error message
    if (Array.isArray(options.errorList) && options.errorList.length > 0) {

      options.errorList.forEach(function(entry) {
        jQuery('.wps-connector-heading').after('<div class="notice notice-' + options.noticeType + '">' + entry + '</div>');
      });

    } else {
      jQuery('.wps-connector-heading').after('<div class="notice notice-' + options.noticeType + '">' + options.errorList + '</div>');

    }

  } else {
    jQuery('.wps-connector-heading').after('<div class="notice notice-success">Successfully disconnected</div>');

  }


  clearLocalstorageCache();

  if (!options.resync) {
    resetConnectSubmit();
  }

  initCloseModalEvents();

}


/*

Connect Init

*/
function disconnectInit() {
  onDisconnectionFormSubmit();
}

export {
  disconnectInit,
  uninstallPluginData,
  updateDomAfterDisconnect
};
