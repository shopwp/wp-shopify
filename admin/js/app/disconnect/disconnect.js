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
  removeTrueAndTransformToArray
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
  closeModal,
  insertCheckmark,
  updateConnectStatusHeading,
  clearConnectInputs,
  resetConnectSubmit
} from '../utils/utils-dom';

import {
  uninstallPlugin
} from '../ws/ws.js';

import {
  setConnectionProgress
} from '../ws/localstorage.js';

import {
  connectInit
} from '../connect/connect.js';


/*

On connection uninstall ...

*/
async function uninstallPluginData(options = false, reconnect = true) {

  if(options === false) {
    options = {
      headingText: 'Canceled',
      stepText: 'Canceled connection',
      buttonText: 'Exit Connection',
      xMark: true
    }
  }

  try {

    var uninstallData = await uninstallPlugin();

    if (uninstallData.hasOwnProperty('data')) {
      var errorList = removeTrueAndTransformToArray(uninstallData.data);

    } else {
      var errorList = removeTrueAndTransformToArray(uninstallData[0]);

    }

    updateDomAfterDisconnect(options, errorList);

    // Safe to reconnect again
    if (reconnect) {
      console.log('Initializing reconnect ...');
      connectInit();

    } else {
      console.log('NOT initializing reconnect ...');
    }

  } catch (error) {

    updateDomAfterDisconnect(options);
    return;

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
    setConnectionStepMessage('Disconnecting', '(Please wait. This may take up to 60 seconds depending on how many products you have.)');

    /*

    Disconnecting ..

    */
    try {

      var uninstallResponse = await uninstallPluginData({
        headingText: 'Disconnected',
        stepText: 'Disconnected Shopify store',
        buttonText: 'Exit Connection'
      });

      console.log("uninstallResponse: ", uninstallResponse);

      return true;

    } catch (error) {

      // Something happened, user needs to try
      // disconnecting again
      console.log('... Error disconnecting ...', error);
      return error;

    }

  });

}


/*

updateDomAfterDisconnect

*/
function updateDomAfterDisconnect(options, errorList = []) {

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

  if(!options.noticeText) {
    var noticeText = 'Successfully disconnected from Shopify.';

  } else {
    var noticeText = options.noticeText;

  }


  // TODO: Modularize this, can put in Utils
  if (Array.isArray(errorList) && errorList.length > 0) {

    errorList.forEach(function(entry) {
      jQuery('.wps-connector-heading').after('<div class="notice notice-warning">' + entry + '</div>');
    });

  } else {
    jQuery('.wps-connector-heading').after('<div class="notice notice-success">' + noticeText + '</div>');

  }


  resetConnectSubmit();
  closeModal();
  // unbindDisconnectForm();

  // var redirectURL = window.location.origin + window.location.pathname + '?page=wps-settings';
  // window.location.href = redirectURL;

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
