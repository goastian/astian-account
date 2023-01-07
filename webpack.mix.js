let mix = require('laravel-mix');

mix.js('resources/js/app.js', 'public/dist')
    .sass('resources/scss/app.scss', 'public/dist');