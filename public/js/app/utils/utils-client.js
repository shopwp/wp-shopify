/*

Checks for an active client object. We store this
upon initial bootstrap for ease of use.

*/
function clientActive() {

  if (!WP_Shopify.client) {
    return false;

  } else {
    return true;
  }

}


function getClient() {

  if (clientActive()) {
    return WP_Shopify.client;
  }

}


function setClient(client) {
  WP_Shopify.client = client;
}


export {
  clientActive,
  getClient,
  setClient
}
