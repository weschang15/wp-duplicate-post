module.exports = function(api) {
  api.cache(true);
  const presets = [
    [
      "@babel/preset-env",
      {
        targets: {
          browsers: ["last 2 versions"]
        },
        forceAllTransforms: true,
        debug: false,
        modules: false,
        useBuiltIns: false
      }
    ]
  ];
  const plugins = ["@babel/plugin-proposal-object-rest-spread"];

  const env = {
    test: {
      presets: [["@babel/preset-env"]]
    }
  };

  return {
    env,
    presets,
    plugins
  };
};
