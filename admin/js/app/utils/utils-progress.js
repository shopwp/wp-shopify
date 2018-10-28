import isArray from 'lodash/isArray';
import mapKeys from 'lodash/mapKeys';
import filter from 'lodash/filter';
import split from 'lodash/split';
import isEmpty from 'lodash/isEmpty';
import forOwn from 'lodash/forOwn';
import forEach from 'lodash/forEach';
import orderBy from 'lodash/orderBy';
import has from 'lodash/has';
import isEqual from 'lodash/isEqual';
import to from 'await-to-js';

import {
  checkMark
} from './utils-animations';

import {
  getProgressCount,
  startProgress,
  progressSessionStart,
  getSyncNotices,
  getWebhooksRemovalStatus,
  getPostsRelationshipsStatus,
  killSyncing,
  getDataRemovalStatus
} from '../ws/ws';

import {
  isWordPressError,
  enable,
  disable
} from './utils';

import {
  insertCheckmark,
  insertXMark,
  updateDomAfterSync,
  setConnectionStepMessage,
  clearConnectInputs,
  getConnectorCancelButton,
  getToolsButtons,
  resetConnectSubmit,
  setDisconnectSubmit,
  showSyncByCollectionsNotice,
  hideSyncByCollectionsNotice,
  resetSyncByCollectionOptions
} from './utils-dom';

import {
  returnOnlyFailedRequests,
  constructFinalNoticeList,
  addToWarningList,
  filterOutAnyNotice,
  filterOutSelectiveSync,
  filterOutEmptySets,
  filterOutSelectedDataForSync,
  hasConnection,
  returnOnlyFirstError
} from './utils-data';

import {
  syncOff,
  checkForProductPostsRelationships,
  checkForCollectionPostsRelationships,
  checkPostRelationships
} from '../ws/wrappers';

import {
  getCancelSync,
  clearLocalstorageCache,
  isConnectionInProgress,
  setConnectionProgress
} from '../ws/localstorage';

import {
  syncingConfigManualCancel,
  syncingConfigWebhooksSuccess,
  syncingConfigUnexpectedFailure,
  syncingConfigJavascriptError,
  syncingConfigErrorBeforeSync
} from '../ws/syncing-config';

import {
  activateToolButtons,
  toolsInit
} from '../tools/tools';

import { clearAllCache } from '../tools/cache';

import {
  connectInit
} from '../connect/connect';

import {
  disconnectInit
} from '../disconnect/disconnect';

import {
  populateSyncByCollections
} from '../settings/settings.jsx';

/*

Create Progress Loader

*/
function createProgressLoader() {
  jQuery('.wps-progress-notice:first-of-type').append('<div class="wps-loader"></div>');
}


/*

Remove Progress Loader

*/
function removeProgressLoader() {
  jQuery('.wps-progress-notice .wps-loader').remove();
}


/*

Stop Progress Loader

*/
function stopProgressLoader(timer) {
  clearInterval(timer);
}


function constructErrorsAndWarnings(syncNoitces) {

  var noticeList = [];

  if (!isEmpty(syncNoitces.data.syncing_errors)) {

    forEach(syncNoitces.data.syncing_errors.error, function(errorMessage) {
      noticeList.push({
        type: 'error',
        message: errorMessage
      });
    });

  }

  if (!isEmpty(syncNoitces.data.syncing_warnings)) {

    forEach(syncNoitces.data.syncing_warnings.warning, function(warningMessage) {
      noticeList.push({
        type: 'warning',
        message: warningMessage
      });
    });

  }

  return noticeList;

}


/*

Sorts the array of notices by type so the results will look like this:

[
  { type: error, message: 'Msg 1' },
  { type: warning, message: 'Msg 1' }
]

*/
function sortSyncNoticeList(noticeList) {
  return orderBy(noticeList, 'type', 'desc');
}


function checkForNoticeTypeInNoticeList(noticeList, type) {

  var found = false;

  forEach(noticeList, notice => {

    if (notice.type === type) {
      found = true;
    }
  });

  return found;

}


/*

Checks for an error notice in list

*/
function checkForErrorInNoticeList(noticeList) {
  return checkForNoticeTypeInNoticeList(noticeList, 'error');
}


/*

Checks for a warning notice in list

*/
function checkForWarningInNoticeList(noticeList) {
  return checkForNoticeTypeInNoticeList(noticeList, 'warning');
}


