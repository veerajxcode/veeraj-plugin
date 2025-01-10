const path = require('path');
const DependencyExtractionWebpackPlugin = require('@wordpress/dependency-extraction-webpack-plugin');

module.exports = {
  entry: {
    admin: './assets/js/admin-script.js', // Admin script
    'block': './assets/js/gutenberg-block/table.js', // Entry file for the block
  },
  output: {
    filename: '[name].bundle.js', // Dynamically name bundles based on the entry key
    path: path.resolve(__dirname, 'assets/js/dist'), // Output directory
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
    ],
  },
  plugins: [
    new DependencyExtractionWebpackPlugin(), // Extract dependencies for WordPress enqueue
  ],
  mode: 'development', // Use 'production' for minified files in production
  devtool: 'source-map', // Enable source maps for easier debugging
};
