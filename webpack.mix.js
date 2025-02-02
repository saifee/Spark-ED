let mix = require('laravel-mix');                           // If you are new to this then please visit https://laravel.com/docs/5.5/mix
const webpack = require('webpack');

module.exports = {
  rules: [
    {
      test: /\.s(c|a)ss$/,
      use: [
        'vue-style-loader',
        'css-loader',
        {
          loader: 'sass-loader',
          // Requires sass-loader@^7.0.0
          options: {
            implementation: require('sass'),
            fiber: require('fibers'),
            indentedSyntax: true // optional
          },
          // Requires sass-loader@^8.0.0
          options: {
            implementation: require('sass'),
            sassOptions: {
              fiber: require('fibers'),
              indentedSyntax: true // optional
            },
          },
        },
      ],
    },
  ],
}

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

var plugin =  'resources/plugins/';
const BundleAnalyzerPlugin = require('webpack-bundle-analyzer').BundleAnalyzerPlugin;
const CompressionPlugin = require('compression-webpack-plugin');

mix.babelConfig({
  plugins: ['@babel/plugin-syntax-dynamic-import'],
});

mix.js('resources/js/app.js', 'public/js/app.js')
  .combine([
    plugin + 'sidebarmenu.js',
    plugin + 'slimscroll/jquery.slimscroll.js',
    plugin + 'waves/waves.js',
    plugin + 'sticky-kit/sticky-kit.min.js',
    plugin + 'autosize/autosize.min.js',
    plugin + 'duplicate.js',
    'resources/js/custom.js',
  ],'public/js/plugin.js')
    .sass('resources/sass/style.scss', 'public/css')
    .sass('resources/sass/style-rtl.scss', 'public/css')
    .sass('resources/sass/colors/blue.scss', 'public/css/colors')
    .sass('resources/sass/colors/default.scss', 'public/css/colors')
    .sass('resources/sass/colors/green.scss', 'public/css/colors')
    .sass('resources/sass/colors/megna.scss', 'public/css/colors')
    .sass('resources/sass/colors/purple.scss', 'public/css/colors')
    .sass('resources/sass/colors/red.scss', 'public/css/colors')
    .browserSync({
        files: [ 'public/js/**/*.js', 'public/css/**/*.css' ],
        proxy: 'instikit.test'
    })
    .webpackConfig({
        devtool: "cheap-module-source-map",     // "eval-source-map" or "inline-source-map" or "cheap-module-source-map" or "eval"
        plugins: [
            // new BundleAnalyzerPlugin(),      // load this package to see which plugins with its size detail
            new CompressionPlugin({             // very import to compress the assets
                filename: "[path].gz[query]",
                algorithm: "gzip",
                test: /\.js$|\.css$|\.html$|\.svg$/,
                threshold: 10240,
                minRatio: 0.8
            }),
            new webpack.IgnorePlugin(/^\.\/locale$/, /moment$/)
        ],
        resolve: {
            alias: {
                '@sass': path.resolve(__dirname, 'resources', 'sass'),
                '@js': path.resolve(__dirname, 'resources', 'js'),
                '@var': path.resolve(__dirname, 'resources', 'var'),
                '@components': path.resolve(__dirname, 'resources', 'js', 'components'),
                '@layouts': path.resolve(__dirname, 'resources', 'js', 'layouts'),
                '@routers': path.resolve(__dirname, 'resources', 'js', 'routers'),
                '@services': path.resolve(__dirname, 'resources', 'js', 'services'),
                '@views': path.resolve(__dirname, 'resources', 'js', 'views'),
                '@widgets': path.resolve(__dirname, 'resources', 'js', 'widgets')
            }
        },
        output: {
            chunkFilename: '[name].js?id=[chunkhash]',
        },
    });

if (mix.inProduction()) {                       // In production environtment use versioning
    mix.version();
}
