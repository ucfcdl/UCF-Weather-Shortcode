var gulp         = require('gulp'),
    bower        = require('gulp-bower'),
    scsslint     = require('gulp-scss-lint'),
    sass         = require('gulp-sass'),
    cleanCss     = require('gulp-clean-css'),
    autoprefixer = require('gulp-autoprefixer'),
    rename       = require('gulp-rename'),
    eslint       = require('gulp-eslint'),
    concat       = require('gulp-concat'),
    ifFixed      = require('gulp-eslint-if-fixed'),
    sourcemaps   = require('gulp-sourcemaps'),
    uglify       = require('gulp-uglify'),
    readme       = require('gulp-readme-to-markdown'),
    runSequence  = require('run-sequence');

var config = {
  src: {
    scssPath: './src/scss',
    jsPath: './src/js'
  },
  dist: {
    cssPath: './static/css',
    jsPath: './static/js',
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

gulp.task('scss', function() {
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

gulp.task('css', function() {
  runSequence('scss-lint', 'scss');
});

gulp.task('eslint', function() {
  return gulp.src(config.src.jsPath + '/**/*.js')
    .pipe(eslint({fix: true}))
    .pipe(eslint.format())
    .pipe(ifFixed(config.src.jsPath));
});

gulp.task('js-admin', function() {
  return gulp.src(config.src.jsPath + '/admin.js')
    .pipe(sourcemaps.init())
    .pipe(rename('ucf-weather-admin.min.js'))
    .pipe(uglify())
    .pipe(gulp.dest(config.dist.jsPath));
});

gulp.task('js', function() {
  runSequence('eslint', 'js-admin');
});

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
  gulp.watch(config.src.jsPath + '/**/*.js', ['js']);
});

gulp.task('default', function() {
  runSequence('components', 'css', 'js', 'readme');
});
