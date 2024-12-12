const path = require("path");
const HTMLWebpackPlugin = require("html-webpack-plugin");
const { CleanWebpackPlugin } = require("clean-webpack-plugin");
const CopyWebpackPlugin = require("copy-webpack-plugin");
const MiniCssExtractPlugin = require("mini-css-extract-plugin");
// const OptimizeCssAssetWebpackPlugin = require('optimize-css-assets-webpack-plugin') не используется
const CssMinimizerPlugin = require("css-minimizer-webpack-plugin");
const TerserWebpackPlugin = require("terser-webpack-plugin");
const { BundleAnalyzerPlugin } = require("webpack-bundle-analyzer");
const autoprefixer = require("autoprefixer");
const isDev = process.env.NODE_ENV === "development";
const isProd = !isDev;
const fs = require('fs')
const ENV = {};



// достаем настройки из ENV
 const fileENV = fs.readFileSync("./.env", {encodeding:"utf8", flag:'r'});

  fileENV.toString().split("\n").forEach((row)=>{

    if (row.trim().indexOf("#") == 0 || row.trim() == "" || row.trim().indexOf("\n") == 0) {
    } else {
      if(row.split("=").length > 1){
            let conf = row.split("=");
            conf[1] = conf[1].replace('"',"");
            conf[1] = conf[1].replace('"', "");
            conf[1] = conf[1].replace("'", "");
            conf[1] = conf[1].replace("'", "");
            ENV[conf[0].trim()] = conf[1].replaceAll(["\"","\'", "\r", "\n"], "").trim()
      }
    }
  })


console.log(ENV);



const optimization = () => {
  const config = {
    splitChunks: {
      chunks: "all",
    },
  };

  if (isProd) {
    config.minimizer = [new CssMinimizerPlugin(), new TerserWebpackPlugin()];
  }

  return config;
};

const filename = (ext) =>
  isDev
    ? `./${ext.toUpperCase()}/[name].${ext}`
    : `./${ext.toUpperCase()}/[name].[hash].${ext}`;

const cssLoaders = (extra) => {
  const loaders = [
    {
      loader: MiniCssExtractPlugin.loader,
      options: {
      },
    },
    "css-loader",
  ];

  if (extra) {
    loaders.push(extra);
  }

  return loaders;
};

const babelOptions = (preset) => {
  const opts = {
    presets: ["@babel/preset-env"],
    // plugins: [
    //   '@babel/plugin-transform-class-properties'
    // ]
  };

  if (preset) {
    opts.presets.push(preset);
  }

  return opts;
};

const jsLoaders = () => {
  const loaders = [
    {
      loader: "babel-loader",
      options: babelOptions(),
    },
  ];

  if (isDev) {
    loaders.push("eslint-loader");
  }

  return loaders;
};

const plugins = () => {
  const base = [

    new HTMLWebpackPlugin({
      template: "./index.php",
      filename: "./view/index.php",
      minify: {
        collapseWhitespace: isProd,
      },
    }),


    new CleanWebpackPlugin({
      default:['view/CSS/**', 'view/JS/**'],
      cleanOnceBeforeBuildPatterns: [
        'view/CSS/**', 
        'view/JS/**',
        // "**/*",
        "!PHP/**",
        // "!view/static/**",
        "!*.php",
        "!.htaccess",
      ],
    }),

    new CopyWebpackPlugin({
      patterns: [
        {
          context: path.resolve(__dirname, "src/images"),
          from: "*.*",
          to: "view/images",
        },
      ],
    }),

    new MiniCssExtractPlugin({
      filename: `./view/CSS/[name][hash].css`,
    }),
  ];

  // if (isProd) {
  //   base.push(new BundleAnalyzerPlugin());
  // }

  return base;
};

module.exports = {
  context: path.resolve(__dirname, "src"),
  mode: "development",
  entry: {
    main: "./main.tsx",
  },

  output: {
    filename: "view/JS/[name][hash].js",
    path: path.resolve(__dirname, "dist"),
    publicPath: isProd ? ENV["WEBSITEPOROJECT"] + "/" : ENV["WEBSITEDEV"] + "/",
    assetModuleFilename: "view/images/[name][ext][query]",
  },
  resolve: {
    extensions: [".tsx", ".ts", ".jsx", ".js", ".json", ".png"],
    alias: {
      "@models": path.resolve(__dirname, "src/models"), //относительные пути при импортах
      "@": path.resolve(__dirname, "src"),
    },
  },
  optimization: optimization(),
  devServer: {
    static: path.resolve(__dirname, "\\" + ENV["FOLDER_PROJECT"] + "\\view\\"),
    port: 8000,
    hot: isDev,
  },
  // devtool: isDev ? 'source-map' : '',
  plugins: plugins(),
  module: {
    rules: [
      {
        test: /\.css$/,
        use: cssLoaders(),
      },
      {
        test: /\.less$/,
        use: cssLoaders("less-loader"),
      },
      {
        test: /\.s[ac]ss$/,
        use: [
          // 'style-loader',
          MiniCssExtractPlugin.loader,
          "css-loader",
          "sass-loader",
        ],
      },
      {
        test: /\.(png|jpg|svg|gif)$/,
        type: "asset/resource",
        // use: ['file-loader'],
      },
      {
        test: /\.(ttf|woff|woff2|eot)$/,
        use: ["file-loader"],
      },
      {
        test: /\.xml$/,
        use: ["xml-loader"],
      },
      {
        test: /\.csv$/,
        use: ["csv-loader"],
      },
      {
        test: /\.js$/,
        exclude: /node_modules/,
        use: jsLoaders(),
      },
      {
        test: /\.(ts|tsx|jsx)?$/,
        exclude: [/node_modules/],
        loader: "ts-loader",
        options: {
          configFile: "tsconfig.json",
        },
      },

      //   {
      //     test: /\.ts$/,
      //     exclude: /node_modules/,
      //     use: {
      //       loader: 'babel-loader',
      //       options: {
      //         presets: [
      //           ['@babel/preset-typescript', { targets: "defaults" }]
      //         ],
      //         plugins: ['@babel/plugin-transform-runtime']
      //       }
      //     }
      //   },
      {
        test: /\.(?:js|mjs|cjs)$/,
        exclude: /node_modules/,
        use: {
          loader: "babel-loader",
          options: {
            presets: [["@babel/preset-env", { targets: "defaults" }]],
            plugins: ["@babel/plugin-transform-runtime"],
          },
        },
      },
    ],
  },
};

