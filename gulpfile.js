var argv = require('yargs').argv;
var gulpif = require('gulp-if');
var rename = require('gulp-rename');
var gulp = require('gulp');
var gutil = require('gulp-util');
var plumber = require('gulp-plumber');
var sass = require('gulp-sass');
var coffee = require('gulp-coffee');
var rupture = require('rupture');
var autoprefixer = require('gulp-autoprefixer');
var htmlmin = require('gulp-htmlmin');
var replace = require('gulp-replace');
var htmlreplace = require('gulp-html-replace');

var paths = {
  sass: './assets/sass/*.scss',
  coffee: './assets/coffee/*.coffee',
}

var dest = {
  css: './',
  js: './assets/js/',
  images: './assets/images/'
}

gulp.task('compile-sass', function() {
  var options = {
    use: [rupture(), autoprefixer()],
    compress: argv.prod ? true : false
  };
  return gulp.src('./assets/sass/style.scss')
    .pipe(plumber())
    .pipe(sass(options))
    .pipe(replace('images/', dest.images))
    .pipe(autoprefixer({
      browsers: ['last 2 versions'],
      cascade: false
    }))
    .pipe(gulp.dest(dest.css))
  .on('end', function() {
    log('Sass done');
    if (argv.prod) log('CSS minified');
  });
});

gulp.task('compile-coffee', function() {
  gulp.src('./assets/coffee/carousel.coffee')
    .pipe(coffee({bare: true}))
    .pipe(gulpif(argv.prod, rename('carousel.min.js')))
    .pipe(gulp.dest(dest.js))
  .on('end', function() {
    log('Coffee done');
    if (argv.prod) log('JS minified');
  });
  return gulp.src('./assets/coffee/main.coffee')
    .pipe(coffee({bare: true}))
    .pipe(gulpif(argv.prod, rename('main.min.js')))
    .pipe(gulp.dest(dest.js))
  .on('end', function() {
    log('Coffee done');
    if (argv.prod) log('JS minified');
  });
});

gulp.task('watch', function() {
  gulp.watch(paths.sass, ['compile-sass']);
  gulp.watch(paths.coffee, ['compile-coffee']);
});


gulp.task('default', [
  'compile-sass',
  'compile-coffee',
  'watch'
]);

gulp.task('prod', [
  'compile-sass',
  'compile-coffee'
]);

function log(message) {
  gutil.log(gutil.colors.bold.green(message));
}