///////////////////
// Images Public //
///////////////////

import gulp from 'gulp';
import config from '../config';
import svgo from 'gulp-svgo';

gulp.task('images-public', () => {
  return gulp.src(config.files.svgsPublic)
    .pipe(svgo())
    .pipe(gulp.dest(config.folders.svgsPublic));
});
