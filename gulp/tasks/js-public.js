////////
// JS //
////////

import gulp from 'gulp';
import config from '../config';
import webpack from 'gulp-webpack';
import uglify from 'gulp-uglify';

gulp.task('js-public', done => {

  return gulp.src(config.files.jsPublic)
    .pipe(webpack(require('../../public/webpack.config.js')))
    // .pipe(uglify())
    .pipe(gulp.dest(config.folders.jsPublic));

});
