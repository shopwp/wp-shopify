//////////////////
// Images Admin //
//////////////////

import gulp from 'gulp';
import config from '../config';
import svgo from 'gulp-svgo';

gulp.task('images-admin', () => {
  return gulp.src(config.files.svgsAdmin)
    .pipe(svgo())
    .pipe(gulp.dest(config.folders.svgsAdmin));
});
