import { hasItemsInLocalStorage } from './ws-products';
import { renderCartItems, updateTotalCartPricing } from '../cart/cart-ui';

/*

Fetch Cart
Returns: Promise

*/
function fetchCart(shopify) {
  return shopify.fetchCart( localStorage.getItem('wps-last-cart-id') );
};


/*

Create Cart
Returns: Promise

*/
function createCart(shopify) {
  return shopify.createCart();
}


/*

Set cart items

*/
function setCart(cart) {
  localStorage.setItem('wps-last-cart-id', cart.id);
}


/*

Add 'quantity' amount of product 'variant' to cart

*/
function updateCart(variant, quantity, shopify) {

  return new Promise(async function(resolve, reject) {

    var options = {
      variant: variant,
      quantity: quantity
    };

    var cart = await fetchCart(shopify);
    // console.log('cart before', cart);

    cart = await cart.createLineItemsFromVariants(options);
    // console.log('cart after', cart);

    renderCartItems(shopify);
    updateTotalCartPricing(shopify);
    // updateCartTabButton(shopify);

    resolve('Done updating cart UI');

  });

};


/*

Initialize Cart

*/
async function initCart(shopify) {

  if(hasItemsInLocalStorage()) {
    // var cart = await fetchCart(shopify);

    return await renderCartItems(shopify);
    // showAllProducts(shopify);

  } else {

    var cart = await createCart(shopify);
    setCart(cart);
    // showAllProducts(shopify);
    return cart;

  }

}

export { fetchCart, createCart, setCart, updateCart, initCart };
