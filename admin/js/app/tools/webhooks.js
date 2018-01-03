import isError from 'lodash/isError';

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
  addConnectorStepMessage,
  addNotice,
  showAnyWarnings
} from '../utils/utils-dom';

import {
  connectionInProgress,
  setConnectionProgress,
  setModalCache
} from '../ws/localstorage';

import {
  setSyncingIndicator,
  removeWebhooks,
  registerWebhooks
} from '../ws/ws';

import {
  syncWebhooks
} from '../ws/syncing';

import {
  onModalClose
} from '../forms/events';

import {
  enable,
  disable,
  showSpinner,
  isWordPressError
} from '../utils/utils';

import {
  startProgressBar,
  endProgressBar,
  mapProgressDataFromSessionValues,
  appendProgressBars,
  progressStatus
} from '../utils/utils-progress';

import {
  updateDomAfterDisconnect
} from '../disconnect/disconnect.js';



/*

When Resync form is submitted ...

TODO: We could potentially enhance performance considerably if we do
checksum comparisons. Look into this.

*/
function onWebhooksSubmit() {

  jQuery(".wps-is-active #wps-button-webhooks").unbind().on('click', async function(e) {

    e.preventDefault();

    var $resyncButton = jQuery(this);

    console.log("$resyncButton: ", $resyncButton);

    disable($resyncButton);
    injectConnectorModal( createConnectorModal('Reconnecting Webhooks ...', 'Cancel') );

    // Sets up cancel & close listenters
    onModalClose();
    setConnectionProgress(true);


    /*

    Step 1. Turn on syncing flag

    */
    try {

      var updatingSyncingIndicator = await setSyncingIndicator(1);

      if (isWordPressError(updatingSyncingIndicator)) {
        throw updatingSyncingIndicator.data;

      } else if (isError(updatingSyncingIndicator)) {
        throw updatingSyncingIndicator;

      } else {
        setConnectionStepMessage('Removing any existing webhooks ...');

      }

    } catch(errors) {

      updateModalHeadingText('Canceling ...');
      endProgressBar();

      updateDomAfterDisconnect({
        headingText: 'Canceled',
        buttonText: 'Exit Sync',
        xMark: true,
        errorList: errors,
        clearInputs: false,
        resync: true,
        noticeType: 'error'
      });

      enable($resyncButton);
      return;

    }


    /*

    Step 2. Clearing current data

    */
    try {

      var removedResponse = await removeWebhooks();
      console.log("removedResponse: ", removedResponse);

      if (isWordPressError(removedResponse)) {
        throw removedResponse.data;

      } else if (isError(removedResponse)) {
        throw removedResponse;

      } else {
        setConnectionStepMessage('Syncing new webhooks ...');

      }

    } catch(errors) {
      console.log("errors: ", errors);
      updateModalHeadingText('Canceling ...');
      endProgressBar();

      updateDomAfterDisconnect({
        headingText: 'Canceled',
        buttonText: 'Exit Sync',
        xMark: true,
        errorList: errors,
        clearInputs: false,
        resync: true,
        noticeType: 'error'
      });

      enable($resyncButton);
      return;

    }


    /*

    Start the progress bar

    */
    try {

      var progressSession = await startProgressBar(true, ['webhooks']);
      console.log("progressSession: ", progressSession);
      if (isWordPressError(progressSession)) {
        throw progressSession.data;

      } else if (isError(progressSession)) {
        throw progressSession;
      }

    } catch (errors) {

      updateModalHeadingText('Canceling ...');
      endProgressBar();

      updateDomAfterDisconnect({
        headingText: 'Canceled',
        errorList: errors,
        buttonText: 'Exit Sync',
        xMark: true,
        clearInputs: false,
        resync: true,
        noticeType: 'error'
      });

      enable($resyncButton);
      return;

    }


    /*

    Begin polling for the status ...

    */
    await progressStatus();

    // var steps = mapProgressDataFromSessionValues(progressSession.data);

    appendProgressBars(progressSession.data);




    /*

    Step 2. Syncing new data

    */
    try {

      var registerWebhooksResp = await syncWebhooks();

      console.log("registerWebhooksResp: ", registerWebhooksResp);

      if (isWordPressError(registerWebhooksResp)) {
        throw registerWebhooksResp.data;

      } else if (isError(registerWebhooksResp)) {
        throw registerWebhooksResp;

      } else {
        setConnectionStepMessage('Finishing ...');

      }

    } catch(errors) {
      console.log("syncWebhooks: ", errors);
      updateModalHeadingText('Canceling ...');
      endProgressBar();

      updateDomAfterDisconnect({
        headingText: 'Canceled',
        errorList: errors,
        buttonText: 'Exit Sync',
        xMark: true,
        clearInputs: false,
        resync: true,
        noticeType: 'error'
      });

      enable($resyncButton);
      return;

    }


    /*

    End the progress bar

    */
    endProgressBar();


    /*

    Step 6. Setting Syncing Indicator

    */
    try {

      var updatingSyncingIndicatorResponse = await setSyncingIndicator(0);

      if (isWordPressError(updatingSyncingIndicatorResponse)) {
        throw updatingSyncingIndicatorResponse.data;

      } else if (isError(updatingSyncingIndicatorResponse)) {
        throw updatingSyncingIndicatorResponse;
      }

    } catch(errors) {

      updateDomAfterDisconnect({
        xMark: true,
        headingText: 'Canceled',
        buttonText: 'Exit Sync',
        errorList: errors,
        clearInputs: false,
        resync: true,
        noticeType: 'error'
      });

      enable($resyncButton);
      return;

    }

    console.log('Any warnings to show? ', registerWebhooksResp.data.warnings);

    showAnyWarnings(registerWebhooksResp.data.warnings, 'Warning: Unable to connect the webhook: ');


    initCloseModalEvents();
    insertCheckmark();
    setConnectionNotice('Success! You\'re now syncing with Shopify.', 'success');
    updateModalHeadingText('Sync Complete');
    updateModalButtonText("Ok, let's go!");
    enable($resyncButton);




  });


}


export {
  onWebhooksSubmit
};
