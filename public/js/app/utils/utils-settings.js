/*

hasEnableCustomCheckoutDomain

*/
function hasEnableCustomCheckoutDomain() {
  return WP_Shopify.settings.enableCustomCheckoutDomain;
}


/*

hasCurrencyCode

*/
function hasCurrencyCode() {
  return WP_Shopify.settings.hasCurrencyCode;
}


export {
  hasEnableCustomCheckoutDomain,
  hasCurrencyCode
}
