const webpack = require("webpack");
//const path = require("path");
const MiniCssExtractPlugin = require("mini-css-extract-plugin");

module.exports = {
  outputDir: "../assets/widgets",
  configureWebpack: {
    plugins: [
      new MiniCssExtractPlugin({
        filename: "/css/elementor-express-add-ons.css"
      })
    ],
    output: {
      filename: "./js/elementor-express-add-ons.js"
    }
  },
  chainWebpack: config => {
    config.optimization.delete("splitChunks");
  },
  "transpileDependencies": [
    "vuetify"
  ]
};

