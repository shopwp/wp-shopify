import {
  initCloseModalEvents,
  updateModalHeadingText,
  updateCurrentConnectionStepText
} from '../utils/utils-dom';

import {
  connectionInProgress,
  setConnectionProgress
} from '../ws/localstorage';

import {
  resetProgressIndicators
} from '../utils/utils';

import {
  setSyncingIndicator
} from '../ws/ws';

/*

When the user closes any modal
Returns: undefined

*/
function onModalClose() {

  // Cancel request when user clicks cancel button
  jQuery('.wps-btn-cancel').unbind().on('click', async function(e) {

    jQuery(this).prop("disabled", true);
    resetProgressIndicators();
    setConnectionProgress('false');
    updateModalHeadingText('Canceling ...');
    updateCurrentConnectionStepText('Cleaning up ...');

    // This is responsible for firing the "cancel event" during a resync
    setSyncingIndicator(0);

  });

  // Cancel request when user clicks outside modal ...
  jQuery(document).on('click', function(event) {

    // if (!jQuery(event.target).closest('.wps-connector-progress').length) {
    //   initCloseModalEvents();
    //   resetProgressIndicators();
    //   setConnectionProgress('false');
    // }

  });

  // Cancel request when user hits escape ...
  jQuery(document).keyup(function(e) {

    if (e.keyCode == 27) {

      jQuery('.wps-btn-cancel').prop("disabled", true);
      resetProgressIndicators();
      setConnectionProgress('false');
      updateModalHeadingText('Canceling ...');
      updateCurrentConnectionStepText('Cleaning up ...');
      setSyncingIndicator(0);

    }

  });

};

export {
  onModalClose
};
