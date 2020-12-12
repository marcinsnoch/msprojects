const {
    src,
    dest,
    watch,
    series,
} = require("gulp");

const autoprefixer = require("gulp-autoprefixer");
const concat = require("gulp-concat");
const imagemin = require("gulp-imagemin");
const minify = require("gulp-minify");
const newer = require("gulp-newer");
const plumber = require("gulp-plumber");
const rename = require("gulp-rename");
const sass = require("gulp-sass");

const outputDir = "public";

// Optimize Images
function images() {
    return src(["src/img/**/*", "src/photos/**/*"])
        .pipe(newer(outputDir + "/img"))
        .pipe(
            imagemin([
                imagemin.gifsicle({
                    interlaced: true
                }),
                imagemin.mozjpeg({
                    quality: 75,
                    progressive: true
                }),
                imagemin.optipng({
                    optimizationLevel: 5
                }),
                imagemin.svgo({
                    plugins: [
                        {
                            removeViewBox: false,
                            collapseGroups: true
                        }
                    ]
                })
            ])
        )
        .pipe(dest(outputDir + "/img"));
}

// SASS to CSS
function css() {
    return src("src/scss/*.*")
        .pipe(plumber())
        .pipe(sass({
            outputStyle: "compressed"
        }))
        .pipe(autoprefixer())
        .pipe(rename({
            suffix: ".min"
        }))
        .pipe(dest(outputDir + "/css"));
}

// Concat JS Scripts
function concat_js() {
    return src(["src/js/*.*"])
        // .pipe(concat("application.js"))
        .pipe(dest(outputDir + "/js"));
}

// Compress JS Script
function compress_js() {
    return src(outputDir + "/js/*.js")
        .pipe(
            minify({
                ext: {
                    min: ".min.js"
                },
                ignoreFiles: ["*min.js"]
            })
        )
        .pipe(dest(outputDir + "/js"));
}

// Watch files and run tasks
function watchFiles() {
    "use strict";
    watch("src/scss/**/*", css);
    watch("src/js/**/*", series(concat_js, compress_js));
    watch("src/img/**/*", images);
}

exports.css = css;
exports.images = images;
exports.compress_js = compress_js;
exports.concat_js = series(concat_js, compress_js);

exports.watch = watchFiles;
exports.default = series(css, concat_js, images);