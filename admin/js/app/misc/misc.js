import to from 'await-to-js';
import forEach from 'lodash/forEach';

import {
  migrateTables
} from '../ws/ws';

import {
  getErrorContents
} from '../utils/utils-data';

import {
  enable,
  disable,
  showLoader,
  hideLoader,
  getWordPressErrorMessage,
  getWordPressErrorType,
  getJavascriptErrorMessage,
  isWordPressError
} from '../utils/utils';

import {
  toggleActive,
  showAdminNotice,
  stopSpinner
} from '../utils/utils-dom';



function enableButtons($button, $wrapper) {
  enable($button);
  hideLoader($button);
  $wrapper.removeClass('is-working');
}

function disableButtons($button) {
  enable($button);
  hideLoader($button);
}


/*

Perform the database migration ...

*/
function onMigrationSubmit() {


  jQuery("#wps-button-migrate").off().on('click', async function(e) {

    e.preventDefault();

    var $button = jQuery(this);
    var $wrapper = $button.parent();
    var $spinner = $wrapper.find('.spinner');


    disableButtons($button, $spinner);

    if (window.confirm("Warning: This will modify your database tables to be compatible with the new (1.2.2) version of WP Shopify. Please don\'t leave this screen until the migration finishes. Finally, please ensure that you've made a backup as potential data loss could occur!")) {

      $wrapper.addClass('is-working');
      // toggleActive($spinner);
      showLoader($button);


      var [migrateTablesError, migrateTablesData] = await to( migrateTables() );

      if (migrateTablesError) {

        hideLoader($button);
        $wrapper.removeClass('is-working');

        return showAdminNotice( getJavascriptErrorMessage(migrateTablesError) );
      }


      if (isWordPressError(migrateTablesData)) {

        showAdminNotice(
          getWordPressErrorMessage(migrateTablesData),
          getWordPressErrorType(migrateTablesData)
        );

        hideLoader($button);
        $wrapper.removeClass('is-working');

        return;

      }

      hideLoader($button);
      $wrapper.removeClass('is-working');
      disable($button);
      showAdminNotice('Successfully migrated database tables to the latest version of WP Shopify! You\'re ready to go!', 'updated');


    } else {

      hideLoader($button);
      $wrapper.removeClass('is-working');

    }

  });

}


function initMisc() {
  onMigrationSubmit();
}


export {
  onMigrationSubmit,
  initMisc
};
