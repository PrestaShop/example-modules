const path = require('path');
const {merge} = require('webpack-merge');
const common = require('./common.js');

/**
 * Returns the development webpack config,
 * by merging development specific configuration with the common one.
 */
const devConfig = () => (merge(
  common,
  {
    devtool: 'inline-source-map',
    devServer: {
      hot: true,
      contentBase: path.resolve(__dirname, '/../public'),
      publicPath: '/',
    },
  },
)
);

module.exports = devConfig;
