const mix = require("laravel-mix");

/*
mix.webpackConfig({
    stats: {
        children: false,
    },
});*/

mix.js("resources/js/app.js", "js")
    .js("resources/js/pages.js", "js")
    .vue()
    .sass("resources/scss/app.scss", "css/app.css")
    .sass("resources/scss/pages.scss", "css/pages.css")
    .postCss("resources/css/app.css", "css/tailwind.css", [
        require("@tailwindcss/postcss"),
    ])
    .version();
