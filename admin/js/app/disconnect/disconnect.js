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
  enable,
  setNonce,
  showSpinner,
  removeTrueAndTransformToArray,
  isWordPressError,
  isConnected
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
  resetConnectSubmit,
  updateDomAfterSync,
  resetConnectionDOM,
  getConnectorCancelButton,
  getToolsButtons
} from '../utils/utils-dom';

import {
  returnOnlyFailedRequests,
  constructFinalNoticeList,
  addToWarningList
} from '../utils/utils-data';

import {
  uninstallPlugin,
  removeConnectionData
} from '../ws/ws';

import {
  clearSync,
  syncOff
} from '../ws/wrappers';

import {
  setConnectionProgress,
  clearLocalstorageCache,
  removeConnectionProgress
} from '../ws/localstorage';

import {
  connectInit,
  prepareBeforeSync
} from '../connect/connect';

import {
  clearAllCache
} from '../tools/cache';

import {
  removeExistingData,
  syncOn
} from '../ws/syncing';


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

Updates the connector modal with the proper messaging

*/
function showCleanDataMessaging() {

  // updateModalHeadingText('Disconnecting ...');
  updateModalButtonText('Stop disconnecting');
  setConnectionStepMessage('Removing added data ...', '(Please wait, this may take up to 5 minutes depending on the size of your store and speed of your internet connection.)');
  // insertCheckmark();

  jQuery('.wps-progress-bar-wrapper').remove();

  attachStopEvent();

}


/*

Called when the user manually cancels an in-progress disconnect

*/
function attachStopEvent() {

  jQuery('.wps-btn-cancel').on('click', function() {

    if (!isConnected()) {
      resetConnectionDOM();
      resetConnectSubmit();
    }

    ejectConnectorModal();

  });

}


function disconnectionFormSubmitHandler(e) {

  e.preventDefault();

  return new Promise(async (resolve, reject) => {

    var warningList = [];

    prepareBeforeSync();

    updateModalHeadingText('Disconnecting ...');
    updateModalButtonText('Cancel disconnecting');
    setConnectionStepMessage('Preparing to disconnect ...');

    /*

    1. Turn syncing on

    */
    try {
      var syncOnResponse = await syncOn();

    } catch (errors) {

      updateDomAfterSync({
        noticeList: returnOnlyFailedRequests(errors)
      });

      resolve();
      return;

    }

    warningList = addToWarningList(warningList, syncOnResponse);


    /*

    2. Clear all cache

    */
    try {
      var clearAllCacheResponse = await clearAllCache();

    } catch (errors) {

      updateDomAfterSync({
        noticeList: returnOnlyFailedRequests(errors)
      });

      resolve();
      return;

    }


    insertCheckmark();
    setConnectionStepMessage('Removing added Shopify data ...', '(Please wait, this may take up to 5 minutes depending on the size of your store and speed of your internet connection.)');
    warningList = addToWarningList(warningList, clearAllCacheResponse);



    /*

    3. Remove product data

    */
    try {
      var removeExistingDataResponse = await removeExistingData();

    } catch (errors) {

      updateDomAfterSync({
        noticeList: returnOnlyFailedRequests(errors)
      });

      resolve();
      return;

    }

    warningList = addToWarningList(warningList, removeExistingDataResponse);


    /*

    4. Remove connection data

    */
    try {
      var removeConnectionDataResponse = await removeConnectionData();

    } catch (errors) {

      updateDomAfterSync({
        noticeList: returnOnlyFailedRequests(errors)
      });

      resolve();
      return;

    }


    insertCheckmark();
    setConnectionStepMessage('Cleaning up ...');
    warningList = addToWarningList(warningList, removeConnectionDataResponse);


    /*

    5. Turn sync off

    */
    try {
      var syncOffResponse = await syncOff();

    } catch (errors) {

      updateDomAfterSync({
        noticeList: returnOnlyFailedRequests(errors)
      });

      resolve();
      return;

    }

    warningList = addToWarningList(warningList, syncOffResponse);


    /*

    6. Finally update DOM

    */
    updateDomAfterSync({
      headingText: 'Disconnected',
      stepText: 'Finished disconnecting',
      noticeList: constructFinalNoticeList(warningList),
      noticeType: 'success'
    });

    clearConnectInputs();

    enable( getConnectorCancelButton() );
    disable( getToolsButtons() );

  });

}


/*

Disconnecting

*/
function onDisconnectionFormSubmit() {

  var $formConnect = jQuery("#wps-connect");
  var $submitButton = $formConnect.find('input[type="submit"]');

  unbindConnectForm();

  $formConnect.on('submit.disconnect', disconnectionFormSubmitHandler);

}


/*

Connect Init

*/
function disconnectInit() {
  onDisconnectionFormSubmit();
}

export {
  disconnectInit,
  showCleanDataMessaging
};
