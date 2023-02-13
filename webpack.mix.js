let mix = require('laravel-mix');

mix.js('src/resources/js/reports.js', 'public/js')
    .sass('src/resources/sass/reports.scss', 'public/css')
    .sass('src/resources/sass/reports-print.scss', 'public/css')
	.copyDirectory('src/resources/images', 'public/images');

    
if (mix.inProduction()) {
    mix.version();
}