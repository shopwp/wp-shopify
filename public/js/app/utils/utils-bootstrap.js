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

  if (hasExistingCredentials()) {

    try {
      var creds = await getExistingShopifyCreds();
    } catch(error) {
      console.log('1 getExistingShopifyCreds error: ', error);
    }

    try {
      var shopify = await shopifyInit(creds);
    } catch(error) {
      console.log('1 shopifyInit error: ', error);
    }

    try {
      var cart = await initCart(shopify);
    } catch(error) {
      console.log('1 initCart error: ', error);
    }

    bootstrapEvents(shopify);
    bootstrapUI(shopify);

  } else {

    try {
      var creds = await getShopifyCreds();
    } catch(error) {
      console.log('2 getShopifyCreds error: ', error);
    }

    try {
      var savedCreds = await setShopifyCreds(creds);
    } catch(error) {
      console.log('2 setShopifyCreds error: ', error);
    }

    try {
      var shopify = await shopifyInit(creds);
    } catch(error) {
      console.log('2 shopifyInit error: ', error);
    }

    try {
      var cart = await initCart(shopify);
    } catch(error) {
      console.log('2 initCart error: ', error);
    }

    bootstrapEvents(shopify);
    bootstrapUI(shopify);

  }

}

export default bootstrap;
