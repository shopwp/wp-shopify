import to from 'await-to-js';
import isError from 'lodash/isError';

import {
  getItemCounts,
  syncPluginData
} from '../ws/middleware';

import {
  clearAllCache
} from '../ws/wrappers';

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
  updateDomAfterSync,
  createModal
} from '../utils/utils-dom';

import {
  setWebhooksReconnect
} from '../ws/localstorage';

import {
  post,
  deletion
} from '../ws/ws';

import {
  endpointConnectionCheck,
  endpointToolsClearSynced
} from '../ws/api/api-endpoints';

import {
  getPublishedProductIds
} from '../ws/api/api-products';

import {
  saveCounts
} from '../ws/api/api-syncing';

import {
  syncOn,
  removeExistingData
} from '../ws/syncing';

import {
  syncingConfigErrorBeforeSync,
  syncingConfigManualCancel,
  syncingConfigJavascriptError
} from '../ws/syncing-config';

import {
  enable,
  disable,
  isWordPressError,
  isJavascriptError,
  getDataFromArray,
  getWordPressErrorMessage,
  getJavascriptErrorMessage,
  getWordPressErrorType
} from '../utils/utils';

import {
  initSyncingTimer
} from '../utils/utils-timer';

import {
  getSelectiveSyncOptions
} from '../settings/settings.jsx';

import {
  startProgressBar,
  mapProgressDataFromSessionValues,
  appendProgressBars,
  progressStatus,
  cleanUpAfterSync,
  manuallyCanceled,
  afterDataRemoval
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
  disconnectInit
} from '../disconnect/disconnect';


