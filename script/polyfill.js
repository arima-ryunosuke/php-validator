/*
 * polyfill
 *
 * - Array.from()
 * - Array.find()
 * - Array.findIndex()
 * - Element.matches()
 * - Element.closest()
 * - Element.remove()
 * - DocumentFragment.firstElementChild
 * - NodeList.forEach()
 * - new CustomEvent()
 * - Promise
 */
(function (undefined) {
    // @see https://developer.mozilla.org/docs/Web/JavaScript/Reference/Global_Objects/Array/from#Polyfill
    (function () {
        if (!Array.from) {
            Array.from = (function () {
                var toStr = Object.prototype.toString;
                var isCallable = function (fn) {
                    return typeof fn === 'function' || toStr.call(fn) === '[object Function]';
                };
                var toInteger = function (value) {
                    var number = Number(value);
                    if (isNaN(number)) {
                        return 0;
                    }
                    if (number === 0 || !isFinite(number)) {
                        return number;
                    }
                    return (number > 0 ? 1 : -1) * Math.floor(Math.abs(number));
                };
                var maxSafeInteger = Math.pow(2, 53) - 1;
                var toLength = function (value) {
                    var len = toInteger(value);
                    return Math.min(Math.max(len, 0), maxSafeInteger);
                };

                // The length property of the from method is 1.
                return function from(arrayLike/*, mapFn, thisArg */) {
                    // 1. Let C be the this value.
                    var C = this;

                    // 2. Let items be ToObject(arrayLike).
                    var items = Object(arrayLike);

                    // 3. ReturnIfAbrupt(items).
                    if (arrayLike == null) {
                        throw new TypeError('Array.from requires an array-like object - not null or undefined');
                    }

                    // 4. If mapfn is undefined, then let mapping be false.
                    var mapFn = arguments.length > 1 ? arguments[1] : void undefined;
                    var T;
                    if (typeof mapFn !== 'undefined') {
                        // 5. else
                        // 5. a If IsCallable(mapfn) is false, throw a TypeError exception.
                        if (!isCallable(mapFn)) {
                            throw new TypeError('Array.from: when provided, the second argument must be a function');
                        }

                        // 5. b. If thisArg was supplied, let T be thisArg; else let T be undefined.
                        if (arguments.length > 2) {
                            T = arguments[2];
                        }
                    }

                    // 10. Let lenValue be Get(items, "length").
                    // 11. Let len be ToLength(lenValue).
                    var len = toLength(items.length);

                    // 13. If IsConstructor(C) is true, then
                    // 13. a. Let A be the result of calling the [[Construct]] internal method
                    // of C with an argument list containing the single item len.
                    // 14. a. Else, Let A be ArrayCreate(len).
                    var A = isCallable(C) ? Object(new C(len)) : new Array(len);

                    // 16. Let k be 0.
                    var k = 0;
                    // 17. Repeat, while k < len… (also steps a - h)
                    var kValue;
                    while (k < len) {
                        kValue = items[k];
                        if (mapFn) {
                            A[k] = typeof T === 'undefined' ? mapFn(kValue, k) : mapFn.call(T, kValue, k);
                        }
                        else {
                            A[k] = kValue;
                        }
                        k += 1;
                    }
                    // 18. Let putStatus be Put(A, "length", len, true).
                    A.length = len;
                    // 20. Return A.
                    return A;
                };
            }());
        }
    })();

    // @see https://developer.mozilla.org/ja/docs/Web/JavaScript/Reference/Global_Objects/Array/find
    (function () {
        if (!Array.prototype.find) {
            Object.defineProperty(Array.prototype, 'find', {
                value: function (predicate) {
                    // 1. Let O be ? ToObject(this value).
                    if (this == null) {
                        throw TypeError('"this" is null or not defined');
                    }

                    var o = Object(this);

                    // 2. Let len be ? ToLength(? Get(O, "length")).
                    var len = o.length >>> 0;

                    // 3. If IsCallable(predicate) is false, throw a TypeError exception.
                    if (typeof predicate !== 'function') {
                        throw TypeError('predicate must be a function');
                    }

                    // 4. If thisArg was supplied, let T be thisArg; else let T be undefined.
                    var thisArg = arguments[1];

                    // 5. Let k be 0.
                    var k = 0;

                    // 6. Repeat, while k < len
                    while (k < len) {
                        // a. Let Pk be ! ToString(k).
                        // b. Let kValue be ? Get(O, Pk).
                        // c. Let testResult be ToBoolean(? Call(predicate, T, « kValue, k, O »)).
                        // d. If testResult is true, return kValue.
                        var kValue = o[k];
                        if (predicate.call(thisArg, kValue, k, o)) {
                            return kValue;
                        }
                        // e. Increase k by 1.
                        k++;
                    }

                    // 7. Return undefined.
                    return undefined;
                },
                configurable: true,
                writable: true
            });
        }
    })();

    // @see https://developer.mozilla.org/ja/docs/Web/JavaScript/Reference/Global_Objects/Array/findIndex
    (function () {
        if (!Array.prototype.findIndex) {
            Array.prototype.findIndex = function (predicate) {
                if (this === null) {
                    throw new TypeError('Array.prototype.findIndex called on null or undefined');
                }
                if (typeof predicate !== 'function') {
                    throw new TypeError('predicate must be a function');
                }
                var list = Object(this);
                var length = list.length >>> 0;
                var thisArg = arguments[1];
                var value;

                for (var i = 0; i < length; i++) {
                    value = list[i];
                    if (predicate.call(thisArg, value, i, list)) {
                        return i;
                    }
                }
                return -1;
            };
        }
    })();

    // @see https://developer.mozilla.org/docs/Web/API/Element/closest#Polyfill
    (function () {
        if (!Element.prototype.matches) {
            Element.prototype.matches = Element.prototype.msMatchesSelector || Element.prototype.webkitMatchesSelector;
        }

        if (!Element.prototype.closest) {
            Element.prototype.closest = function (s) {
                var el = this;

                do {
                    if (el.matches(s)) {
                        return el;
                    }
                    el = el.parentElement || el.parentNode;
                } while (el !== null && el.nodeType === 1);
                return null;
            };
        }
    })();

    // @see https://developer.mozilla.org/docs/Web/API/ChildNode/remove#Polyfill
    (function (arr) {
        arr.forEach(function (item) {
            if (item.hasOwnProperty('remove')) {
                return;
            }
            Object.defineProperty(item, 'remove', {
                configurable: true,
                enumerable: true,
                writable: true,
                value: function remove() {
                    if (this.parentNode !== null) {
                        this.parentNode.removeChild(this);
                    }
                }
            });
        });
    })([Element.prototype, CharacterData.prototype, DocumentType.prototype]);

    // @see https://developer.mozilla.org/docs/Web/API/ParentNode/firstElementChild#Polyfill_for_IE8_IE9_and_Safari
    (function (constructor) {
        if (constructor && constructor.prototype && !('firstElementChild' in constructor.prototype)) {
            Object.defineProperty(constructor.prototype, 'firstElementChild', {
                get: function () {
                    var node, nodes = this.childNodes, i = 0;
                    while (!!(node = nodes[i++])) {
                        if (node.nodeType === 1) {
                            return node;
                        }
                    }
                    return null;
                }
            });
        }
    })(window.DocumentFragment);

    // @see https://developer.mozilla.org/docs/Web/API/NodeList/forEach#Polyfill
    (function () {
        if (window.NodeList && !NodeList.prototype.forEach) {
            NodeList.prototype.forEach = Array.prototype.forEach;
        }
    })();

    // @see https://developer.mozilla.org/docs/Web/API/CustomEvent/CustomEvent
    (function () {
        if (typeof window.CustomEvent === "function") {
            return false;
        }

        function CustomEvent(event, params) {
            params = params || {bubbles: false, cancelable: false, detail: undefined};
            var evt = document.createEvent('CustomEvent');
            evt.initCustomEvent(event, params.bubbles, params.cancelable, params.detail);
            return evt;
        }

        // noinspection JSValidateTypes
        CustomEvent.prototype = window.Event.prototype;
        // noinspection JSValidateTypes
        window.CustomEvent = CustomEvent;
    })();

    // @see https://www.promisejs.org/polyfills/promise-7.0.4.js
    (function e(t, n, r) {
        function s(o, u) {
            if (!n[o]) {
                if (!t[o]) {
                    var a = typeof require == "function" && require;
                    if (!u && a) {
                        return a(o, !0);
                    }
                    if (i) {
                        return i(o, !0);
                    }
                    var f = new Error("Cannot find module '" + o + "'");
                    throw f.code = "MODULE_NOT_FOUND", f;
                }
                var l = n[o] = {
                    exports: {}
                };
                t[o][0].call(l.exports, function (e) {
                    var n = t[o][1][e];
                    return s(n ? n : e);
                }, l, l.exports, e, t, n, r);
            }
            return n[o].exports;
        }

        var i = typeof require == "function" && require;
        for (var o = 0; o < r.length; o++) {
            s(r[o]);
        }
        return s;
    })({
        1: [function (require, module, exports) {
            "use strict";
            var asap = require("asap/raw");

            function noop() {}

            var LAST_ERROR = null;
            var IS_ERROR = {};

            function getThen(obj) {
                try {
                    return obj.then;
                }
                catch (ex) {
                    LAST_ERROR = ex;
                    return IS_ERROR;
                }
            }

            function tryCallOne(fn, a) {
                try {
                    return fn(a);
                }
                catch (ex) {
                    LAST_ERROR = ex;
                    return IS_ERROR;
                }
            }

            function tryCallTwo(fn, a, b) {
                try {
                    fn(a, b);
                }
                catch (ex) {
                    LAST_ERROR = ex;
                    return IS_ERROR;
                }
            }

            module.exports = Promise;

            function Promise(fn) {
                if (typeof this !== "object") {
                    throw new TypeError("Promises must be constructed via new");
                }
                if (typeof fn !== "function") {
                    throw new TypeError("not a function");
                }
                this._37 = 0;
                this._12 = null;
                this._59 = [];
                if (fn === noop) {
                    return;
                }
                doResolve(fn, this);
            }

            Promise._99 = noop;
            Promise.prototype.then = function (onFulfilled, onRejected) {
                if (this.constructor !== Promise) {
                    return safeThen(this, onFulfilled, onRejected);
                }
                var res = new Promise(noop);
                handle(this, new Handler(onFulfilled, onRejected, res));
                return res;
            };

            function safeThen(self, onFulfilled, onRejected) {
                return new self.constructor(function (resolve, reject) {
                    var res = new Promise(noop);
                    res.then(resolve, reject);
                    handle(self, new Handler(onFulfilled, onRejected, res));
                });
            }

            function handle(self, deferred) {
                while (self._37 === 3) {
                    self = self._12;
                }
                if (self._37 === 0) {
                    self._59.push(deferred);
                    return;
                }
                asap(function () {
                    var cb = self._37 === 1 ? deferred.onFulfilled : deferred.onRejected;
                    if (cb === null) {
                        if (self._37 === 1) {
                            resolve(deferred.promise, self._12);
                        }
                        else {
                            reject(deferred.promise, self._12);
                        }
                        return;
                    }
                    var ret = tryCallOne(cb, self._12);
                    if (ret === IS_ERROR) {
                        reject(deferred.promise, LAST_ERROR);
                    }
                    else {
                        resolve(deferred.promise, ret);
                    }
                });
            }

            function resolve(self, newValue) {
                if (newValue === self) {
                    return reject(self, new TypeError("A promise cannot be resolved with itself."));
                }
                if (newValue && (typeof newValue === "object" || typeof newValue === "function")) {
                    var then = getThen(newValue);
                    if (then === IS_ERROR) {
                        return reject(self, LAST_ERROR);
                    }
                    if (then === self.then && newValue instanceof Promise) {
                        self._37 = 3;
                        self._12 = newValue;
                        finale(self);
                        return;
                    }
                    else if (typeof then === "function") {
                        doResolve(then.bind(newValue), self);
                        return;
                    }
                }
                self._37 = 1;
                self._12 = newValue;
                finale(self);
            }

            function reject(self, newValue) {
                self._37 = 2;
                self._12 = newValue;
                finale(self);
            }

            function finale(self) {
                for (var i = 0; i < self._59.length; i++) {
                    handle(self, self._59[i]);
                }
                self._59 = null;
            }

            function Handler(onFulfilled, onRejected, promise) {
                this.onFulfilled = typeof onFulfilled === "function" ? onFulfilled : null;
                this.onRejected = typeof onRejected === "function" ? onRejected : null;
                this.promise = promise;
            }

            function doResolve(fn, promise) {
                var done = false;
                var res = tryCallTwo(fn, function (value) {
                    if (done) {
                        return;
                    }
                    done = true;
                    resolve(promise, value);
                }, function (reason) {
                    if (done) {
                        return;
                    }
                    done = true;
                    reject(promise, reason);
                });
                if (!done && res === IS_ERROR) {
                    done = true;
                    reject(promise, LAST_ERROR);
                }
            }
        }, {
            "asap/raw": 4
        }],
        2: [function (require, module, exports) {
            "use strict";
            var Promise = require("./core.js");
            module.exports = Promise;
            var TRUE = valuePromise(true);
            var FALSE = valuePromise(false);
            var NULL = valuePromise(null);
            var UNDEFINED = valuePromise(undefined);
            var ZERO = valuePromise(0);
            var EMPTYSTRING = valuePromise("");

            function valuePromise(value) {
                var p = new Promise(Promise._99);
                p._37 = 1;
                p._12 = value;
                return p;
            }

            Promise.resolve = function (value) {
                if (value instanceof Promise) {
                    return value;
                }
                if (value === null) {
                    return NULL;
                }
                if (value === undefined) {
                    return UNDEFINED;
                }
                if (value === true) {
                    return TRUE;
                }
                if (value === false) {
                    return FALSE;
                }
                if (value === 0) {
                    return ZERO;
                }
                if (value === "") {
                    return EMPTYSTRING;
                }
                if (typeof value === "object" || typeof value === "function") {
                    try {
                        var then = value.then;
                        if (typeof then === "function") {
                            return new Promise(then.bind(value));
                        }
                    }
                    catch (ex) {
                        return new Promise(function (resolve, reject) {
                            reject(ex);
                        });
                    }
                }
                return valuePromise(value);
            };
            Promise.all = function (arr) {
                var args = Array.prototype.slice.call(arr);
                return new Promise(function (resolve, reject) {
                    if (args.length === 0) {
                        return resolve([]);
                    }
                    var remaining = args.length;

                    function res(i, val) {
                        if (val && (typeof val === "object" || typeof val === "function")) {
                            if (val instanceof Promise && val.then === Promise.prototype.then) {
                                while (val._37 === 3) {
                                    val = val._12;
                                }
                                if (val._37 === 1) {
                                    return res(i, val._12);
                                }
                                if (val._37 === 2) {
                                    reject(val._12);
                                }
                                val.then(function (val) {
                                    res(i, val);
                                }, reject);
                                return;
                            }
                            else {
                                var then = val.then;
                                if (typeof then === "function") {
                                    var p = new Promise(then.bind(val));
                                    p.then(function (val) {
                                        res(i, val);
                                    }, reject);
                                    return;
                                }
                            }
                        }
                        args[i] = val;
                        if (--remaining === 0) {
                            resolve(args);
                        }
                    }

                    for (var i = 0; i < args.length; i++) {
                        res(i, args[i]);
                    }
                });
            };
            Promise.reject = function (value) {
                return new Promise(function (resolve, reject) {
                    reject(value);
                });
            };
            Promise.race = function (values) {
                return new Promise(function (resolve, reject) {
                    values.forEach(function (value) {
                        Promise.resolve(value).then(resolve, reject);
                    });
                });
            };
            Promise.prototype["catch"] = function (onRejected) {
                return this.then(null, onRejected);
            };
        }, {
            "./core.js": 1
        }],
        3: [function (require, module, exports) {
            "use strict";
            var rawAsap = require("./raw");
            var freeTasks = [];
            var pendingErrors = [];
            var requestErrorThrow = rawAsap.makeRequestCallFromTimer(throwFirstError);

            function throwFirstError() {
                if (pendingErrors.length) {
                    throw pendingErrors.shift();
                }
            }

            module.exports = asap;

            function asap(task) {
                var rawTask;
                if (freeTasks.length) {
                    rawTask = freeTasks.pop();
                }
                else {
                    rawTask = new RawTask();
                }
                rawTask.task = task;
                rawAsap(rawTask);
            }

            function RawTask() {
                this.task = null;
            }

            RawTask.prototype.call = function () {
                try {
                    this.task.call();
                }
                catch (error) {
                    if (asap.onerror) {
                        asap.onerror(error);
                    }
                    else {
                        pendingErrors.push(error);
                        requestErrorThrow();
                    }
                }
                finally {
                    this.task = null;
                    freeTasks[freeTasks.length] = this;
                }
            };
        }, {
            "./raw": 4
        }],
        4: [function (require, module, exports) {
            (function (global) {
                "use strict";
                module.exports = rawAsap;

                function rawAsap(task) {
                    if (!queue.length) {
                        requestFlush();
                        flushing = true;
                    }
                    queue[queue.length] = task;
                }

                var queue = [];
                var flushing = false;
                var requestFlush;
                var index = 0;
                var capacity = 1024;

                function flush() {
                    while (index < queue.length) {
                        var currentIndex = index;
                        index = index + 1;
                        queue[currentIndex].call();
                        if (index > capacity) {
                            for (var scan = 0, newLength = queue.length - index; scan < newLength; scan++) {
                                queue[scan] = queue[scan + index];
                            }
                            queue.length -= index;
                            index = 0;
                        }
                    }
                    queue.length = 0;
                    index = 0;
                    flushing = false;
                }

                var BrowserMutationObserver = global.MutationObserver || global.WebKitMutationObserver;
                if (typeof BrowserMutationObserver === "function") {
                    requestFlush = makeRequestCallFromMutationObserver(flush);
                }
                else {
                    requestFlush = makeRequestCallFromTimer(flush);
                }
                rawAsap.requestFlush = requestFlush;

                function makeRequestCallFromMutationObserver(callback) {
                    var toggle = 1;
                    var observer = new BrowserMutationObserver(callback);
                    var node = document.createTextNode("");
                    observer.observe(node, {
                        characterData: true
                    });
                    return function requestCall() {
                        toggle = -toggle;
                        node.data = toggle;
                    };
                }

                function makeRequestCallFromTimer(callback) {
                    return function requestCall() {
                        var timeoutHandle = setTimeout(handleTimer, 0);
                        var intervalHandle = setInterval(handleTimer, 50);

                        function handleTimer() {
                            clearTimeout(timeoutHandle);
                            clearInterval(intervalHandle);
                            callback();
                        }
                    };
                }

                rawAsap.makeRequestCallFromTimer = makeRequestCallFromTimer;
            }).call(this, typeof global !== "undefined" ? global : typeof self !== "undefined" ? self : typeof window !== "undefined" ? window : {});
        }, {}],
        5: [function (require, module, exports) {
            if (typeof Promise.prototype.done !== "function") {
                Promise.prototype.done = function (onFulfilled, onRejected) {
                    var self = arguments.length ? this.then.apply(this, arguments) : this;
                    self.then(null, function (err) {
                        setTimeout(function () {
                            throw err;
                        }, 0);
                    });
                };
            }
        }, {}],
        6: [function (require, module, exports) {
            var asap = require("asap");
            if (typeof Promise === "undefined") {
                Promise = require("./lib/core.js");
                require("./lib/es6-extensions.js");
            }
            require("./polyfill-done.js");
        }, {
            "./lib/core.js": 1,
            "./lib/es6-extensions.js": 2,
            "./polyfill-done.js": 5,
            asap: 3
        }]
    }, {}, [6]);
})();
