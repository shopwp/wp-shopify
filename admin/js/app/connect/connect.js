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
  progressStatus
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
  setPluginSettings,
  getPluginSettings,
  getAuthToken,
  getShopifyURL,
  getAuthUser,
  updateAuthUser,
  uninstallPlugin,
  insertConnectionData,
  getConnectionData,
  setSyncingIndicator,
  syncWithCPT,
  saveCountsToSession,
  endProgress
} from '../ws/ws.js';

import {
  syncOff,
  clearSync
} from '../ws/wrappers.js';

import {
  connectionInProgress,
  setConnectionProgress,
  setModalCache,
  clearLocalstorageCache,
  setStartingURL,
  syncIsCanceled
} from '../ws/localstorage.js';

import {
  clearAllCache
} from '../tools/cache.js';

import {
  disconnectInit
} from '../disconnect/disconnect.js';



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

    prepareBeforeSync();
    setConnectionStepMessage('Preparing connection ...');

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
    setConnectionStepMessage('Starting connection ...');


    /*

    2. Save connection

    */
    try {
      await saveConnection( getConnectionFormData(form) );

    } catch (errors) {
      console.error("saveConnection: ", errors);

      updateDomAfterSync({
        noticeList: returnOnlyFailedRequests(errors)
      });

      resolve();
      return;

    }


    /*

    3. Start progress bar

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

    4. Get item count

    */
    try {

      var itemCountsResp = await getItemCounts();
      var allCounts = getDataFromArray(itemCountsResp);

    } catch (errors) {
      console.error("getItemCounts: ", errors);

      updateDomAfterSync({
        noticeList: returnOnlyFailedRequests(errors)
      });

      resolve();
      return;

    }


    /*

    5. Save item counts

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

    6. Remove existing data

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
    updateModalHeadingText('Syncing ...');
    updateModalButtonText('Cancel syncing process');
    setConnectionStepMessage('Syncing Shopify data ...', '(Please wait, this may take up to 5 minutes depending on the size of your store and speed of your internet connection.)');


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

    9. Turn sync off

    */
    try {
      await syncOff();

    } catch (errors) {
      console.error("syncOff: ", errors);

      updateDomAfterSync({
        noticeList: returnOnlyFailedRequests(errors)
      });

      resolve();
      return;

    }


    /*

    10. Finally update DOM

    */
    updateDomAfterSync({
      headingText: 'Connected',
      buttonText: 'Ok, let\'s go!',
      status: 'is-connected',
      stepText: 'Finished syncing',
      noticeList: [{
        type: 'success',
        message: 'Success! You\'re now connected and syncing with Shopify.'
      }],
      noticeType: 'success'
    });

    disconnectInit();


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
