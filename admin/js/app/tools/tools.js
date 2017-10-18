import { onResyncSubmit } from './resync';
import { onCacheClear } from './cache';

/*

Tools Init

*/
function toolsInit() {
  onResyncSubmit();
  onCacheClear();
}

export {
  toolsInit
}
