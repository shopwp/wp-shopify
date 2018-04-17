////////
// JS //
////////

import webpack from 'webpack';
import gulp from 'gulp';
import config from '../config';
import webpackStream from 'webpack-stream';
import uglify from 'gulp-uglify';

gulp.task('js-admin', done => {

  return gulp.src(config.files.jsEntryAdmin)
    .pipe( webpackStream( config.webpackConfig(config.names.jsAdmin), webpack))
    .pipe(gulp.dest(config.folders.dist));

});
