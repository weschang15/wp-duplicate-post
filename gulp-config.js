const project = "./";
const src = "src";
const dest = "public";
const proxy = "test.dev";

module.exports = {
  unoptimized: [
    `${dest}/css/*.css`,
    `!${dest}/css/*.min.css`,
    `${dest}/js/*.js`,
    `${dest}/js/*.js.map`,
    `!${dest}/js/*.min.js`
  ],
  css: {
    dest: `${dest}/css/`
  },
  images: {
    src: `${project}/images/**`,
    dest: `${dest}/images`
  },
  js: {
    entries: [
      `${src}/js/*.js`,
      `!${src}/js/helpers/`,
      `!${src}/js/components/`,
      `!${src}/js/deps/`,
      `!${src}/js/templates/`
    ],
    src: `${src}/js/**/*.js`,
    dest: `${dest}/js`
  },
  php: {
    src: "**/*.php"
  },
  project: project,
  proxy: proxy,
  sass: {
    src: `${src}/sass/**/*.sass`,
    dest: `${dest}/css`
  }
};
