const mix = require('laravel-mix');

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

mix.js('resources/js/app.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css').version();
//.version()为每一次的文件修改做哈希处理。
// 只要文件修改，哈希值就会变，提醒客户端需要重新加载文件，很巧妙地解决了静态文件缓存。
// 解决文件版本变更。
