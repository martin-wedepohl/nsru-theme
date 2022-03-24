// // Load Gulp...of course
const gulp = require('gulp');
const { src, dest, task, series, watch, parallel } = require('gulp');

// // Utility plugins
var notify       = require( 'gulp-notify' );
var image        = require( 'gulp-image' );

// Directories
var destDir      = '../../wp-content/themes/nsru-theme/';
var partialDir   = destDir + 'partials/';

// Style Sheets
var styleSRC     = './style.css';

// Root Files
var rootFiles    = ['./functions.php', './header.php', './LICENSE', './README.md', './style.css', 'virtual_ticket_template.php'];

// Partials
var partialFiles = ['./partials/**/*'];

// Images
var imageSRC     = './screenshot.png';
var imageURL     = destDir;

// Watches
var rootWatch    = rootFiles;
var partialWatch = partialFiles;
var imageWatch   = imageSRC;

function compress_images( done ) {
    src( imageSRC )
        .pipe(image())
        .pipe( dest( imageURL ) );
    done();
}

function copyRootFiles(done) {
    gulp.src(rootFiles, {allowEmpty: true})
        .pipe(gulp.dest(destDir));
    done();
}

function copyPartialFiles(done) {
    gulp.src(partialFiles, {allowEmpty: true})
        .pipe(gulp.dest(partialDir));
    done();
}

function watch_files() {
	watch( imageWatch, compress_images );
	watch( rootWatch, copyRootFiles );
	watch( partialWatch, copyPartialFiles );
	src( styleSRC )
		.pipe( notify({ message: 'Gulp is Watching, Happy Coding!' }) );
};

task("watch", series(parallel(compress_images, copyRootFiles, copyPartialFiles), watch_files));