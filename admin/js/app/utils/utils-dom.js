import forOwn from 'lodash/forOwn';
import isArray from 'lodash/isArray';
import isEmpty from 'lodash/isEmpty';
import forEach from 'lodash/forEach';
import isPlainObject from 'lodash/isPlainObject';
import isString from 'lodash/isString';

import {
  checkMark,
  slideInDown
} from './utils-animations';

import {
  connectInit,
  prepareBeforeSync
} from '../connect/connect';

import {
  initDismissNoticeEvents
} from '../notices/notices';

import {
  getCombinedExitOptions,
  hasConnection,
  getConnectionStatus
} from './utils-data';

import {
  enable
} from './utils';

import {
  manuallyCanceled
} from './utils-progress';

import {
  onModalClose
} from '../forms/events';

import {
  getModalCache,
  isConnectionInProgress,
  removeModalCache,
  removeConnectionProgress,
  clearLocalstorageCache,
  syncIsCanceled
} from '../ws/localstorage';

import he from 'he';

/*

Create a checkmark
Returns: $element

*/
function createX() {
  return jQuery('<span class="dashicons dashicons-no"></span>');
};


/*

Create a checkmark
Returns: $element

*/
function createCheckmark() {
  return jQuery('<span class="dashicons dashicons-yes"></span>');
};


/*

Util: Inject the connector modal

*/
function injectConnectorModal($connectorModal) {
  jQuery('body').append($connectorModal);
};


/*

Util: Delete connector modal

*/
function ejectConnectorModal() {
  jQuery('body').find('.wps-connector-wrapper').remove();
};


/*

Util: Update status heading

*/
function updateConnectStatusHeading(status) {

  if(status === 'is-connected') {
    jQuery('.wps-status-heading .wps-status').removeClass('is-disconnected').addClass('is-connected').text('Connected');

  } else {
    jQuery('.wps-status-heading .wps-status').removeClass('is-connected').addClass('is-disconnected').text('Disconnected');

  }

}


/*

Show the connector modal

*/
function showConnectorModal($connectorModal) {

  if ($connectorModal.length) {

    if (typeof $connectorModal === 'string') {
      jQuery($connectorModal).find('.wps-connector-progress').show();

    } else {
      $connectorModal.find('.wps-connector-progress').show();
    }

  } else {
    return false;
  }

};


/*

Creates a new connector modal if one doesn't already exist
Returns: $element

*/
function modalDOM(heading = 'Connecting ...', cancelText = 'Cancel connection') {

  if (getModalCache() === null) {

    return jQuery('<div class="wps-connector-wrapper"><div class="wps-connector wps-connector-progress wps-animated wps-fadeInDown"><span class="wps-modal-close dashicons dashicons-no-alt"></span><h1 class="wps-connector-heading"><span>' + heading + '</span> <img class="wps-connector-logo" src="' + WP_Shopify.pluginsDirURL + 'admin/imgs/shopify.svg" /> to <img class="wps-connector-logo" src="' + WP_Shopify.pluginsDirURL + 'admin/imgs/logo-wp.svg" /></h1><div class="l-row"><button type="button" name="button" class="button button-primary wps-btn wps-btn-cancel">' + cancelText + '</button></div><div class="wps-connector-content"></div></ div><footer class="wps-modal-footer"><span>Sync duration: </span><span id="wps-sync-duration"></span></footer></div>');

  } else {
    return getModalCache();
  }

};


/*

Creates a modal

*/
function createModal(heading = '', cancelText = '') {

  prepareBeforeSync();

  var $connectorModal = modalDOM(heading, cancelText);

  injectConnectorModal($connectorModal);
  onModalClose();
  showConnectorModal($connectorModal);

}


/*

Change text of connector modal

*/
function updateModalHeadingText(text) {
  jQuery('.wps-connector-heading span').text(text);
}


/*

Adds a message to the step
Returns: undefined

*/
function addConnectorStepMessage(content, type = '', supportingMessage = '') {

  if ( !syncIsCanceled() ) {

    var $notice;

    if (type === 'error') {
      $notice = jQuery('<div class="wps-progress-notice wps-progress-notice-error"><div class="wps-progress-notice-group"><span class="wps-progress-text">' + content + '<small class="wps-progress-text-supporting">' + supportingMessage + '</small></span></div></div>');

    } else {

      $notice = jQuery('<div class="wps-progress-notice wps-progress-notice-success"><div class="wps-progress-notice-group"><span class="wps-progress-text">' + content + '<small class="wps-progress-text-supporting">' + supportingMessage + '</small></span><div class="spinner is-active"></div></div></div>');

    }

    jQuery('.wps-connector-wrapper').find('.wps-connector-content').prepend($notice);

  }

};


/*

Insert Checkmark

*/
function insertCheckmark() {

  jQuery('.wps-connector-content .wps-progress-notice:first-child').append( createCheckmark() );
  jQuery('.wps-connector-content .wps-progress-notice:first-child .spinner').removeClass('is-active');
  jQuery('.wps-connector-content .wps-progress-notice:first-child').addClass('is-inactive');

  checkMark( jQuery('.wps-connector-content .wps-progress-notice:first-child').find('.dashicons') );

}