/*

Constructs the heading text depending on notice type

*/
function constructHeadingText(noticeList, options) {

  if (options && options.headingText) {
    return options.headingText;

  } else {

    if (checkForErrorInNoticeList(noticeList)) {
      return 'Sync failed with errors';

    } else if (checkForWarningInNoticeList(noticeList)) {
      return 'Sync completed with warnings';

    } else {
      return 'Sync completed successfully';
    }

  }

}


function constructStepText(noticeList, options) {


  if (options && options.stepText) {
    return options.stepText;

  } else {

    if (checkForErrorInNoticeList(noticeList)) {
      return 'Finished syncing with errors';

    } else if (checkForWarningInNoticeList(noticeList)) {
      return 'Finished syncing with warnings';

    } else {
      return 'Finished syncing';
    }

  }

}


function constructButtonText(options) {

  if (options && options.buttonText) {
    return options.buttonText;

  } else {
    return 'Close WP Shopify Sync';
  }

}


function constructStatus(options) {

  if (options && options.status) {
    return options.status;

  } else {

    if (hasConnection()) {
      return 'is-connected';

    } else {
      return 'is-disconnected';
    }

  }

}


function constructXMark(options) {

  if (options && options.xMark) {
    return options.xMark;

  } else {
    return false;

  }

}


function setConnectionFieldsState() {

  if (hasConnection()) {

    toolsInit();
    disconnectInit();
    setDisconnectSubmit();
    activateToolButtons();
    hideSyncByCollectionsNotice();
    populateSyncByCollections();

  } else {

    showSyncByCollectionsNotice();
    clearConnectInputs();
    enable( getConnectorCancelButton() );
    disable( getToolsButtons() );
    resetConnectSubmit();
    connectInit();

  }

}



/*

Start Progress Loader

*/
async function cleanUpAfterSync(options = false) {

  if ( isConnectionInProgress() ) {

    try {
      await killSyncing();

    } catch (error) {
      console.error('WP Shopify error: Failed to kill sync: ', error);
    }


    if ( manuallyCanceled() ) {
      setConnectorFinishState();
      updateDomAfterSync(options); // We pass in the config object from higher up
      setConnectionFieldsState();
      return;
    }


    try {
      var noticeList = await getSyncNotices();

    } catch (error) {
      console.error('WP Shopify error: Failed to get sync notices: ', error);
    }


    WP_Shopify.reconnectingWebhooks = false;

    setConnectorFinishState();
    setConnectionFieldsState();


    // Any client-side JS errors will automatically replace any server-level errors
    if (options.noticeList) {
      var finalNoticeList = options.noticeList;

    } else {
      var finalNoticeList = constructFinalNoticeList( sortSyncNoticeList( constructErrorsAndWarnings(noticeList) ) );
    }


     // We dont really need to wait for this. If the cache doesnt clear in time its not the end of the world.
    clearLocalstorageCache();
    clearAllCache();



    /*

    9. Finally update DOM

    headingText: 'Disconnected',
    stepText: 'Finished disconnecting'

    */

    updateDomAfterSync({
      headingText: constructHeadingText(finalNoticeList, options),
      buttonText: constructButtonText(options),
      status: constructStatus(options),
      stepText: constructStepText(finalNoticeList, options),
      noticeList: finalNoticeList,
      xMark: constructXMark(options)
    });

    setConnectionProgress(false);


  }

}


function setConnectorFinishState() {
  jQuery('.wps-connector').addClass('wps-is-finished');
}



/*

Update Progress Loader

*/
function startProgressBar(resync = false, includes = [], excludes = []) {

  return new Promise( async (resolve, reject) => {

    try {
      var sessionVariables = await progressSessionStart(resync, includes, excludes); // progress_session_create

    } catch (error) {
      reject(error);
      return;
    }

    resolve(sessionVariables);
    return sessionVariables;

  });

}


/*

Not Empty Value

*/
function notEmptyValue(value) {
  return !isEmpty(value);
}


/*

Shorten Session Step Names

*/
function shortenSessionStepNames(value, key, obj) {

  var splitName = split(key, 'wps_progress_current_amount_');
  return filter(splitName, notEmptyValue)[0];

}


/*

Map Progress Data From Session Values

*/
function mapProgressDataFromSessionValues(session) {
  return mapKeys(session, shortenSessionStepNames);
}


