const { series, parallel, src, dest } = require('gulp');
const clean = require('gulp-clean');
const babel = require('gulp-babel');
const concat = require('gulp-concat');
const uglify = require('gulp-uglify');
const autoprefixer = require('autoprefixer')
const postcss = require('gulp-postcss')
const cleanCSS = require('gulp-clean-css');


function removeCompiled(cb) {
  return src([
      './js/*.min.js',
      './css/*.min.css',
    ], {read: false})
    .pipe(clean());
  cb();
}

function concatJs(cb) {
  // return src('./js/**/*.es6.js')
  return src([
      './simplesite/js/**/*.js',
      './simplesite/js/*.js',
      './js/**/*.js',
    ])
    .pipe(concat('app.min.js'))
    .pipe(dest('./js/'));
  cb();
}

function babelJs(cb) {
  return src('./js/app.min.js')
    // .pipe(concat('app.min.js'))
    .pipe(babel({
        presets: ['@babel/env']
    }))
    .pipe(uglify())
    .pipe(dest('./js/'))
  cb();
}

function concatCss(cb) {
  // return src('./css/**/*.css')
  //   // .pipe(sourcemaps.init())
  //   .pipe(concat('app.min.css'))
  //   .pipe(postcss([ autoprefixer() ]))
  //   // .pipe(sourcemaps.write('.'))
  //   .pipe(dest('./dest'))

  return src([
      './simplesite/css/**/*.css',
      './css/**/*.css',
    ])
    .pipe(concat('app.min.css'))
    .pipe(postcss([
      autoprefixer()
    ]))
    .pipe(dest('./css/'))
  cb();
}

function cleanCss(cb) {
  return src('./css/app.min.css')
    .pipe(cleanCSS({compatibility: 'ie9'}))
    .pipe(dest('./css/'));
  cb();
}

// exports.build = series(removeCompiled, concatJs, babelJs, concatCss);
exports.build = series(removeCompiled, concatJs, concatCss, cleanCss);
exports.default = exports.build;
