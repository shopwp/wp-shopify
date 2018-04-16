///////////
// Build //
///////////

import gulp from 'gulp';
import preprocess from 'gulp-preprocess';
import config from '../config';
import replace from 'gulp-replace';
import phpunit from 'gulp-phpunit';

gulp.task('build', () => {

  return gulp.src(config.files.buildEntry)
    .pipe(phpunit('/usr/local/bin/phpunit', {
      stopOnFailure: true
    }));
    // .pipe(preprocess({
    //   context: {
    //     NODE_ENV: config.buildTier
    //   }
    // }))
    // .pipe(replace('<?php ?>', function(match, p1, offset, string) {
    //   console.log('Found ' + match);
    //   return '';
    // }))
    // .pipe( gulp.dest(config.folders[config.buildTier]) );

});
