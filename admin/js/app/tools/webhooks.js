import isError from 'lodash/isError';
import to from 'await-to-js';

import {
  initSyncingTimer
} from '../utils/utils-timer';

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
  setWebhooksReconnect,
  setCancelSync
} from '../ws/localstorage';

import {
  getItemCounts
} from '../ws/middleware';

import {
  deleteWebhooks,
  registerWebhooks
} from '../ws/api/api-webhooks';

import {
  saveCounts
} from '../ws/api/api-syncing';

import {
  syncOn
} from '../ws/syncing';

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
      return resolve();
    }

    if (isWordPressError(syncOnResponse)) {
      cleanUpAfterSync( syncingConfigErrorBeforeSync(syncOnResponse) );
      return resolve();
    }

    if (manuallyCanceled()) {
      cleanUpAfterSync( syncingConfigManualCancel() );
      return resolve();
    }



    insertCheckmark();
    setConnectionStepMessage('Removing any existing webhooks first ...', '(Please wait, this might take 30 seconds or so)');


    /*

    2. Remove webhook

    */

    var [deleteWebhooksError, deleteWebhooksResponse] = await to( deleteWebhooks() );

    if (deleteWebhooksError) {
      cleanUpAfterSync( syncingConfigJavascriptError(deleteWebhooksError) );
      return resolve();
    }

    if (isWordPressError(deleteWebhooksResponse)) {
      cleanUpAfterSync( syncingConfigErrorBeforeSync(deleteWebhooksResponse) );
      return resolve();
    }

    if (manuallyCanceled()) {
      cleanUpAfterSync( syncingConfigManualCancel() );
      return resolve();
    }


    /*

    Only fires once webhooks have been removed ...

    */
    afterWebhooksRemoval( async () => {

      var [itemCountsRespError, itemCountsResp] = await to( getItemCounts() ); // delete_webhooks

      if (itemCountsRespError) {
        cleanUpAfterSync( syncingConfigJavascriptError(itemCountsRespError) );
        return resolve();
      }

      if (isWordPressError(itemCountsResp)) {
        cleanUpAfterSync( syncingConfigErrorBeforeSync(itemCountsResp) );
        return resolve();
      }

      if (manuallyCanceled()) {
        cleanUpAfterSync( syncingConfigManualCancel() );
        return resolve();
      }



      var allCounts = filterOutEmptySets( filterOutSelectiveSync( filterOutAnyNotice( getDataFromArray(itemCountsResp) ) ) );



      /*

      5. Save item counts

      */
      var [saveCountsError, saveCountsResponse] = await to( saveCounts({
        counts: allCounts,
        exclusions: [
          'connection',
          'shop',
          'smart_collections',
          'custom_collections',
          'products',
          'collects',
          'orders',
          'customers'
        ]
      }) );



      if (saveCountsError) {
        cleanUpAfterSync( syncingConfigJavascriptError(saveCountsError) );
        return resolve();
      }

      if (isWordPressError(saveCountsResponse)) {
        cleanUpAfterSync( syncingConfigErrorBeforeSync(saveCountsResponse) );
        return resolve();
      }

      if (manuallyCanceled()) {
        cleanUpAfterSync( syncingConfigManualCancel() );
        return resolve();
      }



      insertCheckmark();
      setConnectionStepMessage('Syncing new webhooks ...');



      /*

      3. Start progress bar

      */

      var [progressSessionError, progressSession] = await to( startProgressBar(true, ['webhooks']) );


      if (progressSessionError) {
        cleanUpAfterSync( syncingConfigJavascriptError(progressSessionError) );
        return resolve();
      }

      if (isWordPressError(progressSession)) {
        cleanUpAfterSync( syncingConfigErrorBeforeSync(progressSession) );
        return resolve();
      }

      if (manuallyCanceled()) {
        cleanUpAfterSync( syncingConfigManualCancel() );
        return resolve();
      }


      /*

      4. Begin polling for the status ... creates a cancelable loop

      */



      appendProgressBars( filterOutSelectedDataForSync(allCounts, [
        'shop',
        'smart_collections',
        'custom_collections',
        'products',
        'collects',
        'orders',
        'customers'
      ]) );

      setWebhooksReconnect(true);
      initSyncingTimer();
      progressStatus();
      registerWebhooks();


    });

  });


}


export {
  onWebhooksSubmit
};
