import bootstrap from "./utils/utils-bootstrap";
import { showError } from "./utils/utils-common";

(function($) {

  "use strict";

  $(async function() {

    try {
      await bootstrap();

    } catch (error) {
      console.error('WP Shopify Error bootstrap: ', error);
      showError(error);

    }

  });

}(jQuery));
