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
  containsPathAfterShopifyDomain
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
  closeModal,
  insertCheckmark,
  setConnectionMessage
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
  getConnectionData
} from '../ws/ws.js';

import {
  connectionInProgress,
  setConnectionProgress,
  setModalCache,
  clearLocalstorageCache
} from '../ws/localstorage.js';

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

      // var formData = jQuery(form).serialize();
      var formData = formatConnectorFormData(jQuery(form).serializeArray());


      setConnectionProgress("true");

      disable($submitButton);
      R.forEach(showSpinner, $submitButton);

      injectConnectorModal($connectorModal);

      // Close Listenter
      onModalClose();

      showConnectorModal($connectorModal);
      setNonce( $formInputNonce.val() );
      setConnectionStepMessage('Saving settings');


      try {
        await insertConnectionData(formData);
        setConnectionStepMessage('Getting plugin settings');

      } catch (error) {

        updateModalHeadingText('Canceling ...');
        await uninstallPluginData();

        return;

      }


      /*

      Get Waypoint auth token...

      */
      try {
        var authToken = await getAuthToken();

        setConnectionStepMessage('Getting user auth data');

      } catch (error) {

        updateModalHeadingText('Canceling ...');

        await uninstallPluginData();

        return;

      }


      /*

      Get Waypoint auth user data ...

      */
      try {

        var authUserData = await getAuthUser(authToken.token);
        setConnectionStepMessage('Setting user auth data');

      } catch (error) {

        updateModalHeadingText('Canceling ...');

        await uninstallPluginData();

        return;

      }


      /*

      Update auth user data ...

      */
      try {

        var yep = await updateAuthUser(authToken.token, authUserData);
        console.log("yep: ", yep);
        setConnectionStepMessage('Creating Shopify URL');

      } catch (error) {

        updateModalHeadingText('Canceling ...');
        await uninstallPluginData();

        return;

      }


      /*

      Getting Shopify URL ...

      */
      try {

        var shopifyURL = await getShopifyURL();

        console.log("shopifyURL: ", shopifyURL);

        setConnectionStepMessage('Redirecting to Shopify');
        updateModalHeadingText('Redirecting ...');

      } catch (error) {

        updateModalHeadingText('Canceling ...');
        await uninstallPluginData();

        return;

      }


      /*

      Sending user to Shopify for OAuth ...

      */
      setTimeout(async function() {
        if( connectionInProgress() === 'true') {

          /*

          Saving modal to LS for later use

          */
          var $modalCopy = jQuery('.wps-connector-wrapper').clone();
          setModalCache( $modalCopy.wrap('<p/>').parent().html() );

          // Let's go!
          window.location = shopifyURL;

        } else {

          updateModalHeadingText('Canceling ...');
          await uninstallPluginData();

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
  setConnectionStepMessage('Syncing Shopify data', '(Please wait. This may take up to 60 seconds depending on how many products you have.)');


  try {

    console.log('Syncing products and collections ...');

    //
    // TODO:
    // Create a real-time progress bar to show syncing progress
    //

    await syncPluginData();

    // removeProgressLoader();

    console.log('Done!');

    // setConnectionStepMessage('Redirecting to Shopify');

    closeModal();
    insertCheckmark();
    setConnectionMessage('Success! You\'re now connected and syncing with Shopify.', 'success');
    updateModalHeadingText('Connected');
    setConnectionProgress("false");
    updateModalButtonText("Ok, let's go!");
    disconnectInit();

  } catch (error) {

    jQuery(document).unbind();
    closeModal();

    console.log('........................');
    console.log(error);
    console.log('........................');

    try {

      console.log('Uninstalling ...');
      updateModalHeadingText('Canceling ...');

      await uninstallPluginData({
        headingText: 'Canceled',
        stepText: error,
        buttonText: 'Exit Connection',
        xMark: true
      });

      console.log('Done uninstalling ...');

    } catch(err) {
      console.log('Error uninstalling ...', err);

    }

    console.log('8 Done cleaning up');

  }

}


/*

Connect Init

*/
function connectInit() {
  onConnectionFormSubmit();
  // onAuthRedirect();
}

export { connectInit, onAuthRedirect };
