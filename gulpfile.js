// Modules
const { watch, src, dest } = require('gulp'),
	uglify = require('gulp-uglify'),
	babel = require('gulp-babel'),
	sass = require('gulp-sass'),
	autoprefixer = require('gulp-autoprefixer'),
	imageMin = require('gulp-imagemin'),
	sourcemaps = require('gulp-sourcemaps'),
	concat = require('gulp-concat'),
	htmlmin = require('gulp-htmlmin'),
	browserSync = require('browser-sync').create();

// Tasks
function scss(){
	return src('src/scss/style.scss')
	.pipe(sourcemaps.init({loadMaps: true}))
	// .pipe(sass({outputStyle: 'compressed'}).on('error', sass.logError))
	.pipe(sass().on('error', sass.logError))
	.pipe(autoprefixer({
		browsers: ['last 2 versions']
	}))
	.pipe(sourcemaps.write())
	.pipe(concat('style.min.css'))
	.pipe(dest('dist/maps'))
	.pipe(dest('dist/css'))
	.pipe(browserSync.stream());
}

function js(cb){
	cb();
	return src(['src/js/jquery.js', 'src/js/vendor/*.js','src/js/main.js'])
	.pipe(sourcemaps.init({loadMaps: true}))
	.pipe(babel({
		presets: ['@babel/preset-env']
	}))
	// .pipe(uglify())
	.pipe(sourcemaps.write())
	.pipe(concat('all.min.js'))
	.pipe(dest('dist/maps'))
	.pipe(dest('dist/js'))
	.pipe(browserSync.stream());
}

function fonts(){
	return src('./src/fonts/*')
		.pipe(dest('./dist/fonts'));
}

function images(){
	return src('./src/img/*.{png,gif,jpg}')
	.pipe(imageMin())
	.pipe(dest('./dist/img'));
}

function svgs(){
	return src('./src/img/svgs/*')
	.pipe(dest('./dist/img'));
}

function html(){
	return src('src/index.html')
	.pipe(htmlmin({ collapseWhitespace: true }))
	.pipe(dest('./dist'))
	.pipe(browserSync.stream());
}

function defaultTask(){
	watch('src/js/**/*.js',{ ignoreInitial: false }, js),
	watch('src/scss/**/*.scss', { ignoreInitial: false }, scss),
	watch('src/fonts/*',{ ignoreInitial: false }, fonts),
	watch('src/img/*.{png,gif,jpg}',{ ignoreInitial: false }, images);
	watch('src/img/svgs/*',{ ignoreInitial: false }, svgs);
	watch('src/index.html',{ ignoreInitial: false }, html),
	browserSync.init({server: "./dist"});
}

// Exports
exports.js = js;
exports.scss = scss;
exports.html = html;
exports.images = images;
exports.svgs = svgs;
exports.fonts = fonts;
exports.default = defaultTask;