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
  images: './assets/images/',
  webfonts: './assets/webfonts/'
}

function compileSass()  {
  var options = {
    use: [rupture(), autoprefixer()],
    compress: argv.prod ? true : false
  };
  return gulp.src(['./assets/sass/admin.scss', './assets/sass/style.scss'])
    .pipe(plumber())
    .pipe(sass(options))
    .pipe(replace('images/', dest.images))
    .pipe(replace('webfonts/', dest.webfonts))
    .pipe(autoprefixer({
      browsers: ['last 2 versions'],
      cascade: false
    }))
    .pipe(gulp.dest(dest.css))
  .on('end', function() {
    log('Sass done');
    if (argv.prod) log('CSS minified');
  });
}

function compileCoffee()  {
  return gulp.src('./assets/coffee/main.coffee')
    .pipe(coffee({bare: true}))
    .pipe(gulp.dest(dest.js))
  .on('end', function() {
    log('Coffee done');
    if (argv.prod) log('JS minified');
  });
}

function watchFiles()  {
  gulp.watch(paths.sass, compileSass);
  gulp.watch(paths.coffee, compileCoffee);
}


gulp.task('default', gulp.parallel(compileSass, compileCoffee, watchFiles));
gulp.task('prod', gulp.parallel(compileSass, compileCoffee));

function log(message) {
  gutil.log(gutil.colors.bold.green(message));
}