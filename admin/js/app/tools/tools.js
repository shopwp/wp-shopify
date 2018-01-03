import { onResyncSubmit } from './resync';
import { onCacheClear } from './cache';
import { onClearSubmit } from './clear';
import { onWebhooksSubmit } from './webhooks';

/*

Tools Init

*/
function toolsInit() {
  onResyncSubmit();
  onCacheClear();
  onClearSubmit();
  onWebhooksSubmit();
}

export {
  toolsInit
}
