import {
  shopifyInit,
  getShopifyCreds
} from '../ws/ws-auth';

import { initCart } from '../ws/ws-cart';
import { productEvents } from '../products/products-events';
import { cartEvents } from '../cart/cart-events';
import { updateCartCounter, updateTotalCartPricing } from '../cart/cart-ui';
import { isError } from './utils-common';
import { removeProductOptionIds } from '../ws/ws-products';


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
function bootstrapUI(shopify, cart) {

  updateCartCounter(shopify, cart);
  updateTotalCartPricing(shopify, cart);
  removeProductOptionIds();

}


/*

Init Shopify
TODO: Little bit of duplication happening here. Could be done better.

*/
async function bootstrap() {

  // Get Shopify Credentials
  try {

    var creds = await getShopifyCreds();

    if (isError(creds)) {
      console.log("creds: ", creds);
      throw creds.data;
    }

  } catch(error) {
    console.error('getShopifyCreds error: ', error);
    return error;
  }


  // Shopify Init
  try {
    var shopify = await shopifyInit(creds.data);

  } catch(error) {
    console.error('shopifyInit error: ', error);
    return error;
  }


  // Init Cart
  try {
    var cart = await initCart(shopify);

    if (isError(cart)) {
      console.log("cart: ", cart);
      throw cart.data;
    }

  } catch(error) {
    console.error('initCart error: ', error);
    return error;
  }


  // Bootstrap
  bootstrapEvents(shopify);
  bootstrapUI(shopify, cart);

}

export default bootstrap;
