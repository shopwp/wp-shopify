import forEach from 'lodash/forEach';
import to from 'await-to-js';

import {
  unbindDisconnectForm,
  unbindConnectForm
} from '../forms/forms';

import {
  disable,
  enable,
  removeTrueAndTransformToArray,
  isWordPressError,
  isConnected,
  hideLoader
} from '../utils/utils';

import {
  injectConnectorModal,
  ejectConnectorModal,
  showConnectorModal,
  setConnectionStepMessage,
  updateModalButtonText,
  updateModalHeadingText,
  updateCurrentConnectionStepText,
  insertXMark,
  insertCheckmark,
  clearConnectInputs,
  resetConnectSubmit,
  updateDomAfterSync,
  resetConnectionDOM,
  getConnectorCancelButton,
  getToolsButtons,
  hideAdminNoticeByType,
  showAdminNotice,
  resetSyncByCollectionOptions,
  createModal
} from '../utils/utils-dom';

import {
  returnOnlyFailedRequests,
  constructFinalNoticeList,
  addToWarningList,
  setConnectionStatus,
  returnOnlyFirstError
} from '../utils/utils-data';

import {
  removeConnectionData,
  removeWebhooks,
  checkForActiveConnection,
  deleteOnlySyncedData,
  resetNoticeFlags
} from '../ws/ws';

import {
  afterWebhooksRemoval,
  cleanUpAfterSync,
  manuallyCanceled
} from '../utils/utils-progress';

import {
  syncOff,
  resetNoticesAndClearCache,
  noConnectionReset
} from '../ws/wrappers';

import {
  clearLocalstorageCache,
  removeConnectionProgress,
  setWebhooksReconnect
} from '../ws/localstorage';

import {
  connectInit
} from '../connect/connect';

import {
  clearAllCache
} from '../tools/cache';

import {
  removeExistingData,
  syncOn
} from '../ws/syncing';

import {
  syncingConfigJavascriptError,
  syncingConfigErrorBeforeSync,
  syncingConfigDisconnection,
  syncingConfigManualCancel
} from '../ws/syncing-config';


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

Callback fired on disconnect form submit

