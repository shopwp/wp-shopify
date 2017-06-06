//////////////////
// Initializing //
//////////////////

import gulp from "gulp";
import config from "../config";

gulp.task('default',
  gulp.series(gulp.parallel('js-admin', 'js-public', 'css-admin', 'css-public'), 'watch')
);
