var gulp         = require('gulp'),
    bower        = require('gulp-bower'),
    scsslint     = require('gulp-scss-lint'),
    sass         = require('gulp-sass'),
    cleanCss     = require('gulp-clean-css'),
    autoprefixer = require('gulp-autoprefixer'),
    rename       = require('gulp-rename'),
    readme       = require('gulp-readme-to-markdown'),
    runSequence  = require('run-sequence');

var config = {
  src: {
    scssPath: './src/scss'
  },
  dist: {
    cssPath: './static/css',
    fontPath: './static/fonts'
  },
  pkgs: {
    npmPath: './node_modules',
    bowerPath: './src/components',
    weatherIcons: './src/components/weather-icons'
  }
};

gulp.task('bower', function() {
  bower()
    .pipe(gulp.dest(config.pkgs.bowerPath));
});

gulp.task('move-components-fonts', function() {
  return gulp.src(config.pkgs.weatherIcons + '/font/**/*')
    .pipe(gulp.dest(config.dist.fontPath));
});

gulp.task('components', ['move-components-fonts']);

gulp.task('scss-lint', function() {
  return gulp.src(config.src.scssPath + '/**/*.scss')
    .pipe(scsslint({
      'maxBuffer': 400 * 1024
    }));
});

gulp.task('scss', ['scss-lint'], function() {
  return gulp.src(config.src.scssPath + '/ucf-weather.scss')
    .pipe(sass().on('error', sass.logError))
    .pipe(cleanCss())
    .pipe(autoprefixer({
      // Supported browsers in package.json
      cascade: false
    }))
    .pipe(rename('ucf-weather.min.css'))
    .pipe(gulp.dest(config.dist.cssPath));
});

gulp.task('css', ['scss-lint', 'scss']);

gulp.task('readme', function() {
  return gulp.src('./readme.txt')
    .pipe(readme({
      details: false,
      screenshot_ext: []
    }))
    .pipe(gulp.dest('.'));
});

gulp.task('watch', function() {
  gulp.watch(config.src.scssPath + '/**/*.scss', ['css']);
});

gulp.task('default', function() {
  runSequence('components', 'css', 'readme');
});
