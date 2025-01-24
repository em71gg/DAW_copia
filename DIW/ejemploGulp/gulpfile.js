const gulp = require('gulp');
const sass = require('gulp-sass')(require('sass'));
const autoprefixer = require('gulp-autoprefixer');
const browserSync = require('browser-sync').create();

// Tarea de compilación de Sass
function compileSass() {
    return gulp.src('scss/**/*.scss')
        .pipe(sass().on('error', sass.logError))
        .pipe(autoprefixer())
        .pipe(gulp.dest('css'))
        .pipe(browserSync.stream());
}

// Tarea de vigilancia
function watchTask() {
    browserSync.init({
        server: {
            baseDir: './'
        }
    });

    gulp.watch('scss/**/*.scss', compileSass);
    gulp.watch('*.html').on('change', browserSync.reload);
}

// Exportar tareas
exports.default = gulp.series(compileSass, watchTask);
