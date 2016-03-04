var gulp = require('gulp'),
    coffee = require('gulp-coffee'),
    notify = require('gulp-notify'),
    coffeelint = require('gulp-coffeelint'),
    uglify = require('gulp-uglify');

var paths = {
    scriptSrc: ['./Resources/Private/CoffeeScript/*.coffee'],
    scriptDst: './Resources/Public/JavaScript/'
}

gulp.task('coffee', ['coffee-lint'], function () {
    gulp.src(paths.scriptSrc)
        .pipe(coffee({bare: true}))
		.pipe(uglify())
        .pipe(gulp.dest(paths.scriptDst))
});

gulp.task('coffee-lint', function () {
    gulp.src(paths.scriptSrc)
        .pipe(coffeelint())
        .pipe(coffeelint.reporter('checkstyle'))
});

gulp.task('compile', ['coffee']);

gulp.task('watch', function () {
    gulp.watch(paths.scriptSrc, ['coffee']);
});

gulp.task('default', ['compile', 'watch']);
