let mix = require('laravel-mix')

mix.options({terser: {extractComments: false}})

mix.js('resources/js/app.js', 'public/app.js').react()
mix.less('resources/less/app.less', 'public/app.css')