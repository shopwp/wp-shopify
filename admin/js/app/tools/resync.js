import {
  isError
} from 'lodash';

import {
  syncPluginData
} from '../ws/middleware';

import {
  createConnectorModal,
  injectConnectorModal,
  showConnectorModal,
  setConnectionStepMessage,
  showAdminNotice,
  updateModalButtonText,
  updateModalHeadingText,
  updateCurrentConnectionStepText,
  insertXMark,
  closeModal,
  insertCheckmark,
  setConnectionMessage
} from '../utils/utils-dom';

import {
  connectionInProgress,
  setConnectionProgress,
  setModalCache,
  clearLocalstorageCache
} from '../ws/localstorage.js';

import {
  setSyncingIndicator,
  removePluginData
} from '../ws/ws.js';

import {
  onModalClose
} from '../forms/events';

import {
  enable,
  disable,
  showSpinner
} from '../utils/utils';

import {
  uninstallPluginData,
  updateDomAfterDisconnect
} from '../disconnect/disconnect.js';


/*

When License key form is submitted ...

*/
function onResyncSubmit() {

  jQuery(".wps-is-active #wps-button-sync").unbind().on('click', async function(e) {

    e.preventDefault();

    var $resyncButton = jQuery(this);

    clearLocalstorageCache();

    disable($resyncButton);
    // R.forEach(showSpinner, $resyncButton);

    injectConnectorModal( createConnectorModal('Resyncing ...', 'Cancel sync') );

    // Sets up close listenters
    onModalClose();

    setConnectionProgress(true);



    /*

    Step 1. Turn on syncing flag

    */
    try {
      var updatingSyncingIndicator = await setSyncingIndicator(1);

    } catch(error) {

      updateModalHeadingText('Canceling ...');

      updateDomAfterDisconnect({
        noticeText: 'Syncing stopped and existing data cleared',
        headingText: 'Canceled',
        stepText: error,
        buttonText: 'Exit Sync',
        xMark: true
      }, 'Stopped syncing');

      enable($resyncButton);
      return;

    }


    /*

    Step 2. Clearing current data

    */
    try {

      setConnectionStepMessage('Clearing existing data');
      var removedResponse = await removePluginData();

      if (isError(removedResponse)) {
        throw new Error(removedResponse.message);
      }

    } catch(error) {

      updateModalHeadingText('Canceling ...');

      updateDomAfterDisconnect({
        noticeText: 'Syncing stopped and existing data cleared',
        headingText: 'Canceled',
        stepText: error,
        buttonText: 'Exit Sync',
        xMark: true
      }, 'Stopped syncing');

      enable($resyncButton);
      return;

    }


    /*

    Step 2. Syncing new data

    */
    try {

      setConnectionStepMessage('Syncing new data');
      var syncPluginDataResp = await syncPluginData();

      if (isError(syncPluginDataResp)) {
        throw new Error(syncPluginDataResp.message);
      }

    } catch(error) {

      updateModalHeadingText('Canceling ...');

      try {

        var uninstallResponse = await removePluginData();

        updateDomAfterDisconnect({
          headingText: 'Canceled',
          stepText: error,
          buttonText: 'Exit Sync',
          xMark: true
        });

        enable($resyncButton);
        return uninstallResponse;

      } catch(errorDataRemoval) {

        updateDomAfterDisconnect({
          noticeText: 'Syncing stopped and existing data cleared',
          headingText: 'Canceled',
          stepText: errorDataRemoval,
          buttonText: 'Exit Sync',
          xMark: true
        });

        enable($resyncButton);
        return uninstallResponse;

      }

    }


    /*

    Step 3. Setting Syncing Indicator

    */
    try {
      await setSyncingIndicator(0);

    } catch(error) {

      updateModalHeadingText('Canceling ...');

      updateDomAfterDisconnect({
        noticeText: 'Syncing stopped and existing data cleared',
        headingText: 'Canceled',
        stepText: error,
        buttonText: 'Exit Sync',
        xMark: true
      }, 'Stopped syncing');

      enable($resyncButton);
      return;

    }

    closeModal();
    insertCheckmark();
    setConnectionMessage('Success! You\'re now syncing with Shopify.', 'success');
    updateModalHeadingText('Sync Complete');
    updateModalButtonText("Ok, let's go!");
    enable($resyncButton);

  });

}


export {
  onResyncSubmit
};
