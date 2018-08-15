import { getErrorContents } from '../utils/utils-notices';


/*

Returns true if transient exists / found -- false otherwise

*/
function getCheckoutCache(checkoutID) {

  return new Promise((resolve, reject) => {

    const action_name = 'get_checkout_cache';

    jQuery.ajax({
      method: 'POST',
      url: WP_Shopify.ajax,
      dataType: 'json',
      data: {
        action: action_name,
        checkoutID: checkoutID,
        nonce: WP_Shopify.nonce
      },
      success: data => resolve(data),
      error: (xhr, txt, err) => {
        reject( getErrorContents(xhr, err, action_name) );
      }
    });

  });

}


/*

Cache Cart

*/
function setCheckoutCache(checkoutID) {

  return new Promise((resolve, reject) => {

    const action_name = 'set_checkout_cache';

    jQuery.ajax({
      method: 'POST',
      url: WP_Shopify.ajax,
      dataType: 'json',
      data: {
        action: action_name,
        checkoutID: checkoutID,
        nonce: WP_Shopify.nonce
      },
      success: data => resolve(data),
      error: (xhr, txt, err) => {
        reject( getErrorContents(xhr, err, action_name) );
      }
    });

  });

}


export {
  getCheckoutCache,
  setCheckoutCache
}
