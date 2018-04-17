import { shopifyInit, getShopifyCreds, findShopifyCreds } from '../ws/ws-auth';
import { initCart, fetchCart } from '../ws/ws-cart';
import { productEvents } from '../products/products-events';
import { cartEvents } from '../cart/cart-events';
import { updateCartCounter, updateTotalCartPricing, showUIElements, renderCartItems } from '../cart/cart-ui';
import { isError } from './utils-common';
import { isEmptyCart } from './utils-cart';
import { removeProductOptionIds } from '../ws/ws-products';


/*

Bootstrap: Events

*/
function bootstrapEvents(shopify, cart) {

  return new Promise( (resolve, reject) => {

    productEvents(shopify);
    cartEvents(shopify, cart);

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

    Step 1. Find the Storefront credentials

    */
    try {
      var creds = await findShopifyCreds(); // LS || wps_get_credentials_frontend

    } catch(error) {
      reject(error);
      return;

    }


    /*

    Step 2. Init Shopify lib

    */
    try {

      // Calls LS
      var shopify = await shopifyInit(creds);

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

    Step 4. Add event handlers

    */
    try {
      await bootstrapEvents(shopify, cart);

    } catch(error) {

      reject(error);
      return;

    }


    /*

    Step 5. Enable and show cart icon / add to cart buttons

    */
    showUIElements();

    /*

    Step 6. Flush cache, render cart items, and update DOM

    */
    try {

      await Promise.all([
        renderCartItems(shopify, cart),
        bootstrapUI(shopify, cart)
      ]);


    } catch (error) {
      reject(error);
      return;

    }

    jQuery(document).trigger("wpshopify_bootstrap_after", [cart]);
    resolve();

  });

}

export default bootstrap;
