//////////////////
// Initializing //
//////////////////

import gulp from "gulp";
import config from "../config";

gulp.task('default', done => {

  if (config.isBuilding) {
    gulp.parallel('js-admin', 'js-public', 'css-admin', 'css-public', 'css-public-core', 'css-public-grid', 'images-public', 'images-admin')(done);

  } else {
    gulp.series(gulp.parallel('js-admin', 'js-public', 'css-admin', 'css-public', 'css-public-core', 'css-public-grid'), 'watch')(done);
  }

});
