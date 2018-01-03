import forEachRamda from 'ramda/es/forEach';
import isError from 'lodash/isError';

import {
  syncPluginData
} from '../ws/middleware';

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
  isWordPressError
} from '../utils/utils';

import {
  createProgressLoader,
  removeProgressLoader
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
  setDisconnectSubmit
} from '../utils/utils-dom';

import {
  resetSyncingURL
} from '../utils/utils-data';

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
  removePluginData,
  syncWithCPT
} from '../ws/ws.js';

import {
  connectionInProgress,
  setConnectionProgress,
  setModalCache,
  clearLocalstorageCache,
  setStartingURL
} from '../ws/localstorage.js';

import {
  clearAllCache
} from '../tools/cache.js';

import {
  uninstallPluginData,
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
    success: function(label) {
      onSuccess(label, $submitButton);
    },
    errorPlacement: function(error) {
      onError(error, $submitButton);
    },
    submitHandler: async function(form) {

      clearLocalstorageCache();

      var $formInputNonce = jQuery("#wps_settings_connection_nonce_id");
      var $connectorModal = createConnectorModal();
      var domain = jQuery('#wps_settings_connection_domain').val();

      setStartingURL(window.location.pathname + window.location.search);

      // Clear protocol from input field if user mistakenly enters ...
      if (containsProtocol(domain) || containsPathAfterShopifyDomain(domain)) {
        jQuery('#wps_settings_connection_domain').val(cleanDomainURL(domain));
      }

      var formData = formatConnectorFormData(jQuery(form).serializeArray());

      setConnectionProgress("true");

      disable($submitButton);
      forEachRamda(showSpinner, $submitButton);

      injectConnectorModal($connectorModal);

      // Close Listenter
      onModalClose();

      showConnectorModal($connectorModal);
      setNonce( $formInputNonce.val() );


      /*

      Step 1. Insert Connection Data

      */
      try {

        setConnectionStepMessage('Saving connection ...');

        var connectionData = await insertConnectionData(formData); // wps_insert_connection

        if (isWordPressError(connectionData)) {
          throw connectionData.data;

        } else if (isError(connectionData)) {
          throw connectionData;

        }

      } catch (errors) {

        uninstallPluginData({
          errorList: errors,
          xMark: true,
          headingText: 'Canceled',
          stepText: 'Unable to finish syncing at insertConnectionData',
          buttonText: 'Exit Sync'
        });

        return;

      }


      /*

      Step 2. Remove any existing data

      */
      try {

        setConnectionStepMessage('Cleaning out any existing data ...');

        var removePluginDataResp = await removePluginData();

        if (isWordPressError(removePluginDataResp)) {

          throw removePluginDataResp.data;

        } else if (isError(removePluginDataResp)) {

          throw removePluginDataResp;

        }

      } catch(errors) {

        return uninstallPluginData({
          errorList: errors,
          xMark: true,
          headingText: 'Canceled',
          stepText: 'Unable to finish syncing',
          buttonText: 'Exit Sync',
          clearInputs: true
        });

      }


      /*

      Step 1. Turn on syncing flag

      */
      try {

        setConnectionStepMessage('Preparing to sync ...');

        var updatingSyncingIndicator = await setSyncingIndicator(1);

        if (isWordPressError(updatingSyncingIndicator)) {

          throw updatingSyncingIndicator.data;

        } else if (isError(updatingSyncingIndicator)) {

          throw updatingSyncingIndicator;

        }

      } catch(errors) {

        return uninstallPluginData({
          errorList: errors,
          xMark: true,
          headingText: 'Canceled',
          stepText: 'Unable to finish syncing',
          buttonText: 'Exit Sync',
          clearInputs: true
        });

      }


      /*

      Step 3. Sync data

      */
      try {

        //
        // TODO:
        // Create a real-time progress bar to show syncing progress
        //


        updateModalHeadingText('Syncing ...');
        updateModalButtonText('Cancel syncing process');
        setConnectionStepMessage('Syncing Shopify data ...', '(Please wait, this may take up to 5 minutes depending on the size of your store and speed of your internet connection.)');

        var syncPluginDataResp = await syncPluginData();

        if (isWordPressError(syncPluginDataResp)) {
          throw syncPluginDataResp.data;

        } else if (isError(syncPluginDataResp)) {
          throw syncPluginDataResp;

        } else {
          setConnectionStepMessage('Finishing ...');
        }

      } catch (errors) {

        return uninstallPluginData({
          errorList: errors,
          xMark: true,
          headingText: 'Canceled',
          stepText: 'Unable to finish syncing',
          buttonText: 'Exit Sync',
          clearInputs: true
        });

      }


      /*

      Step 4. Sync new data with CPT

      */
      // try {
      //   var syncWithCPTResponse = await syncWithCPT();
      //
      //   if (isWordPressError(syncWithCPTResponse)) {
      //     throw syncWithCPTResponse.data;
      //
      //   } else if (isError(syncWithCPTResponse)) {
      //     throw syncWithCPTResponse;
      //
      //   } else {
      //     setConnectionStepMessage('Finishing ...');
      //   }
      //
      // } catch(errors) {
      //
      //   return uninstallPluginData({
      //     stepText: 'Failed syncing custom post types',
      //     headingText: 'Canceled',
      //     errorList: errors,
      //     buttonText: 'Exit Sync',
      //     xMark: true
      //   });
      //
      // }


      /*

      Step 4. Clear all plugin cache

      */
      try {

        var clearAllCacheResponse = await clearAllCache();

        if (isWordPressError(clearAllCacheResponse)) {
          throw clearAllCacheResponse.data;

        } else if (isError(syncPluginDataResp)) {
          throw clearAllCacheResponse;

        }

      } catch(errors) {

        return uninstallPluginData({
          errorList: errors,
          xMark: true,
          headingText: 'Canceled',
          stepText: 'Unable to finish syncing',
          buttonText: 'Exit Sync',
          clearInputs: true
        });

      }


      /*

      Step 5. Turn off syncing flag

      */
      try {

        var updatingSyncingIndicator = await setSyncingIndicator(0);

        if (isWordPressError(updatingSyncingIndicator)) {

          throw updatingSyncingIndicator.data;

        } else if (isError(updatingSyncingIndicator)) {

          throw updatingSyncingIndicator;

        } else {

          initCloseModalEvents();
          insertCheckmark();
          setConnectionNotice('Success! You\'re now connected and syncing with Shopify.', 'success');
          updateModalHeadingText('Connected');
          setConnectionProgress("false");
          updateModalButtonText("Ok, let's go!");
          setDisconnectSubmit();
          disconnectInit();

        }

      } catch(errors) {

        return uninstallPluginData({
          errorList: errors,
          xMark: true,
          headingText: 'Canceled',
          stepText: 'Unable to finish syncing',
          buttonText: 'Exit Sync',
          clearInputs: true
        });

      }

      // setConnectionStepMessage('Redirecting to Shopify');

    }

  });

}


/*

Step 2/2: OAuth Redirect

*/
async function onAuthRedirect() {

  jQuery('body').addClass('wps-is-back-from-shopify');

  resetSyncingURL();

  /*

  At this point we can start syncing the products / collections

  */
  injectConnectorModal( createConnectorModal() );

  onModalClose();
  insertCheckmark();
  updateModalHeadingText('Syncing ...');
  updateModalButtonText('Cancel syncing process');
  setConnectionStepMessage('Syncing Shopify data ...', '(Please wait, this may take up to 5 minutes depending on the size of your store and speed of your internet connection.)');




}


/*

Connect Init

*/
function connectInit() {
  onConnectionFormSubmit();
}

export {
  connectInit,
  onAuthRedirect,
  resetSyncingURL
}
