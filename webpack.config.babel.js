const webpack = require('webpack');
const utils = require('webpack-config-utils');
const WriteFilePlugin = require('write-file-webpack-plugin');
const ProgressBarPlugin = require('progress-bar-webpack-plugin');
const validate = require('webpack-validator');
const path = require('path');
const env = process.env.NODE_ENV;
const webpackUtils = utils.getIfUtils(env);
const ifProd = webpackUtils.ifProd;
const ifNotProd = webpackUtils.ifNotProd;
const ImageminPlugin = require('imagemin-webpack-plugin').default;
const CopyWebpackPlugin = require('copy-webpack-plugin');
const ExtractTextPlugin = require('extract-text-webpack-plugin');
const OptimizeCssAssetsPlugin = require('optimize-css-assets-webpack-plugin');
const HappyPack = require('happypack');
const UglifyJsPlugin = require('uglifyjs-webpack-plugin');
const Visualizer = require('webpack-visualizer-plugin');
const HardSourceWebpackPlugin = require('hard-source-webpack-plugin');
const ParallelUglifyPlugin = require('webpack-parallel-uglify-plugin');

module.exports = () => {

  return {

    /*

    false === smaller bundle size
    eval === faster bundle time

    */
    devtool: ifProd(false, false),
    entry: {
      admin: [
        'babel-polyfill',
        path.resolve('admin/js/app/app.js'),
      ],
      public: [
        path.resolve('public/js/app/app.js'),
        path.resolve('public/css/app/app.scss')
      ],
      grid: [
        path.resolve('public/css/app/grid.scss')
      ],
      core: [
        path.resolve('public/css/app/core.scss')
      ]
    },
    watch: true,
    output: {
      path: path.resolve('dist'),
      filename: '[name].min.js'
    },
    plugins: [
      new HardSourceWebpackPlugin(),
      new webpack.optimize.ModuleConcatenationPlugin(),
      new Visualizer(),
      new ParallelUglifyPlugin({
        exclude: /(node_modules|bower_components)/,
        cacheDir: path.resolve('node_modules/.cache'),
        sourceMap: false,
        uglifyJS: {
          output: {
            comments: false
          },
          compress: {
            warnings: false
          }
        }
      }),
      new webpack.ProvidePlugin({
        Bottleneck: "Bottleneck",
        ramda: "ramda",
        ShopifyBuy: "shopify-buy",
        validator: "validator",
        crypto: "crypto",
        dateFormat: "dateFormat",
        currencyFormatter: "currency-formatter"
      }),
      new webpack.DefinePlugin({
        'process.env.NODE_ENV': JSON.stringify('production')
      }),
      new webpack.optimize.OccurrenceOrderPlugin(),
      new webpack.NoEmitOnErrorsPlugin(),
      new ProgressBarPlugin(),
      new WriteFilePlugin(),
      new ExtractTextPlugin({
        filename: '../css/[name].min.css'
      }),
      new OptimizeCssAssetsPlugin({
        cssProcessor: require('cssnano'),
        cssProcessorOptions: {
          discardComments: {
            removeAll: false
          },
          zindex: false
        },
        canPrint: true
      })
    ],
    resolve: {
      extensions: ['.js'],
      alias: {
        bottleneck: "bottleneck",
        validator: "validator",
        ramda: "ramda",
        crypto: "crypto",
        dateFormat: "dateFormat",
        currencyFormatter: "currency-formatter",
        ShopifyBuy: "shopify-buy"
      }
    },
    module: {
      rules: [
        {
          test: /\.js$/,
          include: [
            path.resolve('public/js/app/'),
            path.resolve('admin/js/app/'),
          ],
          enforce: 'pre',
          use: [
            'babel-loader?presets[]=es2015&plugins[]=transform-async-to-generator',
            "eslint-loader",
          ],
        },
        {
          test: /\.(png|jpe?g|gif|woff|woff2|eot|ttf|svg)$/,
          exclude: /node_modules/,
          use: [{
            loader: 'url-loader?limit=100000'
          }]
        },
        {
          test: /\.scss$/,
          include: [
            path.resolve('public/css/app/'),
            path.resolve('admin/css/app/'),
          ],
          use: ExtractTextPlugin.extract({
            fallback: 'style-loader',
            use: [
              {
                loader: 'css-loader',
                options: {
                  url: false,
                  minimize: true,
                  sourceMap: true
                }
              },
              {
                loader: 'sass-loader',
                options: {
                  sourceMap: true
                }
              }
            ]
          })
        }
      ]
    }
  }

}
