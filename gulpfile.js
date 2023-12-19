// Grab our gulp packages
const gulp = require('gulp');
const sass = require('gulp-sass')(require('sass'));
const cleanCSS = require('gulp-clean-css');
const autoprefixer = require('gulp-autoprefixer');
const sourcemaps = require('gulp-sourcemaps');
const uglify = require('gulp-uglify');
//const concat = require('gulp-concat');
//const rename = require('gulp-rename');




// Compile Main Sass, create source Map and Autoprefix
gulp.task('styles', function() {
    
    return gulp.src('sass/*.scss')
        .pipe(sourcemaps.init()) // Start Sourcemaps
        .pipe(sass())
        .pipe(autoprefixer())
		//.pipe(cleanCSS({compatibility: '*'}))
        .pipe(sourcemaps.write('.')) // Creates sourcemaps for minified styles
		.pipe(gulp.dest('./'));

});

// Compile Sass for blocks
gulp.task('blocks-styles', function() {
    
	return gulp.src('partials/blocks/*/*.scss')
		.pipe(sass())
		.pipe(autoprefixer())
		//.pipe(cleanCSS({compatibility: '*'}))
		.pipe(gulp.dest(function (file) {
			return file.base;
		}));
});

gulp.task('all-styles', gulp.series('styles','blocks-styles'));
gulp.task('styles-all', gulp.series('styles','blocks-styles'));



// Minify main js
gulp.task('scripts', function() {
	return gulp.src('js/*.js')
		.pipe(uglify())
		.pipe(gulp.dest('js/min'));
});

// Minify js for blocks
gulp.task('blocks-scripts', function() {
	return gulp.src('partials/blocks/*/*.js')
	.pipe(uglify())
	.pipe(gulp.dest('js/min'));
});

gulp.task('all-scripts', gulp.series('scripts','blocks-scripts'));

// Watch files for changes (without Browser-Sync)
gulp.task('watch', function() {

	// Watch main theme .scss files
	gulp.watch('sass/*/*.scss', gulp.series('styles'));

	// Watch blocks .scss files
	gulp.watch('partials/blocks/*/*.scss', gulp.series('blocks-styles'));

	// Watch main js files
	gulp.watch('js/*.js', gulp.series('scripts'));

	// Watch blocks .js files
	gulp.watch('partials/blocks/*/*.js', gulp.series('blocks-scripts'));


}); 