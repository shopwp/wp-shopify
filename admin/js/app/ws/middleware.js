import {
  syncConnection,
  syncShop,
  syncProducts,
  syncCollects,
  syncSmartCollections,
  syncCustomCollections
} from './syncing';

import {
  setConnectionStepMessage
} from '../connect/connect';

import {
  updateModalHeadingText
} from '../utils/utils-dom';

import {
  removePluginData
} from '../ws/ws';

import {
  uninstallPluginData
} from '../disconnect/disconnect';


/*

Syncing Shopify data with WordPress CPT

*/
async function syncPluginData() {


  // 1. Smart Collections
  try {
    await syncSmartCollections();

  } catch(errors) {

    // uninstallPluginData({
    //   errorList: errors,
    //   xMark: true
    // });

    return new Error(errors);

  }


  // 2. Smart Collections
  try {
    await syncCustomCollections();

  } catch(errors) {

    // uninstallPluginData({
    //   errorList: errors,
    //   xMark: true
    // });

    return new Error(errors);

  }


  // 3. Remaining data
  try {

    var remainingResp = await Promise.all([
      syncConnection(),
      syncShop(),
      syncProducts(),
      syncCollects()
    ]);

  } catch(errors) {

    // uninstallPluginData({
    //   errorList: errors,
    //   xMark: true
    // });

    return new Error(errors);

  }

  return remainingResp;


}

export {
  syncPluginData
}
