const { series, parallel, src, dest, watch } = require("gulp");
const { log } = console;

const plugins = require("gulp-load-plugins");
const config = require("./gulp-config");
const $ = plugins({
  pattern: ["*"],
  scope: ["devDependencies"]
});

const server = $.browserSync.create();
const { production, development } = $.environments;
const { unoptimized, js, php, proxy, sass } = config;
const gulpUglify = $.uglifyEs.default;

const clean = async () => await src(unoptimized).pipe($.clean());

const javascript = cb => {
  $.pump(
    [
      src(js.entries),
      development($.sourcemaps.init()),
      $.betterRollup(
        {
          plugins: [
            $.rollupPluginNodeResolve(),
            $.rollupPluginCommonjs(),
            $.rollupPluginBabel({
              runtimeHelpers: true
            })
          ]
        },
        {
          format: "iife"
        }
      ),
      $.include().on("error", log),
      production(gulpUglify()),
      $.rename(path => {
        path.basename = $.dashify(path.basename);
        path.extname = production() ? ".min.js" : ".js";
      }),
      $.size({
        showFiles: true
      }),
      development($.sourcemaps.write("")),
      dest(js.dest),
      $.browserSync.stream()
    ],
    cb
  );
};

const styles = async () =>
  await src(sass.src)
    .pipe($.plumber())
    .pipe(
      $.sass({
        importer: $.sassModuleImporter(),
        outputStyle: "expanded"
      }).on("error", $.sass.logError)
    )
    .pipe(
      $.autoprefixer({
        browsers: ["last 2 versions"],
        cascade: false
      })
    )
    .pipe(
      production(
        $.cleanCss(
          {
            compatibility: "ie9",
            debug: true,
            level: {
              2: {
                restructureRules: true
              }
            }
          },
          details => {
            const {
              name,
              stats: { originalSize, minifiedSize }
            } = details;

            log(
              `${name} - Original size: ${originalSize} | Minified size: ${minifiedSize}`
            );
          }
        )
      )
    )
    .pipe(
      $.rename(path => {
        path.extname = production() ? ".min.css" : ".css";
      })
    )
    .pipe(dest(sass.dest))
    .pipe($.browserSync.stream());

const reload = async () => {
  await server.reload();
};

const watcher = cb => {
  watch([sass.src, "src/sass/**/*.scss"], styles);
  watch(js.src, javascript);
  watch(php.src).on("change", reload);
};

const serve = cb => {
  $.browserSync.init({
    files: php.src,
    proxy: proxy,
    open: false,
    notify: true
  });
  cb();
};

module.exports = {
  dev: series(parallel(styles, javascript), serve, watcher),
  build: series(parallel(styles, javascript), clean)
};
