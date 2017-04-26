'use strict';

var del = require('del'),
	gulp = require('gulp'),
	sass = require('gulp-sass'),
	postcss = require('gulp-postcss'),
	cleanCss = require('gulp-clean-css'),
	path = require('path');

var admConfig = {
	cssPath: './phpBB/adm/style/assets/css',
	fontsPath: './phpBB/adm/style/assets/fonts',
	imagesPath: './phpBB/adm/style/assets/images',
	jsPath: './phpBB/adm/style/assets/js',
	sassPath: './phpBB/adm/style/assets/scss',
	bootstrapDir: './node_modules/bootstrap/'
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
				admConfig.bootstrapDir + '/scss'
			]
		}).on("error", sass.logError))
		.pipe(postcss(processors))
		.pipe(cleanCss({compatibility: 'ie8'}))
		.pipe(gulp.dest(admConfig.cssPath));
});

gulp.task('copy_adm_js', function() {
	return gulp.src(admConfig.bootstrapDir + '/dist/js/bootstrap.min.js')
		.pipe(gulp.src(admConfig.bootstrapDir + '/../tether/dist/js/tether.min.js'))
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
