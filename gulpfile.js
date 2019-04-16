const gulp = require('gulp');
const browserSync = require('browser-sync').create();
const sass = require('gulp-sass');
const autoprefixer = require('gulp-autoprefixer');
const csso = require('gulp-csso');
const uglify = require('gulp-uglify');

// Set the browser that you want to support
const AUTOPREFIXER_BROWSERS = [
  'ie >= 10',
  'ie_mob >= 10',
  'ff >= 30',
  'chrome >= 34',
  'safari >= 7',
  'opera >= 23',
  'ios >= 7',
  'android >= 4.4',
  'bb >= 10'
];

// Compile Sass & Inject Into Browser
gulp.task('sass', function() {
    return gulp.src(['node_modules/bootstrap/scss/bootstrap.scss', 'resources/assets/scss/**/*.scss'])
        .pipe(sass())
        .pipe(autoprefixer({browsers: AUTOPREFIXER_BROWSERS}))
        .pipe(csso())
        .pipe(gulp.dest("public/assets/css"))
        .pipe(browserSync.stream());
});

// Move JS Files to src/js
gulp.task('js', function() {
    return gulp.src(['node_modules/bootstrap/dist/js/bootstrap.min.js', 'node_modules/jquery/dist/jquery.min.js', 'node_modules/popper.js/dist/umd/popper.min.js', 'resources/assets/js/*.js'])
        .pipe(uglify())
        .pipe(gulp.dest("public/assets/js"))
        .pipe(browserSync.stream());
});

// Watch Sass & Serve
gulp.task('serve', function() {

    browserSync.init({
        proxy: "http://localhost/antaraja/public/"
    });

    gulp.watch(['node_modules/bootstrap/scss/bootstrap.scss', 'resources/assets/scss/**/*.scss'], gulp.series('sass'));
    gulp.watch("public/*.php").on('change', browserSync.reload);
    gulp.watch("resources/views/**/*.twig").on('change', browserSync.reload);
});

// Move Fonts to src/fonts
gulp.task('fonts', function() {
    return gulp.src('node_modules/font-awesome/fonts/*')
        .pipe(gulp.dest('public/assets/fonts'))
});

// Move Font Awesome CSS to src/css
gulp.task('fa', function() {
    return gulp.src('node_modules/font-awesome/css/font-awesome.min.css')
        .pipe(gulp.dest('public/assets/css'))
});

gulp.task('default', gulp.parallel('js', 'serve', 'fa', 'fonts'));
