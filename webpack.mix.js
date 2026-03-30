const mix = require('laravel-mix');

mix.autoload({
    jquery: ['$', 'jQuery', 'window.jQuery'],
});

mix
    .sass('resources/assets/demo1/sass/app.scss', 'public/assets/demo1/css')
    .options({ processCssUrls: false })

    // ✅ Main app (keep minimal)
    .js('resources/assets/demo1/js/app.js', 'public/assets/demo1/js')

    // ✅ Separate entry points (IMPORTANT)
    .js('resources/assets/demo1/js/map.js', 'public/assets/demo1/js')
    .js('resources/assets/demo1/js/home.js', 'public/assets/demo1/js')
    .js('resources/assets/demo1/js/property.js', 'public/assets/demo1/js')

    // ✅ Extract only real vendor libs
    .extract([
        'jquery',
        'bootstrap',
        'select2'
    ])

    .version()

    .webpackConfig({
        optimization: {
            minimize: true,
            splitChunks: {
                chunks: 'async', // ✅ better than 'all'
            },
        },
        output: {
            chunkFilename: 'js/[name].js?id=[chunkhash]',
        }
    });

// Source maps only in debug
if (process.env.APP_DEBUG === 'true') {
    mix.webpackConfig({
        devtool: 'inline-source-map',
    }).sourceMaps();
}