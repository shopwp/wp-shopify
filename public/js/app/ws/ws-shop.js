

function shopActive() {

  if (!WP_Shopify.shop) {
    return false;

  } else {
    return true;
  }

}

function getShop() {
  return WP_Shopify.shop;
}

function setShop(shop) {

  if (!shopActive()) {
    WP_Shopify.shop = shop;
  }

}


/*

Gets the Shop info

client: The Shopify instance

Returns an immediately resolved promise if shop exists

*/
function getShopInfo(client) {

  if ( shopActive() ) {
    return new Promise( (resolve, reject) => resolve( getShop() ) );
  }

  return client.shop.fetchInfo();

}


function getMoneyFormat(shop) {
  return shop.moneyFormat;
}


export {
  getMoneyFormat,
  getShopInfo,
  getShop,
  setShop
}