/*

Insert X Mark

*/
function insertXMark() {
  jQuery('.wps-connector-content .wps-progress-notice:first-child').append( createX() );
  jQuery('.wps-connector-content .wps-progress-notice:first-child .spinner').removeClass('is-active');
}


/*

Inserts a step in a connection process

*/
function setConnectionStepMessage(message, supportingMessage = '') {

  if (isConnectionInProgress()) {
    addConnectorStepMessage(message, '', supportingMessage);
  }

}


/*

Inserts a step in a connection process

*/
function setConnectionNotice(message, type) {
  jQuery('.wps-connector-heading').after('<div class="notice notice-' + type + '">' + message + '</div>');
}


function showSyncByCollectionsNotice(errorMessage = false) {

  if (errorMessage) {

    jQuery('#wps-sync-by-collections-checkbox-wrapper .notice')
      .replaceWith('<p class="notice notice-error inline">' + he.decode(errorMessage) + '</p>');
  }

  jQuery('#wps-sync-by-collections-checkbox-wrapper').removeClass('wps-is-hidden');
  jQuery('#wps-sync-by-collections-wrapper').addClass('wps-is-hidden');

}


function hideSyncByCollectionsNotice() {

  jQuery('#wps-sync-by-collections-checkbox-wrapper').addClass('wps-is-hidden');
  jQuery('#wps-sync-by-collections-wrapper').removeClass('wps-is-hidden');

}


/*

Inserts a step in a connection process

*/
function addNotice(message, type) {

  var $heading = jQuery('.wps-connector-heading');
  var $existingNotices = $heading.next('.notice');

  if ($existingNotices.length) {
    $existingNotices.after('<div class="notice notice-' + type + '">' + message + '</div>');

  } else {
    setConnectionNotice(message, type);
  }


}


/*

Close the connector modal
Returns: undefined

*/
function initCloseModalEvents() {

  jQuery('.wps-btn-cancel, .wps-modal-close').unbind().on('click', function() {

    jQuery('.wps-connector-wrapper').remove();
    jQuery(document).unbind();
    clearLocalstorageCache();

  });

  // Cancel request when user clicks outside modal ...
  jQuery(document).on('click', function(event) {

    if (!jQuery(event.target).closest('.wps-connector').length) {

      jQuery('.wps-connector-wrapper').remove();
      jQuery(document).unbind();
      clearLocalstorageCache();

    }

  });

};


/*

Toggle an element active / inactive (hides / shows)

*/
function toggleActive($element) {
  $element.toggleClass('wps-is-active');
}


/*

Util: Stop spinners
Returns: $element without .is-active class
TODO: make dom element instead of classs

*/
function stopSpinner(spinner) {
  jQuery(spinner).removeClass('wps-is-active');
}


/*

Show canceling connection

*/
function updateCurrentConnectionStepText(text) {
  jQuery('.wps-connector-content .wps-progress-notice:first-child .wps-progress-text').text(text);
}


/*

Update Modal Text

*/
function updateModalButtonText(text) {
  jQuery('.wps-btn-cancel').text(text);
}



function showCollectionsNotice(message, type) {

  if (message) {
    jQuery('#wps-sync-by-collections-wrapper').empty()
    .append('<div class="notice notice-' + type + ' inline"><p>' + he.decode(message) + '</p></div>');
  }

}


/*

Reset sync by collections options

*/
function resetSyncByCollectionOptions() {
  jQuery("#wps-sync-by-collections option:selected").removeAttr("selected");
  jQuery("#wps-sync-by-collections").trigger("chosen:updated");
}


/*

Hides an admin notice by type

*/
function hideAdminNoticeByType(type = false) {

  if (type) {
    jQuery('.notice[data-dismiss-name="' + type + '"]').fadeOut();
  }

}


/*

Shows admin notice

*/
function showAdminNotice(message, type = 'error') {

  var message_decoded = '';

  if (message && isString(message)) {
    message_decoded = he.decode(message);
  }

  var $msgContainer = jQuery('#wps-errors');

  var $notice = jQuery('<div class="wps-notice notice is-dismissible ' + type + '"><p>' + message_decoded + '</p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></div>');

  $msgContainer.empty().removeClass('wps-is-hidden').append($notice);
  // slideInDown($notice);

  initDismissNoticeEvents();

  var $msg = $msgContainer.find('.notice');

  if (type === 'updated') {

    setTimeout(function() {

      $msg.slideUp('fast', function() {
        $msgContainer.addClass('wps-is-hidden');
      });

    }, 5000);

  }

  enableRecentlyActiveButton();
  hideRecentlyActiveSpinners();

}


/*

Remove Checkmarks

*/
function removeCheckmarks($form) {
  $form.find('input').removeClass('valid');
}


/*

Clear Connect Inputs

*/
function clearConnectInputs(options = false) {

  if (!options.keepInputs) {
    jQuery('#wps-connect .wps-form-group input').val('').removeClass('valid').prop('disabled', false);
  }

}


