// // Load Gulp...of course
const { src, dest, task, series, watch, parallel } = require('gulp');

// // CSS related plugins
var sass         = require( 'gulp-sass' );
var autoprefixer = require( 'gulp-autoprefixer' );

// // JS related plugins
var uglify       = require( 'gulp-uglify' );
var babelify     = require( 'babelify' );
var browserify   = require( 'browserify' );
var source       = require( 'vinyl-source-stream' );
var buffer       = require( 'vinyl-buffer' );
var stripDebug   = require( 'gulp-strip-debug' );

// // Utility plugins
var rename       = require( 'gulp-rename' );
var sourcemaps   = require( 'gulp-sourcemaps' );
var notify       = require( 'gulp-notify' );
var options      = require( 'gulp-options' );
var gulpif       = require( 'gulp-if' );
var image        = require( 'gulp-image' );

// // Browers related plugins
var browserSync  = require( 'browser-sync' ).create();

// // Project related variables
var projectURL   = 'https://north-shore-round-up.local/';

// Style Sheets
var styleSRC     = 'src/scss/style.scss';
//var styleForm    = 'src/scss/form.scss';
//var styleSlider  = 'src/scss/slider.scss';
//var styleAuth    = 'src/scss/auth.scss';
var styleURL     = './dist/css/';
var mapURL       = './';

// Javascript
var jsSRC        = 'src/js/';
var jsAdmin      = 'script.js';
//var jsIndex      = 'index.js';
//var jsMutation   = 'mutation.js';
var jsFiles      = [jsAdmin/*, jsIndex, jsMutation*/];
var jsURL        = './dist/js/';

// Images
//var imageSRC     = 'src/images/*';
//var imageURL     = './dist/images/';

// Watches
var styleWatch   = 'src/scss/**/*.scss';
var jsWatch      = 'src/js/**/*.js';
var phpWatch     = './**/*.php';
var htmlWatch    = './**/*.html';
var htmWatch     = './**/*.htm';
//var imageWatch   = 'src/images/**/*';

function css(done) {
	src([styleSRC/*, styleForm, styleSlider, styleAuth*/])
		.pipe( sass({
			errLogToConsole: true,
			outputStyle: 'expanded'
		}) )
		.pipe( dest( styleURL ) )   // If want to write uncompressed file
		.pipe( sourcemaps.init() )
		.pipe( sass({
			errLogToConsole: true,
			outputStyle: 'compressed'
		}) )
		.on( 'error', console.error.bind( console ) )
		.pipe( autoprefixer({ browsers: [ 'last 2 versions', '> 5%', 'Firefox ESR' ] }) )
        .pipe( rename( { suffix: '.min' } ) )
		.pipe( sourcemaps.write( mapURL ) )
		.pipe( dest( styleURL ) )
		.pipe( browserSync.stream() );
	done();
}

function js(done) {
	jsFiles.map(function (entry) {
		return browserify({
			entries: [jsSRC + entry]
		})
		.transform( babelify, { presets: [ '@babel/preset-env' ] } )
		.bundle()
		.pipe( source( entry ) )
		.pipe( buffer() )
		.pipe( gulpif( options.has( 'production' ), stripDebug() ) )
		.pipe( dest( jsURL ) )      // If want to write uncompressed file
		.pipe( sourcemaps.init({ loadMaps: true }) )
		.pipe( uglify() )
        .pipe( rename( { suffix: '.min' } ) )
		.pipe( sourcemaps.write( '.' ) )
		.pipe( dest( jsURL ) )
		.pipe( browserSync.stream() );
	});
	done();
}

//function compress_images( done ) {
//    src( imageSRC )
//        .pipe(image())
//        .pipe( dest( imageURL ) );
//    done();
//}

function reload(done) {
	browserSync.reload();
	done();
}

function browser_sync(done) {
	browserSync.init({
		proxy: projectURL,
		https: {
			key: '/Users/Martin/AppData/Roaming/Local by Flywheel/routes/certs/north-shore-round-up.local.key',
			cert: '/Users/Martin/AppData/Roaming/Local by Flywheel/routes/certs/north-shore-round-up.local.crt'
		},
		injectChanges: true,
		open: false
	});
    done();
}

function watch_files() {
//	watch( imageWatch, reload );
	watch( phpWatch, reload );
	watch( htmlWatch, reload );
	watch( htmWatch, reload );
	watch( styleWatch, css );
	watch( jsWatch, series( js, reload ) );
	src( styleSRC )
		.pipe( notify({ message: 'Gulp is Watching, Happy Coding!' }) );
};

task("css", css);
task("js", js);
//task("compress_images", compress_images);
//task("default", series(css, js, compress_images));
task("default", series(css, js));
task("watch", parallel(browser_sync, watch_files));