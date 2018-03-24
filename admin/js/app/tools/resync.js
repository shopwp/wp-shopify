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
  setConnectionNotice,
  updateDomAfterSync
} from '../utils/utils-dom';

import {
  setWebhooksReconnect
} from '../ws/localstorage.js';

import {
  setSyncingIndicator,
  syncWithCPT,
  saveCountsToSession
} from '../ws/ws.js';

import {
  syncOff,
  clearSync
} from '../ws/wrappers.js';

import {
  syncOn,
  saveConnection,
  saveCounts,
  removeExistingData,
  syncData
} from '../ws/syncing';

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
  getSelectiveSyncOptions
} from '../settings/settings';

import {
  startProgressBar,
  mapProgressDataFromSessionValues,
  appendProgressBars,
  progressStatus,
  forceProgressBarsComplete
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
  activateToolButtons
} from './tools';

import {
  prepareBeforeSync
} from '../connect/connect';

import {
  disconnectInit
} from '../disconnect/disconnect.js';


/*

When Resync form is submitted ...

TODO: We could potentially enhance performance considerably if we do
checksum comparisons. Look into this.

*/
function onResyncSubmit() {

  jQuery(".wps-is-active #wps-button-sync").unbind().on('click', function(e) {

    e.preventDefault();

    return new Promise(async (resolve, reject) => {

      var warningList = [];

      prepareBeforeSync();
      updateModalHeadingText('Re-syncing ...');
      setConnectionStepMessage('Preparing re-sync ...');
      setWebhooksReconnect(false);

      /*

      1. Turn sync on

      */
      try {
        var syncOnResponse = await syncOn();
console.log("syncOnResponse: ", syncOnResponse);
      } catch (errors) {

        updateDomAfterSync({
          noticeList: returnOnlyFailedRequests(errors)
        });

        resolve();
        return;

      }

      insertCheckmark();
      setConnectionStepMessage('Starting re-sync ...');
      warningList = addToWarningList(warningList, syncOnResponse);
console.log("warningList: ", warningList);
      /*

      2. Start progress bar

      */
      try {
        var startProgressBarResponse = await startProgressBar( true, getSelectiveSyncOptions() );

      } catch (errors) {

        updateDomAfterSync({
          noticeList: returnOnlyFailedRequests(errors)
        });

        resolve();
        return;

      }

      insertCheckmark();
      setConnectionStepMessage('Determining the number of items to sync ...');
      warningList = addToWarningList(warningList, startProgressBarResponse);


      /*

      3. Get item counts

      */
      try {

        var itemCountsResp = await getItemCounts();
        var allCounts = filterOutEmptySets( filterOutSelectiveSync( filterOutAnyNotice( getDataFromArray(itemCountsResp) ) ) );


      } catch (errors) {

        updateDomAfterSync({
          noticeList: returnOnlyFailedRequests(errors)
        });

        resolve();
        return;

      }

      warningList = addToWarningList(warningList, itemCountsResp);


      /*

      4. Save item counts

      */
      try {
        var saveCountsResponse = await saveCounts(allCounts); // save_counts

      } catch (errors) {

        updateDomAfterSync({
          noticeList: returnOnlyFailedRequests(errors)
        });

        resolve();
        return;

      }

      insertCheckmark();

      setConnectionStepMessage('Cleaning out any existing data first ...');
      warningList = addToWarningList(warningList, saveCountsResponse);


      /*

      5. Remove existing data

      */
      try {
        var removeExistingDataResponse = await removeExistingData();
console.log("removeExistingDataResponse: ", removeExistingDataResponse);
      } catch (errors) {
console.log("removeExistingDataResponse errors: ", errors);
        updateDomAfterSync({
          noticeList: returnOnlyFailedRequests(errors)
        });

        resolve();
        return;

      }

      insertCheckmark();
      updateModalButtonText('Cancel re-syncing process');
      setConnectionStepMessage('Syncing Shopify data ...', '(Please wait, this may take up to 5 minutes depending on the size of your store and speed of your internet connection.)');
      warningList = addToWarningList(warningList, removeExistingDataResponse);


      /*

      6. Begin polling for the status ... creates a cancelable loop

      */
      progressStatus();
      appendProgressBars( filterOutSelectedDataForSync(allCounts, ['webhooks']) );


      /*

      7. Sync Data

      */
      try {
        var syncResp = await syncData();

      } catch (errors) {

        updateDomAfterSync({
          noticeList: returnOnlyFailedRequests(errors)
        });

        resolve();
        return;

      }

      insertCheckmark();
      setConnectionStepMessage('Cleaning up ...');
      warningList = addToWarningList(warningList, syncResp);
      console.log("syncResp: ", syncResp);
      console.log("warningList: ", warningList);
      forceProgressBarsComplete();


      /*

      8. Turn sync off

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

      9. Finally update DOM

      */
      updateDomAfterSync({
        headingText: 'Re-sync complete',
        buttonText: 'Ok, let\'s go!',
        status: 'is-connected',
        stepText: 'Finished re-syncing',
        noticeList: constructFinalNoticeList(warningList),
        noticeType: 'success'
      });

      activateToolButtons();


    });

  });


}


export {
  onResyncSubmit
};
