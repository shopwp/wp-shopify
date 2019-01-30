import isError from 'lodash/isError';
import to from 'await-to-js';

import {
  injectConnectorModal,
  updateModalHeadingText,
  toggleActive,
  showAdminNotice,
  hideAdminNoticeByType
} from '../utils/utils-dom';

import {
  afterWebhooksRemoval,
  afterDataRemoval
} from '../utils/utils-progress';

import {
  hasConnection,
  returnOnlyFirstError
} from '../utils/utils-data';

import {
  clearLocalstorageCache
} from '../ws/localstorage';

import {
  post
} from '../ws/ws';

import {
  endpointConnectionCheck,
  endpointToolsClearAll
} from '../ws/api/api-endpoints';

import {
  deleteWebhooks
} from '../ws/api/api-webhooks';

import {
  clearAllCache,
  deleteStandAloneData
} from '../ws/wrappers';

import {
  enable,
  disable,
  showLoader,
  hideLoader,
  isWordPressError,
  getWordPressErrorMessage,
  getJavascriptErrorMessage,
  getWordPressErrorType
} from '../utils/utils';

import {
  syncOn
} from '../ws/syncing';


/*

When clear submit form is submitted ...

*/
function onClearSubmit() {

  jQuery("#wps-button-clear-all-data").unbind().on('click', async function(e) {

    if (window.confirm("Warning: This will delete all WordPress posts created from your Shopify data. Do you really want to remove?")) {

      e.preventDefault();

      var $button = jQuery(this);
      var $spinner = $button.parent().find('.spinner');

      disable($button);
      toggleActive($spinner);
      showLoader($button);

      clearLocalstorageCache();

      WP_Shopify.isClearing = true;



      /*

      Checks for an open connection to the server ...

      */
      var [serverCheckError, serverCheckData] = await to( post( endpointConnectionCheck() ) );

      if (serverCheckError) {
        return showAdminNotice( getJavascriptErrorMessage(serverCheckError) );
      }

      if (isWordPressError(serverCheckData)) {

        return showAdminNotice(
          getWordPressErrorMessage(serverCheckData),
          getWordPressErrorType(serverCheckData)
        );

      }


      /*

      Turn syncing flag on

      */
      try {

        var syncOnResponse = await syncOn();

        if (isWordPressError(syncOnResponse)) {

          showAdminNotice(
            getWordPressErrorMessage(syncOnResponse),
            getWordPressErrorType(syncOnResponse)
          );

          return;

        }

      } catch (error) {

        showAdminNotice( getJavascriptErrorMessage(error) );
        return;

      }


      /*

      No active connection exists. Just drop data and clear cache.

      */
      if ( !hasConnection() ) {


        try {

          var clearAllCacheResponse = await clearAllCache();

          if (isWordPressError(clearAllCacheResponse)) {

            showAdminNotice(
              getWordPressErrorMessage(clearAllCacheResponse),
              getWordPressErrorType(clearAllCacheResponse)
            );

            return;

          }

        } catch(error) {

          showAdminNotice( getJavascriptErrorMessage(error) );
          return;

        }


        /*

        Removing data that doesnt require an active Shopify connection

        */
        var [deletionError, deletionData] = await to( deleteStandAloneData() );

        if (deletionError) {
          showAdminNotice( getJavascriptErrorMessage(deletionError) );
          return;
        }

        if (isWordPressError(deletionData)) {

          showAdminNotice( returnOnlyFirstError(deletionData) );
          return;

        }


        afterDataRemoval(async () => {

          showAdminNotice('Successfully removed all data', 'updated');
          hideAdminNoticeByType('notice_warning_app_uninstalled');

          return;

        });


      } else {


        /*

        Delete posts and synced data from custom tables

        */

        var [removedError, removedResponse] = await to( post( endpointToolsClearAll() ) );

        if (removedError) {
          return showAdminNotice( getJavascriptErrorMessage(removedError) );
        }

        if (isWordPressError(removedResponse)) {

          return showAdminNotice(
            getWordPressErrorMessage(removedResponse),
            getWordPressErrorType(removedResponse)
          );

        }



        afterDataRemoval(async () => {


          if ( hasConnection() ) {






              /*

              Step 2. Clear all plugin cache

              */
              try {

                var clearAllCacheResponse = await clearAllCache();

                if (isWordPressError(clearAllCacheResponse)) {

                  showAdminNotice(
                    getWordPressErrorMessage(clearAllCacheResponse),
                    getWordPressErrorType(clearAllCacheResponse)
                  );

                  return;

                }

              } catch(error) {

                showAdminNotice( getJavascriptErrorMessage(error) );
                return;

              }

              showAdminNotice('Successfully removed all data', 'updated');
              hideAdminNoticeByType('notice_warning_app_uninstalled');




          } else {


            /*

            Reset notices and clear cache

            */
            try {

              var clearAllCacheResponse = await clearAllCache();

              if (isWordPressError(clearAllCacheResponse)) {

                showAdminNotice(
                  getWordPressErrorMessage(clearAllCacheResponse),
                  getWordPressErrorType(clearAllCacheResponse)
                );

                return;

              }

            } catch(error) {

              showAdminNotice( getJavascriptErrorMessage(error) );
              return;

            }

            showAdminNotice('Successfully removed all data', 'updated');
            hideAdminNoticeByType('notice_warning_app_uninstalled');

          }

        });

      }

    }

  });

}


export {
  onClearSubmit
}
