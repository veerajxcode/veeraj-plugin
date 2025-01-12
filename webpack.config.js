const path = require('path');
const DependencyExtractionWebpackPlugin = require('@wordpress/dependency-extraction-webpack-plugin');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');

module.exports = {
  entry: {
    admin: './assets/js/admin-script.js', // Admin script
    'table': './assets/js/gutenberg-block/table.js', // Entry file for the table
    'frontend': './assets/js/gutenberg-block/table-frontend.js', // Entry file for the table
  },
  output: {
    filename: 'js/[name].bundle.js', // Output JS files inside 'js' folder
    path: path.resolve(__dirname, 'assets/build'), // Output directory inside 'assets/build'
  },
  module: {
    rules: [
      {
        test: /\.js$/,
        exclude: /node_modules/,
        use: {
          loader: 'babel-loader',
          options: {
            presets: ['@wordpress/babel-preset-default'],
          },
        },
      },
      {
        test: /\.css$/,
        use: [
          MiniCssExtractPlugin.loader, // Extract CSS into separate files
          'css-loader', // Handles CSS imports
        ],
      },
    ],
  },
  plugins: [
    new DependencyExtractionWebpackPlugin(), // Extract dependencies for WordPress enqueue
    new MiniCssExtractPlugin({
      filename: 'css/[name].bundle.css', // Output CSS files inside 'css' folder
    }),
  ],
  mode: 'development', // Use 'production' for minified files in production
  devtool: 'source-map', // Enable source maps for easier debugging
};
