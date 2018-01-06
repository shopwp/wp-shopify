import isError from 'lodash/isError';

import {
  syncPluginData,
  getItemCounts
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
  setConnectionNotice
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
  syncWithCPT,
  saveCountsToSession
} from '../ws/ws.js';

import {
  onModalClose
} from '../forms/events';

import {
  enable,
  disable,
  showSpinner,
  isWordPressError,
  getDataFromArray
} from '../utils/utils';

import {
  startProgressBar,
  endProgressBar,
  mapProgressDataFromSessionValues,
  appendProgressBars,
  progressStatus
} from '../utils/utils-progress';

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
    injectConnectorModal( createConnectorModal('Re-syncing ...', 'Cancel sync') );

    // Sets up cancel & close listenters
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
      endProgressBar();

      updateDomAfterDisconnect({
        headingText: 'Canceled',
        buttonText: 'Exit Sync',
        xMark: true,
        errorList: errors,
        clearInputs: false,
        resync: true,
        noticeType: 'error'
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
        setConnectionStepMessage('Determining the number of items to sync ...');

      }

    } catch(errors) {

      updateModalHeadingText('Canceling ...');
      endProgressBar();

      updateDomAfterDisconnect({
        headingText: 'Canceled',
        buttonText: 'Exit Sync',
        xMark: true,
        errorList: errors,
        clearInputs: false,
        resync: true,
        noticeType: 'error'
      });

      enable($resyncButton);
      return;

    }


    /*

    Start the progress bar

    */
    try {

      var progressSession = await startProgressBar(true);

      if (isWordPressError(progressSession)) {
        throw progressSession.data;

      } else if (isError(progressSession)) {
        throw progressSession;
      }

    } catch (errors) {

      updateModalHeadingText('Canceling ...');
      endProgressBar();

      updateDomAfterDisconnect({
        headingText: 'Canceled',
        errorList: errors,
        buttonText: 'Exit Sync',
        xMark: true,
        clearInputs: false,
        resync: true,
        noticeType: 'error'
      });

      enable($resyncButton);
      return;

    }


    /*

    Step 2. Clearing current data

    */
    try {

      var allCounts = getDataFromArray( await getItemCounts() );

      if (isWordPressError(allCounts)) {
        console.log("1");
        throw allCounts.data;

      } else if (isError(allCounts)) {
        console.log("2");
        throw allCounts;

      } else {
        console.log("3");

      }

    } catch(errors) {
      console.log("4");
      updateModalHeadingText('Canceling ...');
      endProgressBar();
      console.log("5");
      updateDomAfterDisconnect({
        headingText: 'Canceled',
        buttonText: 'Exit Sync',
        xMark: true,
        errorList: errors,
        clearInputs: false,
        resync: true,
        noticeType: 'error'
      });

      enable($resyncButton);
      return;

    }


    /*

    Step 2. Clearing current data

    */
    try {

      console.log("allCounts: ", allCounts);

      var saveCountsResponse = await saveCountsToSession(allCounts);
      console.log("saveCountsResponse: ", saveCountsResponse);

      if (isWordPressError(saveCountsResponse)) {
        console.log("11");
        throw saveCountsResponse.data;

      } else if (isError(saveCountsResponse)) {
        console.log("22");
        throw saveCountsResponse;

      } else {
        console.log("33");
        setConnectionStepMessage('Syncing Shopify data ...', '(Please wait, this may take up to 5 minutes depending on the size of your store and speed of your internet connection.)');

      }

    } catch(errors) {
      console.log("44");
      updateModalHeadingText('Canceling ...');
      endProgressBar();
      console.log("55");
      updateDomAfterDisconnect({
        headingText: 'Canceled',
        buttonText: 'Exit Sync',
        xMark: true,
        errorList: errors,
        clearInputs: false,
        resync: true,
        noticeType: 'error'
      });

      enable($resyncButton);
      return;

    }



    /*

    Begin polling for the status ...

    */
    await progressStatus();

    // var steps = mapProgressDataFromSessionValues(progressSession.data);
    console.log("allCounts: ", allCounts);
    appendProgressBars(allCounts);


    /*

    Step 2. Syncing new data

    */
    try {

      var syncPluginDataResp = await syncPluginData();

      console.log("&&&&& syncPluginDataResp: ", syncPluginDataResp);

      if (isWordPressError(syncPluginDataResp)) {
        throw syncPluginDataResp.data;

      } else if (isError(syncPluginDataResp)) {
        throw syncPluginDataResp;

      } else {
        setConnectionStepMessage('Finishing ...');

      }

    } catch(errors) {
      console.log("syncPluginDatasyncPluginData: ", errors);
      updateModalHeadingText('Canceling ...');
      endProgressBar();

      updateDomAfterDisconnect({
        headingText: 'Canceled',
        errorList: errors,
        buttonText: 'Exit Sync',
        xMark: true,
        clearInputs: false,
        resync: true,
        noticeType: 'error'
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
    //     xMark: true,
    //     clearInputs: false,
    //     resync: true
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

      endProgressBar();

      updateDomAfterDisconnect({
        xMark: true,
        headingText: 'Canceled',
        buttonText: 'Exit Sync',
        errorList: errors,
        clearInputs: false,
        resync: true,
        noticeType: 'error'
      });

      enable($resyncButton);
      return;

    }


    /*

    End the progress bar

    */
    endProgressBar();


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
        errorList: errors,
        clearInputs: false,
        resync: true,
        noticeType: 'error'
      });

      enable($resyncButton);
      return;

    }

    initCloseModalEvents();
    insertCheckmark();
    setConnectionNotice('Success! You\'re now syncing with Shopify.', 'success');
    updateModalHeadingText('Sync Complete');
    updateModalButtonText("Ok, let's go!");
    enable($resyncButton);


  });


}


export {
  onResyncSubmit
};
