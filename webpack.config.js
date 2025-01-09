const path = require('path');

module.exports = {
  entry: {
    admin: './assets/js/admin-script.js', // Admin script
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
