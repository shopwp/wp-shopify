import isError from 'lodash/isError';
import to from 'await-to-js';

import {
  injectConnectorModal,
  showConnectorModal,
  setConnectionStepMessage,
  showAdminNotice,
  updateModalButtonText,
  updateModalHeadingText,
  updateCurrentConnectionStepText,
  insertXMark,
  insertCheckmark,
  setConnectionNotice,
  addNotice,
  showAnyWarnings,
  updateDomAfterSync,
  createModal
} from '../utils/utils-dom';

import {
  setModalCache,
  syncIsCanceled,
  setWebhooksReconnect,
  setCancelSync
} from '../ws/localstorage';

import {
  getItemCounts
} from '../ws/middleware';

import {
  setSyncingIndicator,
  removeWebhooks,
  registerWebhooks
} from '../ws/ws';

import {
  syncWebhooks,
  syncOn,
  saveCounts
} from '../ws/syncing';

import {
  syncOff
} from '../ws/wrappers.js';

import {
  enable,
  disable,
  isWordPressError,
  getDataFromArray
} from '../utils/utils';

import {
  startProgressBar,
  mapProgressDataFromSessionValues,
  appendProgressBars,
  progressStatus,
  afterWebhooksRemoval,
  cleanUpAfterSync,
  manuallyCanceled
} from '../utils/utils-progress';

import {
  returnOnlyFailedRequests,
  constructFinalNoticeList,
  addToWarningList,
  filterOutAnyNotice,
  filterOutSelectiveSync,
  filterOutEmptySets,
  filterOutSelectedDataForSync
} from '../utils/utils-data';

import {
  syncingConfigJavascriptError,
  syncingConfigErrorBeforeSync,
  syncingConfigManualCancel
} from '../ws/syncing-config';


/*

Webhook re-sync

TODO: We could potentially enhance performance considerably if we do
checksum comparisons. Look into this.

*/
function onWebhooksSubmit() {

  jQuery("#wps-button-webhooks")
    .off()
    .on('click', webhooksSubmitCallback);

}


/*

Webhook re-sync callback

*/
async function webhooksSubmitCallback(e) {

  e.preventDefault();

  createModal('Reconnecting Webhooks', 'Cancel webhooks sync');

  WP_Shopify.reconnectingWebhooks = true;
  WP_Shopify.isSyncing = true;

  return new Promise(async (resolve, reject) => {

    setConnectionStepMessage('Preparing sync ...');


    var [syncOnResponseError, syncOnResponse] = await to( syncOn() );

    if (syncOnResponseError) {
      cleanUpAfterSync( syncingConfigJavascriptError(syncOnResponseError) );
      resolve();
      return;
    }

    if (isWordPressError(syncOnResponse)) {
      cleanUpAfterSync( syncingConfigErrorBeforeSync(syncOnResponse) );
      resolve();
      return;
    }

    if (manuallyCanceled()) {
      cleanUpAfterSync( syncingConfigManualCancel() );
      resolve();
      return;
    }



    insertCheckmark();
    setConnectionStepMessage('Removing any existing webhooks first ...', '(Please wait, this might take 30 seconds or so)');


    /*

    2. Remove webhook

    */

    var [removeWebhooksError, removeWebhooksResponse] = await to( removeWebhooks() ); // delete_webhooks

    if (removeWebhooksError) {
      cleanUpAfterSync( syncingConfigJavascriptError(removeWebhooksError) );
      resolve();
      return;
    }

    if (isWordPressError(removeWebhooksResponse)) {
      cleanUpAfterSync( syncingConfigErrorBeforeSync(removeWebhooksResponse) );
      resolve();
      return;
    }

    if (manuallyCanceled()) {
      cleanUpAfterSync( syncingConfigManualCancel() );
      resolve();
      return;
    }


    /*

    Only fires once webhooks have been removed ...

    */
    afterWebhooksRemoval(async () => {

      var [itemCountsRespError, itemCountsResp] = await to( getItemCounts() ); // delete_webhooks

      if (itemCountsRespError) {
        cleanUpAfterSync( syncingConfigJavascriptError(itemCountsRespError) );
        resolve();
        return;
      }

      if (isWordPressError(itemCountsResp)) {
        cleanUpAfterSync( syncingConfigErrorBeforeSync(itemCountsResp) );
        resolve();
        return;
      }

      if (manuallyCanceled()) {
        cleanUpAfterSync( syncingConfigManualCancel() );
        resolve();
        return;
      }




      var allCounts = filterOutEmptySets( filterOutSelectiveSync( filterOutAnyNotice( getDataFromArray(itemCountsResp) ) ) );


      /*

      5. Save item counts

      */
      var [saveCountsError, saveCountsResponse] = await to( saveCounts( allCounts, [
        'connection',
        'shop',
        'smart_collections',
        'custom_collections',
        'products',
        'collects',
        'orders',
        'customers'
      ]) ); // delete_webhooks

      if (saveCountsError) {
        cleanUpAfterSync( syncingConfigJavascriptError(saveCountsError) );
        resolve();
        return;
      }

      if (isWordPressError(saveCountsResponse)) {
        cleanUpAfterSync( syncingConfigErrorBeforeSync(saveCountsResponse) );
        resolve();
        return;
      }

      if (manuallyCanceled()) {
        cleanUpAfterSync( syncingConfigManualCancel() );
        resolve();
        return;
      }



      insertCheckmark();
      setConnectionStepMessage('Syncing new webhooks ...');



      /*

      3. Start progress bar

      */

      var [progressSessionError, progressSession] = await to( startProgressBar(true, ['webhooks']) );

      if (progressSessionError) {
        cleanUpAfterSync( syncingConfigJavascriptError(progressSessionError) );
        resolve();
        return;
      }

      if (isWordPressError(progressSession)) {
        cleanUpAfterSync( syncingConfigErrorBeforeSync(progressSession) );
        resolve();
        return;
      }

      if (manuallyCanceled()) {
        cleanUpAfterSync( syncingConfigManualCancel() );
        resolve();
        return;
      }


      /*

      4. Begin polling for the status ... creates a cancelable loop

      */

      appendProgressBars(progressSession.data);
      setWebhooksReconnect(true);

      progressStatus();

      syncWebhooks(removeWebhooksResponse.data); // register_all_webhooks


    });

  });


}


export {
  onWebhooksSubmit
};
