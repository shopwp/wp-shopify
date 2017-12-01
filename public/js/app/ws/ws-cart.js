import { needsCacheFlush, flushCache } from '../utils/utils-cart';
import { getCartID } from './ws-products';
import { setCartCache } from './ws-settings';
import { renderCartItems, renderSingleCartItem, updateTotalCartPricing } from '../cart/cart-ui';

/*

Fetch Cart
Returns: Promise

*/
async function fetchCart(shopify) {

  if (!shopify) {
    return false;
  }

  // Either get the current cart instance or create a new one
  try {

    var cartID = getCartID();

    if (!cartID) {
      throw new Error('Cart is null');
    }

    var cart = await shopify.fetchCart( getCartID() );

  } catch(e) {

    var cart = await createCart(shopify);

  }

  return cart ? cart : false;

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
      var cart = await fetchCart(shopify);

    } catch(error) {
      reject(error);
    }

    try {

      var newCart = await createLineItemsFromVariants({
        variant: variant,
        quantity: quantity
      }, shopify);

    } catch(error) {
      reject(error);
    }


    /*

    Only caches if needed ...

    */
    try {

      await setCartCache(newCart.id);

    } catch (error) {
      console.error('Cached cart ERROR', error);
      reject(error);
    }


    renderSingleCartItem(shopify, newCart, variant);
    updateTotalCartPricing(shopify, newCart);
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
async function initCart(shopify) {

  const currentCartID = getCartID();
  var transientFound;

  // This only runs if user already has a cart instance. New users skip.
  if (currentCartID) {

    transientFound = await needsCacheFlush(currentCartID);

    if (!transientFound) {

      try {
        await flushCache(shopify);

      } catch (error) {
        console.error("flushCache: ", error);
      }

    }

  }


  /*

  Render the actual cart items (if any). Empty for new users.

  */
  try {

    var cart = await renderCartItems(shopify);

  } catch(error) {
    console.error("renderCartItems: ", error)
    return error;

  }


  // Save Cart ID to LS if new user
  if (!currentCartID) {
    saveCartID(cart);
  }

  // Saves cart ID to LS 'wps-last-cart-id' which is used by getCartID
  // updateTotalCartPricing(shopify);

  return cart;

}


export {
  fetchCart,
  createCart,
  saveCartID,
  updateCart,
  initCart,
  createLineItemsFromVariants
};
