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
  addToWarningList,
  setConnectionStatus,
  returnOnlyFirstError
} from '../utils/utils-data';

import {
  post
} from '../ws/ws';

import {
  deleteWebhooks
} from '../ws/api/api-webhooks';

import {
  endpointConnectionDelete,
  endpointConnectionCheck,
  endpointToolsClearAll,
  endpointToolsClearSynced
} from '../ws/api/api-endpoints';

import {
  afterWebhooksRemoval,
  cleanUpAfterSync,
  manuallyCanceled
} from '../utils/utils-progress';

import {
  clearAllCache,
  noConnectionReset
} from '../ws/wrappers';

import {
  clearLocalstorageCache,
  removeConnectionProgress,
  setWebhooksReconnect
} from '../ws/localstorage';

import {
  connectInit,
  getConnectionFormData
} from '../connect/connect';

import {
  removeExistingData,
  syncOn,
  syncOff
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



    var [syncOnError, syncOnData] = await to( syncOn() );

    if (syncOnError) {
      cleanUpAfterSync( syncingConfigJavascriptError(syncOnError) );
      return resolve();
    }

    if (isWordPressError(syncOnData)) {
      cleanUpAfterSync( syncingConfigErrorBeforeSync( returnOnlyFirstError(syncOnData) ) );
      return resolve();
    }

    if (manuallyCanceled()) {
      cleanUpAfterSync( syncingConfigManualCancel() );
      return resolve();
    }


    /*

    1. Turn syncing on

    */

    insertCheckmark();
    setConnectionStepMessage('Removing added Shopify data ...', '(Please wait, this may take up to 5 minutes depending on the size of your store and speed of internet connection.)');


    /*

    3. Remove product data

    */
    var [removeExistingDataError, removeExistingDataResp] = await to( post( endpointToolsClearSynced() ) );

    if (removeExistingDataError) {
      cleanUpAfterSync( syncingConfigJavascriptError(removeExistingDataError) );
      return resolve();
    }

    if (isWordPressError(removeExistingDataResp)) {
      cleanUpAfterSync( syncingConfigErrorBeforeSync( returnOnlyFirstError(removeExistingDataResp) ) );
      return resolve();
    }

    if (manuallyCanceled()) {
      cleanUpAfterSync( syncingConfigManualCancel() );
      return resolve();
    }


    /*

    Remove webhooks

    */

    setConnectionStatus(false);




      // Remove connection data
      var [deleteConnectionError, deleteConnectionData] = await to( post( endpointConnectionDelete() ));

      if (deleteConnectionError) {
        cleanUpAfterSync( syncingConfigJavascriptError(deleteConnectionError) );
        return resolve();
      }

      if (isWordPressError(deleteConnectionData)) {
        cleanUpAfterSync( syncingConfigErrorBeforeSync( returnOnlyFirstError(deleteConnectionData) ) );
        return resolve();
      }

      if (manuallyCanceled()) {
        cleanUpAfterSync( syncingConfigManualCancel() );
        return resolve();
      }


      /*

      6. Turn sync off

      */

      insertCheckmark();
      setConnectionStepMessage('Cleaning up ...');

      var [syncOffError, syncOffData] = await to( syncOff() );

      if (syncOffError) {
        cleanUpAfterSync( syncingConfigJavascriptError(syncOffError) );
        return resolve();
      }

      if (isWordPressError(syncOffData)) {
        cleanUpAfterSync( syncingConfigErrorBeforeSync( returnOnlyFirstError(syncOffData) ) );
        return resolve();
      }

      if (manuallyCanceled()) {
        cleanUpAfterSync( syncingConfigManualCancel() );
        return resolve();
      }


      /*

      2. Clear all cache

      */
      var [clearAllCacheError, clearAllCacheData] = await to( clearAllCache() );

      if (clearAllCacheError) {
        cleanUpAfterSync( syncingConfigJavascriptError(clearAllCacheError) );
        return resolve();
      }

      if (isWordPressError(clearAllCacheData)) {
        cleanUpAfterSync( syncingConfigErrorBeforeSync( returnOnlyFirstError(clearAllCacheData) ) );
        return resolve();
      }

      if (manuallyCanceled()) {
        cleanUpAfterSync( syncingConfigManualCancel() );
        return resolve();
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
