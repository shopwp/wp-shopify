import { onResyncSubmit } from './resync';
import { onCacheClear, clearAllCache } from './cache';
import { onClearSubmit } from './clear';
import { onWebhooksSubmit } from './webhooks';

/*

Tools Init

*/
function toolsInit() {

  onResyncSubmit();
  onCacheClear();
  onClearSubmit();
  

}

function activateToolButtons() {

  jQuery('.tab-content .wps-is-not-active').removeClass('wps-is-not-active').addClass('wps-is-active');

  var $inputs = jQuery('.wps-is-active.wps-button-group input[type="submit"]');

  $inputs.prop('disabled', false);

}

export {
  toolsInit,
  activateToolButtons
}
