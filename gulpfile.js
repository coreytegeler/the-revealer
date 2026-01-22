const argv = require('yargs').argv;
const gulp = require('gulp');
const gutil = require('gulp-util');
const sass = require('gulp-sass')(require('sass'));
const coffee = require('gulp-coffee');
const zip = require('gulp-zip');

const paths = {
  sass: './src/sass/*.scss',
  coffee: './src/coffee/*.coffee',
}

const dest = {
  css: './',
  js: './assets/js/',
  images: './assets/images/',
  webfonts: './assets/webfonts/'
}

function compileSass() {
  const options = {
  };
  return gulp.src([
    './src/sass/admin.scss',
    './src/sass/style.scss'
  ])
    .pipe(sass(options).on('error', sass.logError))
    .pipe(gulp.dest(dest.css))
    .on('end', function () {
      log('Sass done');
      if (argv.prod) log('CSS minified');
    });
}

function compileCoffee() {
  return gulp.src('./src/coffee/main.coffee')
    .pipe(coffee({ bare: true }))
    .pipe(gulp.dest(dest.js))
    .on('end', function () {
      log('Coffee done');
      if (argv.prod) log('JS minified');
    });
}

function watchFiles() {
  gulp.watch(paths.sass, compileSass);
  gulp.watch(paths.coffee, compileCoffee);
}

function packageTheme() {
  return gulp.src([
    '**/*',
    '!node_modules/**',
    '!src/**',
    '!.git/**',
    '!.gitignore',
    '!.DS_Store',
    '!gulpfile.js',
    '!package.json',
    '!package-lock.json',
    '!*.zip',
    '!error_log'
  ], { base: '..' })
    .pipe(zip('the-revealer-wp-theme.zip'))
    .pipe(gulp.dest('.'))
    .on('end', function () {
      log('Theme packaged successfully!');
    });
}


gulp.task('dev', gulp.parallel(
  compileSass,
  compileCoffee,
  watchFiles
));

gulp.task('prod', gulp.parallel(
  compileSass,
  compileCoffee
));

gulp.task('package', gulp.series(
  gulp.parallel(compileSass, compileCoffee),
  packageTheme
));

function log(message) {
  gutil.log(gutil.colors.bold.green(message));
}