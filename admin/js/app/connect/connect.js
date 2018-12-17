import to from 'await-to-js';
import forEach from 'lodash/forEach';

import {
  getItemCounts,
  syncPluginData
} from '../ws/middleware';

import {
  syncOn,
  removeExistingData
} from '../ws/syncing';

import {
  noConnectionReset,
  clearAllCache
} from '../ws/wrappers';

import {
  saveCounts
} from '../ws/api/api-syncing';

import {
  endpointConnection,
  endpointConnectionCheck,
  endpointToolsClearSynced,
} from '../ws/api/api-endpoints';

import {
  getShop
} from '../ws/api/api-shop';

import {
  syncingConfigErrorBeforeSync,
  syncingConfigJavascriptError,
  syncingConfigSavedConnectionOnly,
  syncingConfigManualCancel
} from '../ws/syncing-config';

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
  initSyncingTimer
} from '../utils/utils-timer';

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
  createModal,
  hideAdminNoticeByType
} from '../utils/utils-dom';

import {
  returnOnlyFailedRequests,
  constructFinalNoticeList,
  addToWarningList,
  filterOutAnyNotice,
  filterOutSelectiveSync,
  filterOutEmptySets,
  setConnectionStatus,
  returnOnlyFirstError
} from '../utils/utils-data';

import {
  post,
  deletion,
  endProgress
} from '../ws/ws';

import {
  deleteWebhooks
} from '../ws/api/api-webhooks';

import {
  getPublishedProductIds
} from '../ws/api/api-products';

import {
  setModalCache,
  clearLocalstorageCache,
  setStartingURL,
  setWebhooksReconnect,
  setCancelSync,
  setConnectionProgress
} from '../ws/localstorage';

import {
  getSelectiveSyncOptions
} from '../settings/settings.jsx';

