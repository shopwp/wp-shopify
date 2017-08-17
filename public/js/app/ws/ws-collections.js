import 'whatwg-fetch';

/*

Getting all collections, returns promise

*/
function getAllCollections(shopify) {
  return shopify.fetchAllCollections();
}

export { getAllCollections };
