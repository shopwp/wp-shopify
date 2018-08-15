import to from 'await-to-js';
import isError from 'lodash/isError';
import forEach from 'lodash/forEach';

import {
  getItemCounts,
  syncPluginData
} from '../ws/middleware';

import {
  syncOn,
  saveConnection,
  saveCounts,
  removeExistingData
} from '../ws/syncing';

import {
  syncingConfigErrorBeforeSync,
  syncingConfigJavascriptError,
  syncingConfigSavedConnectionOnly,
  syncingConfigManualCancel
} from '../ws/syncing-config';

import {
  streamShop
} from '../ws/streaming';

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
  showSpinner,
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
  afterWebhooksRemoval,
  cleanUpAfterSync,
  afterDataRemoval,
  manuallyCanceled
} from '../utils/utils-progress';


import {
  setConnectionStepMessage,
  showAdminNotice,
  updateModalButtonText,
  updateModalHeadingText,
  updateCurrentConnectionStepText,
  insertXMark,
  insertCheckmark,
  setConnectionNotice,
  setDisconnectSubmit,
  resetConnectSubmit,
  updateDomAfterSync,
  ejectConnectorModal,
  createModal
} from '../utils/utils-dom';

import {
  returnOnlyFailedRequests,
  constructFinalNoticeList,
  addToWarningList,
  filterOutAnyNotice,
  filterOutSelectiveSync,
  filterOutSelectedDataForSync,
  filterOutEmptySets,
  setConnectionStatus,
  returnOnlyFirstError
} from '../utils/utils-data';

import {
  setPluginSettings,
  getConnectionData,
  setSyncingIndicator,
  endProgress,
  removeWebhooks,
  checkForValidServerConnection
} from '../ws/ws';

import {
  syncOff
} from '../ws/wrappers';

import {
  setModalCache,
  clearLocalstorageCache,
  setStartingURL,
  syncIsCanceled,
  setWebhooksReconnect,
  setCancelSync,
  setConnectionProgress
} from '../ws/localstorage';

import {
  getSelectiveSyncOptions
} from '../settings/settings';

import {
  activateToolButtons
} from '../tools/tools';

import {
  clearAllCache
} from '../tools/cache';




function onSuccess(label, $submitButton) {

  label.addClass("valid").text("Ok!");
  jQuery('#wps-errors').addClass('wps-is-hidden');
  enable($submitButton);

}


function onError(error, $submitButton) {
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

  var domain = jQuery('#wps_settings_general_domain').val();

  // Clear protocol from input field if user mistakenly enters ...
  if (containsProtocol(domain) || containsPathAfterShopifyDomain(domain)) {
    jQuery('#wps_settings_general_domain').val(cleanDomainURL(domain));
  }

}


function getConnectionFormData(form) {
  return formatConnectorFormData(jQuery(form).serializeArray());
}



function prepareBeforeSync() {

  var $formConnect = jQuery("#wps-connect");
  var $submitButton = $formConnect.find('input[type="submit"]');

  // Start with syncing cache clear
  clearLocalstorageCache();

  // Removes previous modal if one exists
  ejectConnectorModal();

  // Removes added protocol or superfluous characters from domain field
  sanitizeDomainField();

  // Disable submit button once clicked
  disable($submitButton);
  forEach($submitButton, showSpinner);

  setCancelSync(false);
  setConnectionProgress(true);

}


function saveConnectionOnly(saveConnectionResponse) {

  if (saveConnectionResponse.data.save_connection_only == '1') {
    return true;

  } else {
    return false;

  }

}


