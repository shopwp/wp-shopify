import {
  syncPluginData
} from '../forms/forms';

import {
  getModalCache,
  connectionInProgress,
  removeModalCache,
  removeConnectionProgress,
  removeConnectionNonce,
  clearLocalstorageCache
} from '../ws/localstorage';


/*

Create a checkmark
Returns: $element

*/
function createX() {
  return jQuery('<div class="wps-icon-xmark"></div>');
};


/*

Create a checkmark
Returns: $element

*/
function createCheckmark() {
  return jQuery('<div class="wps-icon-checkmark"></div>');
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
TODO: Needs dynamic image paths

*/
function createConnectorModal(heading = 'Connecting ...', cancelText = 'Cancel connection') {

  if(getModalCache() === null) {

    return jQuery('<div class="wps-connector-wrapper"><div class="wps-connector wps-connector-progress wps-animated wps-fadeInDown"><h1 class="wps-connector-heading"><span>' + heading + '</span> <img class="wps-connector-logo" src="' + window.wps.pluginsDirURL + 'admin/imgs/shopify.svg" /> to <img class="wps-connector-logo" src="' + window.wps.pluginsDirURL + 'admin/imgs/logo-wp.svg" /></h1><div class="l-row"><button type="button" name="button" class="button button-primary wps-btn wps-btn-cancel">' + cancelText + '</button></div><div class="wps-connector-content"></div></ div></div>');

  } else {
    return getModalCache();
  }

};


/*

Change text of connector modal

*/
function updateModalHeadingText(text) {
  jQuery('.wps-connector-heading span').text(text);
}


/*

Util: Adds a notice message to the connector modal
Returns: undefined

*/
function addConnectorNotice(content, type = '', supportingMessage = '') {

  var $notice;

  if(type === 'error') {
    $notice = jQuery('<div class="wps-progress-notice wps-progress-notice-error"><div class="wps-progress-notice-group"><span class="wps-progress-text">' + content + '<small class="wps-progress-text-supporting">' + supportingMessage + '</small></span></div></div>');

  } else {
    $notice = jQuery('<div class="wps-progress-notice wps-progress-notice-success"><div class="wps-progress-notice-group"><span class="wps-progress-text">' + content + '<small class="wps-progress-text-supporting">' + supportingMessage + '</small></span><div class="spinner is-active"></div></div></div>');

  }

  jQuery('.wps-connector-wrapper').find('.wps-connector-content').prepend($notice);

};


/*

Insert Checkmark

*/
function insertCheckmark() {
  jQuery('.wps-connector-content .wps-progress-notice:first-child').append( createCheckmark() );
  jQuery('.wps-connector-content .wps-progress-notice:first-child .spinner').removeClass('is-active');
  jQuery('.wps-connector-content .wps-progress-notice:first-child').addClass('is-inactive');
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

  if (connectionInProgress() !== 'false') {
    insertCheckmark();
    addConnectorNotice(message, '', supportingMessage);
  }

}


/*

Inserts a step in a connection process

*/
function setConnectionMessage(message, type) {
  jQuery('.wps-connector-heading').after('<div class="notice notice-' + type + '">' + message + '</div>');
}


/*

Close the connector modal
Returns: undefined

*/
function initCloseModalEvents() {

  jQuery('.wps-btn-cancel').unbind().on('click', function() {

    jQuery('.wps-connector-wrapper').remove();
    jQuery(document).unbind();

  });

  // Cancel request when user clicks outside modal ...
  jQuery(document).on('click', function(event) {

    if (!jQuery(event.target).closest('.wps-connector').length) {
      jQuery('.wps-connector-wrapper').remove();
      jQuery(document).unbind();
    }

  });

  // Cancel request when user hits escape ...
  jQuery(document).keyup(function(e) {

    jQuery('.wps-connector-wrapper').remove();
    jQuery(document).unbind();

  });

};


/*

Toggle an element active / inactive (hides / shows)

*/
function toggleActive($element) {
  $element.toggleClass('wps-is-active');
};


/*

Util: Stop spinners
Returns: $element without .is-active class
TODO: make dom element instead of classs

*/
function stopSpinner(spinner) {
  jQuery(spinner).removeClass('wps-is-active');
};


/*

Show canceling connection

*/
function updateCurrentConnectionStepText(text) {
  jQuery('.wps-connector-content .wps-progress-notice:first-child .wps-progress-text').text(text);
};


/*

Update Modal Text

*/
function updateModalButtonText(text) {
  jQuery('.wps-btn-cancel').text(text);
}



/*

Update Modal Text

*/
function showAdminNotice(message, type) {

  var $msgContainer = jQuery('#wps-errors');

  $msgContainer.removeClass('wps-is-hidden').html('<div class="notice ' + type + '"><p><strong>' + message + '</strong></p></div>');

  var $msg = $msgContainer.find('.notice');

  if (type === 'updated') {

    setTimeout(function() {

      $msg.slideUp('fast', function() {
        $msgContainer.addClass('wps-is-hidden');
      });

    }, 5000);

  }

}


function removeCheckmarks($form) {
  $form.find('input').removeClass('valid');
}


function clearConnectInputs() {
  jQuery('#wps_settings_connection_js_access_token, #wps_settings_connection_domain').val('').removeClass('valid').prop('disabled', false);
}


function resetConnectSubmit() {
  jQuery('#submitDisconnect')
    .val('Connect your Shopify Account')
    .prop('disabled', false)
    .attr('name', 'submitConnect')
    .attr('id', 'submitConnect');
}


function setDisconnectSubmit() {

  jQuery('#submitConnect')
    .val('Disconnect your Shopify Account')
    .prop('disabled', false)
    .attr('name', 'submitDisconnect')
    .attr('id', 'submitDisconnect');

  jQuery('#wps_settings_connection_js_access_token').prop('disabled', true);
  jQuery('#wps_settings_connection_domain').prop('disabled', true);

}


export {
  createX,
  createCheckmark,
  injectConnectorModal,
  showConnectorModal,
  createConnectorModal,
  addConnectorNotice,
  initCloseModalEvents,
  stopSpinner,
  insertCheckmark,
  insertXMark,
  removeCheckmarks,
  updateModalButtonText,
  updateModalHeadingText,
  updateCurrentConnectionStepText,
  setConnectionStepMessage,
  setConnectionMessage,
  showAdminNotice,
  toggleActive,
  ejectConnectorModal,
  updateConnectStatusHeading,
  clearConnectInputs,
  resetConnectSubmit,
  setDisconnectSubmit
};
