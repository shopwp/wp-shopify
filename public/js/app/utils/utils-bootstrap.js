import to from 'await-to-js';
import { shopifyInit, formatCredsFromServer, findShopifyCreds } from '../ws/ws-auth';
import { getCheckout, setCheckout, checkoutCompleted, createCheckout } from '../ws/ws-cart';
import { getShopInfo, setShop } from '../ws/ws-shop';
import { productEvents } from '../products/products-events';
import { showProductsMetaUI, cacheInitialProductPricing } from '../products/products-ui';
import { resetVariantSelectors } from '../products/products-meta';
import { cartEvents } from '../cart/cart-events';
import { isCheckoutEmpty, clearLS } from './utils-cart';
import { globalEvents } from './utils-events';
import { logNotice, noticeConfigUnableToBuildCheckout, noticeConfigUnableToFlushCache, noticeConfigUnableToCreateNewShoppingSession } from './utils-notices';
import { triggerEventAfterBootstrap, triggerEventBeforeBootstrap } from './utils-triggers';
import { updateCartCounter, updateTotalCartPricing, renderCartItems, emptyCartUI, enableCartIcon, renderCartState, showFixedCartIcon } from '../cart/cart-ui';
import { removeProductOptionIds, getCheckoutID } from '../ws/ws-products';


/*

Bootstrap: Events

*/
function bootstrapEvents(client, checkout) {

  productEvents(client);
  cartEvents(client, checkout);
  globalEvents();

  enableCartIcon();

}


/*

Bootstrap: UI elements

*/
function bootstrapProductsUI(client, checkout) {
  removeProductOptionIds();
  resetVariantSelectors(); // Resets DOM related to selecting options
  showProductsMetaUI();
  cacheInitialProductPricing();
}


function bootstrapCartUI(client, checkout) {

  showFixedCartIcon();

  if ( isCheckoutEmpty(checkout) ) {
    emptyCartUI(checkout);

  } else {

    updateCartCounter(client, checkout);
    updateTotalCartPricing(checkout);
    renderCartItems(checkout);

  }

  renderCartState();

}


function cacheGlobalObjects(shop, checkout) {

  setShop(shop);

  // Only set if it's not already set OR expired
  if ( !getCheckoutID() ) {
    setCheckout(checkout);
  }

}


/*

Bootstrap front-end app. Runs every page load.

*/
function bootstrap() {

  triggerEventBeforeBootstrap();

  return new Promise( async (resolve, reject) => {

    // Step 1. Get the Storefront credentials
    var [credsError, creds] = await to( findShopifyCreds() );

    if (credsError) {
      logNotice('findShopifyCreds', credsError, 'error');
      return reject(credsError);
    }


    // Step 2. Init Shopify library
    var [shopifyInitError, client] = await to( shopifyInit( formatCredsFromServer(creds) ) );

    if (shopifyInitError) {
      logNotice('shopifyInit', shopifyInitError, 'error');
      return reject(shopifyInitError);
    }


    /*

    getCheckout is responsible for returning a workable 'checkout' instance. We cache the result further down
    within setCheckout, so getCheckout will check the cache for the ID each time and use that if it exists.
    Therefore it's important that we clear this ID each time the user finishes checking out. Also, since we
    store the ID in LS each time the user clears their cache they will lose their checkout contents.

    */
    var [shopAndCheckoutError, shopAndCheckout] = await to( Promise.all([ getShopInfo(client), getCheckout(client) ]) );

    if (shopAndCheckoutError) {
      logNotice('getShopInfo Promise or getCheckout Promise', shopAndCheckoutError, 'error');
      return reject( noticeConfigUnableToBuildCheckout() );
    }

    // Step 4. Cache Shop / Checkout objects if needed
    var [shop, checkout] = shopAndCheckout;


    // If the existing checkout was completed, get a new, different one
    if ( checkoutCompleted(checkout) ) {

      var [clearLSError, flushCacheResp ] = await to ( clearLS() );

      if (clearLSError) {
        logNotice('clearLS', clearLSError, 'error');
        return reject( noticeConfigUnableToFlushCache() );
      }


      var [createCheckoutError, newCheckout ] = await to( createCheckout(client) );

      if (createCheckoutError) {
        logNotice('createCheckout after checkout completed', createCheckoutError, 'error');
        return reject( noticeConfigUnableToCreateNewShoppingSession() );
      }


      // If all goes well, set the new checkout session to the existing session
      checkout = newCheckout;

    }


    cacheGlobalObjects(shop, checkout);

    // Step 5. Bootstrap Cart UI and events
    bootstrapCartUI(client, checkout);
    bootstrapEvents(client, checkout);
    bootstrapProductsUI();

    triggerEventAfterBootstrap();

    resolve();

  });

}

export default bootstrap;
