////////
// JS //
////////

import webpack from 'webpack';
import gulp from 'gulp';
import config from '../config';
import webpackStream from 'webpack-stream';
import uglify from 'gulp-uglify';

gulp.task('js-public', done => {

  return gulp
    .src(config.files.jsEntryPublic)
    .pipe(webpackStream(config.webpackConfig(config.names.jsPublic), webpack ))
    .pipe(gulp.dest(config.folders.dist) );

});
