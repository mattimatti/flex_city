/*!
 * 
 * https://markgoodyear.com/2014/01/getting-started-with-gulp/
 * 
 * gulp
 * $ npm install gulp-ruby-sass gulp-autoprefixer gulp-cssnano gulp-jshint gulp-concat gulp-uglify gulp-imagemin gulp-notify gulp-rename gulp-livereload gulp-cache del --save-dev
 */

var gulp = require('gulp');
var jshint = require("gulp-jshint");
var sass = require("gulp-sass");
var autoprefixer = require('gulp-autoprefixer');
var minifyCss = require("gulp-minify-css");
var minifyHtml = require("gulp-minify-html");
var sass = require('gulp-ruby-sass');
var autoprefixer = require('gulp-autoprefixer');
var cssnano = require('gulp-cssnano');
var jshint = require('gulp-jshint');
var uglify = require('gulp-uglify');
var imagemin = require('gulp-imagemin');
var rename = require('gulp-rename');
var concat = require('gulp-concat');
var notify = require('gulp-notify');
var cache = require('gulp-cache');
var livereload = require('gulp-livereload');
var del = require('del');
var uglify = require("gulp-uglify");
var format = require('gulp-format');
// 
var mainBowerFiles = require('gulp-main-bower-files');
var gulpFilter = require('gulp-filter');

// Styles
gulp.task('styles', function() {
	return sass('src/styles/main.scss', {
		style : 'expanded'
	}).pipe(autoprefixer('last 2 version')).pipe(gulp.dest('dist/css')).pipe(rename({
		suffix : '.min'
	})).pipe(cssnano()).pipe(gulp.dest('dist/css')).pipe(notify({
		message : 'Styles task complete'
	}));
});


// Scripts
gulp.task('jshint', function() {
	return gulp.src('src/js/**/*.js').pipe(jshint('.jshintrc')).pipe(jshint.reporter('default'));
});

// Scripts
gulp.task('scripts', function() {
	return gulp.src([ 'src/libs/vendor.js', 'src/js/main.js' ]).pipe(concat('main.js')).pipe(gulp.dest('dist/js')).pipe(rename({
		suffix : '.min'
	})).pipe(uglify()).pipe(gulp.dest('dist/js')).pipe(notify({
		message : 'Scripts task complete'
	}));
});


// Images
gulp.task('images', function() {
	return gulp.src('src/img/**/*').pipe(cache(imagemin({
		optimizationLevel : 3,
		progressive : true,
		interlaced : true
	}))).pipe(gulp.dest('dist/img')).pipe(notify({
		message : 'Images task complete'
	}));
});


// Clean
gulp.task('clean', function() {
	return del([ 'dist/css', 'dist/js', 'dist/img' ]);
});


// Default task
gulp.task('default', [ 'clean' ], function() {
	gulp.start('lib', 'styles', 'scripts', 'images');
});


//Clean
gulp.task('cleanlib', function() {
	return del([ 'src/lib/*' ]);
});


// Default task
gulp.task('lib', function() {
	gulp.start('cleanlib', 'main-bower-files');
});


gulp.task('main-bower-files', function() {

	var filterJS = gulpFilter('**/*.js', {
		restore : true
	});

	return gulp.src('./bower.json').pipe(mainBowerFiles({
		overrides : {
			bootstrap : {
				main : [ './dist/js/bootstrap.js', './dist/css/*.min.*', './dist/fonts/*.*' ]
			}
		}
	})).pipe(filterJS).pipe(concat('vendor.js')).pipe(filterJS.restore).pipe(gulp.dest('src/libs'));
});


// Watch
gulp.task('watch', function() {

	// Watch .scss files
	gulp.watch('src/styles/**/*.scss', [ 'styles' ]);

	// Watch .js files
	gulp.watch('src/js/**/*.js', [ 'scripts' ]);

	// Watch image files
	gulp.watch('src/img/**/*', [ 'images' ]);

	// Create LiveReload server
	livereload.listen();

	// Watch any files in dist/, reload on change
	gulp.watch([ 'dist/**' ]).on('change', livereload.changed);

});


/**

// including plugins
var fs = require('fs'), concat = require("gulp-concat"), header = require("gulp-header");


// Get copyright using NodeJs file system
var getCopyright = function() {
	return fs.readFileSync('Copyright');
};

// task
gulp.task('concat-copyright', function() {
	gulp.src('./JavaScript/*.js') // path to your files
	.pipe(concat('concat-copyright.js')) // concat and name it "concat-copyright.js"
	.pipe(header(getCopyright())).pipe(gulp.dest('path/to/destination'));
});


// task
gulp.task('minify-js', function() {
	gulp.src('./JavaScript/*.js') // path to your files
	.pipe(uglify()).pipe(gulp.dest('path/to/destination'));
});


//task
gulp.task('compile-sass', function() {
	gulp.src('./Sass/one.sass') // path to your file
	.pipe(sass()).pipe(gulp.dest('path/to/destination'));
});

// task
gulp.task('jsLint', function() {
	gulp.src('./JavaScript/*.js') // path to your files
	.pipe(jshint()).pipe(jshint.reporter()); // Dump results
});

//task
gulp.task('minify-html', function() {
	gulp.src('./Html/*.html') // path to your files
	.pipe(minifyHtml()).pipe(gulp.dest('path/to/destination'));
});


gulp.task('minify-css', function() {
	gulp.src('./Css/one.css') // path to your file
	.pipe(minifyCss()).pipe(gulp.dest('path/to/destination'));
});


// http://clang.llvm.org/docs/ClangFormatStyleOptions.html

gulp.task('check-format', function() {
	return gulp.src('gulp.js').pipe(format.check());
});

gulp.task('format', function() {
	return gulp.src('gulp.js').pipe(format());
});


*/
