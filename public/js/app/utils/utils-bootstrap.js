import { shopifyInit, getShopifyCreds } from '../ws/ws-auth';
import { initCart, fetchCart, flushCacheIfNeeded } from '../ws/ws-cart';
import { productEvents } from '../products/products-events';
import { cartEvents } from '../cart/cart-events';
import { updateCartCounter, updateTotalCartPricing, showUIElements, renderCartItems } from '../cart/cart-ui';
import { isError } from './utils-common';
import { isEmptyCart } from './utils-cart';
import { removeProductOptionIds } from '../ws/ws-products';


/*

Bootstrap: Events

*/
function bootstrapEvents(shopify) {

  return new Promise( (resolve, reject) => {

    productEvents(shopify);
    cartEvents(shopify);
    resolve();

  });

}


/*

Bootstrap: UI elements

*/
function bootstrapUI(shopify, cart) {

  return new Promise( async (resolve, reject) => {

    if ( isEmptyCart(cart) ) {
      resolve();
    }

    updateCartCounter(shopify, cart);
    removeProductOptionIds();

    try {

      await updateTotalCartPricing(shopify, cart);

    } catch (error) {
      reject(error);
    }

    resolve();

  });

}



/*

Bootstrap front-end app. Runs every page load.

*/
function bootstrap() {

  jQuery(document).trigger("wpshopify_bootstrap_before");
  
  return new Promise( async (resolve, reject) => {

    /*

    Step 1. Get Shopify Credentials

    */
    try {

      var creds = await getShopifyCreds(); // wps_get_credentials_frontend

      if (isError(creds)) {
        throw creds.data;
      }

    } catch(error) {
      reject(error);
      return;
    }


    /*

    Step 2. Init Shopify lib

    */
    try {

      // Calls LS
      var shopify = await shopifyInit(creds.data);

      if (isError(shopify)) {
        throw shopify.data;
      }

    } catch(error) {
      reject(error);
      return;
    }


    /*

    Step 3. Get or create cart instance

    */
    try {

      // Retrieves existing or makes new
      var cart = await fetchCart(shopify);

    } catch(error) {

      reject(error);
      return;

    }


    /*

    Step 4. Flush cache, render cart items, and update DOM

    */
    try {

      await Promise.all([
        flushCacheIfNeeded(shopify, cart),
        renderCartItems(shopify, cart),
        bootstrapUI(shopify, cart)
      ]);

    } catch (error) {
      reject(error);
      return;

    }


    /*

    Step 5. Add event handlers

    */
    try {

      await bootstrapEvents(shopify);

    } catch(error) {

      reject(error);
      return;

    }


    /*

    Step 6. Enable and show cart icon / add to cart buttons

    */
    showUIElements();

    jQuery(document).trigger("wpshopify_bootstrap_after", [cart]);

    resolve();

  });

}

export default bootstrap;
