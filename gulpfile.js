// Modules
const { watch, src, dest } = require("gulp"),
  uglify = require("gulp-uglify"),
  babel = require("gulp-babel"),
  sass = require("gulp-sass"),
  autoprefixer = require("gulp-autoprefixer"),
  imageMin = require("gulp-imagemin"),
  svgmin = require("gulp-svgmin"),
  concat = require("gulp-concat"),
  browserSync = require("browser-sync").create(),
  util = require("gulp-util"),
  config = {
    production: !!util.env.production
  };

// Dev Tasks

// CSS
function scss() {
  return src("src/scss/style.scss")
    .pipe(
      autoprefixer({
        browsers: ["last 2 versions"]
      })
    )
    .pipe(
      config.production
        ? sass({ outputStyle: "compressed" }).on("error", sass.logError)
        : sass().on("error", sass.logError)
    )
    .pipe(concat("style.min.css"))
    .pipe(dest("dist/css"))
    .pipe(browserSync.stream());
}

// JS
function js() {
  return src("src/js/scripts/*.js")
    .pipe(
      babel({
        presets: ["@babel/preset-env"]
      })
    )
    .pipe(config.production ? uglify() : util.noop())
    .pipe(concat("all.min.js"))
    .pipe(dest("dist/js"))
    .pipe(browserSync.stream());
}

// IMAGES
function img() {
  return src("./src/img/*.{png,gif,jpg}")
    .pipe(imageMin())
    .pipe(dest("./dist/img"));
}

function svg() {
  return src("./src/img/svg/*")
    .pipe(svgmin())
    .pipe(dest("./dist/img"));
}

// COPY
function fonts() {
  return src("./src/fonts/*").pipe(dest("./dist/fonts"));
}

function defaultTask() {
  watch("src/js/**/*.js", { ignoreInitial: false }, js);
  watch("src/scss/**/*.scss", { ignoreInitial: false }, scss);
  watch("src/fonts/*", { ignoreInitial: false }, fonts);
  watch("src/img/*.{png,gif,jpg}", { ignoreInitial: false }, img);
  watch("src/img/svg/*", { ignoreInitial: false }, svg);
  browserSync.init({ proxy: "http://localhost:8000/" });
}

// Exports
exports.js = js;
exports.scss = scss;
exports.fonts = fonts;
exports.img = img;
exports.svg = svg;
exports.default = defaultTask;
