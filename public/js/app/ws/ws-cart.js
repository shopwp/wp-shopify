import { needsCacheFlush, flushCache } from '../utils/utils-cart';
import { getCartID } from './ws-products';
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

    renderSingleCartItem(shopify, newCart, variant);
    updateTotalCartPricing(shopify, newCart);
    resolve(newCart);

  });

};


/*

Initialize Cart
Returns a cart instance

*/
async function initCart(shopify) {

  var cacheFlushNeeded;
  var cart;

  /*

  Check cache flush status ..

  */
  try {
    cacheFlushNeeded = await needsCacheFlush();

  } catch (error) {
    console.error("needsCacheFlush", error);
  }


  /*

  Flush cache if needed ...

  */
  // if (cacheFlushNeeded) {
  //
  //   try {
  //     await flushCache(shopify);
  //
  //   } catch (error) {
  //     console.error("flushCache: ", error);
  //
  //   }
  //
  // }


  /*

  Render the actual cart items (if any)

  */
  try {
    cart = await renderCartItems(shopify);

  } catch(error) {
    console.error("renderCartItems: ", error)
    return error;

  }

  saveCartID(cart);
  updateTotalCartPricing(shopify);

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
