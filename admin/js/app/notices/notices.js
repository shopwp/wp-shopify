import {
  cacheNoticeDismissal
} from '../ws/ws';

/*

Init

*/
function cacheAdminNoticeDismissal() {

  jQuery('.wps-notice').on('click', '.notice-dismiss', async function(event, el) {

    var $notice = jQuery(this).parent('.notice.is-dismissible');
    var dismiss_name = $notice.attr('data-dismiss-name');

    if (dismiss_name) {

      try {
        var response = await cacheNoticeDismissal(dismiss_name);

      } catch (e) {
        console.error('WP Shopify Admin Notice Error: ', e);
      }

    }

  });

}


function initDismissNoticeEvents() {

  jQuery('.wps-notice.is-dismissible .notice-dismiss').on('click', e => {
    jQuery(e.currentTarget).parent().fadeOut();
  });

}


/*

Init

*/
function noticesInit() {
  cacheAdminNoticeDismissal();
}

export {
  noticesInit,
  initDismissNoticeEvents
}
