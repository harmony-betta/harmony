const gulp = require('gulp');
const browserSync = require('browser-sync').create();
const sass = require('gulp-sass');

// Compile Sass & Inject Into Browser
gulp.task('sass', function() {
    return gulp.src(['node_modules/bootstrap/scss/bootstrap.scss', 'resources/assets/scss/**/*.scss'])
        .pipe(sass())
        .pipe(gulp.dest("public/assets/css"))
        .pipe(browserSync.stream());
});

// Move JS Files to src/js
gulp.task('js', function() {
    return gulp.src(['node_modules/bootstrap/dist/js/bootstrap.min.js', 'node_modules/jquery/dist/jquery.min.js', 'node_modules/popper.js/dist/umd/popper.min.js', 'resources/assets/js/*.js'])
        .pipe(gulp.dest("public/assets/js"))
        .pipe(browserSync.stream());
});

// Watch Sass & Serve
gulp.task('serve', ['sass'], function() {

    browserSync.init({
        proxy: "harmony-framework.dev"
    });

    gulp.watch(['node_modules/bootstrap/scss/bootstrap.scss', 'resources/assets/scss/**/*.scss'], ['sass']);
    gulp.watch("public/*.php").on('change', browserSync.reload);
});

// Move Fonts to src/fonts
gulp.task('fonts', function() {
    return gulp.src('node_modules/font-awesome/fonts/*')
        .pipe(gulp.dest('public/assets/fonts'))
})

// Move Font Awesome CSS to src/css
gulp.task('fa', function() {
    return gulp.src('node_modules/font-awesome/css/font-awesome.min.css')
        .pipe(gulp.dest('public/assets/css'))
})

gulp.task('default', ['js', 'serve', 'fa', 'fonts']);