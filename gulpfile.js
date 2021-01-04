var gulp = require('gulp');
var uglify = require('gulp-uglify');
var concat = require('gulp-concat');
var minifyCSS = require('gulp-minify-css');
const image = require('gulp-image');
var mainBowerFiles = require('gulp-main-bower-files');
var gulpFilter = require('gulp-filter');

/** Gulp admin **/
// css minify-css 
gulp.task('task-back-lib-styles', function () {
    var filterCSS = gulpFilter('**/*.css', {restore: true});
    gulp.src('bower.json')
        .pipe(mainBowerFiles({group: ['admin']}))
        .pipe(filterCSS)
        .pipe(concat('lib-styles.css'))
        .pipe(minifyCSS())
        .pipe(gulp.dest('web/assets/css'));
});
gulp.task('task-back-styles', function () {
    return gulp.src([
            
            'web/dev/back/css/components.min.css',
            'web/dev/back/css/layout.min.css',
            'web/dev/back/css/darkblue.min.css'
            
        ])
        .pipe(minifyCSS())
        .pipe(concat('style-back.min.css'))
        .pipe(gulp.dest('web/assets/css'))
});
gulp.task('task-styles-login', function () {
    return gulp.src([
            'web/dev/back/css/login-5.min.css'
        ])
        .pipe(minifyCSS())
        .pipe(concat('style-login.min.css'))
        .pipe(gulp.dest('web/assets/css'))
});

// Concatenate & Minify JS
gulp.task('task-back-lib-scripts', function () {
    var filterJS = gulpFilter('**/*.js', {restore: true});
    gulp.src('bower.json')
        .pipe(mainBowerFiles({group: ['admin']}))
        .pipe(filterJS)
        .pipe(concat('lib-back.min.js'))
        .pipe(uglify())
        .pipe(gulp.dest('web/assets/js'))
});
gulp.task('task-back-script', function () {
    return gulp.src([
            'web/dev/back/js/dataTables.bootstrap.js',
            'web/dev/back/js/config-dataTable.js',
            'web/dev/back/js/layout.min.js',
            'web/dev/back/js/config.js',
            'web/dev/back/js/jquery.maskedinput.min.js',
            'web/dev/back/js/app.min.js'
        ])
        .pipe(concat('script-back.min.js'))
        .pipe(uglify())
        .pipe(gulp.dest('web/assets/js'));
});

/** End Gulp admin **/

/** Gupl Front**/

// css minify-css
gulp.task('task-front-lib-styles', function () {
    var filterCSS = gulpFilter('**/*.css', {restore: true});
    gulp.src('bower.json')
        .pipe(mainBowerFiles({group: ['frontend']}))
        .pipe(filterCSS)
        .pipe(concat('front-lib-styles.css'))
        .pipe(minifyCSS())
        .pipe(gulp.dest('web/assets/css'));
});
gulp.task('task-front-styles', function () {
    return gulp.src([
            'web/dev/front/css/scrollPress.min.css',
            'web/dev/front/css/styles.css'
        ])
        .pipe(minifyCSS())
        .pipe(concat('style-front.min.css'))
        .pipe(gulp.dest('web/assets/css'))
});

// Concatenate & Minify JS
gulp.task('task-front-lib-scripts', function () {
    var filterJS = gulpFilter('**/*.js', {restore: true});
    gulp.src('bower.json')
        .pipe(mainBowerFiles({group: ['frontend' ]}))
        .pipe(filterJS)
        .pipe(concat('lib-front.min.js'))
        .pipe(uglify())
        .pipe(gulp.dest('web/assets/js'))
});
gulp.task('task-front-fancybox', function () {
    var filterJS = gulpFilter('**/*.js', {restore: true});
    gulp.src('bower.json')
        .pipe(mainBowerFiles({group: [ 'frontend-fancybox' ]}))
        .pipe(filterJS)
        .pipe(concat('front-fancybox.min.js'))
        .pipe(uglify())
        .pipe(gulp.dest('web/assets/js'))
});
gulp.task('task-front-script', function () {
    return gulp.src([
            'web/dev/front/js/scrollPress.min.js',
            'web/dev/front/js/main.js'
             ])
        .pipe(concat('script-front.min.js'))
        .pipe(uglify())
        .pipe(gulp.dest('web/assets/js'));
});
/** End Gupl Front**/
// image  Optimize PNG, JPG, GIF, SVG images
gulp.task('image', function () {
    gulp.src(
        [   'web/dev/back/images/*',
            'web/dev/front/images/*'
        ]
        )
        .pipe(image())
        .pipe(gulp.dest('web/assets/images'));
});
// Fonts
gulp.task('fonts', function () {
    return gulp.src([
            'web/dev/front/fonts/*.*',
            'web/bower_components/simple-line-icons/fonts/*.*',
            'web/bower_components/components-font-awesome/fonts/*.*',
            'web/bower_components/bootstrap/dist/fonts/*.*'
        ])
        .pipe(gulp.dest('web/assets/fonts'));
});
// Default Task
gulp.task('default', [
                        /**** Style front and back ****/

                      'task-styles-login',
                      'task-back-styles',
                      'task-back-lib-styles',
                      'task-front-lib-styles',
                      'task-front-styles',

                         /**** End Style front and back ****/

                         /**** script front and back ****/

                      'task-front-lib-scripts',
                      'task-front-script',
                      'task-back-lib-scripts',
                      'task-back-script',

                         /**** End script front and back ****/
                      'image',
                      'fonts'
                    ]);