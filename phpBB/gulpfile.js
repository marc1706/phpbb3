'use strict';

var del = require('del'),
	gulp = require('gulp'),
	sass = require('gulp-sass');

var admConfig = {
	cssPath: './adm/style/assets/css',
	sassPath: './adm/style/assets/scss',
	bootstrapDir: './vendor/twbs/bootstrap-sass'
};

gulp.task('adm_compile_sass', ['clean_adm'], function() {
	console.log(admConfig.bootstrapDir + '/assets/stylesheets');
	return gulp.src(admConfig.sassPath + '/style.scss')
		.pipe(sass({
			style: 'compressed',
			includePaths: [
				admConfig.sassPath,
				admConfig.bootstrapDir + '/assets/stylesheets'
			]
		})
			.on("error", sass.logError))
		.pipe(gulp.dest(admConfig.cssPath));
});

gulp.task('watch_adm', function() {
	gulp.watch('adm/style/assets/scss/*.scss',['adm_compile_sass']);
});

gulp.task('clean_adm', function () {
	del(['adm/style/assets/css/*']);
});
