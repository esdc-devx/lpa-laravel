let mix = require('laravel-mix');
const path = require('path');

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

mix.js('resources/assets/js/login.js', 'public/js')
   .js('resources/assets/js/app.js', 'public/js')
   .sass('resources/assets/sass/app.scss', 'public/css')
   .sourceMaps()
   .webpackConfig({
      resolve: {
        alias: {
          '@': path.resolve('resources/assets/js'),
          '@sass': path.resolve('resources/assets/sass'),
          '@components': path.resolve('resources/assets/js/components'),
          '@axios': path.resolve('resources/assets/js/axios'),
          '@mixins': path.resolve('resources/assets/js/mixins'),
          '@api': path.resolve('resources/assets/js/api'),
          '@views': path.resolve('resources/assets/js/views'),
        },
        extensions: ['.vue', '.js', '.scss']
      }
    });

// This fixes HMR not compiling the assets and reloading the browser
// with laravel-mix@^2.0
// https://github.com/JeffreyWay/laravel-mix/issues/1483#issuecomment-366685986
Mix.listen('configReady', (webpackConfig) => {
  if (Mix.isUsing('hmr')) {
    // Remove leading '/' from entry keys
    webpackConfig.entry = Object.keys(webpackConfig.entry).reduce((entries, entry) => {
      entries[entry.replace(/^\//, '')] = webpackConfig.entry[entry];
      return entries;
    }, {});

    // Remove leading '/' from ExtractTextPlugin instances
    webpackConfig.plugins.forEach((plugin) => {
      if (plugin.constructor.name === 'ExtractTextPlugin') {
        plugin.filename = plugin.filename.replace(/^\//, '');
      }
    });
  }
});
