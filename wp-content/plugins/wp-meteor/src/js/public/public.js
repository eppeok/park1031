import jQueryMock from "./includes/mocks/jquery";
import InteractionEvents from "./includes/utils/interaction-events";
import dispatcher from "./includes/utils/dispatcher";
import delta from "./includes/utils/delta";
import elementorAnimations from "./includes/elementor/animations";
import elementorPP from "./includes/elementor/pp-menu";
const DCL = "DOMContentLoaded", RSC = "readystatechange", M = "message", separator = "----", S = "SCRIPT", c = process.env.DEBUG ? console.log : () => {
}, ce = console.error, prefix = "data-wpmeteor-", Object_defineProperty = Object.defineProperty, Object_defineProperties = Object.defineProperties, javascriptBlocked = "javascript/blocked", isJavascriptRegexp = /^(text\/javascript|module)$/i, _rAF = "requestAnimationFrame", _rIC = "requestIdleCallback", _setTimeout = "setTimeout";
const w = window, d = document, a = "addEventListener", r = "removeEventListener", ga = "getAttribute", sa = "setAttribute", ra = "removeAttribute", ha = "hasAttribute", L = "load", E = "error";
const windowEventPrefix = w.constructor.name + "::";
const documentEventPrefix = d.constructor.name + "::";
const forEach = function(callback, thisArg) {
  thisArg = thisArg || w;
  for (var i2 = 0; i2 < this.length; i2++) {
    callback.call(thisArg, this[i2], i2, this);
  }
};
if ("NodeList" in w && !NodeList.prototype.forEach) {
  process.env.DEBUG && c("polyfilling NodeList.forEach");
  NodeList.prototype.forEach = forEach;
}
if ("HTMLCollection" in w && !HTMLCollection.prototype.forEach) {
  process.env.DEBUG && c("polyfilling HTMLCollection.forEach");
  HTMLCollection.prototype.forEach = forEach;
}
if (_wpmeteor["elementor-animations"]) {
  elementorAnimations();
}
if (_wpmeteor["elementor-pp"]) {
  elementorPP();
}
const reorder = [];
const delayed = [];
const wheight = window.innerHeight || document.documentElement.clientHeight;
const wwidth = window.innerWidth || document.documentElement.clientWidth;
let DONE = false;
let eventQueue = [];
let listeners = {};
let WindowLoaded = false;
let firstInteractionFired = false;
let firedEventsCount = 0;
let rAF = d.visibilityState === "visible" ? w[_rAF] : w[_setTimeout];
let rIC = w[_rIC] || rAF;
d[a]("visibilitychange", () => {
  rAF = d.visibilityState === "visible" ? w[_rAF] : w[_setTimeout];
  rIC = w[_rIC] || rAF;
});
const nextTick = w[_setTimeout];
let createElementOverride;
const capturedAttributes = ["src", "async", "defer", "type", "integrity"];
const O = Object, definePropert = "definePropert";
O[definePropert + "y"] = (object, property, options) => {
  if (object === w && ["jQuery", "onload"].indexOf(property) >= 0 || (object === d || object === d.body) && ["readyState", "write", "writeln", "on" + RSC].indexOf(property) >= 0) {
    if (["on" + RSC, "on" + L].indexOf(property) && options.set) {
      listeners["on" + RSC] = listeners["on" + RSC] || [];
      listeners["on" + RSC].push(options.set);
    } else {
      process.env.DEBUG && ce("Denied " + (object.constructor || {}).name + " " + property + " redefinition");
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
if (process.env.DEBUG) {
  d[a](RSC, () => {
    c(delta(), separator, RSC, d.readyState);
  });
  d[a](DCL, () => {
    c(delta(), separator, DCL);
  });
  dispatcher.on("l", () => {
    c(delta(), separator, "L");
    c(delta(), separator, firedEventsCount + " queued events fired");
  });
  w[a](L, () => {
    c(delta(), separator, L);
  });
}
let origAddEventListener, origRemoveEventListener;
let dOrigAddEventListener = d[a].bind(d);
let dOrigRemoveEventListener = d[r].bind(d);
let wOrigAddEventListener = w[a].bind(w);
let wOrigRemoveEventListener = w[r].bind(w);
if ("undefined" != typeof EventTarget) {
  origAddEventListener = EventTarget.prototype.addEventListener;
  origRemoveEventListener = EventTarget.prototype.removeEventListener;
  dOrigAddEventListener = origAddEventListener.bind(d);
  dOrigRemoveEventListener = origRemoveEventListener.bind(d);
  wOrigAddEventListener = origAddEventListener.bind(w);
  wOrigRemoveEventListener = origRemoveEventListener.bind(w);
}
const dOrigCreateElement = d.createElement.bind(d);
const origReadyStateGetter = d.__proto__.__lookupGetter__("readyState").bind(d);
let readyState = "loading";
Object_defineProperty(d, "readyState", {
  get() {
    return readyState;
  },
  set(value) {
    return readyState = value;
  }
});
const hasUnfiredListeners = (eventNames) => {
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
let currentlyFiredEvent;
const firedListeners = {};
const fireQueuedEvents = (eventNames) => {
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
              d.readyState = readyState2;
              currentlyFiredEvent = name;
              try {
                firedEventsCount++;
                process.env.DEBUG && c(delta(), "firing " + event.type + "(" + d.readyState + ") for", func.prototype ? func.prototype.constructor : func);
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
dOrigAddEventListener(DCL, (e) => {
  process.env.DEBUG && c(delta(), "enqueued document " + DCL);
  eventQueue.push([e, origReadyStateGetter(), d]);
});
dOrigAddEventListener(RSC, (e) => {
  process.env.DEBUG && c(delta(), "enqueued document " + RSC);
  eventQueue.push([e, origReadyStateGetter(), d]);
});
wOrigAddEventListener(DCL, (e) => {
  process.env.DEBUG && c(delta(), "enqueued window " + DCL);
  eventQueue.push([e, origReadyStateGetter(), w]);
});
const jQuery = new jQueryMock();
wOrigAddEventListener(L, (e) => {
  process.env.DEBUG && c(delta(), "enqueued window " + L);
  eventQueue.push([e, origReadyStateGetter(), w]);
  if (!iterating) {
    fireQueuedEvents([DCL, RSC, M, L]);
    jQuery.init();
  }
});
const messageListener = (e) => {
  process.env.DEBUG && c(delta(), "enqueued window " + M);
  eventQueue.push([e, d.readyState, w]);
};
const restoreMessageListener = () => {
  wOrigRemoveEventListener(M, messageListener);
  (listeners[windowEventPrefix + "message"] || []).forEach((listener) => {
    wOrigAddEventListener(M, listener);
  });
  process.env.DEBUG && c(delta(), "message listener restored");
};
wOrigAddEventListener(M, messageListener);
dispatcher.on("fi", d.dispatchEvent.bind(d, new CustomEvent("fi")));
dispatcher.on("fi", () => {
  process.env.DEBUG && c(delta(), separator, "starting iterating on first interaction");
  firstInteractionFired = true;
  iterating = true;
  mayBePreloadScripts();
  d.readyState = "loading";
  nextTick(iterate);
});
const startIterating = () => {
  WindowLoaded = true;
  if (firstInteractionFired && !iterating) {
    process.env.DEBUG && c(delta(), separator, "starting iterating on window.load");
    d.readyState = "loading";
    nextTick(iterate);
  }
  wOrigRemoveEventListener(L, startIterating);
};
wOrigAddEventListener(L, startIterating);
if (_wpmeteor.rdelay >= 0) {
  new InteractionEvents().init(_wpmeteor.rdelay);
}
let scriptsToLoad = 1;
const scriptLoaded = () => {
  process.env.DEBUG && c(delta(), "scriptLoaded", scriptsToLoad - 1);
  if (!--scriptsToLoad) {
    nextTick(dispatcher.emit.bind(dispatcher, "l"));
  }
};
let i = 0;
let iterating = false;
const iterate = () => {
  process.env.DEBUG && c(delta(), "it", i++, reorder.length);
  const element = reorder.shift();
  if (element) {
    if (element[ga](prefix + "src")) {
      if (element[ha](prefix + "async")) {
        process.env.DEBUG && c(delta(), "async", scriptsToLoad, element);
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
      process.env.DEBUG && ce("running next iteration", element, element.origtype, element.origtype == javascriptBlocked);
      nextTick(iterate);
    }
  } else {
    if (hasUnfiredListeners([DCL, RSC, M])) {
      fireQueuedEvents([DCL, RSC, M]);
      nextTick(iterate);
    } else if (firstInteractionFired && WindowLoaded) {
      if (hasUnfiredListeners([L, M])) {
        fireQueuedEvents([L, M]);
        nextTick(iterate);
      } else if (scriptsToLoad > 1) {
        process.env.DEBUG && c(delta(), "waiting for", scriptsToLoad - 1, "more scripts to load", reorder);
        rIC(iterate);
      } else if (delayed.length) {
        while (delayed.length) {
          reorder.push(delayed.shift());
          process.env.DEBUG && c(delta(), "adding delayed script", reorder.slice(-1)[0]);
        }
        mayBePreloadScripts();
        nextTick(iterate);
      } else {
        if (w.RocketLazyLoadScripts) {
          try {
            RocketLazyLoadScripts.run();
          } catch (e) {
            ce(e);
          }
        }
        d.readyState = "complete";
        restoreMessageListener();
        jQuery.unmock();
        iterating = false;
        DONE = true;
        w[_setTimeout](scriptLoaded);
      }
    } else {
      iterating = false;
    }
  }
};
const cloneScript = (el) => {
  const newElement = dOrigCreateElement(S);
  const attrs = el.attributes;
  for (var i2 = attrs.length - 1; i2 >= 0; i2--) {
    newElement[sa](attrs[i2].name, attrs[i2].value);
  }
  const type = el[ga](prefix + "type");
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
  ["after", "type", "src", "async", "defer"].forEach((postfix) => newElement[ra](prefix + postfix));
  return newElement;
};
const replaceScript = (el, newElement) => {
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
const unblock = (el, callback) => {
  let src = el[ga](prefix + "src");
  if (src) {
    process.env.DEBUG && c(delta(), "unblocking src", src);
    const newElement = cloneScript(el);
    const addEventListener = origAddEventListener ? origAddEventListener.bind(newElement) : newElement[a].bind(newElement);
    if (el.getEventListeners) {
      el.getEventListeners().forEach(([event, listener]) => {
        process.env.DEBUG && c(delta(), "re-adding event listeners to cloned element", event, listener);
        addEventListener(event, listener);
      });
    }
    if (callback) {
      addEventListener(L, callback);
      addEventListener(E, callback);
    }
    newElement.src = src;
    const oldChild = replaceScript(el, newElement);
    const type = newElement[ga]("type");
    process.env.DEBUG && c(delta(), "unblocked src", src, newElement);
    if ((!oldChild || el[ha]("nomodule") || type && !isJavascriptRegexp.test(type)) && callback) {
      callback();
    }
  } else if (el.origtype === javascriptBlocked) {
    process.env.DEBUG && c(delta(), "unblocking inline", el);
    replaceScript(el, cloneScript(el));
    process.env.DEBUG && c(delta(), "unblocked inline", el);
  } else {
    process.env.DEBUG && ce(delta(), "already unblocked", el);
    if (callback) {
      callback();
    }
  }
};
const removeEventListener = (name, func) => {
  const pos = (listeners[name] || []).indexOf(func);
  if (pos >= 0) {
    listeners[name][pos] = void 0;
    return true;
  }
};
let documentAddEventListener = (event, func, ...args) => {
  if ("HTMLDocument::" + DCL == currentlyFiredEvent && event === DCL && !func.toString().match(/jQueryMock/)) {
    dispatcher.on("l", d.addEventListener.bind(d, event, func, ...args));
    return;
  }
  if (func && (event === DCL || event === RSC)) {
    process.env.DEBUG && c(delta(), "enqueuing event listener", event, func);
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
let documentRemoveEventListener = (event, func) => {
  if (event === DCL) {
    const name = documentEventPrefix + event;
    removeEventListener(name, func);
  }
  return dOrigRemoveEventListener(event, func);
};
Object_defineProperties(d, {
  [a]: {
    get() {
      return documentAddEventListener;
    },
    set() {
      return documentAddEventListener;
    }
  },
  [r]: {
    get() {
      return documentRemoveEventListener;
    },
    set() {
      return documentRemoveEventListener;
    }
  }
});
const preconnects = {};
const preconnect = (src) => {
  if (!src)
    return;
  try {
    if (src.match(/^\/\/\w+/))
      src = d.location.protocol + src;
    const url = new URL(src);
    const href = url.origin;
    if (href && !preconnects[href] && d.location.host !== url.host) {
      const s = dOrigCreateElement("link");
      s.rel = "preconnect";
      s.href = href;
      d.head.appendChild(s);
      process.env.DEBUG && c(delta(), "preconnecting", url.origin);
      preconnects[href] = true;
    }
  } catch (e) {
    process.env.DEBUG && ce(delta(), "failed to parse src for preconnect", src);
  }
};
const preloads = {};
const preloadAsScript = (src, isModule, crossorigin, fragment) => {
  var s = dOrigCreateElement("link");
  s.rel = isModule ? "modulepre" + L : "pre" + L;
  s.as = "script";
  if (crossorigin)
    s[sa]("crossorigin", crossorigin);
  s.href = src;
  fragment.appendChild(s);
  preloads[src] = true;
  process.env.DEBUG && c(delta(), s.rel, src);
};
const mayBePreloadScripts = () => {
  if (_wpmeteor.preload && reorder.length) {
    const fragment = d.createDocumentFragment();
    reorder.forEach((script) => {
      const src = script[ga](prefix + "src");
      if (src && !preloads[src] && !script[ga](prefix + "integrity") && !script[ha]("nomodule")) {
        preloadAsScript(src, script[ga](prefix + "type") == "module", script[ha]("crossorigin") && script[ga]("crossorigin"), fragment);
      }
    });
    rAF(d.head.appendChild.bind(d.head, fragment));
  }
};
dOrigAddEventListener(DCL, () => {
  const treorder = [...reorder];
  reorder.splice(0, reorder.length);
  [...d.querySelectorAll("script[" + prefix + "after]"), ...treorder].forEach((el) => {
    if (seenScripts.some((seen) => seen === el)) {
      return;
    }
    const originalAttributeGetter = el.__lookupGetter__("type").bind(el);
    Object_defineProperty(el, "origtype", {
      get() {
        return originalAttributeGetter();
      }
    });
    if ((el[ga](prefix + "src") || "").match(/\/gtm.js\?/)) {
      process.env.DEBUG && c(delta(), "delaying regex", el[ga](prefix + "src"));
      delayed.push(el);
    } else if (el[ha](prefix + "async")) {
      process.env.DEBUG && c(delta(), "delaying async", el[ga](prefix + "src"));
      delayed.unshift(el);
    } else {
      reorder.push(el);
    }
    seenScripts.push(el);
  });
});
const createElement = function(...args) {
  const scriptElt = dOrigCreateElement(...args);
  if (args[0].toUpperCase() !== S || !iterating) {
    return scriptElt;
  }
  process.env.DEBUG && c(delta(), "creating script element");
  const originalSetAttribute = scriptElt[sa].bind(scriptElt);
  const originalGetAttribute = scriptElt[ga].bind(scriptElt);
  const originalHasAttribute = scriptElt[ha].bind(scriptElt);
  originalSetAttribute(prefix + "after", "REORDER");
  originalSetAttribute(prefix + "type", "text/javascript");
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
        process.env.DEBUG && c(delta(), "setting ", property, value);
        return value ? scriptElt[sa](prefix + property, value) : scriptElt[ra](prefix + property);
      },
      get() {
        return scriptElt[ga](prefix + property);
      }
    });
    Object_defineProperty(scriptElt, "orig" + property, {
      get() {
        return originalAttributeGetter();
      }
    });
  });
  scriptElt[a] = function(event, handler) {
    eventListeners.push([event, handler]);
  };
  scriptElt[sa] = function(property, value) {
    if (capturedAttributes.includes(property)) {
      process.env.DEBUG && c(delta(), "setting attribute ", property, value);
      return value ? originalSetAttribute(prefix + property, value) : scriptElt[ra](prefix + property);
    } else if (["onload", "onerror", "onreadystatechange"].includes(property)) {
      process.env.DEBUG && c(delta(), "setting attribute ", property, value);
      if (value) {
        originalSetAttribute(prefix + property, value);
        originalSetAttribute(property, 'document.dispatchEvent(new CustomEvent("wpmeteor:load", { detail: { event: event, target: this } }))');
      } else {
        scriptElt[ra](property);
        scriptElt[ra](prefix + property, value);
      }
    } else {
      originalSetAttribute(property, value);
    }
  };
  scriptElt[ga] = function(property) {
    return capturedAttributes.indexOf(property) >= 0 ? originalGetAttribute(prefix + property) : originalGetAttribute(property);
  };
  scriptElt[ha] = function(property) {
    return capturedAttributes.indexOf(property) >= 0 ? originalHasAttribute(prefix + property) : originalHasAttribute(property);
  };
  const attributes = scriptElt.attributes;
  Object_defineProperty(scriptElt, "attributes", {
    get() {
      const mock = [...attributes].filter((attr) => attr.name !== "type" && attr.name !== prefix + "after").map((attr) => {
        return {
          name: attr.name.match(new RegExp(prefix)) ? attr.name.replace(prefix, "") : attr.name,
          value: attr.value
        };
      });
      return mock;
    }
  });
  return scriptElt;
};
Object.defineProperty(d, "createElement", {
  set(value) {
    if (process.env.DEBUG) {
      if (value == dOrigCreateElement) {
        process.env.DEBUG && c(delta(), "document.createElement restored to original");
      } else if (value === createElement) {
        process.env.DEBUG && c(delta(), "document.createElement overridden");
      } else {
        process.env.DEBUG && c(delta(), "document.createElement overridden by a 3rd party script");
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
const seenScripts = [];
const observer = new MutationObserver((mutations) => {
  if (iterating) {
    mutations.forEach(({ addedNodes, target }) => {
      addedNodes.forEach((node) => {
        if (node.nodeType === 1) {
          if (S === node.tagName) {
            if ("REORDER" === node[ga](prefix + "after") && (!node[ga](prefix + "type") || isJavascriptRegexp.test(node[ga](prefix + "type")))) {
              process.env.DEBUG && c(delta(), "captured new script", node.cloneNode(true), node);
              const src = node[ga](prefix + "src");
              if (seenScripts.filter((n) => n === node).length) {
                ce("Inserted twice", node);
              }
              if (node.parentNode) {
                seenScripts.push(node);
                if ((src || "").match(/\/gtm.js\?/)) {
                  process.env.DEBUG && c(delta(), "delaying regex", node[ga](prefix + "src"));
                  delayed.push(node);
                  preconnect(src);
                } else if (node[ha](prefix + "async")) {
                  process.env.DEBUG && c(delta(), "delaying async", node[ga](prefix + "src"));
                  delayed.unshift(node);
                  preconnect(src);
                } else {
                  if (src && !node[ga](prefix + "integrity") && !node[ha]("nomodule") && !preloads[src]) {
                    c(delta(), "pre preload", reorder.length);
                    preloadAsScript(src, node[ga](prefix + "type") == "module", node[ha]("crossorigin") && node[ga]("crossorigin"), d.head);
                  }
                  reorder.push(node);
                }
              } else {
                process.env.DEBUG && ce("No parent node for", node, "re-adding to", target);
                node.addEventListener(L, (e) => e.target.parentNode.removeChild(e.target));
                node.addEventListener(E, (e) => e.target.parentNode.removeChild(e.target));
                target.appendChild(node);
              }
            } else {
              process.env.DEBUG && c(delta(), "captured unmodified or non-javascript script", node.cloneNode(true), node);
              dispatcher.emit("s", node.src);
            }
          } else if ("LINK" === node.tagName && node[ga]("as") === "script") {
            preloads[node[ga]("href")] = true;
          }
        }
      });
    });
  }
});
const mutationObserverOptions = {
  childList: true,
  subtree: true,
  attributes: true,
  attributeOldValue: true
};
observer.observe(d.documentElement, mutationObserverOptions);
const origAttachShadow = HTMLElement.prototype.attachShadow;
HTMLElement.prototype.attachShadow = function(options) {
  const shadowRoot = origAttachShadow.call(this, options);
  if (options.mode === "open") {
    observer.observe(shadowRoot, mutationObserverOptions);
  }
  return shadowRoot;
};
dispatcher.on("l", () => {
  if (!createElementOverride || createElementOverride === createElement) {
    d.createElement = dOrigCreateElement;
    observer.disconnect();
  } else {
    process.env.DEBUG && c(delta(), "createElement is overridden, keeping observers in place");
  }
  d.dispatchEvent(new CustomEvent("l"));
});
let documentWrite = (str) => {
  let parent, currentScript;
  if (!d.currentScript || !d.currentScript.parentNode) {
    parent = d.body;
    currentScript = parent.lastChild;
  } else {
    currentScript = d.currentScript;
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
let documentWriteLn = (str) => documentWrite(str + "\n");
Object_defineProperties(d, {
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
let windowAddEventListener = (event, func, ...args) => {
  if ("Window::" + DCL == currentlyFiredEvent && event === DCL && !func.toString().match(/jQueryMock/)) {
    dispatcher.on("l", w.addEventListener.bind(w, event, func, ...args));
    return;
  }
  if ("Window::" + L == currentlyFiredEvent && event === L) {
    dispatcher.on("l", w.addEventListener.bind(w, event, func, ...args));
    return;
  }
  if (func && (event === L || event === DCL || event === M && !DONE)) {
    process.env.DEBUG && c(delta(), "enqueuing event listener", event, func);
    const name = event === DCL ? documentEventPrefix + event : windowEventPrefix + event;
    listeners[name] = listeners[name] || [];
    listeners[name].push(func);
    if (DONE) {
      fireQueuedEvents([event]);
    }
    return;
  }
  return wOrigAddEventListener(event, func, ...args);
};
let windowRemoveEventListener = (event, func) => {
  if (event === L) {
    const name = event === DCL ? documentEventPrefix + event : windowEventPrefix + event;
    removeEventListener(name, func);
  }
  return wOrigRemoveEventListener(event, func);
};
Object_defineProperties(w, {
  [a]: {
    get() {
      return windowAddEventListener;
    },
    set() {
      return windowAddEventListener;
    }
  },
  [r]: {
    get() {
      return windowRemoveEventListener;
    },
    set() {
      return windowRemoveEventListener;
    }
  }
});
const onHandlerOptions = (name) => {
  let handler;
  return {
    get() {
      process.env.DEBUG && c(delta(), separator, "getting " + name.toLowerCase().replace(/::/, ".") + " handler", handler);
      return handler;
    },
    set(func) {
      process.env.DEBUG && c(delta(), separator, "setting " + name.toLowerCase().replace(/::/, ".") + " handler", func);
      if (handler) {
        removeEventListener(name, func);
      }
      listeners[name] = listeners[name] || [];
      listeners[name].push(func);
      return handler = func;
    }
  };
};
dOrigAddEventListener("wpmeteor:load", (e) => {
  const { target, event } = e.detail;
  const el = target === w ? d.body : target;
  const func = el[ga](prefix + "on" + event.type);
  el[ra](prefix + "on" + event.type);
  try {
    const f = new Function("event", func);
    if (target === w) {
      w[a](L, w[a].bind(w, L, f));
    } else {
      f.call(target, event);
    }
  } catch (err) {
    console.err(err);
  }
});
{
  const options = onHandlerOptions(windowEventPrefix + L);
  Object_defineProperty(w, "onload", options);
  dOrigAddEventListener(DCL, () => {
    Object_defineProperty(d.body, "onload", options);
  });
}
Object_defineProperty(d, "onreadystatechange", onHandlerOptions(documentEventPrefix + RSC));
Object_defineProperty(w, "onmessage", onHandlerOptions(windowEventPrefix + M));
if (process.env.DEBUG && location.search.match(/wpmeteorperformance/)) {
  try {
    new PerformanceObserver((entryList) => {
      for (const entry of entryList.getEntries()) {
        c(delta(), "LCP candidate:", entry.startTime, entry);
      }
    }).observe({ type: "largest-contentful-paint", buffered: true });
    new PerformanceObserver((list) => {
      list.getEntries().forEach((e) => c(delta(), "resource loaded", e.name, e));
    }).observe({ type: "resource" });
  } catch (e) {
  }
}
const intersectsViewport = (el) => {
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
const waitForImages = (reallyWait = true) => {
  let imagesToLoad = 1;
  let imagesLoadedCount = -1;
  const seen = {};
  const imageLoadedHandler = () => {
    imagesLoadedCount++;
    if (!--imagesToLoad) {
      process.env.DEBUG && c(delta(), imagesLoadedCount + " eager images loaded");
      nextTick(dispatcher.emit.bind(dispatcher, "i"), _wpmeteor.rdelay);
    }
  };
  Array.from(d.getElementsByTagName("*")).forEach((tag) => {
    let src, style, bgUrl;
    if (tag.tagName === "IMG") {
      let _src = tag.currentSrc || tag.src;
      if (_src && !seen[_src] && !_src.match(/^data:/i)) {
        if ((tag.loading || "").toLowerCase() !== "lazy") {
          src = _src;
          process.env.DEBUG && c(delta(), "loading image", src, "for", tag);
        } else if (intersectsViewport(tag)) {
          src = _src;
          process.env.DEBUG && c(delta(), "loading lazy image", src, "for", tag);
        }
      }
    } else if (tag.tagName === S) {
      preconnect(tag[ga](prefix + "src"));
    } else if (tag.tagName === "LINK" && tag[ga]("as") === "script" && ["pre" + L, "modulepre" + L].indexOf(tag[ga]("rel")) >= 0) {
      preloads[tag[ga]("href")] = true;
    } else if ((style = w.getComputedStyle(tag)) && (bgUrl = (style.backgroundImage || "").match(/^url\s*\((.*?)\)/i)) && (bgUrl || []).length) {
      const url = bgUrl[0].slice(4, -1).replace(/"/g, "");
      if (!seen[url] && !url.match(/^data:/i)) {
        src = url;
        process.env.DEBUG && c(delta(), "loading background", src, "for", tag);
      }
    }
    if (src) {
      seen[src] = true;
      const temp = new Image();
      if (reallyWait) {
        imagesToLoad++;
        temp[a](L, imageLoadedHandler);
        temp[a](E, imageLoadedHandler);
      }
      temp.src = src;
    }
  });
  d.fonts.ready.then(() => {
    process.env.DEBUG && c(delta(), "fonts ready");
    imageLoadedHandler();
  });
};
(() => {
  if (_wpmeteor.rdelay === 0) {
    dOrigAddEventListener(DCL, () => nextTick(waitForImages.bind(null, false)));
  } else {
    wOrigAddEventListener(L, waitForImages);
  }
})();
//# sourceMappingURL=public.js.map
