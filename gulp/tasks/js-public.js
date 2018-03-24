////////
// JS //
////////

import webpack from 'webpack';
import gulp from 'gulp';
import config from '../config';
import gulpWebpack from 'gulp-webpack';
import uglify from 'gulp-uglify';

gulp.task('js-public', done => {

  return gulp.src(config.files.jsEntryPublic)
    .pipe( gulpWebpack( config.webpackConfig(config.names.jsPublic), webpack ))
    .pipe( gulp.dest(config.folders.dist) );

});
