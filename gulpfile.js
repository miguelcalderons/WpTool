const gulp = require('gulp');
const sass = require('gulp-sass')(require('sass'));

const paths = {
  styles: {
    src: "sass/**/*.scss",
    dest: "styles"
  }
};

function style() {
  return gulp
    .src(paths.styles.src)
    .pipe(sass().on("error", sass.logError))
    .pipe(gulp.dest(paths.styles.dest));
}

function watch() {
  style();
  gulp.watch(paths.styles.src, style);
}

exports.style = style;
exports.watch = watch;
exports.default = style;
