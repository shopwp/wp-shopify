import isError from 'lodash/isError';
import forEach from 'lodash/forEach';

import {
  getItemCounts
} from '../ws/middleware';

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
  unbindConnectForm,
  unbindDisconnectForm,
  formatConnectorFormData,
  formConnectionRules
} from '../forms/forms';

import {
  enable,
  disable,
  setNonce,
  showSpinner,
  getUrlParams,
  containsProtocol,
  cleanDomainURL,
  containsPathAfterShopifyDomain,
  isWordPressError,
  getDataFromArray
} from '../utils/utils';

import {
  createProgressLoader,
  removeProgressLoader,
  startProgressBar,
  mapProgressDataFromSessionValues,
  appendProgressBars,
  progressStatus,
  forceProgressBarsComplete
} from '../utils/utils-progress';


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
  setDisconnectSubmit,
  updateConnectStatusHeading,
  resetConnectSubmit,
  updateDomAfterSync,
  ejectConnectorModal
} from '../utils/utils-dom';

import {
  returnOnlyFailedRequests,
  constructFinalNoticeList,
  addToWarningList,
  filterOutAnyNotice,
  filterOutSelectiveSync,
  filterOutSelectedDataForSync,
  filterOutEmptySets
} from '../utils/utils-data';

import {
  setPluginSettings,
  getAuthToken,
  getShopifyURL,
  getAuthUser,
  uninstallPlugin,
  insertConnectionData,
  getConnectionData,
  setSyncingIndicator,
  syncWithCPT,
  endProgress
} from '../ws/ws';

import {
  syncOff,
  clearSync
} from '../ws/wrappers';

import {
  connectionInProgress,
  setConnectionProgress,
  setModalCache,
  clearLocalstorageCache,
  setStartingURL,
  syncIsCanceled,
  setWebhooksReconnect
} from '../ws/localstorage';

import {
  clearAllCache
} from '../tools/cache';

import {
  getSelectiveSyncOptions
} from '../settings/settings';

import {
  toolsInit,
  activateToolButtons
} from '../tools/tools';

import {
  disconnectInit
} from '../disconnect/disconnect';



function onSuccess(label, $submitButton) {

  label.addClass("valid").text("Ok!");
  jQuery('#wps-errors').addClass('wps-is-hidden');
  enable($submitButton);

}


function onError(error, $submitButton) {

  disable($submitButton);
  showAdminNotice(error.text(), 'error');

}


/*

Step 1/2: Shopify Connect

*/
function onConnectionFormSubmit() {

  var $formConnect = jQuery("#wps-connect");
  var $submitButton = $formConnect.find('input[type="submit"]');

  unbindDisconnectForm();

  $formConnect.on('submit.connect', function(e) {
    e.preventDefault();

  }).validate({

    rules: formConnectionRules(),
    success: label => {
      onSuccess(label, $submitButton);
    },
    errorPlacement: error => {
      onError(error, $submitButton);
    },
    submitHandler: form => {
      connectionFormSubmitHandler(form);
    }

  });

}



function sanitizeDomainField() {

  var domain = jQuery('#wps_settings_connection_domain').val();

  // Clear protocol from input field if user mistakenly enters ...
  if (containsProtocol(domain) || containsPathAfterShopifyDomain(domain)) {
    jQuery('#wps_settings_connection_domain').val(cleanDomainURL(domain));
  }

}


function getConnectionFormData(form) {
  return formatConnectorFormData(jQuery(form).serializeArray());
}



function prepareBeforeSync() {

  var $formConnect = jQuery("#wps-connect");
  var $submitButton = $formConnect.find('input[type="submit"]');
  var $formInputNonce = jQuery("#wps_settings_connection_nonce_id");
  var $connectorModal = createConnectorModal();

  // Removes previous modal if one exists
  ejectConnectorModal();

  // Clears all LS cache before we begin
  clearLocalstorageCache();

  // Removes added protocol or superfluous characters from domain field
  sanitizeDomainField();

  // Disable submit button once clicked
  disable($submitButton);
  forEach($submitButton, showSpinner);

  injectConnectorModal($connectorModal);

  // Close Listenter
  onModalClose();

  showConnectorModal($connectorModal);
  setNonce( $formInputNonce.val() );

}


/*

On connect ...

*/
function connectionFormSubmitHandler(form) {

  return new Promise(async (resolve, reject) => {

    var warningList = [];

    prepareBeforeSync();
    setConnectionStepMessage('Preparing connection ...');


    /*

    1. Turn sync on

    */
    try {
      var syncOnResponse = await syncOn();

    } catch (errors) {

      updateDomAfterSync({
        noticeList: returnOnlyFailedRequests(errors)
      });

      resolve();
      return;

    }

    insertCheckmark();
    setConnectionStepMessage('Starting connection ...');
    warningList = addToWarningList(warningList, syncOnResponse);


    /*

    2. Save connection

    */
    try {
      var saveConnectionResponse = await saveConnection( getConnectionFormData(form) );

    } catch (errors) {

      updateDomAfterSync({
        noticeList: returnOnlyFailedRequests(errors)
      });

      resolve();
      return;

    }

    warningList = addToWarningList(warningList, saveConnectionResponse);


    /*

    3. Start progress bar

    */
    try {
      var startProgressBarResponse = await startProgressBar(true, getSelectiveSyncOptions() );

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

    4. Get item count

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

    5. Save item counts

    */
    try {
      var saveCountsResponse = await saveCounts(allCounts);

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

    6. Remove existing data

    */
    try {
      var removeExistingResponse = await removeExistingData();

    } catch (errors) {

      updateDomAfterSync({
        noticeList: returnOnlyFailedRequests(errors)
      });

      resolve();
      return;

    }

    insertCheckmark();
    updateModalHeadingText('Syncing ...');
    updateModalButtonText('Cancel syncing process');
    setConnectionStepMessage('Syncing Shopify data ...', '(Please wait, this may take up to 5 minutes depending on the size of your store and speed of your internet connection.)');
    warningList = addToWarningList(warningList, removeExistingResponse);


    /*

    7. Begin polling for the status ... creates a cancelable loop

    */
    progressStatus();
    appendProgressBars(allCounts);


    /*

    8. Sync data

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
    forceProgressBarsComplete();


    /*

    9. Turn sync off

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

    10. Finally update DOM

    */
    updateDomAfterSync({
      headingText: 'Connected',
      buttonText: 'Ok, let\'s go!',
      status: 'is-connected',
      stepText: 'Finished syncing',
      noticeList: constructFinalNoticeList(warningList),
      noticeType: 'success'
    });

    disconnectInit();
    activateToolButtons();
    toolsInit();

  });

}


/*

Connect Init

*/
function connectInit() {
  onConnectionFormSubmit();
}

export {
  connectInit,
  prepareBeforeSync
}
