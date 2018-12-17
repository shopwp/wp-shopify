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
import toInteger from 'lodash/toInteger';
import toString from 'lodash/toString';
import isNaN from 'lodash/isNaN';
import isFinite from 'lodash/isFinite';

import to from 'await-to-js';

import {
  checkMark
} from './utils-animations';

import {
  get,
  post
} from '../ws/ws';

import {
  getSyncingStatus
} from '../ws/api/api-syncing';

import {
  endpointSyncingCounts,
  endpointSyncingCount,
  endpointSyncingStatusPosts,
  endpointSyncingStatusWebhooks,
  endpointSyncingStatusRemoval,
  endpointSyncingStop,
  endpointSyncingNotices
} from '../ws/api/api-endpoints';

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
  hasConnection,
  returnOnlyFirstError
} from './utils-data';

import {
  stopTimer
} from './utils-timer';

import {
  setPostRelationships
} from '../ws/wrappers';

import {
  getCancelSync,
  clearLocalstorageCache,
  isConnectionInProgress,
  setConnectionProgress
} from '../ws/localstorage';

import {
  isReconnectingWebhooks
} from '../globals/globals-syncing';

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

import {
  clearAllCache
} from '../ws/wrappers';

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


function setConnectionFieldsState(options) {

  if ( hasConnection() ) {

    toolsInit();
    disconnectInit();
    setDisconnectSubmit();
    activateToolButtons();
    hideSyncByCollectionsNotice();
    populateSyncByCollections();

  } else {

    showSyncByCollectionsNotice();
    clearConnectInputs(options);
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

    var [stopSyncError, stopSync] = await to( post( endpointSyncingStop() ) );

    if ( manuallyCanceled() ) {
      setConnectorFinishState();
      removeIsSyncingClass();
      updateDomAfterSync(options); // We pass in the config object from higher up
      setConnectionFieldsState(options);
      stopTimer();
      return;
    }

    var [noticeListError, noticeList] = await to( get( endpointSyncingNotices() ) );


    WP_Shopify.reconnectingWebhooks = false;

    setConnectorFinishState();
    removeIsSyncingClass();
    setConnectionFieldsState(options);
    stopTimer();

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

  return to( get(
    endpointSyncingCounts(), {
      resync: resync,
      includes: includes,
      excludes: excludes
    }
  ));

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
function createProgressBar(stepName, stepMaxTotal) {

  var stepNamePretty = stepName.split('_').join(' ');

  if (stepNamePretty === 'products') {
    stepNamePretty = 'Products <small>(includes images, tags, variants, etc)</small>';
    stepMaxTotal = stepMaxTotal * 6;
  }

  if (stepNamePretty === 'custom_collections') {
    stepMaxTotal = stepMaxTotal * 2;
  }

  if (stepNamePretty === 'smart_collections') {
    stepMaxTotal = stepMaxTotal * 2;
  }

  if (stepNamePretty === 'shop') {
    stepNamePretty = 'Shop <small>(includes store name, location, phone number, etc)</small>';
  }

  if (stepNamePretty === 'collects') {
    stepNamePretty = 'Collects <small>(used to assign products to collections)</small>';
  }

  return jQuery('<div class="wps-progress-bar-wrapper" data-wps-progress-total="' + stepMaxTotal + '" id="wps-progress-bar-' + stepName + '"><span class="wps-progress-step-name">' + stepNamePretty + '</span><span class="wps-progress-step-percentage">0%</span><div class="wps-progress-bar"></div><span class="dashicons dashicons-yes"></span></div>');

}


/*

Insert Progress Bar

*/
function insertProgressBar(stepMaxTotal, stepName) {

  jQuery('.wps-connector-content > .wps-progress-notice')
    .first()
    .after( createProgressBar(stepName, stepMaxTotal) );

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

Is greater than 100 percent

*/
function isGreaterThan100Percent(percentage) {
  return toInteger(percentage) > 100;
}


/*

Forces syncing percentages to never be more than 100

*/
function forceMax100(percentage) {

  if ( isGreaterThan100Percent(percentage) ) {
    percentage = 100;
  }

  return percentage;

}


/*

Get Progress Bar Percentage

*/
function getProgressBarPercentage(stepCurrentValue, stepName) {

  var $progressBarWrapper = jQuery('#wps-progress-bar-' + stepName),
      $progressBar = $progressBarWrapper.find('.wps-progress-bar'),
      maxTotal = toInteger($progressBarWrapper.data('wps-progress-total'));

  var currentTotal = toInteger(stepCurrentValue);

  var percentage = ((currentTotal / maxTotal) * 100);

  if (isGreaterThan100Percent(percentage)) {

    post( endpointSyncingCount(), {
      stepName: stepName,
      matchTotal: true
    });

  }

  return forceMax100(percentage);

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


  if ( isNaN(percentage) || !isFinite(percentage) ) {
    return;
  }


  if (stepCurrentValue == $progressWrapper.data('wps-progress-total')) {

    if (!$progressWrapper.hasClass('wps-is-complete')) {
      $progressWrapper.addClass('wps-is-complete');
      checkMark($progressWrapper.find('.dashicons-yes'));
    }

  }

  $progressWrapper.find('.wps-progress-bar').css('width', percentage + '%');
  $progressWrapper.find('.wps-progress-step-percentage').text( getPercentTextFromNumber( Math.round(percentage) ) );



}


function toggleIsSyncingClass() {
  jQuery('.wps-connector').toggleClass('wps-is-syncing');
}

function removeIsSyncingClass() {
  jQuery('.wps-connector').removeClass('wps-is-syncing');
}


/*

Append Progress Bars

*/
function appendProgressBars(allCounts) {

  toggleIsSyncingClass();

  if ( isArray(allCounts) ) {

    return forEach(allCounts, function(count) {
      return forOwn(count, insertProgressBar);
    });

  }

  return forOwn(allCounts.wps_syncing_totals, insertProgressBar);

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


function isStillSyncing(status) {
  return status.data.is_syncing;
}

function hasSyncingErrors(status) {
  return status.data.has_fatal_errors;
}


/*

Progress Status

*/
async function progressStatus() {


  var [statusError, status] = await to( getSyncingStatus() );


  /*

  This check will stop the syncing process if the poll
  request contained a 4xx error.

  */
  if (statusError) {

    forceProgressBarsComplete();

    return cleanUpAfterSync(
      syncingConfigJavascriptError(statusError)
    );

  }


  /*

  This check will stop the syncing process if the poll
  response was malformed in someway.

  */
  if ( !has(status, 'data') ) {

    forceProgressBarsComplete();

    return cleanUpAfterSync({
      xMark: true
    });

  }


  /*

  This check will stop the syncing process if
  the user manually canceled.

  */
  if ( manuallyCanceled() ) {
    return cleanUpAfterSync();
  }


  /*

  This check will stop the syncing process if any fatal errors
  have occured including Shopify API errors, database errors, etc.

  */
  if ( !isStillSyncing(status) && hasSyncingErrors(status) || hasSyncingErrors(status) ) {

    return cleanUpAfterSync({
      xMark: true
    });

  }

  updateProgressBarTotals(status.data.syncing_totals);
  updateProgressBarCurrentAmounts(status.data.syncing_current_amounts);




  if ( !syncingTotalsMatch(status.data.syncing_totals, status.data.syncing_current_amounts) ) {
    setTimeout(progressStatus, 1000);

  } else {

    /*

    If execution gets here, that means the syncing has finished. Now we
    need to clean up and update the UI.

    */
    forceProgressBarsComplete();
    insertCheckmark();


    // If finished syncing Webhooks ...
    if ( isReconnectingWebhooks() ) {

      setConnectionStepMessage('Finishing sync ...');

      return cleanUpAfterSync(
        syncingConfigWebhooksSuccess()
      );

    }

    if ( manuallyCanceled() || isDisconnecting() ) {
      return cleanUpAfterSync();
    }

    setConnectionStepMessage('Finalizing data ...', '(Please wait, this may take up to 60 seconds depending on the number of products and collections)');


    /*

    Only gets to this point if were syncing products and need to
    reestablish the post type ID connections.

    */
    var [postsError, postsData] = await to( setPostRelationships() );

    if (postsError) {
      return cleanUpAfterSync( syncingConfigJavascriptError(postsError) );
    }

    if (isWordPressError(postsData)) {
      return cleanUpAfterSync( syncingConfigErrorBeforeSync( returnOnlyFirstError(postsData) ) );
    }

    afterPostRelationships(cleanUpAfterSync);


  }

}




/*

Polls for post relationships. Fires callback when done.

*/
async function afterPostRelationships(callback) {

  if ( !isSyncing() && !isConnecting() ) {

    return cleanUpAfterSync(
      syncingConfigManualCancel()
    );

  }


  var [postsStatusError, postsStatus] = await to( get( endpointSyncingStatusPosts() ) );


  if (postsStatusError) {

    return cleanUpAfterSync(
      syncingConfigJavascriptError(postsStatusError)
    );

  }

  if (postsStatus.data) {
    return callback();
  }

  afterPostRelationships(callback);

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

  if ( !isSyncing() && !isClearing() && !isDisconnecting() && !isConnecting() ) {

    return cleanUpAfterSync(
      syncingConfigManualCancel()
    );

  }


  var [webhooksStatusError, webhooksStatus] = await to( get( endpointSyncingStatusWebhooks() ) );

  if (webhooksStatusError) {

    return cleanUpAfterSync(
      syncingConfigJavascriptError(webhooksStatusError)
    );

  }

  if (webhooksStatus.data) {
    return callback();
  }

  afterWebhooksRemoval(callback);

}


/*

Polls for webhooks removal status. Fires callback when done.

*/
async function afterDataRemoval(callback) {

  var [removalStatusError, removalStatus] = await to( get( endpointSyncingStatusRemoval() ) );

  if (removalStatusError) {
    return callback(removalStatusError);
  }

  if (removalStatus.data) {
    return callback(removalStatus);
  }

  afterDataRemoval(callback);

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
