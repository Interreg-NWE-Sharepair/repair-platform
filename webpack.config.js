const path = require('path');
const webpack = require('webpack');
const sass = require('sass');
const fibers = require('fibers');

//  Plugins
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const ImageminPlugin = require('imagemin-webpack-plugin').default;
const CopyPlugin = require('copy-webpack-plugin');
const TerserJSPlugin = require('terser-webpack-plugin');
const OptimizeCSSAssetsPlugin = require('optimize-css-assets-webpack-plugin');
const VueLoaderPlugin = require('vue-loader/lib/plugin');
const VuetifyLoaderPlugin = require('vuetify-loader/lib/plugin');
const WebpackShellPlugin = require('webpack-shell-plugin');

// Load environment variables from .env file
require('dotenv').config();

const isDevelopment = process.env.NODE_ENV === 'development';

function getSourcePath() {
  return path.resolve(process.env.NPM_PATH_SRC || 'resources', ...arguments);
}

function getPublicPath() {
  return path.resolve(process.env.NPM_PATH_PUBLIC || 'public', ...arguments);
}

module.exports = {
  mode: process.env.NODE_ENV,

  entry: {
    app: getSourcePath('js/app.js'),
    flare: getSourcePath('js/flare.js'),
    polyfill: getSourcePath('js/polyfill.js')
  },

  output: {
    publicPath: isDevelopment ? process.env.DEV_SERVER_URL : '',
    path: getPublicPath(),
    filename: 'js/[name].js'
  },

  module: {
    rules: [
      {
        test: /\.m?js$/,
        include: [getSourcePath(), path.resolve(__dirname, 'node_modules/vuetify')],
        use: [
          {
            loader: 'babel-loader',
            options: {
              cacheDirectory: true
            }
          },
          {
            loader: 'eslint-loader'
          }
        ]
      },
      {
        test: /\.scss$/,
        use: [
          'vue-style-loader',
          'css-loader',
          {
            loader: 'sass-loader',
            options: {
              implementation: sass,
              sassOptions: {
                fiber: fibers
              }
            }
          }
        ]
      },
      {
        test: /\.sass$/,
        use: [
          'vue-style-loader',
          'css-loader',
          {
            loader: 'sass-loader',
            options: {
              additionalData: "@import '@/sass/vuetify/app.sass'",
              // @todo appendData (?) zodat we vuetify variabelen kunnen gebruiken in custom css
              implementation: sass,
              sassOptions: {
                fiber: fibers
              }
            }
          }
        ]
      },
      {
        test: /\.css$/,
        use: ['vue-style-loader', 'css-loader', 'postcss-loader']
      },
      {
        test: /\.(ttf|eot|woff|woff2)$/,
        use: {
          loader: 'file-loader',
          options: {
            name: 'fonts/[name].[ext]',
            outputPath: process.env.NPM_PATH_SRC,
            publicPath: ''
          }
        }
      },
      {
        test: /\.(png|jpeg|jpg)$/,
        use: {
          loader: 'file-loader',
          options: {
            name: 'img/[name].[ext]',
            outputPath: process.env.NPM_PATH_PUBLIC,
            publicPath: ''
          }
        }
      },
      {
        test: /\.font\.js/,
        use: ['css-loader', 'webfonts-loader']
      },
      {
        test: /\.vue$/,
        use: [
          {
            loader: 'vue-loader'
          },
          {
            loader: 'eslint-loader'
          }
        ]
      },
      {
        test: /\.svg$/,
        use: ['babel-loader', 'vue-svg-loader']
      }
    ]
  },

  resolve: {
    extensions: ['.js', '.vue', '.css', '.json', '.html', '.png', '.svg', '.jpg', '.jpeg'],
    alias: {
      'vue$': 'vue/dist/vue.esm.js',
      '@': getSourcePath(),
      'medialibrary': path.resolve(__dirname, '/vendor/spatie/laravel-medialibrary-pro/resources/js')
    }
  },

  plugins: [
    new webpack.ProvidePlugin({
      $: 'jquery',
      jQuery: 'jquery',
      Vue: ['vue/dist/vue.esm.js', 'default'],
      ziggyRoute: path.resolve('vendor/tightenco/ziggy/dist/js/route.js')
    }),

    new webpack.DefinePlugin({
      DEV_SERVER_PORT: isDevelopment ? JSON.stringify(process.env.DEV_SERVER_PORT) : null
    }),

    new MiniCssExtractPlugin({
      filename: isDevelopment ? 'css/[name].css' : 'css/[name].min.css'
    }),

    new ImageminPlugin({
      test: /\.(jpe?g|png|gif)$/i
    }),

    new VueLoaderPlugin(),

    new VuetifyLoaderPlugin(),

    new webpack.HotModuleReplacementPlugin(),

    new CopyPlugin([
      {
        from: getSourcePath('img'),
        to: getPublicPath('img')
      }
    ]),

    new WebpackShellPlugin({
      onBuildStart: ['yarn ziggy:generate']
    })
  ],

  optimization: {
    splitChunks: {
      cacheGroups: {
        styles: {
          name: 'styles',
          test: /\.css$/,
          chunks: 'all',
          enforce: true
        }
      }
    },
    minimizer: [
      new TerserJSPlugin({
        terserOptions: {
          output: {
            comments: false
          }
        }
      }),

      new OptimizeCSSAssetsPlugin()
    ]
  },

  stats: {
    children: false
  },

  devtool: isDevelopment ? 'cheap-module-eval-source-map' : 'nosources-source-map',

  // Enable weback Hot Module Replacement
  devServer: {
    // add any additional files in th js/ folder
    contentBase: [getSourcePath('js/app.js')],

    // variables from .env file
    host: process.env.DEV_SERVER_HOST,
    port: process.env.DEV_SERVER_PORT,

    hot: true,

    // Fix invalid host header issue
    allowedHosts: ['.statik.be'],

    // Fix font loading issue
    headers: {
      'Access-Control-Allow-Origin': '*'
    },

    // https: true,
    cert: process.env.DEV_SERVER_CERT || './local-ssl/cert.pem',
    key: process.env.DEV_SERVER_KEY || './local-ssl/key.pem',
    clientLogLevel: 'silent',
    noInfo: true
  },

  watchOptions: {
    poll: true,
    ignored: /node_modules/
  }
};
