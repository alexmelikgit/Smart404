const gulp = require('gulp');
const sass = require('gulp-sass')(require('sass'));
const minCss = require("gulp-clean-css");
const rename = require("gulp-rename");
const prefixer = require("gulp-autoprefixer");
const uglify = require("gulp-uglify");
const pipeline = require('readable-stream').pipeline;
const { reload } = require('browser-sync');
const browserSync = require("browser-sync").create();
const concat = require("gulp-concat");
const { dest } = require('gulp');
const sourcemap = require('gulp-sourcemaps')
const ts = require("gulp-typescript");

gulp.task('concat', (done) =>{
    gulp.src('./assets/css/dist/**/*.css')
        .pipe(concat('styles.css'))
        .pipe(gulp.dest('./assets/css/dist/'))

    done();
})


gulp.task('styles', (done)=>{
    return gulp.src(['assets/css/src/**/*.scss', '!assets/css/src/**/*-nosource.scss'])
        .pipe(sourcemap.init())
        .pipe(sass().on('error', sass.logError))
        .pipe(prefixer())
        .pipe(minCss())
        .pipe(concat("all-styles.css"))
        .pipe(sourcemap.write())
        .pipe(gulp.dest('./assets/css/dist/'))

    done()
})

gulp.task('minjs', (done) =>{

    return gulp.src(['assets/js/src/**/*.js', '!assets/js/src/**/*-nosource.js'])
        .pipe(sourcemap.init())
        .pipe(uglify())
        .pipe(sourcemap.write())
        .pipe(gulp.dest('assets/js/dist'))

    done()
})
gulp.task('tscomp', ()=>{
    return gulp.src("assets/js/src/**/*.ts")
        .pipe(ts({
            noImplicitAny: true,
            module: "es2015",
            lib: ["dom", "es6"],
            noImplicitAny: true,
            sourceMap: true,
            outDir: "dist",
            baseUrl: ".",
            moduleResolution: "node"
        }))
        .pipe(gulp.dest("assets/js/dist/"))
})

gulp.task('watch', (done)=>{
    gulp.watch('assets/css/src/**/*.scss', (done) => {
        gulp.series(['styles'])(done);
    });
    gulp.watch('assets/js/src/**/*.js', (done) => {
        gulp.series(['minjs'])(done);
    });
    gulp.watch("assets/js/src/**/*.ts" , (done) =>{
        gulp.series(["tscomp"])(done)
    })
    done();
})