/*

Clear Connect Inputs

*/
function disableConnectInputs() {
  jQuery('#wps-connect .wps-form-group input').prop('disabled', true).attr('disabled', true);
}


/*

Enables Connection submit button whether disconnection / connection state

*/
function enableConnectionSubmit() {
  enable(jQuery('#wps-connect input[type="submit"]'));
}


/*

Enables any inputs

*/
function enableRecentlyActiveButton() {
  enable( jQuery('.wps-button-group .spinner.wps-is-active').prev() );
}


/*

Hides any active spinners

*/
function hideRecentlyActiveSpinners() {
  jQuery('.wps-button-group .spinner.wps-is-active').removeClass('wps-is-active');
}


/*

Reset Connect Submit

*/
function resetConnectSubmit() {

  jQuery('#submitDisconnect, #wps-connect .wps-button-group input[type="submit"]')
    .val('Connect your Shopify store')
    .prop('disabled', false)
    .attr('disabled', false)
    .attr('name', 'submitConnect')
    .attr('id', 'submitConnect');

}


/*

Set Disconnect Submit

*/
function setDisconnectSubmit() {

  jQuery('#submitConnect')
    .val('Disconnect your Shopify store')
    .prop('disabled', false)
    .attr('disabled', false)
    .attr('name', 'submitDisconnect')
    .attr('id', 'submitDisconnect');

  disableConnectInputs();

}


/*

Set Disconnect Submit

*/
function addStopConnectorClass() {
  jQuery('.wps-connector').addClass('wps-is-stopping');
}


/*

Show any warnings

*/
function showAnyWarnings(warnings, msg = '') {

  forOwn(warnings, (value, key) => {
    addNotice(msg + key, 'warning');
  });

}



/*

Resets the DOM elements related to the connector

*/
function resetConnectionDOM() {

  // clearConnectInputs();
  initCloseModalEvents();
  enable(jQuery('.wps-btn-cancel').blur());
  enableConnectionSubmit();
}


/*

Appends notice
TODO: Move the plain object check elsewhere

*/
function appendNotice(notice) {

  if (!isPlainObject(notice)) {
    notice = {
      type: 'warning',
      message: notice
    }
  }

  var $notice = jQuery('<div class="notice notice-' + notice.type + '">' + he.decode(notice.message) + '</div>');

  jQuery('.wps-connector-heading').after($notice);

  // slideInDown($notice);

}


/*

Showing error message

*/
function updateNotices(exitOptions) {

  if (isArray(exitOptions.noticeList) && !isEmpty(exitOptions.noticeList)) {
    forEach(exitOptions.noticeList, appendNotice);
  }

}

function replaceSpinnersWithCheckmarks(exitOptions) {

  jQuery('.wps-progress-notice').not('.is-inactive').each(function() {

    var $notice = jQuery(this);
    var $spinner = $notice.find('.spinner');

    if ($spinner.length) {

      $spinner.remove();

      if (exitOptions.xMark === true) {
        $notice.append(createX());

      } else {
         $notice.append( createCheckmark() );
         checkMark($notice.find('.dashicons-yes'));
      }

    }

  });

}





/*

Runs as the final step after:
  - Disconnecting
  - Connecting
  - Any erors
  - Manual disconnecting

*/
function updateDomAfterSync(customOptions = {}) {

  const exitOptions = getCombinedExitOptions(customOptions);

  resetConnectionDOM();
  replaceSpinnersWithCheckmarks(exitOptions);

  updateModalHeadingText(exitOptions.headingText);
  updateModalButtonText(exitOptions.buttonText);
  updateCurrentConnectionStepText(exitOptions.stepText);
  updateConnectStatusHeading( getConnectionStatus() );
  updateNotices(exitOptions);

}


/*

Returns the connector cancel button

*/
function getConnectorCancelButton() {
  return jQuery('.wps-connector .wps-btn-cancel');
}


/*

Returns all the Tools buttons

*/
function getToolsButtons() {
  return jQuery('#wps-button-sync, #wps-button-webhooks');
}


export {
  createX,
  createCheckmark,
  injectConnectorModal,
  showConnectorModal,
  modalDOM,
  initCloseModalEvents,
  stopSpinner,
  insertCheckmark,
  insertXMark,
  removeCheckmarks,
  updateModalButtonText,
  updateModalHeadingText,
  updateCurrentConnectionStepText,
  setConnectionStepMessage,
  setConnectionNotice,
  showAdminNotice,
  toggleActive,
  ejectConnectorModal,
  updateConnectStatusHeading,
  clearConnectInputs,
  resetConnectSubmit,
  setDisconnectSubmit,
  addNotice,
  showAnyWarnings,
  updateDomAfterSync,
  resetConnectionDOM,
  addStopConnectorClass,
  getConnectorCancelButton,
  getToolsButtons,
  enableConnectionSubmit,
  enableRecentlyActiveButton,
  hideRecentlyActiveSpinners,
  showCollectionsNotice,
  hideAdminNoticeByType,
  showSyncByCollectionsNotice,
  hideSyncByCollectionsNotice,
  resetSyncByCollectionOptions,
  createModal
}
