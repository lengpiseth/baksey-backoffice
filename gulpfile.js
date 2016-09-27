var gulp        = require('gulp');
var gulpif      = require('gulp-if');
var sass        = require('gulp-sass');
var concat      = require('gulp-concat');
var uglify      = require('gulp-uglify');
var minify      = require('gulp-clean-css');
var sourcemaps  = require('gulp-sourcemaps');
var del         = require('del');

var params = {
    js: {
        watch: [
            'app/Resources/themes/javascript/**/*.js'
        ],
        src : {
            backend: [
                'app/Resources/themes/javascript/backend/**/*.js'
            ]
        },
        dest: 'web/assets/js',
        file: {
            backend: 'backend.js',
            frontend: 'frontend.js'
        },
        clean: true,
        concat: true,
        minify: true
    },
    sass: {
        watch: [
            'app/Resources/themes/scss/**/*.scss',
            'app/Resources/themes/packages/**/*.css'
        ],
        src: {
            frontend: [
                'app/Resources/themes/scss/frontend/import.scss',
                'app/Resources/themes/scss/frontend/**/_*.scss',
                '!app/Resources/themes/scss/frontend/__*.scss'
            ],
            backend: [
                'app/Resources/themes/scss/backend/import.scss',
                'app/Resources/themes/scss/backend/**/_*.scss',
                '!app/Resources/themes/scss/backend/__*.scss'
            ]
        },
        dest: 'web/assets/css',
        destMap: 'web/assets/css/map',
        file: {
            backend: 'backend.css',
            frontend: 'frontend.css'
        },
        clean: true,
        concat: true,
        minify: true
    },
    vendor: {
        css_security:{
            src: [
                'bower_components/font-awesome/css/font-awesome.css',
                'bower_components/bootstrap/dist/css/bootstrap.css',
                'bower_components/adminlte/dist/css/AdminLTE.css',
                'bower_components/adminlte/dist/css/skins/_all-skins.css'
            ],
            dest: 'web/assets/css',
            file: 'security-vendor.css',
            clean: true,
            concat: true,
            minify: false
        },
        css:{
            src: [
                'bower_components/font-awesome/css/font-awesome.css',
                'bower_components/bootstrap/dist/css/bootstrap.css',
                'bower_components/adminlte/plugins/datatables/dataTables.bootstrap.css',
                'bower_components/adminlte/plugins/datatables/extensions/Responsive/css/dataTables.responsive.css',
                'bower_components/adminlte/dist/css/AdminLTE.css',
                'bower_components/adminlte/dist/css/skins/_all-skins.css'
            ],
            dest: 'web/assets/css',
            file: 'backend-vendor.css',
            clean: true,
            concat: true,
            minify: false
        },
        js: {
            src: {
                frontend: [],
                backend: [
                    'bower_components/jquery/dist/jquery.js',
                    'bower_components/jquery-ui/jquery-ui.js',
                    'bower_components/jquery-cookie/jquery.cookie.js',
                    'bower_components/devbridge-autocomplete/src/jquery.autocomplete.js',
                    'bower_components/bootstrap/dist/js/bootstrap.js',
                    'bower_components/adminlte/plugins/datatables/jquery.dataTables.js',
                    'bower_components/adminlte/plugins/datatables/dataTables.bootstrap.js',
                    'bower_components/adminlte/plugins/datatables/extensions/Responsive/js/dataTables.responsive.js',
                    'bower_components/dropzone/dist/dropzone.js',
                    'bower_components/adminlte/dist/js/app.js'
                ]
            },
            dest: 'web/assets/js',
            file: 'backend-vendor.js',
            clean: true,
            concat: true,
            minify: true
        }
    },
    font: {
        src : [
            'bower_components/font-awesome/fonts/FontAwesome*.*'
        ],
        dest: 'web/assets/fonts'
    }
};

gulp.task('js-backend', function () {
    var task;
    task = gulp.src(params.js.src.backend).pipe(sourcemaps.init());
    task = (params.js.concat) ? task.pipe(concat(params.js.file.backend)) : task;
    task
        .pipe(uglify())
        .pipe(sourcemaps.write('./map'))
        .pipe(gulp.dest(params.js.dest));
});

gulp.task('watch-js', function () {
    gulp.watch(params.js.watch, ['js-backend', 'js-vendor-backend']);
});

gulp.task('sass-frontend', function () {
    var task;
    task = gulp.src(params.sass.src.frontend);
    task = (params.sass.concat) ? task.pipe(concat(params.sass.file.frontend)) : task;
    task
        .pipe(sass({errLogToConsole:true}))
        .pipe(minify())
        .pipe(gulp.dest(params.sass.dest));
});

gulp.task('sass-backend', function () {
    var task;
    task = gulp.src(params.sass.src.backend);
    task = (params.sass.concat) ? task.pipe(concat(params.sass.file.backend)) : task;
    task
        .pipe(sourcemaps.init())
        .pipe(sass({errLogToConsole:true}))
        .pipe(gulpif(params.sass.minify, minify()))
        .pipe(gulpif(params.sass.minify, sourcemaps.write('./map')))
        .pipe(gulp.dest(params.sass.dest));
});

gulp.task('watch-sass', function () {
    gulp.watch(params.sass.watch, ['sass-frontend', 'sass-backend']);
});

gulp.task('vendor-css', function () {
    var task;
    task = gulp.src(params.vendor.css.src);
    task = (params.vendor.css.concat) ? task.pipe(concat(params.vendor.css.file)) : task;
    task
        .pipe(gulpif(params.vendor.css.minify, minify()))
        .pipe(gulp.dest(params.vendor.css.dest));
});

gulp.task('vendor-css-security', function () {
    var task;
    task = gulp.src(params.vendor.css_security.src);
    task = (params.vendor.css_security.concat) ? task.pipe(concat(params.vendor.css_security.file)) : task;
    task
        .pipe(gulpif(params.vendor.css_security.minify, minify()))
        .pipe(gulp.dest(params.vendor.css_security.dest));
});

gulp.task('js-vendor-backend', function () {
    var task;
    task = gulp.src(params.vendor.js.src.backend);
    task = (params.vendor.js.concat) ? task.pipe(concat(params.vendor.js.file)) : task;
    task
        .pipe(uglify())
        .pipe(gulp.dest(params.vendor.js.dest));
});

gulp.task('watch-all', [
    'watch-sass',
    'watch-js'
]);

gulp.task('font', function () {
    gulp.src(params.font.src).pipe(gulp.dest(params.font.dest));
});

gulp.task('delete-app-cache', function () {
    del('app/cache').then(function (e) {
        console.log("Cache folder deleted");
    });
});

gulp.task('default', [
    'sass-backend',
    'sass-frontend',
    'vendor-css',
    'js-vendor-backend',
    'font'
]);