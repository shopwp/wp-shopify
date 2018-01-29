/*

Init

*/
function removeCartIconDescription() {

  jQuery('.wps-submit-menu-cart-icon').on('click', function() {

    var refreshIntervalId = setInterval(function() {

      if (jQuery('#menu-to-edit .menu-item .field-description .edit-menu-item-description:contains("WP Shopify Cart Icon")').length) {

        jQuery('#menu-to-edit .menu-item .field-description .edit-menu-item-description:contains("WP Shopify Cart Icon")').closest('.field-description').hide();

        clearInterval(refreshIntervalId);
      }

    }, 500);


  });

  jQuery('#menu-to-edit .menu-item .field-description .edit-menu-item-description:contains("WP Shopify Cart Icon")').hide();

}


/*

Init

*/
function menusInit() {
  removeCartIconDescription();
}

export {
  menusInit
}
