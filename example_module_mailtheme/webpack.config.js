const webpack = require("webpack");
const path = require("path");
const MiniCssExtractPlugin = require('mini-css-extract-plugin');

let config = {
  externals: {
    jquery: 'jQuery',
  },
  entry: {
    main: ['./Resources/js/index.js', './Resources/css/main.scss'],
  },
  output: {
    path: path.resolve(__dirname, "./views"),
    filename: 'js/[name].bundle.js',
    libraryTarget: 'window',
    library: '[name]',
  },
  module: {
    rules: [
      {
        test: /\.js$/,
        exclude: /node_modules/,
        loader: "babel-loader"
      },
      {
        test: [/\.css$|\.scss$/],
        use: [
          {
            loader: MiniCssExtractPlugin.loader,
            options: {
              // you can specify a publicPath here
              // by default it uses publicPath in webpackOptions.output
              publicPath: '../',
              hmr: process.env.NODE_ENV === 'development',
            },
          },
          'css-loader',
          'sass-loader',
        ],
      },
    ]
  },
  plugins: [
    new MiniCssExtractPlugin({
      // Options similar to the same options in webpackOptions.output
      // both options are optional
      filename: 'css/[name].css',
      chunkFilename: 'css/[id].css',
    }),
  ]
}

module.exports = config;
