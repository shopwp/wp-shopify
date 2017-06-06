////////
// JS //
////////

import gulp from 'gulp';
import config from '../config';
import webpack from 'gulp-webpack';
import uglify from 'gulp-uglify';

gulp.task('js-admin', done => {

  return gulp.src(config.files.jsAdmin)
    .pipe(webpack(require('../../admin/webpack.config.js')))
    // .pipe(uglify())
    .pipe(gulp.dest(config.folders.jsAdmin));

});
