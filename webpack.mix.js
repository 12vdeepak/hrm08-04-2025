const mix = require('laravel-mix');


mix.js('resources/js/app.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css')
    .sourceMaps();

mix.options({
    hmrOptions: {
        host: process.env.APP_URL,
        port: 8080
    }
});