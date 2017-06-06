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
  showSpinner
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
async function uninstallPluginData(options = false) {

  if(options === false) {
    options = {
      headingText: 'Canceled',
      stepText: 'Canceled connection',
      buttonText: 'Exit Connection',
      xMark: true
    }
  }

  console.log('Begin uninstalling ... ');

  try {
    var uninstallData = await uninstallPlugin();

    console.log('Successfully uninstalled', uninstallData);
    updateDomAfterDisconnect(options);

    // Safe to reconnect again
    connectInit();
    // unbindDisconnectForm();


  } catch (error) {

    console.log('Error uninstalling ...', error);
    updateDomAfterDisconnect(options);

  }

}


/*

Disconnecting

*/
function onDisconnectionFormSubmit() {

  var $formConnect = jQuery("#wps-connect");
  var $submitButton = $formConnect.find('input[type="submit"]');

  console.log('disconnecting rdy');

  unbindConnectForm();

  $formConnect.on('submit.disconnect', async function(e) {

    e.preventDefault();
    console.log(1);
    // Remove previous connector modal if exists
    ejectConnectorModal();
    console.log(2);
    var $formInputNonce = jQuery("#wps_settings_connection_nonce_id");
    var $connectorModal = createConnectorModal();
    console.log(3);
    setConnectionProgress("true");
    console.log(4);
    disable($submitButton);
    console.log(5);
    R.forEach(showSpinner, $submitButton);
    console.log(6);
    injectConnectorModal($connectorModal);
    console.log(7);
    // Close Listenter
    onModalClose();
    console.log(8);
    updateModalHeadingText('Disconnecting ...');
    console.log(9);
    updateModalButtonText('Stop disconnecting');
    console.log(10);
    showConnectorModal($connectorModal);
    console.log(11);
    setNonce( $formInputNonce.val() );
    console.log(12);
    setConnectionStepMessage('Disconnecting', '(Please wait. This may take up to 60 seconds depending on how many products you have.)');
    console.log(13);

    /*

    Disconnecting ..

    */
    try {
      console.log(14);
      console.log('... Cleaning up ...');

      await uninstallPluginData({
        headingText: 'Disconnected',
        stepText: 'Disconnected Shopify store',
        buttonText: 'Exit Connection'
      });
      console.log(15);
      return true;

    } catch (error) {
      console.log(16);
      // Something happened, user needs to try
      // disconnecting again
      console.log('... Error disconnecting ...', error);
      return error;

    }

    console.log(17);

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
  uninstallPluginData
};