/*

Create Progress Bar

*/
function createProgressBar(stepName, stepTotal) {

  var stepNamePretty = stepName.split('_').join(' ');

  if (stepNamePretty === 'products') {
    stepNamePretty = 'Products <small>(includes images, tags, variants, etc)</small>';
    stepTotal = stepTotal * 6;
  }

  if (stepNamePretty === 'custom_collections') {
    stepTotal = stepTotal * 2;
  }

  if (stepNamePretty === 'smart_collections') {
    stepTotal = stepTotal * 2;
  }

  if (stepNamePretty === 'shop') {
    stepNamePretty = 'Shop <small>(includes store name, location, phone number, etc)</small>';
  }

  if (stepNamePretty === 'collects') {
    stepNamePretty = 'Collects <small>(used to assign products to collections)</small>';
  }

  return jQuery('<div class="wps-progress-bar-wrapper" data-wps-progress-total="' + stepTotal + '" id="wps-progress-bar-' + stepName + '"><span class="wps-progress-step-name">' + stepNamePretty + '</span><span class="wps-progress-step-percentage">0%</span><div class="wps-progress-bar"></div><span class="dashicons dashicons-yes"></span></div>');

}


/*

Insert Progress Bar

*/
function insertProgressBar(stepTotal, stepName) {
  jQuery('.wps-connector-content > .wps-progress-notice').first().after(createProgressBar(stepName, stepTotal));
}


/*

Progress Bar: Update Totals

*/
function updateProgressBarTotals(stepTotals) {
  forOwn(stepTotals, updateProgressBarTotal);
}

function updateProgressBarTotal(stepTotal, stepName) {
  jQuery('#wps-progress-bar-' + stepName).data('wps-progress-total', stepTotal).attr('data-wps-progress-total', stepTotal);
}


/*

Progress Bar: Update Current Amounts

*/
function updateProgressBarCurrentAmounts(currentAmounts) {
  forOwn(currentAmounts, updateProgressCurrentAmount);
}


/*

Get Progress Bar Percentage

*/
function getProgressBarPercentage(stepCurrentValue, stepName) {

  var $progressBarWrapper = jQuery('#wps-progress-bar-' + stepName),
      $progressBar = $progressBarWrapper.find('.wps-progress-bar'),
      maxTotal = $progressBarWrapper.data('wps-progress-total'),
      currentTotal = stepCurrentValue;

  var percentage = ((currentTotal / maxTotal) * 100);

  return percentage;

}


/*

Gets a text version of the actual percent total

*/
function getPercentTextFromNumber(percentNumber) {

  if ( isFinite(percentNumber) ) {
    return percentNumber + '%';

  } else {
    return 'Checking ...'
  }

}


/*

Update Progress Bar Current Amount

*/
function updateProgressCurrentAmount(stepCurrentValue, stepName) {

  var percentage = getProgressBarPercentage(stepCurrentValue, stepName),
      $progressWrapper = jQuery('#wps-progress-bar-' + stepName);

  if (!isNaN(percentage)) {

    if (stepCurrentValue == $progressWrapper.data('wps-progress-total')) {

      if (!$progressWrapper.hasClass('wps-is-complete')) {
        $progressWrapper.addClass('wps-is-complete');
        checkMark($progressWrapper.find('.dashicons-yes'));
      }

    }

    $progressWrapper.find('.wps-progress-bar').css('width', percentage + '%');
    $progressWrapper.find('.wps-progress-step-percentage').text( getPercentTextFromNumber( Math.round(percentage) ) );

  }

}


/*

Append Progress Bars

*/
function appendProgressBars(allCounts) {

  if (isArray(allCounts)) {

    return forEach(allCounts, function(count) {
      return forOwn(count, insertProgressBar);
    });

  } else {
    return forOwn(allCounts.wps_syncing_totals, insertProgressBar);
  }

}


/*

Force Progress Bars to 100%

*/
function forceProgressBarsComplete() {

  var $progressWrapper = jQuery('.wps-progress-bar-wrapper');

  checkMark( jQuery('.wps-progress-bar-wrapper:not(.wps-is-complete) .dashicons-yes') );

  $progressWrapper.addClass('wps-is-complete');
  $progressWrapper.find('.wps-progress-bar').css('width', '100%');
  $progressWrapper.find('.wps-progress-step-percentage').text('100%');

}


function syncingTotalsMatch(totals, currentAmounts) {
  return isEqual(totals, currentAmounts);
}


