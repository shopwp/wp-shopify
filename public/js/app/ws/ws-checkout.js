/*

Fires the on checkout action
Returns: Promise

*/
function beforeCheckoutHook(cart) {

  return jQuery.ajax({
    method: 'POST',
    url: wps.ajax,
    dataType: 'json',
    data: {
      action: 'wps_add_checkout_before_hook',
      cart: cart,
      nonce: wps.nonce
    }
  });

};


/*

Fires the on checkout action
Returns: Promise

*/
function anyCustomAttrs(cart) {

  return jQuery.ajax({
    method: 'GET',
    url: wps.ajax,
    dataType: 'json',
    data: {
      action: 'wps_get_cart_checkout_attrs',
      nonce: wps.nonce
    }
  });

};

export {
  beforeCheckoutHook,
  anyCustomAttrs
};
