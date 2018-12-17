import $ from 'jquery';
import 'babel-polyfill';
// import '@babel/preset-env';
// import '@babel/preset-react';
// import '@babel/polyfill';
// import '@wordpress/components';
// import '@babel/plugin-transform-runtime',
// import '@babel/plugin-syntax-dynamic-import',
// import '@babel/plugin-syntax-import-meta',
// import '@babel/plugin-proposal-class-properties',
// import '@babel/plugin-proposal-json-string'

global.$ = global.jQuery = $;

global.WP_Shopify = {
  ajax: "http://wpshopify.loc/wp-admin/admin-ajax.php",
  API: {
    baseUrl: "http://wpshopify.loc/",
    namespace: "wpshopify/v1",
    restUrl: "http://wpshopify.loc/api/",
    urlPrefix: "api"
  },
  hasConnection: "",
  isClearing: "",
  isConnecting: "",
  isDisconnecting: "",
  isSyncing: "",
  itemsPerRequest: 250,
  latestVersion: "1.2.4",
  latestVersionCombined: "124",
  manuallyCanceled: "",
  maxItemsPerRequest: "250",
  migrationNeeded: "",
  nonce: "2488872091",
  pluginsDirURL: "http://wpshopify.loc/wp-content/plugins/wp-shopify-pro/",
  pluginsPath: "http://wpshopify.loc/wp-content/plugins",
  reconnectingWebhooks: "",
  hasCartTerms: "0",
  selective_sync: {
    all: 1,
    custom_collections: 0,
    customers: 0,
    orders: 0,
    products: 0,
    shop: 1,
    smart_collections: 0,
  },
  siteUrl: "http://wpshopify.loc",
  settings: {
    colorAddToCart: "#FFFFFF"
  }
}
