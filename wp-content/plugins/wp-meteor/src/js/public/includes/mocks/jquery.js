import dispatcher from "../utils/dispatcher";
import delta from "../utils/delta";
const c = process.env.DEBUG ? console.log : () => {
};
const d = document;
const DCL = "DOMContentLoaded";
export default class jQueryMock {
  constructor() {
    this.known = [];
  }
  init() {
    let Mock;
    let Mock$;
    let loaded = false;
    const override = (jQuery) => {
      if (!loaded && jQuery && jQuery.fn && !jQuery.__wpmeteor) {
        process.env.DEBUG && c(delta(), "new jQuery detected", jQuery);
        const enqueue = function(func) {
          process.env.DEBUG && c(delta(), "enqueued jQuery(func)", func);
          d.addEventListener(DCL, (e) => {
            process.env.DEBUG && c(delta(), "running enqueued jQuery function", func);
            func.bind(d)(jQuery, e, "jQueryMock");
          });
          return this;
        };
        this.known.push([jQuery, jQuery.fn.ready, jQuery.fn.init.prototype.ready]);
        jQuery.fn.ready = enqueue;
        jQuery.fn.init.prototype.ready = enqueue;
        jQuery.__wpmeteor = true;
      }
      return jQuery;
    };
    if (window.jQuery) {
      Mock = override(window.jQuery);
    }
    Object.defineProperty(window, "jQuery", {
      get() {
        return Mock;
      },
      set(jQuery) {
        Mock = override(jQuery);
      }
    });
    Object.defineProperty(window, "$", {
      get() {
        return Mock$;
      },
      set($) {
        Mock$ = override($);
      }
    });
    dispatcher.on("l", () => loaded = true);
  }
  unmock() {
    this.known.forEach(([jQuery, oldReady, oldPrototypeReady]) => {
      process.env.DEBUG && c(delta(), "unmocking jQuery", jQuery);
      jQuery.fn.ready = oldReady;
      jQuery.fn.init.prototype.ready = oldPrototypeReady;
    });
  }
}
//# sourceMappingURL=jquery.js.map
