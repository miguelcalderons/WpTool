var gulp        = require('gulp');
var sass        = require('gulp-sass');

function style() {
  return gulp
    .src(paths.styles.src)
    .pipe(sass())
    .on("error", sass.logError)
    .pipe(gulp.dest(paths.styles.dest));
}

exports.style = style;


function watch() {
  style();

  gulp.watch(paths.styles.src, style);
}

exports.watch = watch


var paths = {
  styles: {
    src: "sass/**/*.scss",
    dest: "styles"
  }

};