/*

Progress Status

*/
async function progressStatus(setRelationships = false) {

  // progress_status
  var [statusError, status] = await to( getProgressCount() );


  if (statusError) {

    forceProgressBarsComplete();

    return cleanUpAfterSync(
      syncingConfigJavascriptError(statusError)
    );

  }


  if ( !has(status, 'data') ) {
    forceProgressBarsComplete();
    return cleanUpAfterSync({ xMark: true });
  }


  updateProgressBarTotals(status.data.syncing_totals);
  updateProgressBarCurrentAmounts(status.data.syncing_current_amounts);


  if ( !syncingTotalsMatch(status.data.syncing_totals, status.data.syncing_current_amounts) ) {
    setTimeout(progressStatus, 800);

  } else {

    if (status.data.has_fatal_errors) {

      return cleanUpAfterSync({
        xMark: true
      });

    } else {


      /*

      If finished syncing Webhooks ...

      */

      if (reconnectingWebhooks() && isSyncing()) {

        forceProgressBarsComplete();

        insertCheckmark();
        setConnectionStepMessage('Cleaning up ...');

        return cleanUpAfterSync(
          syncingConfigWebhooksSuccess()
        );

      }


      /*

      If syncing manually canceled ...

      */
      if (!isSyncing() || manuallyCanceled() || isDisconnecting()) {

        return cleanUpAfterSync();

      } else {

        forceProgressBarsComplete();

        insertCheckmark();
        setConnectionStepMessage('Finishing sync ...');

        /*

        Syncing products finished

        */

        var [postsError, postsData] = await to( checkPostRelationships() );

        if (postsError) {
          return cleanUpAfterSync( syncingConfigJavascriptError(postsError) );
        }

        if (isWordPressError(postsData)) {
          return cleanUpAfterSync( syncingConfigErrorBeforeSync( returnOnlyFirstError(postsData) ) );
        }

        afterPostRelationships(async () => {

          return cleanUpAfterSync();

        });


      }


    }

  }

}




/*

Polls for post relationships. Fires callback when done.

*/
async function afterPostRelationships(callback) {

  if (!isSyncing()) {

    return cleanUpAfterSync(
      syncingConfigManualCancel()
    );

  }


  try {

    var postRelationshipsStatus = await getPostsRelationshipsStatus(); // get_posts_relationships_status

    if (postRelationshipsStatus.data) {
      callback();

    } else {

      setTimeout(function() {
        afterPostRelationships(callback);
      }, 500);

    }

  } catch (error) {
    console.error('getPostRelationshipsStatus error: ', error);
  }

}



function isSyncing() {
  return WP_Shopify.isSyncing;
}

function manuallyCanceled() {
  return WP_Shopify.manuallyCanceled;
}

function reconnectingWebhooks() {
  return WP_Shopify.reconnectingWebhooks;
}

function isClearing() {
  return WP_Shopify.isClearing;
}

function isDisconnecting() {
  return WP_Shopify.isDisconnecting;
}

function isConnecting() {
  return WP_Shopify.isConnecting;
}


/*

Polls for webhooks removal status. Fires callback when done.

*/
async function afterWebhooksRemoval(callback) {


  if (!isSyncing() && !isClearing() && !isDisconnecting() && !isConnecting()) {

    return cleanUpAfterSync(
      syncingConfigManualCancel()
    );

  }


  try {

    var webhooksRemoved = await getWebhooksRemovalStatus(); // get_webhooks_removal_status

    if (webhooksRemoved.data) {
      callback();

    } else {

      setTimeout(function() {
        afterWebhooksRemoval(callback);
      }, 1000);

    }

  } catch (error) {
    console.error('afterWebhooksRemoval error: ', error);
  }

}


/*

Polls for webhooks removal status. Fires callback when done.

get_data_removal_status

*/
async function afterDataRemoval(callback) {

  var [afterDataRemovalError, afterDataRemovalData] = await to( getDataRemovalStatus() );

  if (afterDataRemovalError) {
    callback(afterDataRemovalError);
    return;
  }

  if (afterDataRemovalData.data) {
    callback(afterDataRemovalData);

  } else {

    setTimeout(() => {
      afterDataRemoval(callback);
    }, 1000);

  }

}

export {
  createProgressLoader,
  removeProgressLoader,
  startProgressBar,
  progressStatus,
  mapProgressDataFromSessionValues,
  createProgressBar,
  appendProgressBars,
  forceProgressBarsComplete,
  afterWebhooksRemoval,
  afterDataRemoval,
  cleanUpAfterSync,
  afterPostRelationships,
  manuallyCanceled,
  reconnectingWebhooks,
  setConnectionFieldsState,
  setConnectorFinishState,
  checkForNoticeTypeInNoticeList
}
