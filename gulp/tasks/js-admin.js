////////
// JS //
////////

import webpack from 'webpack';
import gulp from 'gulp';
import config from '../config';
import gulpWebpack from 'gulp-webpack';
import uglify from 'gulp-uglify';

gulp.task('js-admin', done => {

  return gulp.src(config.files.jsEntryAdmin)
    .pipe( gulpWebpack( config.webpackConfig(config.names.jsAdmin), webpack))
    .pipe(gulp.dest(config.folders.dist));

});