/*

On connect ...

*/
function connectionFormSubmitHandler(form) {


  createModal('Connecting', 'Cancel connection');

  return new Promise(async (resolve, reject) => {


    setConnectionStepMessage('Preparing Shopify connection ...');

    /*

    1. Clear all plugin cache

    */
    var [clearAllCacheError, clearAllCacheResponse] = await to( clearAllCache() ); // insert_customers

    if (clearAllCacheError) {
      cleanUpAfterSync( syncingConfigJavascriptError(clearAllCacheError) );
      resolve();
      return;

    }

    if (isWordPressError(clearAllCacheResponse)) {
      cleanUpAfterSync( syncingConfigErrorBeforeSync(clearAllCacheResponse) );
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

    var [checkForValidServerConnectionError, checkForValidServerConnectionData] = await to( checkForValidServerConnection() );

    if (checkForValidServerConnectionError) {
      cleanUpAfterSync( syncingConfigJavascriptError(checkForValidServerConnectionError) );
      resolve();
      return
    }

    if (isWordPressError(checkForValidServerConnectionData)) {
      cleanUpAfterSync( syncingConfigErrorBeforeSync( returnOnlyFirstError(checkForValidServerConnectionData) ) );
      resolve();
      return;

    }

    if (manuallyCanceled()) {
      cleanUpAfterSync( syncingConfigManualCancel() );
      resolve();
      return;
    }


    /*

    2. Save connection

    */

    insertCheckmark();
    setConnectionStepMessage('Saving Shopify connection ...');

    var [saveConnectionError, saveConnectionData] = await to( saveConnection( getConnectionFormData(form) ) );

    if (saveConnectionError) {
      cleanUpAfterSync( syncingConfigJavascriptError(saveConnectionError) );
      resolve();
      return
    }

    if (isWordPressError(saveConnectionData)) {
      cleanUpAfterSync( syncingConfigErrorBeforeSync( returnOnlyFirstError(saveConnectionData) ) );
      resolve();
      return;
    }

    if (manuallyCanceled()) {
      cleanUpAfterSync( syncingConfigManualCancel() );
      resolve();
      return;
    }




    WP_Shopify.isConnecting = true;
    setConnectionStatus(true);


    if (saveConnectionOnly(saveConnectionData)) {

      var [streamShopError, streamShopData] = await to( streamShop() );

      if (streamShopError) {
        cleanUpAfterSync( syncingConfigJavascriptError(streamShopError) );
        resolve();
        return
      }

      if (isWordPressError(streamShopData)) {
        cleanUpAfterSync( syncingConfigErrorBeforeSync( returnOnlyFirstError(streamShopData) ) );
        resolve();
        return;
      }

      if (manuallyCanceled()) {
        cleanUpAfterSync( syncingConfigManualCancel() );
        resolve();
        return;
      }

      cleanUpAfterSync( syncingConfigSavedConnectionOnly() );

      resolve();
      return;

    }


    /*

    Remove existing data

    */

    insertCheckmark();
    setConnectionStepMessage('Removing any existing data first ...', '(Please wait, this might take 30 seconds or so)');

    var [removeExistingError, removeExistingDataResp] = await to( removeExistingData() );

    if (removeExistingError) {
      cleanUpAfterSync( syncingConfigJavascriptError(removeExistingError) );
      resolve();
      return
    }

    if (isWordPressError(removeExistingDataResp)) {
      cleanUpAfterSync( syncingConfigErrorBeforeSync( returnOnlyFirstError(removeExistingDataResp) ) );
      resolve();
      return;
    }

    if (manuallyCanceled()) {
      cleanUpAfterSync( syncingConfigManualCancel() );
      resolve();
      return;
    }


    /*

    Only fires once data has been removed ...

    */
    afterDataRemoval(async () => {

      var [syncOnError, syncOnData] = await to( syncOn() );

      if (syncOnError) {
        cleanUpAfterSync( syncingConfigJavascriptError(syncOnError) );
        resolve();
        return
      }

      if (isWordPressError(syncOnData)) {
        cleanUpAfterSync( syncingConfigErrorBeforeSync( returnOnlyFirstError(syncOnData) ) );
        resolve();
        return;
      }

      if (manuallyCanceled()) {
        cleanUpAfterSync( syncingConfigManualCancel() );
        resolve();
        return;
      }






        insertCheckmark();
        setConnectionStepMessage('Starting connection ...');


        var [startProgressBarError, startProgressBarResponse] = await to( startProgressBar(true, getSelectiveSyncOptions() ) );

        if (startProgressBarError) {
          cleanUpAfterSync( syncingConfigJavascriptError(startProgressBarError) );
          resolve();
          return
        }

        if (isWordPressError(startProgressBarResponse)) {
          cleanUpAfterSync( syncingConfigErrorBeforeSync( returnOnlyFirstError(startProgressBarResponse) ) );
          resolve();
          return;
        }

        if (manuallyCanceled()) {
          cleanUpAfterSync( syncingConfigManualCancel() );
          resolve();
          return;
        }



        insertCheckmark();
        setConnectionStepMessage('Determining the number of items to sync ...');

        var [itemCountsError, itemCountsResp] = await to( getItemCounts() );

        if (itemCountsError) {
          cleanUpAfterSync( syncingConfigJavascriptError(itemCountsError) );
          resolve();
          return
        }

        if (isWordPressError(itemCountsResp)) {
          cleanUpAfterSync( syncingConfigErrorBeforeSync( returnOnlyFirstError(itemCountsResp) ) );
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

        var allCounts = filterOutEmptySets( filterOutSelectiveSync( filterOutAnyNotice( getDataFromArray(itemCountsResp) ) ) );

        var [saveCountsError, saveCountsResponse] = await to( saveCounts(allCounts) ); // insert_syncing_totals

        if (saveCountsError) {
          cleanUpAfterSync( syncingConfigJavascriptError(saveCountsError) );
          resolve();
          return
        }

        if (isWordPressError(saveCountsResponse)) {
          cleanUpAfterSync( syncingConfigErrorBeforeSync( returnOnlyFirstError(saveCountsResponse) ) );
          resolve();
          return;
        }

        if (manuallyCanceled()) {
          cleanUpAfterSync( syncingConfigManualCancel() );
          resolve();
          return;
        }


        insertCheckmark();
        updateModalHeadingText('Syncing');
        updateModalButtonText('Cancel syncing process');
        setConnectionStepMessage('Syncing Shopify data ...', '(Please wait, this may take up to 5 minutes depending on the size of your store and speed of internet connection.)');

        appendProgressBars(allCounts);

        // 7. Begin polling for the status ... creates a cancelable loop
        progressStatus();

        syncPluginData(allCounts, true);



    });

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
