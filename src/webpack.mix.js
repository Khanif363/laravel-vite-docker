const mix = require("laravel-mix");

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js("resources/js/app.js", "public/assets/js")
    .postCss("resources/css/app.css", "public/assets/css", [
        require("tailwindcss"),
    ])
    .copyDirectory("vendor/tinymce/tinymce", "public/js/tinymce")
    .copy(
        "node_modules/@fortawesome/fontawesome-free/webfonts",
        "public/fonts/vendor/@fortawesome/fontawesome-free"
    )
    .sass("resources/sass/app.scss", "public/assets/icon");
