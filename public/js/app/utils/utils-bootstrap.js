import {
  getExistingShopifyCreds,
  shopifyInit,
  getShopifyCreds,
  setShopifyCreds,
  hasExistingCredentials
} from '../ws/ws-auth';

import { initCart } from '../ws/ws-cart';
import { productEvents } from '../products/products-events';
import { cartEvents } from '../cart/cart-events';
import { updateCartCounter, updateTotalCartPricing } from '../cart/cart-ui';


/*

bootstrapEvents

*/
function bootstrapEvents(shopify) {
  productEvents(shopify);
  cartEvents(shopify);
}


/*

bootstrapUI

*/
function bootstrapUI(shopify) {
  updateCartCounter(shopify);
  updateTotalCartPricing(shopify);
}


/*

Init Shopify
TODO: Little bit of duplication happening here. Could be done better.

*/
async function bootstrap() {
console.log('11111111 ');
  if (hasExistingCredentials()) {
    var creds = await getExistingShopifyCreds();
    var shopify = await shopifyInit(creds);
    var cart = await initCart(shopify);
    console.log('shopify', shopify);
    console.log('cart', cart);
    bootstrapEvents(shopify);
    bootstrapUI(shopify);

  } else {
    var creds = await getShopifyCreds();
    console.log('creds: ', creds);

    var savedCreds = await setShopifyCreds(creds);

    console.log('savedCreds: ', savedCreds);

    var shopify = await shopifyInit(creds)

    console.log('shopify: ', shopify);

    var cart = await initCart(shopify);

    bootstrapEvents(shopify);
    bootstrapUI(shopify);

  }

}

export default bootstrap;
