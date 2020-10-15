const gulp = require('gulp'),
    sass = require('gulp-sass'),
    prefixer = require('gulp-autoprefixer'),
    path = require('path'),
    browserSync = require('browser-sync').create(),
    jshint = require('gulp-jshint'),
    babel = require('gulp-babel'),
    uglify = require('gulp-uglify'),
    plumber = require('gulp-plumber'),
    imgMin = require('gulp-imagemin'),
    minify = require("gulp-babel-minify");
cleanCSS = require('gulp-clean-css');

gulp.task('js', function () {
    gulp.src('source/js/script.js')
        // .pipe(jshint())
        //.pipe(uglify())
        .pipe(gulp.dest('../assets/js'))
});

gulp.task('styles', function () {
    gulp.src('source/scss/style.scss')
        .pipe(sass().on('error', sass.logError))
        .pipe(prefixer())
        .pipe(cleanCSS({
            compatibility: 'ie8',
            inline: ['none']
        }))
        .pipe(gulp.dest('../assets/css'))
        .pipe(browserSync.stream());
});

gulp.task('browserSync', function () {
    // find project name
    var dir = path.resolve(__dirname, '..'),
        n = dir.lastIndexOf('\\'),
        project_name = dir.substring(n + 1) + '/';

    browserSync.init({
        proxy: "http://localhost/beautycooking",
        files: [
            "../**/*.php",
            "../templates/**/*.twig",
            "source/js/**/*.js",
        ]
    });

});

//Watch task
gulp.task('default', ['styles', 'js', 'browserSync'], function () {
    gulp.watch('source/scss/**/*.scss', {interval: 500}, ['styles']);
    gulp.watch('source/js/**/*.js', {interval: 500}, ['js']);
    gulp.watch('source/', {interval: 500}, ['browserSync']);
    gulp.watch('../**/*.php', {interval: 500}, ['browserSync']);
});
