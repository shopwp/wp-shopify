////////
// JS //
////////

import webpack from 'webpack';
import gulp from 'gulp';
import config from '../config';
import webpackStream from 'webpack-stream';
import uglify from 'gulp-uglify';

gulp.task('js', done => {

  return gulp
    .src( config.files.js )
    .pipe( webpackStream( config.webpackConfig(), webpack) )
    .pipe( gulp.dest(config.folders.dist) );

});
