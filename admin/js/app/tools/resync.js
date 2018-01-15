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
  startProgressBar,
  mapProgressDataFromSessionValues,
  appendProgressBars,
  progressStatus
} from '../utils/utils-progress';

import {
  returnOnlyFailedRequests
} from '../utils/utils-data';

import {
  clearAllCache
} from '../tools/cache';

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

      prepareBeforeSync();
      updateModalHeadingText('Re-syncing ...');
      setConnectionStepMessage('Preparing re-sync ...');

      /*

      1. Turn sync on

      */
      try {
        await syncOn();

      } catch (errors) {
        console.error("syncOn: ", errors);

        updateDomAfterSync({
          noticeList: returnOnlyFailedRequests(errors)
        });

        resolve();
        return;

      }

      insertCheckmark();
      setConnectionStepMessage('Starting re-sync ...');


      /*

      2. Start progress bar

      */
      try {
        await startProgressBar(true);

      } catch (errors) {
        console.error("startProgressBar: ", errors);

        updateDomAfterSync({
          noticeList: returnOnlyFailedRequests(errors)
        });

        resolve();
        return;

      }

      insertCheckmark();
      setConnectionStepMessage('Determining the number of items to sync ...');


      /*

      3. Get item counts

      */
      try {

        var itemCountsResp = await getItemCounts();
        console.error("itemCountsResp: ", itemCountsResp);
        var allCounts = getDataFromArray(itemCountsResp);

      } catch (errors) {

        console.error("getItemCountssss: ", returnOnlyFailedRequests(errors));

        updateDomAfterSync({
          noticeList: returnOnlyFailedRequests(errors)
        });

        resolve();
        return;

      }


      /*

      4. Save item counts

      */
      try {
        await saveCounts(allCounts);

      } catch (errors) {

        console.error("saveCounts: ", errors);

        updateDomAfterSync({
          noticeList: returnOnlyFailedRequests(errors)
        });

        resolve();
        return;

      }

      insertCheckmark();
      setConnectionStepMessage('Cleaning out any existing data first ...');


      /*

      5. Remove existing data

      */
      try {
        await removeExistingData();

      } catch (errors) {
        console.error("removeExistingData: ", errors);

        updateDomAfterSync({
          noticeList: returnOnlyFailedRequests(errors)
        });

        resolve();
        return;

      }

      insertCheckmark();

      updateModalButtonText('Cancel re-syncing process');
      setConnectionStepMessage('Syncing Shopify data ...', '(Please wait, this may take up to 5 minutes depending on the size of your store and speed of your internet connection.)');


      /*

      6. Begin polling for the status ... creates a cancelable loop

      */
      progressStatus();
      appendProgressBars(allCounts);


      /*

      7. Sync Data

      */
      try {
        var syncResp = await syncData();

      } catch (errors) {
        console.error("syncData: ", errors);

        updateDomAfterSync({
          noticeList: returnOnlyFailedRequests(errors)
        });

        resolve();
        return;

      }

      insertCheckmark();
      setConnectionStepMessage('Cleaning up ...');


      /*

      8. Turn sync off

      */
      try {
        await syncOff();

      } catch (errors) {
        console.error("syncOff: ", error);

        updateDomAfterSync({
          noticeList: returnOnlyFailedRequests(errors)
        });

        resolve();
        return;

      }


      /*

      9. Finally update DOM

      */
      updateDomAfterSync({
        headingText: 'Re-sync complete',
        buttonText: 'Ok, let\'s go!',
        status: 'is-connected',
        stepText: 'Finished re-syncing',
        noticeList: [{
          type: 'success',
          message: 'Success! You\'ve finished re-syncing with Shopify.'
        }],
        noticeType: 'success'
      });


    });

  });


}


export {
  onResyncSubmit
};
