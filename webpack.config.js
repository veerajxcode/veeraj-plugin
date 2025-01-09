const path = require('path');

module.exports = {
  entry: {
   // main: './assets/js/main.js', // Main script
    admin: './assets/js/admin-script.js', // Admin script
   // table: './assets/js/gutenberg-block/table.js', // Gutenberg block script
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
            presets: ['@babel/preset-env', '@babel/preset-react'],
          },
        },
      },
    ],
  },
  devtool: 'source-map', // Generate source maps for debugging
  devServer: {
    static: './assets/js/dist', // Serve files from the output directory
    hot: true, // Enable hot module replacement
  },
};
