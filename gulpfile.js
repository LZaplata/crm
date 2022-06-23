let {src, dest, watch, parallel} = require("gulp");
let sass = require("gulp-sass");
let minify = require("gulp-clean-css");

function sassTask() {
    return src("assets/sass/app.sass")
        .pipe(sass({outputStyle: "compressed", includePaths: ["node_modules"]}))
        .pipe(dest("public/build/"));
}

function cssTask() {
    return src("node_modules/bootstrap-icons/font/bootstrap-icons.css")
        .pipe(minify())
        .pipe(dest("public/build/"))
}

function fontsTask() {
    return src("node_modules/bootstrap-icons/font/fonts/*")
        .pipe(dest("public/build/fonts/"))
}

function jsTask() {
    return src([
        "node_modules/jquery/dist/jquery.min.js",
        "node_modules/bootstrap/dist/js/bootstrap.js",
        "node_modules/@popperjs/core/dist/umd/popper.js",
        "assets/js/app.js"
    ])
        .pipe(dest("public/build/"));
}

function watchTask() {
    watch([
        "assets/**/app.sass",
        "assets/**/_variables.sass",
    ], {ignoreInitial: false}, sassTask);
}

exports.default = parallel(sassTask, cssTask, fontsTask, jsTask);
exports.watch = watchTask;
