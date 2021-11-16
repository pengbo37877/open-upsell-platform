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

if (mix.inProduction()) {
    mix.options({
        terser: {
            terserOptions: {
                compress: {
                    drop_console: true
                }
            }
        }
    });
    mix.js("resources/js/app.js", "public/js")
        .js("resources/js/ant-rack.js", "public/js")
        .js("resources/js/ant-rack-app.js", "public/js")
        .vue()
        .postCss("resources/css/app.css", "public/css", [
            require("tailwindcss"),
            require("autoprefixer")
        ]);
} else {
    mix.js("resources/js/app.js", "public/js")
        .js("resources/js/ant-rack.js", "public/js")
        .js("resources/js/ant-rack-app.js", "public/js")
        .vue()
        .postCss("resources/css/app.css", "public/css", [
            require("tailwindcss"),
            require("autoprefixer")
        ])
        .postCss("resources/css/ant-rack.css", "public/css", [
            require("tailwindcss"),
            require("autoprefixer")
        ]);
}
