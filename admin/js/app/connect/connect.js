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
  formatConnectorFormData
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
  removeProgressLoader,
  updateProgressLoader
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
  setConnectionMessage,
  setDisconnectSubmit
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
  removePluginData,
  syncWithCPT,
  startProgressBar
} from '../ws/ws.js';

import {
  connectionInProgress,
  setConnectionProgress,
  setModalCache,
  clearLocalstorageCache
} from '../ws/localstorage.js';

import {
  clearAllCache
} from '../tools/cache.js';

import {
  uninstallPluginData,
  disconnectInit
} from '../disconnect/disconnect.js';


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

    rules: {
      "js_access_token": {
        alphaNumeric: true
      },
      "domain": {
        domainRule: true
      }
    },
    success: function(label) {
      label.addClass("valid").text("Ok!");
      jQuery('#wps-errors').addClass('wps-is-hidden');
      enable($submitButton);

    },
    errorPlacement: function(error) {
      disable($submitButton);
      showAdminNotice(error.text(), 'error');

    },
    submitHandler: async function(form) {

      clearLocalstorageCache();

      var $formInputNonce = jQuery("#wps_settings_connection_nonce_id");
      var $connectorModal = createConnectorModal();
      var domain = jQuery('#wps_settings_connection_domain').val();

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
      setConnectionStepMessage('Saving connection ...');


      /*

      Step 1. Insert Connection Data

      */
      try {

        var connectionData = await insertConnectionData(formData);

        if (isWordPressError(connectionData)) {
          throw connectionData.data;

        } else if (isError(connectionData)) {
          throw connectionData;

        } else {
          setConnectionStepMessage('Getting authentication token ...');

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

      Step 2. Get Waypoint auth token...
      (Makes call to wpshop.io)

      */
      try {

        var authToken = await getAuthToken();

        if (isWordPressError(authToken)) {
          throw authToken.data;

        } else if (isError(authToken)) {
          throw authToken;

        } else {
          setConnectionStepMessage('Verifying authenticated user ...');
        }

      } catch (errors) {

        uninstallPluginData({
          errorList: errors,
          xMark: true,
          headingText: 'Canceled',
          stepText: 'Unable to finish syncing at getAuthToken',
          buttonText: 'Exit Sync'
        });

        return;

      }


      /*

      Step 3. Get Waypoint auth user data ...
      (Makes call to wpshop.io)

      */
      try {

        var authUserData = await getAuthUser(authToken.token);

        if (isWordPressError(authUserData)) {
          throw authUserData.data;

        } else if (isError(authUserData)) {
          throw authUserData;

        } else {
          setConnectionStepMessage('Establishing session ...');
        }

      } catch (errors) {

        uninstallPluginData({
          errorList: errors,
          xMark: true,
          headingText: 'Canceled',
          stepText: 'Unable to finish syncing at getAuthUser',
          buttonText: 'Exit Sync'
        });

        return;

      }


      /*

      Step 4. Update auth user data ...
      (Makes call to wpshop.io)

      */
      try {

        var authUserResult = await updateAuthUser(authToken.token, authUserData);

        if (isWordPressError(authUserResult)) {
          throw authUserResult.data;

        } else if (isError(authUserResult)) {
          throw authUserResult;

        } else {
          setConnectionStepMessage('Creating Shopify URL ...');
        }

      } catch (errors) {

        uninstallPluginData({
          errorList: errors,
          xMark: true,
          headingText: 'Canceled',
          stepText: 'Unable to finish syncing at updateAuthUser',
          buttonText: 'Exit Sync'
        });

        return;

      }


      /*

      Step 5. Getting Shopify URL ...

      */
      try {

        var shopifyURLResponse = await getShopifyURL();

        if (isWordPressError(shopifyURLResponse)) {
          throw shopifyURLResponse.data;

        } else if (isError(shopifyURLResponse)) {
          throw shopifyURLResponse;

        } else {

          var shopifyURL = shopifyURLResponse.data;

          setConnectionStepMessage('Redirecting to Shopify ...');
          updateModalHeadingText('Redirecting to Shopify ...');

        }

      } catch (errors) {

        uninstallPluginData({
          errorList: errors,
          xMark: true,
          headingText: 'Canceled',
          stepText: 'Unable to finish syncing at getShopifyURL',
          buttonText: 'Exit Sync'
        });

        return;

      }


      /*

      Step 6. Sending user to Shopify for OAuth ...

      */
      setTimeout(async function() {

        if (connectionInProgress() === 'true') {

          /*

          Saving modal to LS for later use

          */
          setModalCache(
            jQuery('.wps-connector-wrapper')
              .clone()
              .wrap('<p/>')
              .parent()
              .html()
          );

          // Let's go!
          window.location = shopifyURL;

        } else {

          uninstallPluginData({
            headingText: 'Canceled',
            stepText: 'Unable to finish syncing at setModalCache',
            buttonText: 'Exit Sync',
            errorList: 'Stopped by user',
            xMark: false
          });

          return;

        }

      }, 2000);

    }

  });

}


/*

Step 2/2: OAuth Redirect

*/
async function onAuthRedirect() {

  jQuery('body').addClass('wps-is-back-from-shopify');


  /*

  At this point we can start syncing the products / collections

  */
  injectConnectorModal( createConnectorModal() );

  onModalClose();
  insertCheckmark();
  updateModalHeadingText('Syncing ...');
  updateModalButtonText('Cancel syncing process');
  setConnectionStepMessage('Syncing Shopify data ...', '(Please wait, this may take up to 5 minutes depending on the size of your store and speed of your internet connection.)');

  /*

  Step 1. Turn on syncing flag

  */
  try {

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
      buttonText: 'Exit Sync'
    });

  }


  /*

  Step 2. Remove any existing data

  */
  try {

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
      buttonText: 'Exit Sync'
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
    // startProgressBar('some_data');

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
      buttonText: 'Exit Sync'
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
      buttonText: 'Exit Sync'
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
      setConnectionMessage('Success! You\'re now connected and syncing with Shopify.', 'success');
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
      buttonText: 'Exit Sync'
    });

  }

  // setConnectionStepMessage('Redirecting to Shopify');


}


/*

Connect Init

*/
function connectInit() {
  onConnectionFormSubmit();
}

export {
  connectInit,
  onAuthRedirect
}
