'use strict';

var del = require('del');
var gulp = require('gulp');
var sass = require('gulp-sass');

gulp.task('adm_compile_sass', ['clean_adm'], function() {
    gulp.src('adm/style/assets/scss/*.scss')
        .pipe(sass().on('error', sass.logError))
        .pipe(gulp.dest('adm/style/assets/css'));
});

gulp.task('watch_adm', function() {
    gulp.watch('adm/style/assets/scss/*.scss',['adm_compile_sass']);
});

gulp.task('clean_adm', function () {
    del(['adm/style/assets/css/*']);
});
