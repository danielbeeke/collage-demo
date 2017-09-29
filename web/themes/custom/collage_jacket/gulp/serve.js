'use strict';

var gulp = require('gulp');
var reload = global.browserSync.reload;
var config = require('../config.json');

gulp.task('serve', ['css'], function () {
    global.browserSync.init({
        proxy: config.environment
        // ghostMode: false
    });

    gulp.watch([global.paths.js]).on("change", reload);
    gulp.watch([global.paths.scss],['css']);
});