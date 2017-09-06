var gulp = require('gulp'),
    gutil = require('gulp-util'),
    eslint = require('gulp-eslint'),
    phpcs = require('gulp-phpcs');

gulp.task('default', function () {
    return gulp.src('**/*.php')
        // Validate files using PHP Code Sniffer
        // TODO Exclude HtmlPurifier, etc
        .pipe(phpcs({
            bin: 'C:/Users/gunth/AppData/Roaming/Composer/vendor/bin/phpcs',
            standard: 'PSR1',
            warningSeverity: 0
        }))
        // Log all problems that was found
        .pipe(phpcs.reporter('log'));
});
/*
gulp.task('lint', function() {
    return gulp.src('*.js')
        .pipe(eslint({
            configFile: '.eslintrc'
        }))
        .pipe(eslint.format()) // Output till konsollen
        .pipe(eslint.failAfterError());
});
*/
/*
gulp.task('default', ['lint'], function () {
    gutil.log('Lint lyckades');
});
*/
