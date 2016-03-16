'use strict';

var del = require('del'),
	gulp = require('gulp'),
	gulpRename = require('gulp-rename'),
	svgSprite = require('gulp-svg-sprite'),
	svgMin = require('gulp-svgmin'),
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

gulp.task('create_adm_icons', ['create_svg_sprite', 'svg_minify'], function () {
	return del([admConfig.imagesPath + '/svg/white']);
});

gulp.task('create_svg', function (cb) {
	var exec = require('child_process').exec;

	del([admConfig.imagesPath + '/svg/white']);
	// Create svgs
	exec('font-awesome-svg-png --color white --no-png --sizes 128 --dest ' + admConfig.imagesPath + '/svg', function (err, stdout, stderr) {
		console.log(stdout);
		console.log(stderr);
		cb(err);
	});
});

gulp.task('create_svg_sprite', ['create_svg'], function () {
	return gulp.src(admConfig.imagesPath + '/svg/white/svg/*')
		.pipe(svgSprite({
			shape: {
				dimension: {
					maxWidth: 48,
					maxHeight: 48
				},
				spacing: {
					padding: 5
				}
			},
			mode: {
				css: {
					dest: admConfig.imagesPath + '/svg',
					layout: 'grid',
					sprite: 'sprite.svg',
					bust: false,
					render: {
						scss: {
							dest: '../../../../../' + admConfig.sassPath + '/_sprite.scss',
							template: admConfig.sassPath + '/_sprite_template'
						}
					}
				}
			},
			variables: {
				mapname: 'icons'
			}
		}))
		.pipe(gulp.dest('./'));
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

gulp.task('svg_minify', function () {
	return gulp.src(admConfig.imagesPath + '/svg/sprite.svg')
		.pipe(svgMin({
			plugins: [{
				mergePaths: true
			}]
		}))
		.pipe(gulpRename('sprite.min.svg'))
		.pipe(gulp.dest(admConfig.imagesPath + '/svg'));
});
