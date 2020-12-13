const {src, dest, watch, series} = require("gulp");

const autoprefixer = require("gulp-autoprefixer");
const cleanCSS = require("gulp-clean-css");
const gulpConcat = require("gulp-concat");
const imagemin = require("gulp-imagemin");
const minify = require("gulp-minify");
const newer = require("gulp-newer");
const plumber = require("gulp-plumber");
const rename = require("gulp-rename");
const sass = require("gulp-sass");

// SCSS to CSS
function sassToCss() {
    return src("src/scss/*.*")
            .pipe(plumber())
            .pipe(
                    sass({
                        outputStyle: "expanded"
                    })
                    )
            .pipe(autoprefixer())
            .pipe(
                    rename({
                        extname: ".css"
                    })
                    )
            .pipe(dest("public/css"));
}

function cssMini() {
    return src(["public/css/*.css", "!public/css/*.min.css"])
            .pipe(cleanCSS({compatibility: "ie8"}))
            .pipe(
                    rename({
                        suffix: ".min"
                    })
                    )
            .pipe(dest("public/css"));
}

// Optimize Images
function images() {
    return src("src/img/**/*")
            .pipe(newer("public/img"))
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
            .pipe(dest("public/img"));
}

// Concat JS Scripts
function concatJs() {
    return (
            src(["src/js/*.js"])
            //.pipe(gulpConcat("application.js"))
            .pipe(dest("public/js"))
            );
}

// Compress JS Script
function compressJs() {
    return src(["public/js/*.js", "!public/js/*.min.js"])
            .pipe(
                    minify({
                        ext: {
                            min: ".min.js"
                        }
                    })
                    )
            .pipe(dest("public/js"));
}

// Watch files and run tasks
function watchFiles() {
    "use strict";
    watch("src/scss/**/*", series(sassToCss, cssMini));
    watch("src/js/**/*", series(concatJs, compressJs));
    watch("src/img/**/*", images);
}

exports.sassToCss = sassToCss;
exports.cssMini = cssMini;
exports.images = images;
exports.concatJs = concatJs;
exports.compressJs = series(concatJs, compressJs);

exports.watch = watchFiles;
exports.default = series(sassToCss, cssMini, images, concatJs, compressJs);