*/
function disconnectionFormSubmitHandler(e) {

  e.preventDefault();

  createModal('Disconnecting', 'Cancel disconnecting');

  return new Promise(async (resolve, reject) => {

    setConnectionStepMessage('Checking for active connection ...');

    WP_Shopify.isDisconnecting = true;



    /*

    Step 1. Clearing current data

    */
    var [checkForActiveConnectionError, checkForActiveConnectionData] = await to( checkForActiveConnection() );

    if (checkForActiveConnectionError) {
      cleanUpAfterSync( syncingConfigJavascriptError(checkForActiveConnectionError) );
      resolve();
      return
    }

    if (manuallyCanceled()) {
      cleanUpAfterSync( syncingConfigManualCancel() );
      resolve();
      return;
    }

    if (isWordPressError(checkForActiveConnectionData)) {

      // No active connection exists. Just drop data and clear cache.
      var [noConnectionResetError, noConnectionResetData] = await to( noConnectionReset() );

      if (noConnectionResetError) {
        cleanUpAfterSync( syncingConfigJavascriptError(noConnectionResetError) );
        resolve();
        return
      }

      if (isWordPressError(noConnectionResetData)) {
        cleanUpAfterSync( syncingConfigErrorBeforeSync( returnOnlyFirstError(noConnectionResetData) ) );
        resolve();
        return;
      }

      if (manuallyCanceled()) {
        cleanUpAfterSync( syncingConfigManualCancel() );
        resolve();
        return;
      }

      showAdminNotice('Successfully removed all data', 'updated');
      hideAdminNoticeByType('notice_warning_app_uninstalled');
      setConnectionStatus(false);

      cleanUpAfterSync( syncingConfigDisconnection() );
      resolve();
      return;

    }


    /*

    1. Turn syncing on

    */

    insertCheckmark();
    setConnectionStepMessage('Removing added Shopify data ...', '(Please wait, this may take up to 5 minutes depending on the size of your store and speed of internet connection.)');

    var [syncOnError, syncOnData] = await to( syncOn() );

    if (syncOnError) {
      cleanUpAfterSync( syncingConfigJavascriptError(syncOnError) );
      resolve();
      return
    }

    if (isWordPressError(syncOnData)) {
      cleanUpAfterSync( syncingConfigErrorBeforeSync( returnOnlyFirstError(syncOnData) ) );
      resolve();
      return;
    }

    if (manuallyCanceled()) {
      cleanUpAfterSync( syncingConfigManualCancel() );
      resolve();
      return;
    }


    /*

    3. Remove product data

    */
    var [removeExistingDataError, removeExistingDataResp] = await to( removeExistingData() );

    if (removeExistingDataError) {
      cleanUpAfterSync( syncingConfigJavascriptError(removeExistingDataError) );
      resolve();
      return
    }

    if (isWordPressError(removeExistingDataResp)) {
      cleanUpAfterSync( syncingConfigErrorBeforeSync( returnOnlyFirstError(removeExistingDataResp) ) );
      resolve();
      return;
    }

    if (manuallyCanceled()) {
      cleanUpAfterSync( syncingConfigManualCancel() );
      resolve();
      return;
    }


    /*

    Remove webhooks

    */

    setConnectionStatus(false);




      // Remove connection data
      var [removeConnectionDataError, removeConnectionDataData] = await to( removeConnectionData() );

      if (removeConnectionDataError) {
        cleanUpAfterSync( syncingConfigJavascriptError(removeConnectionDataError) );
        resolve();
        return
      }

      if (isWordPressError(removeConnectionDataData)) {
        cleanUpAfterSync( syncingConfigErrorBeforeSync( returnOnlyFirstError(removeConnectionDataData) ) );
        resolve();
        return;
      }

      if (manuallyCanceled()) {
        cleanUpAfterSync( syncingConfigManualCancel() );
        resolve();
        return;
      }


      /*

      6. Turn sync off

      */

      insertCheckmark();
      setConnectionStepMessage('Cleaning up ...');

      var [syncOffError, syncOffData] = await to( syncOff() );

      if (syncOffError) {
        cleanUpAfterSync( syncingConfigJavascriptError(syncOffError) );
        resolve();
        return
      }

      if (isWordPressError(syncOffData)) {
        cleanUpAfterSync( syncingConfigErrorBeforeSync( returnOnlyFirstError(syncOffData) ) );
        resolve();
        return;
      }

      if (manuallyCanceled()) {
        cleanUpAfterSync( syncingConfigManualCancel() );
        resolve();
        return;
      }


      /*

      2. Clear all cache

      */
      var [resetNoticesAndClearCacheError, resetNoticesAndClearCacheData] = await to( resetNoticesAndClearCache() );

      if (resetNoticesAndClearCacheError) {
        cleanUpAfterSync( syncingConfigJavascriptError(resetNoticesAndClearCacheError) );
        resolve();
        return
      }

      if (isWordPressError(resetNoticesAndClearCacheData)) {
        cleanUpAfterSync( syncingConfigErrorBeforeSync( returnOnlyFirstError(resetNoticesAndClearCacheData) ) );
        resolve();
        return;
      }

      if (manuallyCanceled()) {
        cleanUpAfterSync( syncingConfigManualCancel() );
        resolve();
        return;
      }


      // Finish
      cleanUpAfterSync( syncingConfigDisconnection() );




  });


}


/*

Disconnecting

*/
function onDisconnectionFormSubmit() {

  var $formConnect = jQuery("#wps-connect");
  var $submitButton = $formConnect.find('input[type="submit"]');

  unbindConnectForm();

  $formConnect.off('submit.disconnect').on('submit.disconnect', disconnectionFormSubmitHandler);

}


/*

Connect Init

*/
function disconnectInit() {
  onDisconnectionFormSubmit();
}

export {
  disconnectInit
};
