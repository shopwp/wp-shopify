import { onResyncSubmit } from './resync';
import { onCacheClear } from './cache';
import { onClearSubmit } from './clear';
import { onWebhooksSubmit } from './webhooks';

/*

Tools Init

*/
function toolsInit() {

  /* @if NODE_ENV='pro' */
  onResyncSubmit();
  /* @endif */

  onCacheClear();
  onClearSubmit();
  onWebhooksSubmit();

}

function activateToolButtons() {
  jQuery('.tab-content .wps-is-not-active').removeClass('wps-is-not-active').addClass('wps-is-active');
}

export {
  toolsInit,
  activateToolButtons
}
