export const styled = (styles = {}) => {
  return Object.keys(styles)
    .map(key => `${key}:${styles[key]}`)
    .join(";");
};
