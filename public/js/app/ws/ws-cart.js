import { needsCacheFlush, flushCache, emptyCartID } from '../utils/utils-cart';
import { getCartID } from './ws-products';
import { setCartCache } from './ws-settings';
import { renderCartItems, renderSingleCartItem, updateTotalCartPricing } from '../cart/cart-ui';

/*

Fetch Cart
Returns: Promise

*/
function fetchCart(shopify) {

  return new Promise( async (resolve, reject) => {

    if (!shopify) {

      reject({
        type: 'error',
        message: 'Shopify instance not found. Please clear your browser cache and reload.'
      });

      return;

    }

    // Either get the current cart instance or create a new one

    // Calls LS
    var cartID = getCartID();

    if ( emptyCartID(cartID) ) {

      try {

        // Calls LS
        var cart = await createCart(shopify);

        saveCartID(cart);

      } catch (error) {

        reject(error);
        return;

      }

    } else {

      try {

        // Calls LS
        var cart = await shopify.fetchCart(cartID);

      } catch (error) {

        reject(error);
        return;

      }

    }

    resolve(cart);
    return;

  });

};


/*

Create Cart
Returns: Promise

*/
async function createCart(shopify) {
  return shopify.createCart();
}


/*

Set cart items

*/
function saveCartID(cart) {
  localStorage.setItem('wps-last-cart-id', cart.id);
}


/*

Create Line Items From Variants

*/
function createLineItemsFromVariants(options, shopify) {

  return new Promise(async function(resolve, reject) {

    try {

      var cart = await fetchCart(shopify);

    } catch(error) {
      reject(error);
    }

    try {
      var newCart = await cart.createLineItemsFromVariants(options);
      resolve(newCart);

    } catch(error) {
      reject(error);
    }

  });

}


/*

Add 'quantity' amount of product 'variant' to cart
Returns a promise that resolves to an updated Cart instance

*/
function updateCart(variant, quantity, shopify) {

  return new Promise(async function(resolve, reject) {

    try {

      var newCart = await createLineItemsFromVariants({
        variant: variant,
        quantity: quantity
      }, shopify);

    } catch(error) {
      reject(error);
      return;
    }


    /*

    Only caches if needed ...

    */
    try {
      await setCartCache(newCart.id);

    } catch (error) {
      reject(error);
      return;
    }

    renderSingleCartItem(shopify, newCart, variant);


    try {
      await updateTotalCartPricing(shopify, newCart);

    } catch (error) {
      reject(error);
      return;
    }

    resolve(newCart);


  });

};


/*

Initialize Cart
Returns a cart instance

The cart will be cleared during any of the following scenarios:
  1. If the user has successully purchased a product
  2. If the user clears the browser cache
  3. If it's been longer than three days

*/
function initCart(shopify, cart) {

  return new Promise( async (resolve, reject) => {

    /*

    Render the actual cart items (if any). Empty for new users.

    */
    try {
      var cart = await renderCartItems(shopify, cart);

    } catch(error) {
      reject(error);
      return;
    }


    // Saves cart ID to LS 'wps-last-cart-id' which is used by getCartID
    // updateTotalCartPricing(shopify);

    resolve(cart);
    return;

  });

}


/*

flushCacheIfNeeded

*/
function flushCacheIfNeeded(shopify, cart) {

  return new Promise( async (resolve, reject) => {

    // Calls LS
    const currentCartID = getCartID();

    // This only runs if user already has a cart instance. New users skip.
    if (currentCartID) {

      try {

        // Calls server
        var transientFound = await needsCacheFlush(currentCartID); // only called here

      } catch (error) {

        reject(error);
        return;

      }


      // If a new cart exists (user cleared cache or went to checkout page)
      if (!transientFound) {

        try {

          // Calls LS
          await flushCache(shopify); // only called here

        } catch (error) {

          reject(error);
          return;
        }

      }

    }

    saveCartID(cart);
    resolve();

  });

}



export {
  fetchCart,
  createCart,
  saveCartID,
  updateCart,
  initCart,
  flushCacheIfNeeded,
  createLineItemsFromVariants
};