/*

When Resync form is submitted ...

TODO: We could potentially enhance performance considerably if we do
checksum comparisons. Look into this.

*/
function onResyncSubmit() {


  jQuery("#wps-button-sync").off().on('click', function(e) {

    e.preventDefault();

    createModal('Resyncing', 'Cancel resync');


    return new Promise(async (resolve, reject) => {


      /*

      Clear all plugin cache

      */

      insertCheckmark();
      setConnectionStepMessage('Preparing for sync ...');

      var [clearAllCacheError, clearAllCacheData] = await to( clearAllCache() );

      if (clearAllCacheError) {
        cleanUpAfterSync( syncingConfigJavascriptError(clearAllCacheError) );
        return resolve();
      }

      if (isWordPressError(clearAllCacheData)) {
        cleanUpAfterSync( syncingConfigErrorBeforeSync(clearAllCacheData) );
        return resolve();
      }

      if (manuallyCanceled()) {
        cleanUpAfterSync( syncingConfigManualCancel() );
        return resolve();
      }


      /*

      Checks for an open connection to the server ...

      */

      insertCheckmark();
      setConnectionStepMessage('Validating server connection ...');

      var [serverCheckError, serverCheck] = await to( post( endpointConnectionCheck() ) );

      if (serverCheckError) {
        cleanUpAfterSync( syncingConfigJavascriptError(serverCheckError) );
        return resolve();
      }

      if (isWordPressError(serverCheck)) {
        cleanUpAfterSync( syncingConfigErrorBeforeSync(serverCheck) );
        return resolve();
      }

      if (manuallyCanceled()) {
        cleanUpAfterSync( syncingConfigManualCancel() );
        return resolve();
      }


      /*

      Sets the is_syncing flag in the database

      */
      var [syncOnError, syncOnData] = await to( syncOn() );

      if (syncOnError) {
        cleanUpAfterSync( syncingConfigJavascriptError(syncOnError) );
        return resolve();
      }

      if (isWordPressError(syncOnData)) {
        cleanUpAfterSync( syncingConfigErrorBeforeSync(syncOnData) );
        return resolve();
      }

      if (manuallyCanceled()) {
        cleanUpAfterSync( syncingConfigManualCancel() );
        return resolve();
      }


      /*

      Fires off the first background process. Server errors will be captured in the Database from here on.

      */

      insertCheckmark();
      setConnectionStepMessage('Removing any existing data first ...', '(Please wait, this might take 30 seconds or so)');

      var [removeExistingDataError, removeExistingDataResponse] = await to( deletion( endpointToolsClearSynced() ) );


      if (removeExistingDataError) {
        cleanUpAfterSync( syncingConfigJavascriptError(removeExistingDataError) );
        return resolve();
      }

      if (isWordPressError(removeExistingDataResponse)) {
        cleanUpAfterSync( syncingConfigErrorBeforeSync(removeExistingDataResponse) );
        return resolve();
      }

      if (manuallyCanceled()) {
        cleanUpAfterSync( syncingConfigManualCancel() );
        return resolve();
      }


      WP_Shopify.isSyncing = true;


      /*

      Only after data has been removed ...

      */
      afterDataRemoval(async (response) => {

        // Is called if our polling fails
        if (isJavascriptError(response)) {
          cleanUpAfterSync( syncingConfigJavascriptError(response) );
          return resolve();
        }

        if (isWordPressError(response)) {
          cleanUpAfterSync( syncingConfigErrorBeforeSync(response) );
          return resolve();
        }

        if (manuallyCanceled()) {
          cleanUpAfterSync( syncingConfigManualCancel() );
          return resolve();
        }




        /*

        Gets the total number of items from Shopify

        */

        insertCheckmark();
        setConnectionStepMessage('Determining the number of items to sync ...');

        var [getItemCountsError, getItemCountsData] = await to( getItemCounts() );

        if (getItemCountsError) {
          cleanUpAfterSync( syncingConfigJavascriptError(getItemCountsError) );
          return resolve();
        }

        if (isWordPressError(getItemCountsData)) {
          cleanUpAfterSync( syncingConfigErrorBeforeSync(getItemCountsData) );
          return resolve();
        }

        if (manuallyCanceled()) {
          cleanUpAfterSync( syncingConfigManualCancel() );
          return resolve();
        }


        /*

        5. Save item counts

        */

        var allCounts = filterOutEmptySets( filterOutSelectiveSync( filterOutAnyNotice( getDataFromArray(getItemCountsData) ) ) );

        var [saveCountsError, saveCountsData] = await to( saveCounts({
          counts: allCounts,
          exclusions: ['webhooks']
        }) );

        if (saveCountsError) {
          cleanUpAfterSync( syncingConfigJavascriptError(saveCountsError) );
          return resolve();
        }

        if (isWordPressError(saveCountsData)) {
          cleanUpAfterSync( syncingConfigErrorBeforeSync(saveCountsData) );
          return resolve();
        }

        if (manuallyCanceled()) {

          cleanUpAfterSync( syncingConfigManualCancel() );
          return resolve();

        }


        /*

        Grabs the getPublishedProductIds and saved them to the DB

        */
        var [publishedIdsError, publishedIdsData] = await to( getPublishedProductIds() );

        if (publishedIdsError) {
          cleanUpAfterSync( syncingConfigJavascriptError(publishedIdsError) );
          return resolve();
        }

        if (isWordPressError(publishedIdsData)) {
          cleanUpAfterSync( syncingConfigErrorBeforeSync(publishedIdsData) );
          return resolve();
        }

        if (manuallyCanceled()) {
          cleanUpAfterSync( syncingConfigManualCancel() );
          return resolve();
        }



        /*

        6. Start progress bar

        */
        var [startProgressBarError, startProgressBarData] = await to( startProgressBar(true, getSelectiveSyncOptions(), ['webhooks']) );

        if (startProgressBarError) {
          cleanUpAfterSync( syncingConfigJavascriptError(startProgressBarError) );
          return resolve();
        }

        if (isWordPressError(startProgressBarData)) {
          cleanUpAfterSync( syncingConfigErrorBeforeSync(startProgressBarData) );
          return resolve();
        }

        if (manuallyCanceled()) {
          cleanUpAfterSync( syncingConfigManualCancel() );
          return resolve();
        }


        /*

        8. Sync Data

        */

        insertCheckmark();
        updateModalButtonText('Cancel resyncing process');
        setConnectionStepMessage('Syncing Shopify data ...', '(Please wait, this may take up to 5 minutes depending on the size of your store and speed of internet connection.)');

        // Excluding webhooks from the resync
        appendProgressBars( filterOutSelectedDataForSync(allCounts, ['webhooks']) );


        //  Begins polling for sync status ...
        initSyncingTimer();
        progressStatus();

        syncPluginData(allCounts)


      });


    });

  });


}


export {
  onResyncSubmit
};
