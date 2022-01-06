const webpack = require('webpack');
const TerserPlugin = require('terser-webpack-plugin');
const {merge} = require('webpack-merge');
const common = require('./common.js');

/**
 * Returns the production webpack config,
 * by merging production specific configuration with the common one.
 *
 */

const prodConfig = () => merge(common, {
  stats: 'minimal',
  optimization: {
    minimizer: [
      new TerserPlugin({
        sourceMap: true,
        terserOptions: {
          output: {
            comments: /@license/i,
          },
        },
        extractComments: false,
      }),
    ],
  },
  plugins: [
    new webpack.DefinePlugin({
      'process.env.NODE_ENV': JSON.stringify('production'),
    }),
  ],
});

module.exports = prodConfig;
