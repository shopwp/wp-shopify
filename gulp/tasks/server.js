////////////
// Server //
////////////

import gulp from 'gulp';
import config from '../config';

gulp.task('server', () => {

  gulp.watch(config.files.cssAdmin, gulp.series('css-admin'));
  gulp.watch(config.files.cssPublic, gulp.series('css-public'));

  gulp.watch(config.files.jsAdmin, gulp.series('js-admin'));
  gulp.watch(config.files.jsPublic, gulp.series('js-public'));

  gulp.watch(config.files.html).on('change', config.bs.reload);

});
