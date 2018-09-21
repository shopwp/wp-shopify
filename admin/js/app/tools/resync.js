import to from 'await-to-js';
import isError from 'lodash/isError';

import {
  getItemCounts,
  syncPluginData
} from '../ws/middleware';

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
  setSyncingIndicator,
  checkForValidServerConnection,
  getPublishedProductIds
} from '../ws/ws';

import {
  syncOff
} from '../ws/wrappers';

import {
  syncOn,
  saveCounts,
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
  getSelectiveSyncOptions
} from '../settings/settings';

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
  clearAllCache
} from '../tools/cache';

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
        resolve();
        return;
      }

      if (isWordPressError(clearAllCacheData)) {
        cleanUpAfterSync( syncingConfigErrorBeforeSync(clearAllCacheData) );
        resolve();
        return;
      }

      if (manuallyCanceled()) {
        cleanUpAfterSync( syncingConfigManualCancel() );
        resolve();
        return;
      }


      /*

      Checks for an open connection to the server ...

      */

      insertCheckmark();
      setConnectionStepMessage('Validating Shopify connection ...');

      var [serverConnectionError, serverConnectionData] = await to( checkForValidServerConnection() );

      if (serverConnectionError) {
        cleanUpAfterSync( syncingConfigJavascriptError(serverConnectionError) );
        resolve();
        return;
      }

      if (isWordPressError(serverConnectionData)) {
        cleanUpAfterSync( syncingConfigErrorBeforeSync(serverConnectionData) );
        resolve();
        return;
      }

      if (manuallyCanceled()) {
        cleanUpAfterSync( syncingConfigManualCancel() );
        resolve();
        return;
      }


      /*

      Fires off the first background process. Server errors will be captured in the Database from here on.

      Calls delete_only_synced_data from Async_Processing_Database

      */

      insertCheckmark();
      setConnectionStepMessage('Removing any existing data first ...', '(Please wait, this might take 30 seconds or so)');

      var [removeExistingDataError, removeExistingDataResponse] = await to( removeExistingData() );

      if (removeExistingDataError) {
        cleanUpAfterSync( syncingConfigJavascriptError(removeExistingDataError) );
        resolve();
        return;
      }

      if (isWordPressError(removeExistingDataResponse)) {
        cleanUpAfterSync( syncingConfigErrorBeforeSync(removeExistingDataResponse) );
        resolve();
        return;
      }

      if (manuallyCanceled()) {
        cleanUpAfterSync( syncingConfigManualCancel() );
        resolve();
        return;
      }


      /*

      Only after data has been removed ...

      */
      afterDataRemoval(async (response) => {

        // Is called if our polling fails
        if (isJavascriptError(response)) {
          cleanUpAfterSync( syncingConfigJavascriptError(response) );
          resolve();
          return;
        }

        if (isWordPressError(response)) {
          cleanUpAfterSync( syncingConfigErrorBeforeSync(response) );
          resolve();
          return;
        }

        if (manuallyCanceled()) {
          cleanUpAfterSync( syncingConfigManualCancel() );
          resolve();
          return;
        }




        /*

        Sets the is_syncing flag in the database

        */
        var [syncOnError, syncOnData] = await to( syncOn() );

        if (syncOnError) {
          cleanUpAfterSync( syncingConfigJavascriptError(syncOnError) );
          resolve();
          return;
        }

        if (isWordPressError(syncOnData)) {
          cleanUpAfterSync( syncingConfigErrorBeforeSync(syncOnData) );
          resolve();
          return;
        }

        if (manuallyCanceled()) {
          cleanUpAfterSync( syncingConfigManualCancel() );
          resolve();
          return;
        }


        /*

        Gets the total number of items from Shopify

        */

        insertCheckmark();
        setConnectionStepMessage('Determining the number of items to sync ...');

        var [getItemCountsError, getItemCountsData] = await to( getItemCounts() );

        if (getItemCountsError) {
          cleanUpAfterSync( syncingConfigJavascriptError(getItemCountsError) );
          resolve();
          return;
        }

        if (isWordPressError(getItemCountsData)) {
          cleanUpAfterSync( syncingConfigErrorBeforeSync(getItemCountsData) );
          resolve();
          return;
        }

        if (manuallyCanceled()) {
          cleanUpAfterSync( syncingConfigManualCancel() );
          resolve();
          return;
        }


        /*

        5. Save item counts

        */
        var allCounts = filterOutEmptySets( filterOutSelectiveSync( filterOutAnyNotice( getDataFromArray(getItemCountsData) ) ) );


        var [saveCountsError, saveCountsData] = await to( saveCounts(allCounts, ['webhooks']) ); // insert_syncing_totals

        if (saveCountsError) {
          cleanUpAfterSync( syncingConfigJavascriptError(saveCountsError) );
          resolve();
          return;
        }

        if (isWordPressError(saveCountsData)) {
          cleanUpAfterSync( syncingConfigErrorBeforeSync(saveCountsData) );
          resolve();
          return;
        }

        if (manuallyCanceled()) {

          cleanUpAfterSync( syncingConfigManualCancel() );
          resolve();
          return;

        }


        /*

        Grabs the getPublishedProductIds and saved them to the DB

        */
        var [publishedIdsError, publishedIdsData] = await to( getPublishedProductIds() );

        if (publishedIdsError) {
          cleanUpAfterSync( syncingConfigJavascriptError(publishedIdsError) );
          resolve();
          return;
        }

        if (isWordPressError(publishedIdsData)) {
          cleanUpAfterSync( syncingConfigErrorBeforeSync(publishedIdsData) );
          resolve();
          return;
        }

        if (manuallyCanceled()) {
          cleanUpAfterSync( syncingConfigManualCancel() );
          resolve();
          return;
        }



        /*

        6. Start progress bar

        */
        var [startProgressBarError, startProgressBarData] = await to( startProgressBar(true, getSelectiveSyncOptions(), ['webhooks']) );

        if (startProgressBarError) {
          cleanUpAfterSync( syncingConfigJavascriptError(startProgressBarError) );
          resolve();
          return;
        }

        if (isWordPressError(startProgressBarData)) {
          cleanUpAfterSync( syncingConfigErrorBeforeSync(startProgressBarData) );
          resolve();
          return;
        }

        if (manuallyCanceled()) {
          cleanUpAfterSync( syncingConfigManualCancel() );
          resolve();
          return;
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
        progressStatus();

        syncPluginData(allCounts)


      });


    });

  });


}


export {
  onResyncSubmit
};
