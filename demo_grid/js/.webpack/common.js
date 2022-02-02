/**
 * Copyright since 2007 PrestaShop SA and Contributors
 * PrestaShop is an International Registered Trademark & Property of PrestaShop SA
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License version 3.0
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/AFL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * @author    PrestaShop SA and Contributors <contact@prestashop.com>
 * @copyright Since 2007 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License version 3.0
 */

const path = require('path');
const webpack = require('webpack');
const keepLicense = require('uglify-save-license');
const TerserPlugin = require('terser-webpack-plugin');
const {merge} = require('webpack-merge');

const psRootDir = path.resolve(process.env.PWD, '../../../admin-dev/themes/new-theme/');
const psJsDir = path.resolve(psRootDir, 'js');

module.exports = {
  entry: {
    demo_grid: [
      '../js/demo',
    ],
  },
  output: {
    path: path.resolve(__dirname, '../../views'),
    filename: '[name].bundle.js',
    publicPath: 'public',
  },
  // devtool: 'source-map', // uncomment me to build source maps (really slow)
  resolve: {
    extensions: ['.js', '.ts'],
    alias: {
      '@PSJs': psJsDir,
      '@app': psJsDir + '/app',
      '@components': psJsDir + '/components',
    },
  },
  module: {
    rules: [
      {
        test: /\.js$/,
        include: path.resolve(__dirname, 'js'),
        loader: 'esbuild-loader',
        options: {
          loader: 'jsx',
          target: 'es2015',
        },
      },
      {
        test: /\.ts?$/,
        loader: 'ts-loader',
        options: {
          onlyCompileBundledFiles: true,
        },
        exclude: /node_modules/,
      },
    ],
  },
  plugins: [],
}
