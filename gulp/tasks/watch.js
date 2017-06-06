/////////////////
// Watch files //
/////////////////

import gulp from 'gulp';
import config from '../config';
import reload from "./reload";

gulp.task('watch', () => {

  // Public Watches
  gulp.watch(config.files.cssPublic, gulp.series('css-public', reload));
  gulp.watch(config.files.jsPublic, gulp.series('js-public', reload));

  // Admin Watches
  gulp.watch(config.files.cssAdmin, gulp.series('css-admin', reload));
  gulp.watch(config.files.jsAdmin, gulp.series('js-admin', reload));

  // General PHP Watch
  gulp.watch(config.files.php, gulp.series(reload));

});
