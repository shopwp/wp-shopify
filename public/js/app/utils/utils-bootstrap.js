import {
  shopifyInit,
  getShopifyCreds
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

  try {
    var creds = await getShopifyCreds();
    // console.log('getShopifyCreds() done');

  } catch(error) {

    // TODO: Show front-end error message
    console.log('getShopifyCreds error: ', error);
  }

  try {
    var shopify = await shopifyInit(creds);
    // console.log('shopifyInit() done');

  } catch(error) {

    // TODO: Show front-end error message
    console.log('shopifyInit error: ', error);
  }

  try {
    await initCart(shopify);
    // console.log('initCart() done');

  } catch(error) {

    // TODO: Show front-end error message
    console.log('initCart error: ', error);
  }

  bootstrapEvents(shopify);
  bootstrapUI(shopify);

}

export default bootstrap;
