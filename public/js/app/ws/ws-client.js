import Client from 'shopify-buy';


/*

If the provided credentials are not valid, an error will be thrown.

*/
function buildClient(creds) {

  return Client.buildClient({
    storefrontAccessToken: creds.js_access_token,
    domain: creds.domain
  });

}

export {
  buildClient
}
