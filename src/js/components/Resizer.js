const Resizer = (function() {
  let callbacks = [];
  let running = false;

  const _resize = () => {
    if (!running) {
      running = true;
      if (window.requestAnimationFrame) {
        window.requestAnimationFrame(_run);
      } else {
        setTimeout(_run, 66);
      }
    }
  };

  const _run = () => {
    callbacks.forEach(cb => {
      cb();
    });

    running = false;
  };

  const _add = cb => {
    if (!cb) {
      throw new Error("A callback has not been provided");
    }

    callbacks.push(cb);
  };

  return {
    add: cb => {
      if (!callbacks.length) window.addEventListener("resize", _resize);
      _add(cb);
    }
  };
})();

export default Resizer;
