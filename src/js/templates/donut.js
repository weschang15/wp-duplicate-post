import { styled } from "../deps/helpers";

const donut = (styles = {}) => {
  const defaults = {
    display: "inline-block",
    border: "4px solid rgba(0, 0, 0, 0.1)",
    "border-left-color": "#4e4084",
    "border-radius": "50%",
    height: "30px",
    width: "30px",
    "vertical-align": "middle"
  };

  const updated = { ...defaults, ...styles };

  return `<span style="${styled(updated)}" class="donut"></span>`;
};

export default donut;
