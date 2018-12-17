function endpointPrefix() {
  return WP_Shopify.API.restUrl + WP_Shopify.API.namespace + '/';
}

/*

Variants

*/
function endpointVariants() {
  return endpointPrefix() + 'variants';
}

/*

Connection Endpoints

*/
function endpointConnection() {
  return endpointPrefix() + 'connection';
}

export {
  endpointVariants,
  endpointConnection
}
