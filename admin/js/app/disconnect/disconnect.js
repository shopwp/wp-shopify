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
  clearLocalstorageCache
} from '../ws/localstorage.js';

import {
  connectInit
} from '../connect/connect.js';

import {
  clearAllCache
} from '../tools/cache.js';




function constructErrorList(errors, currentErrorList) {

  var newErrorList = currentErrorList;

  if (Array.isArray(options.errorList)) {
    newErrorList.push(removeTrueAndTransformToArray(errors));

  } else {
    newErrorList = removeTrueAndTransformToArray(errors);

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
  if(options === false) {

    options = {
      headingText: 'Canceled',
      stepText: 'Unable to finish operation',
      buttonText: 'Exit Sync',
      xMark: true,
      errorList: 'Failed to finish operation at unknown step'
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
      options.errorList = constructErrorList(uninstallData.data, options.errorList);
    }

  } catch (error) {
    options.errorList = constructErrorList(error, options.errorList);

  }


  /*

  Step 2. Clear all plugin cache

  */
  try {

    var clearAllCacheResponse = await clearAllCache();

    if (isWordPressError(uninstallData)) {
      options.errorList = constructErrorList(uninstallData.data, options.errorList);
    }

  } catch(errors) {
    options.errorList = constructErrorList(error, options.errorList);

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

    R.forEach(showSpinner, $submitButton);

    injectConnectorModal($connectorModal);

    // Close Listenter
    onModalClose();

    updateModalHeadingText('Disconnecting ...');
    updateModalButtonText('Stop disconnecting');
    showConnectorModal($connectorModal);
    setNonce( $formInputNonce.val() );
    setConnectionStepMessage('Disconnecting ...', '(Please wait. This may take up to 60 seconds depending on how large your store is.)');

    /*

    Disconnecting ..

    */
    try {

      await uninstallPluginData({
        headingText: 'Disconnected',
        stepText: 'Disconnected Shopify store',
        buttonText: 'Exit Connection',
        xMark: false
      });

    } catch (error) {

      // Something happened, user needs to try
      // disconnecting again
      console.error('... Error disconnecting ...', error);
      return error;

    }

  });

}


/*

updateDomAfterDisconnect

*/
function updateDomAfterDisconnect(options) {

  updateModalHeadingText(options.headingText);
  updateModalButtonText(options.buttonText);
  updateCurrentConnectionStepText(options.stepText);
  updateConnectStatusHeading('is-disconnected');

  clearConnectInputs();
  setConnectionProgress("false");

  if(document.querySelector('.wps-btn-cancel')) {
    document.querySelector('.wps-btn-cancel').disabled = false;
  }


  if(options.xMark) {
    insertXMark();

  } else {
    insertCheckmark();
  }

  // options.errorList = JSON.parse(options.errorList);

  // TODO: Modularize this, can put in Utils

  if (options.xMark) {

    // Showing error message
    if (Array.isArray(options.errorList) && options.errorList.length > 0) {

      options.errorList.forEach(function(entry) {
        jQuery('.wps-connector-heading').after('<div class="notice notice-warning">' + entry + '</div>');
      });

    } else {
      jQuery('.wps-connector-heading').after('<div class="notice notice-warning">' + options.errorList + '</div>');

    }

  } else {
    jQuery('.wps-connector-heading').after('<div class="notice notice-success">Successfully disconnected</div>');

  }


  clearLocalstorageCache();
  resetConnectSubmit();
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
