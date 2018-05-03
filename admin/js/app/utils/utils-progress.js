import isArray from 'lodash/isArray';
import mapKeys from 'lodash/mapKeys';
import filter from 'lodash/filter';
import split from 'lodash/split';
import isEmpty from 'lodash/isEmpty';
import forOwn from 'lodash/forOwn';
import forEach from 'lodash/forEach';

import {
  getProgressCount,
  startProgress,
  endProgress,
  progressSessionStart
} from '../ws/ws';

import {
  isWordPressError
} from './utils';

import {
  getCancelSync
} from '../ws/localstorage';


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


/*

Start Progress Loader

*/
function startProgressLoader() {

}


/*

Progress Status

*/
async function progressStatus() {

  try {

    var status = await getProgressCount(); // wps_progress_status

    if (isWordPressError(status) || getCancelSync()) {
      return;

    } else {

      var syncStatus = status.data.is_syncing;

      if (syncStatus) {

        /*

        At this point we know that we're still syncing and need to update
        the DOM accordingly

        */
        updateProgressBarTotals(status.data.syncing_totals);
        updateProgressBarCurrentAmounts(status.data.syncing_current_amounts);

        setTimeout(progressStatus, 1000);

      } else {
        // console.log('DONE SYNCING');
      }

    }

  } catch (error) {

  }

}


/*

Update Progress Loader

*/
function startProgressBar(resync = false, includes = []) {

  return new Promise( async (resolve, reject) => {

    try {
      var sessionVariables = await progressSessionStart(resync, includes); // wps_progress_session_create

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
    stepNamePretty = 'Products (includes images, tags, variants, etc)'
  }

  if (stepNamePretty === 'shop') {
    stepNamePretty = 'Shop (includes store name, location, phone number, etc)'
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
      currentTotal = stepCurrentValue,
      percentage = ((currentTotal / maxTotal) * 100);

  return percentage;

}


/*

Update Progress Bar Current Amount

*/
function updateProgressCurrentAmount(stepCurrentValue, stepName) {

  var percentage = getProgressBarPercentage(stepCurrentValue, stepName),
      $progressWrapper = jQuery('#wps-progress-bar-' + stepName);

  if (!isNaN(percentage)) {

    if (stepCurrentValue == $progressWrapper.data('wps-progress-total')) {
      $progressWrapper.addClass('wps-is-complete');
    }

    $progressWrapper
      .find('.wps-progress-bar').css('width', percentage + '%');

    $progressWrapper
      .find('.wps-progress-step-percentage').text(Math.round(percentage) + '%');
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
  $progressWrapper.addClass('wps-is-complete');

  $progressWrapper
    .find('.wps-progress-bar').css('width', '100%');

  $progressWrapper
    .find('.wps-progress-step-percentage').text('100%');

}


export {
  createProgressLoader,
  removeProgressLoader,
  startProgressBar,
  progressStatus,
  mapProgressDataFromSessionValues,
  createProgressBar,
  appendProgressBars,
  forceProgressBarsComplete
};
