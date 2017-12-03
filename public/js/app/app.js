import bootstrap from "./utils/utils-bootstrap";

(function($) {
  "use strict";

  $(async function() {

    try {
      await bootstrap();

    } catch (error) {
      console.error("WP Shopify Bootstrap error: ", error);
    }

  });

}(jQuery));
