'use strict';

var gulp = require('gulp');
var sass = require('gulp-sass');
var cssnano = require('gulp-cssnano');
var sourcemaps = require('gulp-sourcemaps');
var autoprefixer = require('gulp-autoprefixer');

var input = 'resources/scss/**/*.scss';
var output = 'public_html/css';

var sassOptions = {
    errLogToConsole: true,
    outputStyle: 'expanded'
};

gulp.task('workflow', function () {
    return gulp
        // Find all `.scss` files from the `stylesheets/` folder
        .src(input)

        .pipe(sourcemaps.init())

        .pipe(sass(sassOptions).on('error', sass.logError))

        .pipe(autoprefixer({
            browsers: ['last 2 versions'],
            cascade: false
        }))

        .pipe(cssnano())

        .pipe(sourcemaps.write('./'))

        // Write the resulting CSS in the output folder
        .pipe(gulp.dest(output));
});

gulp.task('watch', function() {
    return gulp
    // Watch the input folder for change,
    // and run `sass` task when something happens
        .watch(input, ['workflow'])
        // When there is a change,
        // log a message in the console
        .on('change', function(event) {
            console.log('File ' + event.path + ' was ' + event.type + ', running tasks...');
        });
});

gulp.task('default', ['workflow', 'watch']);