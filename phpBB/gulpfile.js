'use strict';

var del = require('del'),
	gulp = require('gulp'),
	sass = require('gulp-sass'),
	postcss = require('gulp-postcss'),
	cleanCss = require('gulp-clean-css'),
	path = require('path');

var admConfig = {
	cssPath: './adm/style/assets/css',
	fontsPath: './adm/style/assets/fonts',
	imagesPath: './adm/style/assets/images',
	jsPath: './adm/style/assets/js',
	sassPath: './adm/style/assets/scss',
	bootstrapDir: './vendor/twbs/bootstrap-sass'
};

gulp.task('adm_compile_sass', ['clean_adm', 'copy_adm_js', 'copy_adm_fonts'], function() {
	var processors = [
		require('autoprefixer')()
	];

	return gulp.src(admConfig.sassPath + '/style.scss')
		.pipe(sass({
			style: 'compressed',
			includePaths: [
				admConfig.sassPath,
				admConfig.bootstrapDir + '/assets/stylesheets'
			]
		}).on("error", sass.logError))
		.pipe(postcss(processors))
		.pipe(cleanCss({compatibility: 'ie8'}))
		.pipe(gulp.dest(admConfig.cssPath));
});

gulp.task('copy_adm_js', function() {
	return gulp.src(admConfig.bootstrapDir + '/assets/javascripts/bootstrap.min.js')
		.pipe(gulp.dest(admConfig.jsPath));
});

gulp.task('copy_adm_fonts', function () {
	return gulp.src(admConfig.bootstrapDir + '/assets/fonts/bootstrap/*')
		.pipe(gulp.dest(admConfig.fontsPath + '/bootstrap/'));
});

gulp.task('watch_adm', function() {
	gulp.watch('adm/style/assets/scss/*.scss',['adm_compile_sass']);
});

gulp.task('clean_adm', function () {
	del(['adm/style/assets/css/*']);
	del(['adm/style/assets/js/*']);
});
