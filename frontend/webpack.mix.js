let mix = require('laravel-mix');
let webpack = require('webpack');

require('laravel-mix-tailwind');
require('laravel-mix-versionhash');
require('laravel-mix-copy-watched');
require('mix-white-sass-icons');

mix.setPublicPath('./');

// 添加 React 支持
mix.webpackConfig({
    externals: {
        jquery: 'jQuery',
    },
    plugins: [
        new webpack.ProvidePlugin({
            $: 'jquery',
            jQuery: 'jquery',
            'window.jQuery': 'jquery',
        })
    ],
    resolve: {
        extensions: ['.js', '.jsx', '.json'],
        alias: {
            '@': __dirname + '/assets'
        }
    },
    module: {
        rules: [
            {
                test: /\.(js|jsx)$/,
                exclude: /node_modules/,
                use: {
                    loader: 'babel-loader',
                    options: {
                        presets: ['@babel/preset-env', '@babel/preset-react']
                    }
                }
            }
        ]
    }
});

mix.sass('assets/styles/main.scss', 'dist/styles')
.sass('assets/styles/admin.scss', 'dist/styles')
.tailwind()
.options({
    postCss: [
        require('css-mqpacker'),
    ],
});


mix.js('src/js/index.js', 'dist/scripts')
.react()
.sass('src/scss/stock-chart.scss', 'dist/styles')
.options({
    processCssUrls: false,
    postCss: [
        require('tailwindcss'),
    ]
});


// 修改 js 配置以支持 React
mix.js('assets/scripts/main.js', 'dist/scripts')
.react(); // 添加 React 支持

//mix.copyWatched('assets/images', 'dist/images')
//.copyWatched('assets/scripts/plugins', 'static/js')
//.copyWatched('assets/fonts', 'dist/fonts');

if (mix.inProduction()) {
    mix.versionHash();
    mix.icons('assets/icons', 'assets/fonts', 'assets/styles/icons.scss');
} else {
    mix.sourceMaps();
    mix.webpackConfig({devtool: 'source-map'});
}

mix.browserSync({
    proxy: 'https://bambooworks.test/stock/aapl-us/',
    files: [
        {
            match: [
                './dist/**/*',
                '../**/*.php',
            ],
            options: {
                ignored: '*.txt',
            },
        },
    ],
    snippetOptions: {
        whitelist: ['/wp-admin/admin-ajax.php'],
        blacklist: ['/wp-admin/**'],
    },
});