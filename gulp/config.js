////////////
// Config //
////////////

import argvs from 'yargs';
import webpack from 'webpack';
import UglifyJsPlugin from 'uglifyjs-webpack-plugin';
import browserSync from 'browser-sync';
import willChange from 'postcss-will-change';
import willChangeTransition from 'postcss-will-change-transition';
import mqpacker from 'css-mqpacker';
import colormin from 'postcss-colormin';
import cssstats from 'postcss-cssstats';
import cssnano from 'cssnano';
import autoprefixer from 'autoprefixer';
import presetEnv from 'postcss-preset-env';
import Visualizer from 'webpack-visualizer-plugin';
import ParallelUglifyPlugin from 'webpack-parallel-uglify-plugin';
import ProgressBarPlugin from 'progress-bar-webpack-plugin';
import path from 'path';

/*

Main Config Object

*/
var config = {

  files: {
    php: ["./*/**.php"],
    jsPublic: [
      './public/js/app/**/*.js',
      '!./public/js/app.min.js',
      '!./public/js/vendor.min.js',
      '!./public/js/app.min.js.map'
    ],
    jsAdmin: [
      './admin/js/app/**/*.js',
      '!./admin/js/app.min.js',
      '!./admin/js/vendor.min.js',
      '!./admin/js/app.min.js.map'
    ],
    jsEntryPublic: './public/js/app/app.js',
    jsEntryAdmin: './admin/js/app/app.js',
    cssPublic: './public/css/**/*.scss',
    cssEntryPublic: './public/css/app/app.scss',
    cssEntryPublicCore: './public/css/app/core.scss',
    cssEntryPublicGrid: './public/css/app/grid.scss',
    cssAdmin: './admin/css/**/*.scss',
    cssEntryAdmin: './admin/css/app/app.scss',
    svgsPublic: './public/imgs/**/*.svg',
    svgsAdmin: './admin/imgs/**/*.svg'
  },
  folders: {
    dist: './dist',
    svgsPublic: './public/imgs',
    svgsAdmin: './admin/imgs',
    cssPublic: './public/css/dist',
    jsPublic: './public/js/dist',
    jsPublicSource: path.resolve('./public/js/app/'),
    cssAdmin: './admin/css/dist',
    jsAdmin: './admin/js/dist',
    jsAdminSource: path.resolve('./admin/js/app/'),
    cache: './node_modules/.cache'
  },
  names: {
    jsVendorPublic: 'public.vendor.min.js',
    jsVendorAdmin: 'admin.vendor.min.js',
    jsPublic: 'public.min.js',
    cssPublic: 'public.min.css',
    cssPublicCore: 'core.min.css',
    cssPublicGrid: 'grid.min.css',
    jsAdmin: 'admin.min.js',
    cssAdmin: 'admin.min.css'
  },
  bs: browserSync.create(),
  serverName: 'wpstest.test',
  isBuilding: argvs.argv.build ? argvs.argv.build : false
};


/*

Webpack Config

*/
function webpackConfig(outputFinalname) {

  return {
    watch: false,
    mode: config.isBuilding ? 'production' : 'development',
    cache: true,
    output: {
      filename: outputFinalname
    },
    resolve: {
      extensions: ['.js']
    },
    plugins: [
      new webpack.optimize.ModuleConcatenationPlugin(),
      new ProgressBarPlugin()
    ],
    optimization: {
      minimizer: [
        new UglifyJsPlugin({
          parallel: true,
          cache: true,
          parallel: true,
          extractComments: true,
          uglifyOptions: {
            compress: true,
            ecma: 6,
            mangle: true,
            safari10: true
          },
          sourceMap: config.isBuilding ? false : true,
        })
      ]
    },
    module: {
      rules: [
        {
          test: /\.js$/,
          exclude: /(node_modules)/,
          enforce: 'pre',
          use: [
            'babel-loader?presets[]=es2015&plugins[]=transform-async-to-generator'
          ],
        }
      ]
    }
  }

}


/*

Postcss Config

*/
function postCSSPlugins() {

  var plugins = [
    willChangeTransition,
    willChange,
    autoprefixer({ browsers: ['last 6 version'] }),
    presetEnv(), // Allows usage of future CSS
    mqpacker(),
    colormin({
      legacy: true
    })
  ];

  // Only run if npm run gulp --build
  if (config.isBuilding) {
    plugins.push(cssnano());
  }

  return plugins;

}


/*

Style Lint Config

*/
function stylelintConfig() {
  return {
    config: {
      rules: {
        "declaration-block-no-duplicate-properties": true,
        "block-no-empty": true,
        "no-extra-semicolons": true,
        "font-family-no-duplicate-names": true
      }
    },
    debug: true,
    reporters: [ { formatter: 'string', console: true }]
  }
}

config.postCSSPlugins = postCSSPlugins;
config.webpackConfig = webpackConfig;
config.stylelintConfig = stylelintConfig;

export default config;
