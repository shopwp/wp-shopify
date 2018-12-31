/////////
// Git //
/////////

import gulp from 'gulp';
import config from '../../config';
import shell from 'gulp-shell';
import git from 'gulp-git';

function gitOptions() {

  return {
    newVersionNumber: config.buildRelease,
    gitFolder: config.isPro ? config.folders.plugin : config.folders.freeDistRepo,
    repoFiles: config.isPro ? config.files.proRepoFiles : config.files.freeRepoFiles
  }

}


/*

Git flow release start

*/
gulp.task('build:git:release:start', done => {

  const options = gitOptions();
  console.log('options.gitFolder', options.gitFolder);
  return gulp
    .src(options.gitFolder, { base: "./" })
    .pipe( shell([
      'git flow release start v' + options.newVersionNumber
    ]));

});


/*

Git flow release finish

*/
gulp.task('build:git:release:publish', done => {

  const options = gitOptions();

  return gulp
    .src(options.gitFolder, { base: "./" })
    .pipe( shell([
      'git flow release publish v' + options.newVersionNumber
    ]));

});


/*

Git flow release finish

-u allows for GPG key

*/
gulp.task('build:git:release:finish', done => {

  const options = gitOptions();

  return gulp
    .src(options.gitFolder, { base: "./" })
    .pipe( shell([
      'git flow release finish v' + options.newVersionNumber
    ]));

});


/*

Pushes git tags to origin

*/
gulp.task('build:git:pushTags', done => {

  const options = gitOptions();

  return gulp
    .src(options.gitFolder, { base: "./" })
    .pipe( shell([
      'git push origin --tags'
    ]));

});


// Run git add with options
gulp.task('build:git:add', done => {

  const options = gitOptions();

  return gulp
    .src( options.repoFiles )
    .pipe( git.add({args: '-f'}) );

});


gulp.task('build:git:commit', done => {

  const options = gitOptions();

  return gulp
    .src( options.repoFiles, { base: "./" })
    .pipe( git.commit('Release: ' + config.buildRelease) );

});


gulp.task('build:git:addcommit', done => {

  if (config.isPro) {
    return done();
  }

  return gulp.series(
    'build:git:add',
    'build:git:commit'
  )(done);

});



gulp.task('build:git:commit', done => {

  const options = gitOptions();

  return gulp
    .src( options.repoFiles, { base: "./" })
    .pipe( git.commit('Release: ' + config.buildRelease) );

});


gulp.task('build:git:cleanUp', done => {

  const options = gitOptions();

  return gulp
    .src(options.gitFolder, { base: "./" })
    .pipe( shell([
      'git checkout master',
      'git push',
      'git checkout develop'
    ]));

});


gulp.task('build:git', done => {

  return gulp.series(
    'build:git:addcommit',
    'build:git:release:start',
    'build:git:release:publish',
    'build:git:release:finish',
    'build:git:pushTags'
  )(done);

});
