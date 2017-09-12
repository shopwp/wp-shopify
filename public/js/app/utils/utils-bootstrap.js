import {
  shopifyInit,
  getShopifyCreds
} from '../ws/ws-auth';

import { initCart } from '../ws/ws-cart';
import { productEvents } from '../products/products-events';
import { cartEvents } from '../cart/cart-events';
import { updateCartCounter, updateTotalCartPricing } from '../cart/cart-ui';
import { isError } from './utils-common';


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


  // Get Shopify Credentials
  try {

    await getShopifyCreds();

    if (isError(creds)) {
      throw connectionData.data;
    }

  } catch(error) {
    console.log('getShopifyCreds error: ', error);
    return error;
  }


  // Shopify Init
  try {
    var shopify = await shopifyInit(creds.data);

  } catch(error) {
    console.log('shopifyInit error: ', error);
    return error;
  }


  // Init Cart
  try {
    await initCart(shopify);

  } catch(error) {
    console.log('initCart error: ', error);
    return error;
  }


  // Bootstrap
  bootstrapEvents(shopify);
  bootstrapUI(shopify);

}

export default bootstrap;
