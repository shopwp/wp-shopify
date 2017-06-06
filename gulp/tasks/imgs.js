////////////
// Images //
////////////

import gulp from 'gulp';
import config from '../config';
import imagemin from 'gulp-imagemin';

gulp.task('imgs', () => {
  return gulp.src(config.files.imgs)
    .pipe(imagemin())
    .pipe(gulp.dest(config.folders.imgsDist));
});
