(() => {
  // node_modules/@aguidrevitch/fpo-inpage-wp-meteor/src/browser/includes/utils/event-emitter.js
  var EventEmitter = class {
    constructor() {
      this.l = [];
    }
    emit(name, data = null) {
      this.l[name] && this.l[name].forEach((l) => l(data));
    }
    on(name, callback) {
      this.l[name] ||= [];
      this.l[name].push(callback);
    }
    off(name, callback) {
      this.l[name] = (this.l[name] || []).filter((c5) => c5 !== callback);
    }
    /*
    once(name, callback) {
        const closure = () => {
            this.off(closure);
            callback();
        }
        this.l[name] ||= [];
        this.l[name].push(closure);
    }
    */
  };

  // node_modules/@aguidrevitch/fpo-inpage-wp-meteor/src/browser/includes/utils/dispatcher.js
  var dispatcher_default = new EventEmitter();

  // node_modules/@aguidrevitch/fpo-inpage-wp-meteor/src/browser/includes/utils/delta.js
  var delta_default = () => Math.round(performance.now()) / 1e3;

  // node_modules/@aguidrevitch/fpo-inpage-wp-meteor/src/browser/includes/mocks/jquery.js
  var c = true ? console.log : () => {
  };
  var d = document;
  var DCL = "DOMContentLoaded";
  var jQueryMock = class {
    constructor() {
      this.known = [];
    }
    init() {
      let Mock;
      let Mock$;
      let loaded = false;
      const override = (jQuery2) => {
        if (!loaded && jQuery2 && jQuery2.fn && !jQuery2.__wpmeteor) {
          c(delta_default(), "new jQuery detected", jQuery2);
          const enqueue = function(func) {
            c(delta_default(), "enqueued jQuery(func)", func);
            d.addEventListener(DCL, (e) => {
              c(delta_default(), "running enqueued jQuery function", func);
              func.bind(d)(jQuery2, e, "jQueryMock");
            });
            return this;
          };
          this.known.push([jQuery2, jQuery2.fn.ready, jQuery2.fn.init.prototype.ready]);
          jQuery2.fn.ready = enqueue;
          jQuery2.fn.init.prototype.ready = enqueue;
          jQuery2.__wpmeteor = true;
        }
        return jQuery2;
      };
      if (window.jQuery) {
        Mock = override(window.jQuery);
      }
      Object.defineProperty(window, "jQuery", {
        get() {
          return Mock;
        },
        set(jQuery2) {
          Mock = override(jQuery2);
        }
        // configurable: true
      });
      Object.defineProperty(window, "$", {
        get() {
          return Mock$;
        },
        set($) {
          Mock$ = override($);
        }
        // configurable: true
      });
      dispatcher_default.on("l", () => loaded = true);
    }
    unmock() {
      this.known.forEach(([jQuery2, oldReady, oldPrototypeReady]) => {
        c(delta_default(), "unmocking jQuery", jQuery2);
        jQuery2.fn.ready = oldReady;
        jQuery2.fn.init.prototype.ready = oldPrototypeReady;
      });
    }
  };

  // node_modules/@aguidrevitch/fpo-inpage-wp-meteor/src/browser/includes/utils/listener-options.js
  var listenerOptions = {};
  ((w4, p) => {
    try {
      const opts = Object.defineProperty({}, p, {
        get: function() {
          return listenerOptions[p] = true;
        }
      });
      w4.addEventListener(p, null, opts);
      w4.removeEventListener(p, null, opts);
    } catch (e) {
    }
  })(window, "passive");
  var listener_options_default = listenerOptions;

  // node_modules/@aguidrevitch/fpo-inpage-wp-meteor/src/browser/includes/utils/interaction-events.js
  var c2 = true ? console.log : () => {
  };
  var w = window;
  var d2 = document;
  var a = "addEventListener";
  var r = "removeEventListener";
  var ra = "removeAttribute";
  var ga = "getAttribute";
  var sa = "setAttribute";
  var DCL2 = "DOMContentLoaded";
  var interactionEvents = ["mouseover", "keydown", "touchmove", "touchend", "wheel"];
  var captureEvents = ["mouseover", "mouseout", "touchstart", "touchmove", "touchend", "click"];
  var prefix = "data-wpmeteor-";
  var separator = "----";
  var InteractionEvents = class {
    init() {
      let firstInteractionFired2 = false;
      let firstInteractionTimeout = false;
      const onFirstInteraction = (e) => {
        c2(delta_default(), separator, "firstInteraction event MAYBE fired", (e || {}).type);
        if (!firstInteractionFired2) {
          c2(delta_default(), separator, "firstInteraction fired");
          firstInteractionFired2 = true;
          c2(delta_default(), separator, "firstInteraction event listeners removed");
          interactionEvents.forEach((event) => d2.body[r](event, onFirstInteraction, listener_options_default));
          clearTimeout(firstInteractionTimeout);
          dispatcher_default.emit("fi");
        }
      };
      const synteticCick = (e) => {
        c2(delta_default(), "creating syntetic click event for", e);
        const event = new MouseEvent("click", {
          view: e.view,
          bubbles: true,
          cancelable: true
        });
        Object.defineProperty(event, "target", { writable: false, value: e.target });
        return event;
      };
      dispatcher_default.on("i", () => {
        if (!firstInteractionFired2) {
          onFirstInteraction();
        }
      });
      const capturedEvents = [];
      const captureEvent = (e) => {
        if (e.target && "dispatchEvent" in e.target) {
          c2(delta_default(), "captured", e.type, e.target);
          if (e.type === "click") {
            e.preventDefault();
            e.stopPropagation();
            capturedEvents.push(synteticCick(e));
          } else if (e.type !== "touchmove") {
            capturedEvents.push(e);
          }
          e.target[sa](prefix + e.type, true);
        }
      };
      dispatcher_default.on("l", () => {
        c2(delta_default(), separator, "removing mouse event listeners");
        captureEvents.forEach((name) => w[r](name, captureEvent));
        let e;
        while (e = capturedEvents.shift()) {
          var target = e.target;
          if (target[ga](prefix + "touchstart") && target[ga](prefix + "touchend") && !target[ga](prefix + "click")) {
            if (target[ga](prefix + "touchmove")) {
              c2(delta_default(), " touchmove happened, so not dispatching click to ", e.target);
            } else {
              target[ra](prefix + "touchmove");
              capturedEvents.push(synteticCick(e));
            }
            target[ra](prefix + "touchstart");
            target[ra](prefix + "touchend");
          } else {
            target[ra](prefix + e.type);
          }
          c2(delta_default(), " dispatching " + e.type + " to ", e.target);
          target.dispatchEvent(e);
        }
      });
      const installFirstInteractionListeners = () => {
        c2(delta_default(), separator, "installing firstInteraction listeners");
        interactionEvents.forEach((event) => d2.body[a](event, onFirstInteraction, listener_options_default));
        c2(delta_default(), separator, "installing mouse event listeners");
        captureEvents.forEach((name) => w[a](name, captureEvent));
        d2[r](DCL2, installFirstInteractionListeners);
      };
      d2[a](DCL2, installFirstInteractionListeners);
    }
  };

  // node_modules/@aguidrevitch/fpo-inpage-wp-meteor/src/browser/includes/elementor/device-mode.js
  var d3 = document;
  var $deviceMode = d3.createElement("span");
  $deviceMode.setAttribute("id", "elementor-device-mode");
  $deviceMode.setAttribute("class", "elementor-screen-only");
  var attached = false;
  var device_mode_default = () => {
    if (!attached) {
      d3.body.appendChild($deviceMode);
    }
    return getComputedStyle($deviceMode, ":after").content.replace(/"/g, "");
  };

  // node_modules/@aguidrevitch/fpo-inpage-wp-meteor/src/browser/includes/elementor/animations.js
  var w2 = window;
  var d4 = document;
  var de = d4.documentElement;
  var c3 = true ? console.log : () => {
  };
  var ga2 = "getAttribute";
  var sa2 = "setAttribute";
  var getClass = (el) => {
    return el[ga2]("class") || "";
  };
  var setClass = (el, value) => {
    return el[sa2]("class", value);
  };
  var animations_default = () => {
    window.addEventListener("load", function() {
      const mode = device_mode_default();
      const vw = Math.max(de.clientWidth || 0, w2.innerWidth || 0);
      const vh = Math.max(de.clientHeight || 0, w2.innerHeight || 0);
      const keys = ["_animation_" + mode, "animation_" + mode, "_animation", "_animation", "animation"];
      Array.from(d4.querySelectorAll(".elementor-invisible")).forEach((el) => {
        const viewportOffset = el.getBoundingClientRect();
        if (viewportOffset.top + w2.scrollY <= vh && viewportOffset.left + w2.scrollX < vw) {
          try {
            const settings = JSON.parse(el[ga2]("data-settings"));
            if (settings.trigger_source) {
              return;
            }
            const animationDelay = settings._animation_delay || settings.animation_delay || 0;
            let animation, key;
            for (var i2 = 0; i2 < keys.length; i2++) {
              if (settings[keys[i2]]) {
                key = keys[i2];
                animation = settings[key];
                break;
              }
            }
            if (animation) {
              c3(delta_default(), "animating with" + animation, el);
              const oldClass = getClass(el);
              const newClass = animation === "none" ? oldClass : oldClass + " animated " + animation;
              const animate = () => {
                setClass(el, newClass.replace(/\belementor-invisible\b/, ""));
                keys.forEach((key2) => delete settings[key2]);
                el[sa2]("data-settings", JSON.stringify(settings));
              };
              let timeout = setTimeout(animate, animationDelay);
              dispatcher_default.on("fi", () => {
                clearTimeout(timeout);
                setClass(el, getClass(el).replace(new RegExp("\\b" + animation + "\\b"), ""));
              });
            }
          } catch (e) {
            console.error(e);
          }
        }
      });
    });
  };

  // node_modules/@aguidrevitch/fpo-inpage-wp-meteor/src/browser/includes/elementor/pp-menu.js
  var d5 = document;
  var ga3 = "getAttribute";
  var sa3 = "setAttribute";
  var qsa = "querySelectorAll";
  var inmega = "data-in-mega_smartmenus";
  var pp_menu_default = () => {
    const div = d5.createElement("div");
    div.innerHTML = '<span class="sub-arrow --wp-meteor"><i class="fa" aria-hidden="true"></i></span>';
    const placeholder = div.firstChild;
    const prevAll = (el) => {
      const result = [];
      while (el = el.previousElementSibling)
        result.push(el);
      return result;
    };
    d5.addEventListener("DOMContentLoaded", function() {
      Array.from(d5[qsa](".pp-advanced-menu ul")).forEach((ul) => {
        if (ul[ga3](inmega)) {
          return;
        } else if ((ul[ga3]("class") || "").match(/\bmega-menu\b/)) {
          ul[qsa]("ul").forEach((ul2) => {
            ul2[sa3](inmega, true);
          });
        }
        let prev = prevAll(ul);
        let a3 = prev.filter((el) => el).filter((el) => el.tagName === "A").pop();
        if (!a3) {
          a3 = prev.map((el) => Array.from(el[qsa]("a"))).filter((el) => el).flat().pop();
        }
        if (a3) {
          const span = placeholder.cloneNode(true);
          a3.appendChild(span);
          const observer2 = new MutationObserver((mutations) => {
            mutations.forEach(({ addedNodes }) => {
              addedNodes.forEach((node) => {
                if (node.nodeType === 1 && "SPAN" === node.tagName) {
                  try {
                    a3.removeChild(span);
                  } catch {
                  }
                }
              });
            });
          });
          observer2.observe(a3, { childList: true });
        }
      });
    });
  };

  // node_modules/@aguidrevitch/fpo-inpage-wp-meteor/src/browser/public.js
  var DCL3 = "DOMContentLoaded";
  var RSC = "readystatechange";
  var M = "message";
  var separator2 = "----";
  var S = "SCRIPT";
  var c4 = true ? console.log : () => {
  };
  var ce = console.error;
  var prefix2 = "data-wpmeteor-";
  var Object_defineProperty = Object.defineProperty;
  var Object_defineProperties = Object.defineProperties;
  var javascriptBlocked = "javascript/blocked";
  var isJavascriptRegexp = /^(text\/javascript|module)$/i;
  var _rAF = "requestAnimationFrame";
  var _rIC = "requestIdleCallback";
  var _setTimeout = "setTimeout";
  var w3 = window;
  var d6 = document;
  var a2 = "addEventListener";
  var r2 = "removeEventListener";
  var ga4 = "getAttribute";
  var sa4 = "setAttribute";
  var ra2 = "removeAttribute";
  var ha = "hasAttribute";
  var L = "load";
  var E = "error";
  var windowEventPrefix = w3.constructor.name + "::";
  var documentEventPrefix = d6.constructor.name + "::";
  var forEach = function(callback, thisArg) {
    thisArg = thisArg || w3;
    for (var i2 = 0; i2 < this.length; i2++) {
      callback.call(thisArg, this[i2], i2, this);
    }
  };
  if ("NodeList" in w3 && !NodeList.prototype.forEach) {
    c4("polyfilling NodeList.forEach");
    NodeList.prototype.forEach = forEach;
  }
  if ("HTMLCollection" in w3 && !HTMLCollection.prototype.forEach) {
    c4("polyfilling HTMLCollection.forEach");
    HTMLCollection.prototype.forEach = forEach;
  }
  if (_wpmeteor["elementor-animations"]) {
    animations_default();
  }
  if (_wpmeteor["elementor-pp"]) {
    pp_menu_default();
  }
  var reorder = [];
  var delayed = [];
  var wheight = window.innerHeight || document.documentElement.clientHeight;
  var wwidth = window.innerWidth || document.documentElement.clientWidth;
  var DONE = false;
  var eventQueue = [];
  var listeners = {};
  var WindowLoaded = false;
  var firstInteractionFired = false;
  var firedEventsCount = 0;
  var rAF = d6.visibilityState === "visible" ? w3[_rAF] : w3[_setTimeout];
  var rIC = w3[_rIC] || rAF;
  d6[a2]("visibilitychange", () => {
    rAF = d6.visibilityState === "visible" ? w3[_rAF] : w3[_setTimeout];
    rIC = w3[_rIC] || rAF;
  });
  var nextTick = w3[_setTimeout];
  var createElementOverride;
  var capturedAttributes = ["src", "async", "defer", "type", "integrity"];
  var O = Object;
  var definePropert = "definePropert";
  O[definePropert + "y"] = (object, property, options) => {
    if (object === w3 && ["jQuery", "onload"].indexOf(property) >= 0 || (object === d6 || object === d6.body) && ["readyState", "write", "writeln", "on" + RSC].indexOf(property) >= 0) {
      if (["on" + RSC, "on" + L].indexOf(property) && options.set) {
        listeners["on" + RSC] = listeners["on" + RSC] || [];
        listeners["on" + RSC].push(options.set);
      } else {
        ce("Denied " + (object.constructor || {}).name + " " + property + " redefinition");
      }
      return object;
    } else if (object instanceof HTMLScriptElement && capturedAttributes.indexOf(property) >= 0) {
      if (!object[property + "Getters"]) {
        object[property + "Getters"] = [];
        object[property + "Setters"] = [];
        Object_defineProperty(object, property, {
          set(value) {
            object[property + "Setters"].forEach((setter) => setter.call(object, value));
          },
          get() {
            return object[property + "Getters"].slice(-1)[0]();
          }
        });
      }
      if (options.get) {
        object[property + "Getters"].push(options.get);
      }
      if (options.set) {
        object[property + "Setters"].push(options.set);
      }
      return object;
    }
    return Object_defineProperty(object, property, options);
  };
  O[definePropert + "ies"] = (object, properties) => {
    for (let i2 in properties) {
      O[definePropert + "y"](object, i2, properties[i2]);
    }
    return object;
  };
  if (true) {
    d6[a2](RSC, () => {
      c4(delta_default(), separator2, RSC, d6.readyState);
    });
    d6[a2](DCL3, () => {
      c4(delta_default(), separator2, DCL3);
    });
    dispatcher_default.on("l", () => {
      c4(delta_default(), separator2, "L");
      c4(delta_default(), separator2, firedEventsCount + " queued events fired");
    });
    w3[a2](L, () => {
      c4(delta_default(), separator2, L);
    });
  }
  var origAddEventListener;
  var origRemoveEventListener;
  var dOrigAddEventListener = d6[a2].bind(d6);
  var dOrigRemoveEventListener = d6[r2].bind(d6);
  var wOrigAddEventListener = w3[a2].bind(w3);
  var wOrigRemoveEventListener = w3[r2].bind(w3);
  if ("undefined" != typeof EventTarget) {
    origAddEventListener = EventTarget.prototype.addEventListener;
    origRemoveEventListener = EventTarget.prototype.removeEventListener;
    dOrigAddEventListener = origAddEventListener.bind(d6);
    dOrigRemoveEventListener = origRemoveEventListener.bind(d6);
    wOrigAddEventListener = origAddEventListener.bind(w3);
    wOrigRemoveEventListener = origRemoveEventListener.bind(w3);
  }
  var dOrigCreateElement = d6.createElement.bind(d6);
  var origReadyStateGetter = d6.__proto__.__lookupGetter__("readyState").bind(d6);
  var readyState = "loading";
  Object_defineProperty(d6, "readyState", {
    get() {
      return readyState;
    },
    set(value) {
      return readyState = value;
    }
  });
  var hasUnfiredListeners = (eventNames) => {
    return eventQueue.filter(([event, , context], j) => {
      if (eventNames.indexOf(event.type) < 0) {
        return;
      }
      if (!context) {
        context = event.target;
      }
      try {
        const name = context.constructor.name + "::" + event.type;
        for (let i2 = 0; i2 < listeners[name].length; i2++) {
          if (listeners[name][i2]) {
            const listenerKey = name + "::" + j + "::" + i2;
            if (!firedListeners[listenerKey]) {
              return true;
            }
          }
        }
      } catch (e) {
      }
    }).length;
  };
  var currentlyFiredEvent;
  var firedListeners = {};
  var fireQueuedEvents = (eventNames) => {
    eventQueue.forEach(([event, readyState2, context], j) => {
      if (eventNames.indexOf(event.type) < 0) {
        return;
      }
      if (!context) {
        context = event.target;
      }
      try {
        const name = context.constructor.name + "::" + event.type;
        if ((listeners[name] || []).length) {
          for (let i2 = 0; i2 < listeners[name].length; i2++) {
            const func = listeners[name][i2];
            if (func) {
              const listenerKey = name + "::" + j + "::" + i2;
              if (!firedListeners[listenerKey]) {
                firedListeners[listenerKey] = true;
                d6.readyState = readyState2;
                currentlyFiredEvent = name;
                try {
                  firedEventsCount++;
                  c4(delta_default(), "firing " + event.type + "(" + d6.readyState + ") for", func.prototype ? func.prototype.constructor : func);
                  if (!func.prototype || func.prototype.constructor === func) {
                    func.bind(context)(event);
                  } else {
                    func(event);
                  }
                } catch (e) {
                  ce(e, func);
                }
                currentlyFiredEvent = null;
              }
            }
          }
        }
      } catch (e) {
        ce(e);
      }
    });
  };
  dOrigAddEventListener(DCL3, (e) => {
    c4(delta_default(), "enqueued document " + DCL3);
    eventQueue.push([e, origReadyStateGetter(), d6]);
  });
  dOrigAddEventListener(RSC, (e) => {
    c4(delta_default(), "enqueued document " + RSC);
    eventQueue.push([e, origReadyStateGetter(), d6]);
  });
  wOrigAddEventListener(DCL3, (e) => {
    c4(delta_default(), "enqueued window " + DCL3);
    eventQueue.push([e, origReadyStateGetter(), w3]);
  });
  var jQuery = new jQueryMock();
  wOrigAddEventListener(L, (e) => {
    c4(delta_default(), "enqueued window " + L);
    eventQueue.push([e, origReadyStateGetter(), w3]);
    if (!iterating) {
      fireQueuedEvents([DCL3, RSC, M, L]);
      jQuery.init();
    }
  });
  var messageListener = (e) => {
    c4(delta_default(), "enqueued window " + M);
    eventQueue.push([e, d6.readyState, w3]);
  };
  var restoreMessageListener = () => {
    wOrigRemoveEventListener(M, messageListener);
    (listeners[windowEventPrefix + "message"] || []).forEach((listener) => {
      wOrigAddEventListener(M, listener);
    });
    c4(delta_default(), "message listener restored");
  };
  wOrigAddEventListener(M, messageListener);
  dispatcher_default.on("fi", d6.dispatchEvent.bind(d6, new CustomEvent("fi")));
  dispatcher_default.on("fi", () => {
    c4(delta_default(), separator2, "starting iterating on first interaction");
    firstInteractionFired = true;
    iterating = true;
    mayBePreloadScripts();
    d6.readyState = "loading";
    nextTick(iterate);
  });
  var startIterating = () => {
    WindowLoaded = true;
    if (firstInteractionFired && !iterating) {
      c4(delta_default(), separator2, "starting iterating on window.load");
      d6.readyState = "loading";
      nextTick(iterate);
    }
    wOrigRemoveEventListener(L, startIterating);
  };
  wOrigAddEventListener(L, startIterating);
  if (_wpmeteor.rdelay >= 0) {
    new InteractionEvents().init(_wpmeteor.rdelay);
  }
  var scriptsToLoad = 1;
  var scriptLoaded = () => {
    c4(delta_default(), "scriptLoaded", scriptsToLoad - 1);
    if (!--scriptsToLoad) {
      nextTick(dispatcher_default.emit.bind(dispatcher_default, "l"));
    }
  };
  var i = 0;
  var iterating = false;
  var iterate = () => {
    c4(delta_default(), "it", i++, reorder.length);
    const element = reorder.shift();
    if (element) {
      if (element[ga4](prefix2 + "src")) {
        if (element[ha](prefix2 + "async")) {
          c4(delta_default(), "async", scriptsToLoad, element);
          scriptsToLoad++;
          unblock(element, scriptLoaded);
          nextTick(iterate);
        } else {
          unblock(element, nextTick.bind(null, iterate));
        }
      } else if (element.origtype == javascriptBlocked) {
        unblock(element);
        nextTick(iterate);
      } else {
        ce("running next iteration", element, element.origtype, element.origtype == javascriptBlocked);
        nextTick(iterate);
      }
    } else {
      if (hasUnfiredListeners([DCL3, RSC, M])) {
        fireQueuedEvents([DCL3, RSC, M]);
        nextTick(iterate);
      } else if (firstInteractionFired && WindowLoaded) {
        if (hasUnfiredListeners([L, M])) {
          fireQueuedEvents([L, M]);
          nextTick(iterate);
        } else if (scriptsToLoad > 1) {
          c4(delta_default(), "waiting for", scriptsToLoad - 1, "more scripts to load", reorder);
          rIC(iterate);
        } else if (delayed.length) {
          while (delayed.length) {
            reorder.push(delayed.shift());
            c4(delta_default(), "adding delayed script", reorder.slice(-1)[0]);
          }
          mayBePreloadScripts();
          nextTick(iterate);
        } else {
          if (w3.RocketLazyLoadScripts) {
            try {
              RocketLazyLoadScripts.run();
            } catch (e) {
              ce(e);
            }
          }
          d6.readyState = "complete";
          restoreMessageListener();
          jQuery.unmock();
          iterating = false;
          DONE = true;
          w3[_setTimeout](scriptLoaded);
        }
      } else {
        iterating = false;
      }
    }
  };
  var cloneScript = (el) => {
    const newElement = dOrigCreateElement(S);
    const attrs = el.attributes;
    for (var i2 = attrs.length - 1; i2 >= 0; i2--) {
      newElement[sa4](attrs[i2].name, attrs[i2].value);
    }
    const type = el[ga4](prefix2 + "type");
    if (type) {
      newElement.type = type;
    } else {
      newElement.type = "text/javascript";
    }
    if ((el.textContent || "").match(/^\s*class RocketLazyLoadScripts/)) {
      newElement.textContent = el.textContent.replace(/^\s*class\s*RocketLazyLoadScripts/, "window.RocketLazyLoadScripts=class").replace("RocketLazyLoadScripts.run();", "");
    } else {
      newElement.textContent = el.textContent;
    }
    ["after", "type", "src", "async", "defer"].forEach((postfix) => newElement[ra2](prefix2 + postfix));
    return newElement;
  };
  var replaceScript = (el, newElement) => {
    const parentNode = el.parentNode;
    if (parentNode) {
      const newParent = parentNode.nodeType === 11 ? dOrigCreateElement(parentNode.host.tagName) : dOrigCreateElement(parentNode.tagName);
      newParent.appendChild(parentNode.replaceChild(newElement, el));
      if (!parentNode.isConnected) {
        ce("Parent for", el, " is not part of the DOM");
        return;
      }
      return el;
    }
    ce("No parent for", el);
  };
  var unblock = (el, callback) => {
    let src = el[ga4](prefix2 + "src");
    if (src) {
      c4(delta_default(), "unblocking src", src);
      const newElement = cloneScript(el);
      const addEventListener = origAddEventListener ? origAddEventListener.bind(newElement) : newElement[a2].bind(newElement);
      if (el.getEventListeners) {
        el.getEventListeners().forEach(([event, listener]) => {
          c4(delta_default(), "re-adding event listeners to cloned element", event, listener);
          addEventListener(event, listener);
        });
      }
      if (callback) {
        addEventListener(L, callback);
        addEventListener(E, callback);
      }
      newElement.src = src;
      const oldChild = replaceScript(el, newElement);
      const type = newElement[ga4]("type");
      c4(delta_default(), "unblocked src", src, newElement);
      if ((!oldChild || el[ha]("nomodule") || type && !isJavascriptRegexp.test(type)) && callback) {
        callback();
      }
    } else if (el.origtype === javascriptBlocked) {
      c4(delta_default(), "unblocking inline", el);
      replaceScript(el, cloneScript(el));
      c4(delta_default(), "unblocked inline", el);
    } else {
      ce(delta_default(), "already unblocked", el);
      if (callback) {
        callback();
      }
    }
  };
  var removeEventListener = (name, func) => {
    const pos = (listeners[name] || []).indexOf(func);
    if (pos >= 0) {
      listeners[name][pos] = void 0;
      return true;
    }
  };
  var documentAddEventListener = (event, func, ...args) => {
    if ("HTMLDocument::" + DCL3 == currentlyFiredEvent && event === DCL3 && !func.toString().match(/jQueryMock/)) {
      dispatcher_default.on("l", d6.addEventListener.bind(d6, event, func, ...args));
      return;
    }
    if (func && (event === DCL3 || event === RSC)) {
      c4(delta_default(), "enqueuing event listener", event, func);
      const name = documentEventPrefix + event;
      listeners[name] = listeners[name] || [];
      listeners[name].push(func);
      if (DONE) {
        fireQueuedEvents([event]);
      }
      return;
    }
    return dOrigAddEventListener(event, func, ...args);
  };
  var documentRemoveEventListener = (event, func) => {
    if (event === DCL3) {
      const name = documentEventPrefix + event;
      removeEventListener(name, func);
    }
    return dOrigRemoveEventListener(event, func);
  };
  Object_defineProperties(d6, {
    [a2]: {
      get() {
        return documentAddEventListener;
      },
      set() {
        return documentAddEventListener;
      }
    },
    [r2]: {
      get() {
        return documentRemoveEventListener;
      },
      set() {
        return documentRemoveEventListener;
      }
    }
  });
  var preconnects = {};
  var preconnect = (src) => {
    if (!src)
      return;
    try {
      if (src.match(/^\/\/\w+/))
        src = d6.location.protocol + src;
      const url = new URL(src);
      const href = url.origin;
      if (href && !preconnects[href] && d6.location.host !== url.host) {
        const s = dOrigCreateElement("link");
        s.rel = "preconnect";
        s.href = href;
        d6.head.appendChild(s);
        c4(delta_default(), "preconnecting", url.origin);
        preconnects[href] = true;
      }
    } catch (e) {
      ce(delta_default(), "failed to parse src for preconnect", src);
    }
  };
  var preloads = {};
  var preloadAsScript = (src, isModule, crossorigin, fragment) => {
    var s = dOrigCreateElement("link");
    s.rel = isModule ? "modulepre" + L : "pre" + L;
    s.as = "script";
    if (crossorigin)
      s[sa4]("crossorigin", crossorigin);
    s.href = src;
    fragment.appendChild(s);
    preloads[src] = true;
    c4(delta_default(), s.rel, src);
  };
  var mayBePreloadScripts = () => {
    if (_wpmeteor.preload && reorder.length) {
      const fragment = d6.createDocumentFragment();
      reorder.forEach((script) => {
        const src = script[ga4](prefix2 + "src");
        if (src && !preloads[src] && !script[ga4](prefix2 + "integrity") && !script[ha]("nomodule")) {
          preloadAsScript(src, script[ga4](prefix2 + "type") == "module", script[ha]("crossorigin") && script[ga4]("crossorigin"), fragment);
        }
      });
      rAF(d6.head.appendChild.bind(d6.head, fragment));
    }
  };
  dOrigAddEventListener(DCL3, () => {
    const treorder = [...reorder];
    reorder.splice(0, reorder.length);
    [...d6.querySelectorAll("script[" + prefix2 + "after]"), ...treorder].forEach((el) => {
      if (seenScripts.some((seen) => seen === el)) {
        return;
      }
      const originalAttributeGetter = el.__lookupGetter__("type").bind(el);
      Object_defineProperty(el, "origtype", {
        get() {
          return originalAttributeGetter();
        }
      });
      if ((el[ga4](prefix2 + "src") || "").match(/\/gtm.js\?/)) {
        c4(delta_default(), "delaying regex", el[ga4](prefix2 + "src"));
        delayed.push(el);
      } else if (el[ha](prefix2 + "async")) {
        c4(delta_default(), "delaying async", el[ga4](prefix2 + "src"));
        delayed.unshift(el);
      } else {
        reorder.push(el);
      }
      seenScripts.push(el);
    });
  });
  var createElement = function(...args) {
    const scriptElt = dOrigCreateElement(...args);
    if (args[0].toUpperCase() !== S || !iterating) {
      return scriptElt;
    }
    c4(delta_default(), "creating script element");
    const originalSetAttribute = scriptElt[sa4].bind(scriptElt);
    const originalGetAttribute = scriptElt[ga4].bind(scriptElt);
    const originalHasAttribute = scriptElt[ha].bind(scriptElt);
    originalSetAttribute(prefix2 + "after", "REORDER");
    originalSetAttribute(prefix2 + "type", "text/javascript");
    scriptElt.type = javascriptBlocked;
    const eventListeners = [];
    scriptElt.getEventListeners = () => {
      return eventListeners;
    };
    O[definePropert + "ies"](scriptElt, {
      "onreadystatechange": {
        set(func) {
          eventListeners.push([L, func]);
        }
      },
      "onload": {
        set(func) {
          eventListeners.push([L, func]);
        }
      },
      "onerror": {
        set(func) {
          eventListeners.push([E, func]);
        }
      }
    });
    capturedAttributes.forEach((property) => {
      const originalAttributeGetter = scriptElt.__lookupGetter__(property).bind(scriptElt);
      O[definePropert + "y"](scriptElt, property, {
        set(value) {
          c4(delta_default(), "setting ", property, value);
          return value ? scriptElt[sa4](prefix2 + property, value) : scriptElt[ra2](prefix2 + property);
        },
        get() {
          return scriptElt[ga4](prefix2 + property);
        }
      });
      Object_defineProperty(scriptElt, "orig" + property, {
        get() {
          return originalAttributeGetter();
        }
      });
    });
    scriptElt[a2] = function(event, handler) {
      eventListeners.push([event, handler]);
    };
    scriptElt[sa4] = function(property, value) {
      if (capturedAttributes.includes(property)) {
        c4(delta_default(), "setting attribute ", property, value);
        return value ? originalSetAttribute(prefix2 + property, value) : scriptElt[ra2](prefix2 + property);
      } else if (["onload", "onerror", "onreadystatechange"].includes(property)) {
        c4(delta_default(), "setting attribute ", property, value);
        if (value) {
          originalSetAttribute(prefix2 + property, value);
          originalSetAttribute(property, 'document.dispatchEvent(new CustomEvent("wpmeteor:load", { detail: { event: event, target: this } }))');
        } else {
          scriptElt[ra2](property);
          scriptElt[ra2](prefix2 + property, value);
        }
      } else {
        originalSetAttribute(property, value);
      }
    };
    scriptElt[ga4] = function(property) {
      return capturedAttributes.indexOf(property) >= 0 ? originalGetAttribute(prefix2 + property) : originalGetAttribute(property);
    };
    scriptElt[ha] = function(property) {
      return capturedAttributes.indexOf(property) >= 0 ? originalHasAttribute(prefix2 + property) : originalHasAttribute(property);
    };
    const attributes = scriptElt.attributes;
    Object_defineProperty(scriptElt, "attributes", {
      get() {
        const mock = [...attributes].filter((attr) => attr.name !== "type" && attr.name !== prefix2 + "after").map((attr) => {
          return {
            name: attr.name.match(new RegExp(prefix2)) ? attr.name.replace(prefix2, "") : attr.name,
            value: attr.value
          };
        });
        return mock;
      }
    });
    return scriptElt;
  };
  Object.defineProperty(d6, "createElement", {
    set(value) {
      if (true) {
        if (value == dOrigCreateElement) {
          c4(delta_default(), "document.createElement restored to original");
        } else if (value === createElement) {
          c4(delta_default(), "document.createElement overridden");
        } else {
          c4(delta_default(), "document.createElement overridden by a 3rd party script");
        }
      }
      if (value !== createElement) {
        createElementOverride = value;
      }
    },
    get() {
      return createElementOverride || createElement;
    }
  });
  var seenScripts = [];
  var observer = new MutationObserver((mutations) => {
    if (iterating) {
      mutations.forEach(({ addedNodes, target }) => {
        addedNodes.forEach((node) => {
          if (node.nodeType === 1) {
            if (S === node.tagName) {
              if ("REORDER" === node[ga4](prefix2 + "after") && (!node[ga4](prefix2 + "type") || isJavascriptRegexp.test(node[ga4](prefix2 + "type")))) {
                c4(delta_default(), "captured new script", node.cloneNode(true), node);
                const src = node[ga4](prefix2 + "src");
                if (seenScripts.filter((n) => n === node).length) {
                  ce("Inserted twice", node);
                }
                if (node.parentNode) {
                  seenScripts.push(node);
                  if ((src || "").match(/\/gtm.js\?/)) {
                    c4(delta_default(), "delaying regex", node[ga4](prefix2 + "src"));
                    delayed.push(node);
                    preconnect(src);
                  } else if (node[ha](prefix2 + "async")) {
                    c4(delta_default(), "delaying async", node[ga4](prefix2 + "src"));
                    delayed.unshift(node);
                    preconnect(src);
                  } else {
                    if (src && !node[ga4](prefix2 + "integrity") && !node[ha]("nomodule") && !preloads[src]) {
                      c4(delta_default(), "pre preload", reorder.length);
                      preloadAsScript(src, node[ga4](prefix2 + "type") == "module", node[ha]("crossorigin") && node[ga4]("crossorigin"), d6.head);
                    }
                    reorder.push(node);
                  }
                } else {
                  ce("No parent node for", node, "re-adding to", target);
                  node.addEventListener(L, (e) => e.target.parentNode.removeChild(e.target));
                  node.addEventListener(E, (e) => e.target.parentNode.removeChild(e.target));
                  target.appendChild(node);
                }
              } else {
                c4(delta_default(), "captured unmodified or non-javascript script", node.cloneNode(true), node);
                dispatcher_default.emit("s", node.src);
              }
            } else if ("LINK" === node.tagName && node[ga4]("as") === "script") {
              preloads[node[ga4]("href")] = true;
            }
          }
        });
      });
    }
  });
  var mutationObserverOptions = {
    childList: true,
    subtree: true,
    attributes: true,
    // attributeFilter: ['src', 'type'],
    attributeOldValue: true
  };
  observer.observe(d6.documentElement, mutationObserverOptions);
  var origAttachShadow = HTMLElement.prototype.attachShadow;
  HTMLElement.prototype.attachShadow = function(options) {
    const shadowRoot = origAttachShadow.call(this, options);
    if (options.mode === "open") {
      observer.observe(shadowRoot, mutationObserverOptions);
    }
    return shadowRoot;
  };
  dispatcher_default.on("l", () => {
    if (!createElementOverride || createElementOverride === createElement) {
      d6.createElement = dOrigCreateElement;
      observer.disconnect();
    } else {
      c4(delta_default(), "createElement is overridden, keeping observers in place");
    }
    d6.dispatchEvent(new CustomEvent("l"));
  });
  var documentWrite = (str) => {
    let parent, currentScript;
    if (!d6.currentScript || !d6.currentScript.parentNode) {
      parent = d6.body;
      currentScript = parent.lastChild;
    } else {
      currentScript = d6.currentScript;
      parent = currentScript.parentNode;
    }
    try {
      const df = dOrigCreateElement("div");
      df.innerHTML = str;
      Array.from(df.childNodes).forEach((node) => {
        if (node.nodeName === S) {
          parent.insertBefore(cloneScript(node), currentScript);
        } else {
          parent.insertBefore(node, currentScript);
        }
      });
    } catch (e) {
      ce(e);
    }
  };
  var documentWriteLn = (str) => documentWrite(str + "\n");
  Object_defineProperties(d6, {
    "write": {
      get() {
        return documentWrite;
      },
      set(func) {
        return documentWrite = func;
      }
    },
    "writeln": {
      get() {
        return documentWriteLn;
      },
      set(func) {
        return documentWriteLn = func;
      }
    }
  });
  var windowAddEventListener = (event, func, ...args) => {
    if ("Window::" + DCL3 == currentlyFiredEvent && event === DCL3 && !func.toString().match(/jQueryMock/)) {
      dispatcher_default.on("l", w3.addEventListener.bind(w3, event, func, ...args));
      return;
    }
    if ("Window::" + L == currentlyFiredEvent && event === L) {
      dispatcher_default.on("l", w3.addEventListener.bind(w3, event, func, ...args));
      return;
    }
    if (func && (event === L || event === DCL3 || event === M && !DONE)) {
      c4(delta_default(), "enqueuing event listener", event, func);
      const name = event === DCL3 ? documentEventPrefix + event : windowEventPrefix + event;
      listeners[name] = listeners[name] || [];
      listeners[name].push(func);
      if (DONE) {
        fireQueuedEvents([event]);
      }
      return;
    }
    return wOrigAddEventListener(event, func, ...args);
  };
  var windowRemoveEventListener = (event, func) => {
    if (event === L) {
      const name = event === DCL3 ? documentEventPrefix + event : windowEventPrefix + event;
      removeEventListener(name, func);
    }
    return wOrigRemoveEventListener(event, func);
  };
  Object_defineProperties(w3, {
    [a2]: {
      get() {
        return windowAddEventListener;
      },
      set() {
        return windowAddEventListener;
      }
    },
    [r2]: {
      get() {
        return windowRemoveEventListener;
      },
      set() {
        return windowRemoveEventListener;
      }
    }
  });
  var onHandlerOptions = (name) => {
    let handler;
    return {
      get() {
        c4(delta_default(), separator2, "getting " + name.toLowerCase().replace(/::/, ".") + " handler", handler);
        return handler;
      },
      set(func) {
        c4(delta_default(), separator2, "setting " + name.toLowerCase().replace(/::/, ".") + " handler", func);
        if (handler) {
          removeEventListener(name, func);
        }
        listeners[name] = listeners[name] || [];
        listeners[name].push(func);
        return handler = func;
      }
      // rocket-loader from CloudFlare tries to override onload so we will let him
      // configurable: true,
    };
  };
  dOrigAddEventListener("wpmeteor:load", (e) => {
    const { target, event } = e.detail;
    const el = target === w3 ? d6.body : target;
    const func = el[ga4](prefix2 + "on" + event.type);
    el[ra2](prefix2 + "on" + event.type);
    try {
      const f = new Function("event", func);
      if (target === w3) {
        w3[a2](L, w3[a2].bind(w3, L, f));
      } else {
        f.call(target, event);
      }
    } catch (err) {
      console.err(err);
    }
  });
  {
    const options = onHandlerOptions(windowEventPrefix + L);
    Object_defineProperty(w3, "onload", options);
    dOrigAddEventListener(DCL3, () => {
      Object_defineProperty(d6.body, "onload", options);
    });
  }
  Object_defineProperty(d6, "onreadystatechange", onHandlerOptions(documentEventPrefix + RSC));
  Object_defineProperty(w3, "onmessage", onHandlerOptions(windowEventPrefix + M));
  if (location.search.match(/wpmeteorperformance/)) {
    try {
      new PerformanceObserver((entryList) => {
        for (const entry of entryList.getEntries()) {
          c4(delta_default(), "LCP candidate:", entry.startTime, entry);
        }
      }).observe({ type: "largest-contentful-paint", buffered: true });
      new PerformanceObserver((list) => {
        list.getEntries().forEach((e) => c4(delta_default(), "resource loaded", e.name, e));
      }).observe({ type: "resource" });
    } catch (e) {
    }
  }
  var intersectsViewport = (el) => {
    let extras = {
      "4g": 1250,
      "3g": 2500,
      "2g": 2500
    };
    const extra = extras[(navigator.connection || {}).effectiveType] || 0;
    const rect = el.getBoundingClientRect();
    const viewport = {
      top: -1 * wheight - extra,
      left: -1 * wwidth - extra,
      bottom: wheight + extra,
      right: wwidth + extra
    };
    if (rect.left >= viewport.right || rect.right <= viewport.left)
      return false;
    if (rect.top >= viewport.bottom || rect.bottom <= viewport.top)
      return false;
    return true;
  };
  var waitForImages = (reallyWait = true) => {
    let imagesToLoad = 1;
    let imagesLoadedCount = -1;
    const seen = {};
    const imageLoadedHandler = () => {
      imagesLoadedCount++;
      if (!--imagesToLoad) {
        c4(delta_default(), imagesLoadedCount + " eager images loaded");
        nextTick(dispatcher_default.emit.bind(dispatcher_default, "i"), _wpmeteor.rdelay);
      }
    };
    Array.from(d6.getElementsByTagName("*")).forEach((tag) => {
      let src, style, bgUrl;
      if (tag.tagName === "IMG") {
        let _src = tag.currentSrc || tag.src;
        if (_src && !seen[_src] && !_src.match(/^data:/i)) {
          if ((tag.loading || "").toLowerCase() !== "lazy") {
            src = _src;
            c4(delta_default(), "loading image", src, "for", tag);
          } else if (intersectsViewport(tag)) {
            src = _src;
            c4(delta_default(), "loading lazy image", src, "for", tag);
          }
        }
      } else if (tag.tagName === S) {
        preconnect(tag[ga4](prefix2 + "src"));
      } else if (tag.tagName === "LINK" && tag[ga4]("as") === "script" && ["pre" + L, "modulepre" + L].indexOf(tag[ga4]("rel")) >= 0) {
        preloads[tag[ga4]("href")] = true;
      } else if ((style = w3.getComputedStyle(tag)) && (bgUrl = (style.backgroundImage || "").match(/^url\s*\((.*?)\)/i)) && (bgUrl || []).length) {
        const url = bgUrl[0].slice(4, -1).replace(/"/g, "");
        if (!seen[url] && !url.match(/^data:/i)) {
          src = url;
          c4(delta_default(), "loading background", src, "for", tag);
        }
      }
      if (src) {
        seen[src] = true;
        const temp = new Image();
        if (reallyWait) {
          imagesToLoad++;
          temp[a2](L, imageLoadedHandler);
          temp[a2](E, imageLoadedHandler);
        }
        temp.src = src;
      }
    });
    d6.fonts.ready.then(() => {
      c4(delta_default(), "fonts ready");
      imageLoadedHandler();
    });
  };
  (() => {
    if (_wpmeteor.rdelay === 0) {
      dOrigAddEventListener(DCL3, () => nextTick(waitForImages.bind(null, false)));
    } else {
      wOrigAddEventListener(L, waitForImages);
    }
  })();
})();
//0.1.16
//# sourceMappingURL=public-debug.js.map
