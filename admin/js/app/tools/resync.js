import isError from 'lodash/isError';

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
  initCloseModalEvents,
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
  removePluginData,
  syncWithCPT
} from '../ws/ws.js';

import {
  onModalClose
} from '../forms/events';

import {
  enable,
  disable,
  showSpinner,
  isWordPressError
} from '../utils/utils';

import {
  clearAllCache
} from '../tools/cache';

import {
  updateDomAfterDisconnect
} from '../disconnect/disconnect.js';





/*

When Resync form is submitted ...

TODO: We could potentially enhance performance considerably if we do
checksum comparisons. Look into this.

*/
function onResyncSubmit() {

  jQuery(".wps-is-active #wps-button-sync").unbind().on('click', async function(e) {

    e.preventDefault();

    var $resyncButton = jQuery(this);

    clearLocalstorageCache();
    disable($resyncButton);
    injectConnectorModal( createConnectorModal('Resyncing ...', 'Cancel sync') );

    // Sets up close listenters
    onModalClose();
    setConnectionProgress(true);


    /*

    Step 1. Turn on syncing flag

    */
    try {
      var updatingSyncingIndicator = await setSyncingIndicator(1);

      if (isWordPressError(updatingSyncingIndicator)) {
        throw updatingSyncingIndicator.data;

      } else if (isError(updatingSyncingIndicator)) {
        throw updatingSyncingIndicator;

      } else {
        setConnectionStepMessage('Preparing for sync ...');

      }

    } catch(errors) {

      updateModalHeadingText('Canceling ...');

      updateDomAfterDisconnect({
        headingText: 'Canceled',
        buttonText: 'Exit Sync',
        xMark: true,
        errorList: errors
      });

      enable($resyncButton);
      return;

    }


    /*

    Step 2. Clearing current data

    */
    try {

      var removedResponse = await removePluginData();

      if (isWordPressError(removedResponse)) {
        throw removedResponse.data;

      } else if (isError(removedResponse)) {
        throw removedResponse;

      } else {
        setConnectionStepMessage('Syncing Shopify data ...', '(Please wait. This may take up to 60 seconds depending on the amount of products.)');

      }

    } catch(errors) {

      updateModalHeadingText('Canceling ...');

      updateDomAfterDisconnect({
        headingText: 'Canceled',
        buttonText: 'Exit Sync',
        xMark: true,
        errorList: errors
      });

      enable($resyncButton);
      return;

    }


    /*

    Step 2. Syncing new data

    */
    try {

      var syncPluginDataResp = await syncPluginData();

      if (isWordPressError(syncPluginDataResp)) {
        throw syncPluginDataResp.data;

      } else if (isError(syncPluginDataResp)) {
        throw syncPluginDataResp;

      } else {
        setConnectionStepMessage('Finishing ...');

      }

    } catch(errors) {

      updateModalHeadingText('Canceling ...');

      updateDomAfterDisconnect({
        headingText: 'Canceled',
        errorList: errors,
        buttonText: 'Exit Sync',
        xMark: true
      });

      enable($resyncButton);
      return;

    }


    /*

    Step 4. Sync new data with CPT

    */
    // try {
    //   var syncWithCPTResponse = await syncWithCPT();
    //
    //   if (isWordPressError(syncWithCPTResponse)) {
    //     throw syncWithCPTResponse.data;
    //
    //   } else if (isError(syncWithCPTResponse)) {
    //     throw syncWithCPTResponse;
    //
    //   } else {
    //     setConnectionStepMessage('Finishing ...');
    //   }
    //
    // } catch(errors) {
    //
    //   updateDomAfterDisconnect({
    //     stepText: 'Failed syncing custom post types',
    //     headingText: 'Canceled',
    //     errorList: errors,
    //     buttonText: 'Exit Sync',
    //     xMark: true
    //   });
    //
    //   enable($resyncButton);
    //
    //   return;
    //
    // }


    /*

    Step 5. Clear all plugin cache

    */
    try {

      var clearAllCacheResponse = await clearAllCache();

      if (isWordPressError(clearAllCacheResponse)) {
        throw clearAllCacheResponse.data;

      } else if (isError(clearAllCacheResponse)) {
        throw clearAllCacheResponse;

      }

    } catch(errors) {

      updateDomAfterDisconnect({
        xMark: true,
        headingText: 'Canceled',
        buttonText: 'Exit Sync',
        errorList: errors
      });

      enable($resyncButton);

      return;

    }


    /*

    Step 6. Setting Syncing Indicator

    */
    try {

      var updatingSyncingIndicatorResponse = await setSyncingIndicator(0);

      if (isWordPressError(updatingSyncingIndicatorResponse)) {
        throw updatingSyncingIndicatorResponse.data;

      } else if (isError(updatingSyncingIndicatorResponse)) {
        throw updatingSyncingIndicatorResponse;
      }

    } catch(errors) {

      updateDomAfterDisconnect({
        xMark: true,
        headingText: 'Canceled',
        buttonText: 'Exit Sync',
        errorList: errors
      });

      enable($resyncButton);

      return;

    }

    initCloseModalEvents();
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