import {
  activateToolButtons
} from '../tools/tools';



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
  return formatConnectorFormData( jQuery(form).serializeArray() );
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

  if (WP_Shopify.settings.connection.saveConnectionOnly) {
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

    1. Clear all plugin cache and reset previous syncing notices

    */
    var [clearAllCacheError, clearAllCacheResponse] = await to( clearAllCache() );

    if (clearAllCacheError) {
      cleanUpAfterSync( syncingConfigJavascriptError(clearAllCacheError) );
      return resolve();
    }

    if (isWordPressError(clearAllCacheResponse)) {
      cleanUpAfterSync( syncingConfigErrorBeforeSync(clearAllCacheResponse) );
      return resolve();
    }

    if (manuallyCanceled()) {
      cleanUpAfterSync( syncingConfigManualCancel() );
      return resolve();
    }


    /*

    2. Save connection

    */

    insertCheckmark();
    setConnectionStepMessage('Saving Shopify connection ...');

    var [saveConnectionError, saveConnectionData] = await to( post(
      endpointConnection(),
      { connection: getConnectionFormData(form) }
    ));

    if (saveConnectionError) {
      cleanUpAfterSync( syncingConfigJavascriptError(saveConnectionError) );
      return resolve();
    }

    if (isWordPressError(saveConnectionData)) {
      cleanUpAfterSync( syncingConfigErrorBeforeSync( returnOnlyFirstError(saveConnectionData) ) );
      return resolve();;
    }

    if (manuallyCanceled()) {
      cleanUpAfterSync( syncingConfigManualCancel() );
      return resolve();;
    }


    /*

    Checks for an open connection to the server ...

    */

    insertCheckmark();
    setConnectionStepMessage('Validating Shopify connection ...');


    var [serverCheckError, serverCheckData] = await to( post( endpointConnectionCheck(), {
      type: 'shopify',
      creds: getConnectionFormData(form)
    }));



    if (serverCheckError) {
      cleanUpAfterSync( syncingConfigJavascriptError(serverCheckError) );
      return resolve();
    }

    if (isWordPressError(serverCheckData)) {

      // No active connection exists. Just drop data and clear cache.
      var [noConnectionResetError, noConnectionResetData] = await to( noConnectionReset() );

      if (noConnectionResetError) {
        cleanUpAfterSync( syncingConfigJavascriptError(noConnectionResetError) );
        return resolve();
      }

      if (isWordPressError(noConnectionResetData)) {
        cleanUpAfterSync( syncingConfigErrorBeforeSync( returnOnlyFirstError(noConnectionResetData) ) );
        return resolve();
      }

      if (manuallyCanceled()) {
        cleanUpAfterSync( syncingConfigManualCancel() );
        return resolve();
      }

      showAdminNotice('Your Shopify API credentials appear to be incorrect. Please double check and try again', 'error');
      hideAdminNoticeByType('notice_warning_app_uninstalled');
      setConnectionStatus(false);

      cleanUpAfterSync({ keepInputs: true  });

      return resolve();;

    }

    if (manuallyCanceled()) {
      cleanUpAfterSync( syncingConfigManualCancel() );
      return resolve();;
    }


    WP_Shopify.isConnecting = true;
    setConnectionStatus(true);


    if (saveConnectionOnly(saveConnectionData)) {

      var [streamShopError, streamShopData] = await to( getShop() );

      if (streamShopError) {
        cleanUpAfterSync( syncingConfigJavascriptError(streamShopError) );
        return resolve();
      }

      if (isWordPressError(streamShopData)) {
        cleanUpAfterSync( syncingConfigErrorBeforeSync( returnOnlyFirstError(streamShopData) ) );
        return resolve();;
      }

      if (manuallyCanceled()) {
        cleanUpAfterSync( syncingConfigManualCancel() );
        return resolve();;
      }

      cleanUpAfterSync( syncingConfigSavedConnectionOnly() );

      return resolve();

    }


    /*

    Remove existing data

    */

    insertCheckmark();
    setConnectionStepMessage('Removing any existing data first ...', '(Please wait, this might take 30 seconds or so)');


    var [removeExistingError, removeExistingDataResp] = await to( deletion( endpointToolsClearSynced() ) );

    if (removeExistingError) {
      cleanUpAfterSync( syncingConfigJavascriptError(removeExistingError) );
      return resolve();
    }

    if (isWordPressError(removeExistingDataResp)) {
      cleanUpAfterSync( syncingConfigErrorBeforeSync( returnOnlyFirstError(removeExistingDataResp) ) );
      return resolve();
    }

    if (manuallyCanceled()) {
      cleanUpAfterSync( syncingConfigManualCancel() );
      return resolve();
    }


    WP_Shopify.isSyncing = true;


    /*

    Only fires once data has been removed ...

    */
    afterDataRemoval( async () => {

      var [syncOnError, syncOnData] = await to( syncOn() );

      if (syncOnError) {
        cleanUpAfterSync( syncingConfigJavascriptError(syncOnError) );
        return resolve();
      }

      if (isWordPressError(syncOnData)) {
        cleanUpAfterSync( syncingConfigErrorBeforeSync( returnOnlyFirstError(syncOnData) ) );
        return resolve();
      }

      if (manuallyCanceled()) {
        cleanUpAfterSync( syncingConfigManualCancel() );
        return resolve();
      }






        insertCheckmark();
        setConnectionStepMessage('Starting connection ...');


        var [startProgressBarError, startProgressBarResponse] = await to( startProgressBar(true, getSelectiveSyncOptions() ) );

        if (startProgressBarError) {
          cleanUpAfterSync( syncingConfigJavascriptError(startProgressBarError) );
          return resolve();
        }

        if (isWordPressError(startProgressBarResponse)) {
          cleanUpAfterSync( syncingConfigErrorBeforeSync( returnOnlyFirstError(startProgressBarResponse) ) );
          return resolve();
        }

        if (manuallyCanceled()) {
          cleanUpAfterSync( syncingConfigManualCancel() );
          return resolve();
        }



        insertCheckmark();
        setConnectionStepMessage('Determining the number of items to sync ...');

        var [itemCountsError, itemCountsResp] = await to( getItemCounts() );

        if (itemCountsError) {
          cleanUpAfterSync( syncingConfigJavascriptError(itemCountsError) );
          return resolve();
        }

        if (isWordPressError(itemCountsResp)) {
          cleanUpAfterSync( syncingConfigErrorBeforeSync( returnOnlyFirstError(itemCountsResp) ) );
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

        5. Save item counts

        */

        var allCounts = filterOutEmptySets( filterOutSelectiveSync( filterOutAnyNotice( getDataFromArray(itemCountsResp) ) ) );

        var [saveCountsError, saveCountsResponse] = await to( saveCounts({
          counts: allCounts
        }) );

        if (saveCountsError) {
          cleanUpAfterSync( syncingConfigJavascriptError(saveCountsError) );
          return resolve();
        }

        if (isWordPressError(saveCountsResponse)) {
          cleanUpAfterSync( syncingConfigErrorBeforeSync( returnOnlyFirstError(saveCountsResponse) ) );
          return resolve();
        }

        if (manuallyCanceled()) {
          cleanUpAfterSync( syncingConfigManualCancel() );
          return resolve();
        }


        insertCheckmark();
        updateModalHeadingText('Syncing');
        updateModalButtonText('Cancel syncing process');
        setConnectionStepMessage('Syncing Shopify data ...', '(Please wait, this may take up to 5 minutes depending on the size of your store and speed of internet connection.)');

        appendProgressBars(allCounts);

        // 7. Begin polling for the status ... creates a cancelable loop
        initSyncingTimer();
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
  prepareBeforeSync,
  getConnectionFormData
}
