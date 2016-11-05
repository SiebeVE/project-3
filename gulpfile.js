const elixir = require('laravel-elixir');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */

elixir(mix => {
    mix.copy('node_modules/readmore-js/readmore.min.js','public/js/libs');

    mix.sass('app.scss')
        .webpack('app.js');


    mix.copy('node_modules/font-awesome/css/font-awesome.min.css','resources/assets/css/libs')
        .copy('node_modules/font-awesome/fonts/','public/fonts')
        .styles([
            "libs/font-awesome.min.css",
        ], 'public/css/libs.css');
});
