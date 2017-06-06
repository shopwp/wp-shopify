import {
  syncConnection,
  syncShop,
  syncProducts,
  syncCollects,
  syncSmartCollections,
  syncCustomCollections
} from './syncing';


/*

Syncing Shopify data with WordPress CPT

*/
function syncPluginData() {

  return Promise.all([
    syncConnection(),
    syncShop(),
    syncProducts(),
    syncCollects(),
    syncSmartCollections(),
    syncCustomCollections()
  ]);

}

export {
  syncPluginData
}
