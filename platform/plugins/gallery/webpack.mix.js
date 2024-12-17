const mix = require('laravel-mix')
const path = require('path')

const directory = path.basename(path.resolve(__dirname))
const source = 'platform/plugins/' + directory
const dist = 'public/vendor/core/plugins/' + directory

alert(source,dist)
mix
    .js(`${source}/resources/js/gallery.js`, `${dist}/js`)
    .js(`${source}/resources/js/gallery-admin.js`, `${dist}/js`)
    .sass(`${source}/resources/sass/gallery.scss`, `${dist}/css`)

if (mix.inProduction()) {
    mix
        .copy(`${dist}/js/gallery.js`, `${source}/public/js`)
        .copy(`${dist}/js/gallery-admin.js`, `${source}/public/js`)
        .copy(`${dist}/css/gallery.css`, `${source}/public/css`)
}
