/******/ (() => { // webpackBootstrap
/******/ 	var __webpack_modules__ = ({

/***/ "./node_modules/@babel/runtime/regenerator/index.js":
/*!**********************************************************!*\
  !*** ./node_modules/@babel/runtime/regenerator/index.js ***!
  \**********************************************************/
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

module.exports = __webpack_require__(/*! regenerator-runtime */ "./node_modules/regenerator-runtime/runtime.js");


/***/ }),

/***/ "./node_modules/regenerator-runtime/runtime.js":
/*!*****************************************************!*\
  !*** ./node_modules/regenerator-runtime/runtime.js ***!
  \*****************************************************/
/***/ ((module) => {

/**
 * Copyright (c) 2014-present, Facebook, Inc.
 *
 * This source code is licensed under the MIT license found in the
 * LICENSE file in the root directory of this source tree.
 */

var runtime = (function (exports) {
  "use strict";

  var Op = Object.prototype;
  var hasOwn = Op.hasOwnProperty;
  var undefined; // More compressible than void 0.
  var $Symbol = typeof Symbol === "function" ? Symbol : {};
  var iteratorSymbol = $Symbol.iterator || "@@iterator";
  var asyncIteratorSymbol = $Symbol.asyncIterator || "@@asyncIterator";
  var toStringTagSymbol = $Symbol.toStringTag || "@@toStringTag";

  function define(obj, key, value) {
    Object.defineProperty(obj, key, {
      value: value,
      enumerable: true,
      configurable: true,
      writable: true
    });
    return obj[key];
  }
  try {
    // IE 8 has a broken Object.defineProperty that only works on DOM objects.
    define({}, "");
  } catch (err) {
    define = function(obj, key, value) {
      return obj[key] = value;
    };
  }

  function wrap(innerFn, outerFn, self, tryLocsList) {
    // If outerFn provided and outerFn.prototype is a Generator, then outerFn.prototype instanceof Generator.
    var protoGenerator = outerFn && outerFn.prototype instanceof Generator ? outerFn : Generator;
    var generator = Object.create(protoGenerator.prototype);
    var context = new Context(tryLocsList || []);

    // The ._invoke method unifies the implementations of the .next,
    // .throw, and .return methods.
    generator._invoke = makeInvokeMethod(innerFn, self, context);

    return generator;
  }
  exports.wrap = wrap;

  // Try/catch helper to minimize deoptimizations. Returns a completion
  // record like context.tryEntries[i].completion. This interface could
  // have been (and was previously) designed to take a closure to be
  // invoked without arguments, but in all the cases we care about we
  // already have an existing method we want to call, so there's no need
  // to create a new function object. We can even get away with assuming
  // the method takes exactly one argument, since that happens to be true
  // in every case, so we don't have to touch the arguments object. The
  // only additional allocation required is the completion record, which
  // has a stable shape and so hopefully should be cheap to allocate.
  function tryCatch(fn, obj, arg) {
    try {
      return { type: "normal", arg: fn.call(obj, arg) };
    } catch (err) {
      return { type: "throw", arg: err };
    }
  }

  var GenStateSuspendedStart = "suspendedStart";
  var GenStateSuspendedYield = "suspendedYield";
  var GenStateExecuting = "executing";
  var GenStateCompleted = "completed";

  // Returning this object from the innerFn has the same effect as
  // breaking out of the dispatch switch statement.
  var ContinueSentinel = {};

  // Dummy constructor functions that we use as the .constructor and
  // .constructor.prototype properties for functions that return Generator
  // objects. For full spec compliance, you may wish to configure your
  // minifier not to mangle the names of these two functions.
  function Generator() {}
  function GeneratorFunction() {}
  function GeneratorFunctionPrototype() {}

  // This is a polyfill for %IteratorPrototype% for environments that
  // don't natively support it.
  var IteratorPrototype = {};
  IteratorPrototype[iteratorSymbol] = function () {
    return this;
  };

  var getProto = Object.getPrototypeOf;
  var NativeIteratorPrototype = getProto && getProto(getProto(values([])));
  if (NativeIteratorPrototype &&
      NativeIteratorPrototype !== Op &&
      hasOwn.call(NativeIteratorPrototype, iteratorSymbol)) {
    // This environment has a native %IteratorPrototype%; use it instead
    // of the polyfill.
    IteratorPrototype = NativeIteratorPrototype;
  }

  var Gp = GeneratorFunctionPrototype.prototype =
    Generator.prototype = Object.create(IteratorPrototype);
  GeneratorFunction.prototype = Gp.constructor = GeneratorFunctionPrototype;
  GeneratorFunctionPrototype.constructor = GeneratorFunction;
  GeneratorFunction.displayName = define(
    GeneratorFunctionPrototype,
    toStringTagSymbol,
    "GeneratorFunction"
  );

  // Helper for defining the .next, .throw, and .return methods of the
  // Iterator interface in terms of a single ._invoke method.
  function defineIteratorMethods(prototype) {
    ["next", "throw", "return"].forEach(function(method) {
      define(prototype, method, function(arg) {
        return this._invoke(method, arg);
      });
    });
  }

  exports.isGeneratorFunction = function(genFun) {
    var ctor = typeof genFun === "function" && genFun.constructor;
    return ctor
      ? ctor === GeneratorFunction ||
        // For the native GeneratorFunction constructor, the best we can
        // do is to check its .name property.
        (ctor.displayName || ctor.name) === "GeneratorFunction"
      : false;
  };

  exports.mark = function(genFun) {
    if (Object.setPrototypeOf) {
      Object.setPrototypeOf(genFun, GeneratorFunctionPrototype);
    } else {
      genFun.__proto__ = GeneratorFunctionPrototype;
      define(genFun, toStringTagSymbol, "GeneratorFunction");
    }
    genFun.prototype = Object.create(Gp);
    return genFun;
  };

  // Within the body of any async function, `await x` is transformed to
  // `yield regeneratorRuntime.awrap(x)`, so that the runtime can test
  // `hasOwn.call(value, "__await")` to determine if the yielded value is
  // meant to be awaited.
  exports.awrap = function(arg) {
    return { __await: arg };
  };

  function AsyncIterator(generator, PromiseImpl) {
    function invoke(method, arg, resolve, reject) {
      var record = tryCatch(generator[method], generator, arg);
      if (record.type === "throw") {
        reject(record.arg);
      } else {
        var result = record.arg;
        var value = result.value;
        if (value &&
            typeof value === "object" &&
            hasOwn.call(value, "__await")) {
          return PromiseImpl.resolve(value.__await).then(function(value) {
            invoke("next", value, resolve, reject);
          }, function(err) {
            invoke("throw", err, resolve, reject);
          });
        }

        return PromiseImpl.resolve(value).then(function(unwrapped) {
          // When a yielded Promise is resolved, its final value becomes
          // the .value of the Promise<{value,done}> result for the
          // current iteration.
          result.value = unwrapped;
          resolve(result);
        }, function(error) {
          // If a rejected Promise was yielded, throw the rejection back
          // into the async generator function so it can be handled there.
          return invoke("throw", error, resolve, reject);
        });
      }
    }

    var previousPromise;

    function enqueue(method, arg) {
      function callInvokeWithMethodAndArg() {
        return new PromiseImpl(function(resolve, reject) {
          invoke(method, arg, resolve, reject);
        });
      }

      return previousPromise =
        // If enqueue has been called before, then we want to wait until
        // all previous Promises have been resolved before calling invoke,
        // so that results are always delivered in the correct order. If
        // enqueue has not been called before, then it is important to
        // call invoke immediately, without waiting on a callback to fire,
        // so that the async generator function has the opportunity to do
        // any necessary setup in a predictable way. This predictability
        // is why the Promise constructor synchronously invokes its
        // executor callback, and why async functions synchronously
        // execute code before the first await. Since we implement simple
        // async functions in terms of async generators, it is especially
        // important to get this right, even though it requires care.
        previousPromise ? previousPromise.then(
          callInvokeWithMethodAndArg,
          // Avoid propagating failures to Promises returned by later
          // invocations of the iterator.
          callInvokeWithMethodAndArg
        ) : callInvokeWithMethodAndArg();
    }

    // Define the unified helper method that is used to implement .next,
    // .throw, and .return (see defineIteratorMethods).
    this._invoke = enqueue;
  }

  defineIteratorMethods(AsyncIterator.prototype);
  AsyncIterator.prototype[asyncIteratorSymbol] = function () {
    return this;
  };
  exports.AsyncIterator = AsyncIterator;

  // Note that simple async functions are implemented on top of
  // AsyncIterator objects; they just return a Promise for the value of
  // the final result produced by the iterator.
  exports.async = function(innerFn, outerFn, self, tryLocsList, PromiseImpl) {
    if (PromiseImpl === void 0) PromiseImpl = Promise;

    var iter = new AsyncIterator(
      wrap(innerFn, outerFn, self, tryLocsList),
      PromiseImpl
    );

    return exports.isGeneratorFunction(outerFn)
      ? iter // If outerFn is a generator, return the full iterator.
      : iter.next().then(function(result) {
          return result.done ? result.value : iter.next();
        });
  };

  function makeInvokeMethod(innerFn, self, context) {
    var state = GenStateSuspendedStart;

    return function invoke(method, arg) {
      if (state === GenStateExecuting) {
        throw new Error("Generator is already running");
      }

      if (state === GenStateCompleted) {
        if (method === "throw") {
          throw arg;
        }

        // Be forgiving, per 25.3.3.3.3 of the spec:
        // https://people.mozilla.org/~jorendorff/es6-draft.html#sec-generatorresume
        return doneResult();
      }

      context.method = method;
      context.arg = arg;

      while (true) {
        var delegate = context.delegate;
        if (delegate) {
          var delegateResult = maybeInvokeDelegate(delegate, context);
          if (delegateResult) {
            if (delegateResult === ContinueSentinel) continue;
            return delegateResult;
          }
        }

        if (context.method === "next") {
          // Setting context._sent for legacy support of Babel's
          // function.sent implementation.
          context.sent = context._sent = context.arg;

        } else if (context.method === "throw") {
          if (state === GenStateSuspendedStart) {
            state = GenStateCompleted;
            throw context.arg;
          }

          context.dispatchException(context.arg);

        } else if (context.method === "return") {
          context.abrupt("return", context.arg);
        }

        state = GenStateExecuting;

        var record = tryCatch(innerFn, self, context);
        if (record.type === "normal") {
          // If an exception is thrown from innerFn, we leave state ===
          // GenStateExecuting and loop back for another invocation.
          state = context.done
            ? GenStateCompleted
            : GenStateSuspendedYield;

          if (record.arg === ContinueSentinel) {
            continue;
          }

          return {
            value: record.arg,
            done: context.done
          };

        } else if (record.type === "throw") {
          state = GenStateCompleted;
          // Dispatch the exception by looping back around to the
          // context.dispatchException(context.arg) call above.
          context.method = "throw";
          context.arg = record.arg;
        }
      }
    };
  }

  // Call delegate.iterator[context.method](context.arg) and handle the
  // result, either by returning a { value, done } result from the
  // delegate iterator, or by modifying context.method and context.arg,
  // setting context.delegate to null, and returning the ContinueSentinel.
  function maybeInvokeDelegate(delegate, context) {
    var method = delegate.iterator[context.method];
    if (method === undefined) {
      // A .throw or .return when the delegate iterator has no .throw
      // method always terminates the yield* loop.
      context.delegate = null;

      if (context.method === "throw") {
        // Note: ["return"] must be used for ES3 parsing compatibility.
        if (delegate.iterator["return"]) {
          // If the delegate iterator has a return method, give it a
          // chance to clean up.
          context.method = "return";
          context.arg = undefined;
          maybeInvokeDelegate(delegate, context);

          if (context.method === "throw") {
            // If maybeInvokeDelegate(context) changed context.method from
            // "return" to "throw", let that override the TypeError below.
            return ContinueSentinel;
          }
        }

        context.method = "throw";
        context.arg = new TypeError(
          "The iterator does not provide a 'throw' method");
      }

      return ContinueSentinel;
    }

    var record = tryCatch(method, delegate.iterator, context.arg);

    if (record.type === "throw") {
      context.method = "throw";
      context.arg = record.arg;
      context.delegate = null;
      return ContinueSentinel;
    }

    var info = record.arg;

    if (! info) {
      context.method = "throw";
      context.arg = new TypeError("iterator result is not an object");
      context.delegate = null;
      return ContinueSentinel;
    }

    if (info.done) {
      // Assign the result of the finished delegate to the temporary
      // variable specified by delegate.resultName (see delegateYield).
      context[delegate.resultName] = info.value;

      // Resume execution at the desired location (see delegateYield).
      context.next = delegate.nextLoc;

      // If context.method was "throw" but the delegate handled the
      // exception, let the outer generator proceed normally. If
      // context.method was "next", forget context.arg since it has been
      // "consumed" by the delegate iterator. If context.method was
      // "return", allow the original .return call to continue in the
      // outer generator.
      if (context.method !== "return") {
        context.method = "next";
        context.arg = undefined;
      }

    } else {
      // Re-yield the result returned by the delegate method.
      return info;
    }

    // The delegate iterator is finished, so forget it and continue with
    // the outer generator.
    context.delegate = null;
    return ContinueSentinel;
  }

  // Define Generator.prototype.{next,throw,return} in terms of the
  // unified ._invoke helper method.
  defineIteratorMethods(Gp);

  define(Gp, toStringTagSymbol, "Generator");

  // A Generator should always return itself as the iterator object when the
  // @@iterator function is called on it. Some browsers' implementations of the
  // iterator prototype chain incorrectly implement this, causing the Generator
  // object to not be returned from this call. This ensures that doesn't happen.
  // See https://github.com/facebook/regenerator/issues/274 for more details.
  Gp[iteratorSymbol] = function() {
    return this;
  };

  Gp.toString = function() {
    return "[object Generator]";
  };

  function pushTryEntry(locs) {
    var entry = { tryLoc: locs[0] };

    if (1 in locs) {
      entry.catchLoc = locs[1];
    }

    if (2 in locs) {
      entry.finallyLoc = locs[2];
      entry.afterLoc = locs[3];
    }

    this.tryEntries.push(entry);
  }

  function resetTryEntry(entry) {
    var record = entry.completion || {};
    record.type = "normal";
    delete record.arg;
    entry.completion = record;
  }

  function Context(tryLocsList) {
    // The root entry object (effectively a try statement without a catch
    // or a finally block) gives us a place to store values thrown from
    // locations where there is no enclosing try statement.
    this.tryEntries = [{ tryLoc: "root" }];
    tryLocsList.forEach(pushTryEntry, this);
    this.reset(true);
  }

  exports.keys = function(object) {
    var keys = [];
    for (var key in object) {
      keys.push(key);
    }
    keys.reverse();

    // Rather than returning an object with a next method, we keep
    // things simple and return the next function itself.
    return function next() {
      while (keys.length) {
        var key = keys.pop();
        if (key in object) {
          next.value = key;
          next.done = false;
          return next;
        }
      }

      // To avoid creating an additional object, we just hang the .value
      // and .done properties off the next function object itself. This
      // also ensures that the minifier will not anonymize the function.
      next.done = true;
      return next;
    };
  };

  function values(iterable) {
    if (iterable) {
      var iteratorMethod = iterable[iteratorSymbol];
      if (iteratorMethod) {
        return iteratorMethod.call(iterable);
      }

      if (typeof iterable.next === "function") {
        return iterable;
      }

      if (!isNaN(iterable.length)) {
        var i = -1, next = function next() {
          while (++i < iterable.length) {
            if (hasOwn.call(iterable, i)) {
              next.value = iterable[i];
              next.done = false;
              return next;
            }
          }

          next.value = undefined;
          next.done = true;

          return next;
        };

        return next.next = next;
      }
    }

    // Return an iterator with no values.
    return { next: doneResult };
  }
  exports.values = values;

  function doneResult() {
    return { value: undefined, done: true };
  }

  Context.prototype = {
    constructor: Context,

    reset: function(skipTempReset) {
      this.prev = 0;
      this.next = 0;
      // Resetting context._sent for legacy support of Babel's
      // function.sent implementation.
      this.sent = this._sent = undefined;
      this.done = false;
      this.delegate = null;

      this.method = "next";
      this.arg = undefined;

      this.tryEntries.forEach(resetTryEntry);

      if (!skipTempReset) {
        for (var name in this) {
          // Not sure about the optimal order of these conditions:
          if (name.charAt(0) === "t" &&
              hasOwn.call(this, name) &&
              !isNaN(+name.slice(1))) {
            this[name] = undefined;
          }
        }
      }
    },

    stop: function() {
      this.done = true;

      var rootEntry = this.tryEntries[0];
      var rootRecord = rootEntry.completion;
      if (rootRecord.type === "throw") {
        throw rootRecord.arg;
      }

      return this.rval;
    },

    dispatchException: function(exception) {
      if (this.done) {
        throw exception;
      }

      var context = this;
      function handle(loc, caught) {
        record.type = "throw";
        record.arg = exception;
        context.next = loc;

        if (caught) {
          // If the dispatched exception was caught by a catch block,
          // then let that catch block handle the exception normally.
          context.method = "next";
          context.arg = undefined;
        }

        return !! caught;
      }

      for (var i = this.tryEntries.length - 1; i >= 0; --i) {
        var entry = this.tryEntries[i];
        var record = entry.completion;

        if (entry.tryLoc === "root") {
          // Exception thrown outside of any try block that could handle
          // it, so set the completion value of the entire function to
          // throw the exception.
          return handle("end");
        }

        if (entry.tryLoc <= this.prev) {
          var hasCatch = hasOwn.call(entry, "catchLoc");
          var hasFinally = hasOwn.call(entry, "finallyLoc");

          if (hasCatch && hasFinally) {
            if (this.prev < entry.catchLoc) {
              return handle(entry.catchLoc, true);
            } else if (this.prev < entry.finallyLoc) {
              return handle(entry.finallyLoc);
            }

          } else if (hasCatch) {
            if (this.prev < entry.catchLoc) {
              return handle(entry.catchLoc, true);
            }

          } else if (hasFinally) {
            if (this.prev < entry.finallyLoc) {
              return handle(entry.finallyLoc);
            }

          } else {
            throw new Error("try statement without catch or finally");
          }
        }
      }
    },

    abrupt: function(type, arg) {
      for (var i = this.tryEntries.length - 1; i >= 0; --i) {
        var entry = this.tryEntries[i];
        if (entry.tryLoc <= this.prev &&
            hasOwn.call(entry, "finallyLoc") &&
            this.prev < entry.finallyLoc) {
          var finallyEntry = entry;
          break;
        }
      }

      if (finallyEntry &&
          (type === "break" ||
           type === "continue") &&
          finallyEntry.tryLoc <= arg &&
          arg <= finallyEntry.finallyLoc) {
        // Ignore the finally entry if control is not jumping to a
        // location outside the try/catch block.
        finallyEntry = null;
      }

      var record = finallyEntry ? finallyEntry.completion : {};
      record.type = type;
      record.arg = arg;

      if (finallyEntry) {
        this.method = "next";
        this.next = finallyEntry.finallyLoc;
        return ContinueSentinel;
      }

      return this.complete(record);
    },

    complete: function(record, afterLoc) {
      if (record.type === "throw") {
        throw record.arg;
      }

      if (record.type === "break" ||
          record.type === "continue") {
        this.next = record.arg;
      } else if (record.type === "return") {
        this.rval = this.arg = record.arg;
        this.method = "return";
        this.next = "end";
      } else if (record.type === "normal" && afterLoc) {
        this.next = afterLoc;
      }

      return ContinueSentinel;
    },

    finish: function(finallyLoc) {
      for (var i = this.tryEntries.length - 1; i >= 0; --i) {
        var entry = this.tryEntries[i];
        if (entry.finallyLoc === finallyLoc) {
          this.complete(entry.completion, entry.afterLoc);
          resetTryEntry(entry);
          return ContinueSentinel;
        }
      }
    },

    "catch": function(tryLoc) {
      for (var i = this.tryEntries.length - 1; i >= 0; --i) {
        var entry = this.tryEntries[i];
        if (entry.tryLoc === tryLoc) {
          var record = entry.completion;
          if (record.type === "throw") {
            var thrown = record.arg;
            resetTryEntry(entry);
          }
          return thrown;
        }
      }

      // The context.catch method must only be called with a location
      // argument that corresponds to a known catch block.
      throw new Error("illegal catch attempt");
    },

    delegateYield: function(iterable, resultName, nextLoc) {
      this.delegate = {
        iterator: values(iterable),
        resultName: resultName,
        nextLoc: nextLoc
      };

      if (this.method === "next") {
        // Deliberately forget the last sent value so that we don't
        // accidentally pass it on to the delegate.
        this.arg = undefined;
      }

      return ContinueSentinel;
    }
  };

  // Regardless of whether this script is executing as a CommonJS module
  // or not, return the runtime object so that we can declare the variable
  // regeneratorRuntime in the outer scope, which allows this module to be
  // injected easily by `bin/regenerator --include-runtime script.js`.
  return exports;

}(
  // If this script is executing as a CommonJS module, use module.exports
  // as the regeneratorRuntime namespace. Otherwise create a new empty
  // object. Either way, the resulting object will be used to initialize
  // the regeneratorRuntime variable at the top of this file.
   true ? module.exports : 0
));

try {
  regeneratorRuntime = runtime;
} catch (accidentalStrictMode) {
  // This module should not be running in strict mode, so the above
  // assignment should always work unless something is misconfigured. Just
  // in case runtime.js accidentally runs in strict mode, we can escape
  // strict mode using a global Function call. This could conceivably fail
  // if a Content Security Policy forbids using Function, but in that case
  // the proper solution is to fix the accidental strict mode problem. If
  // you've misconfigured your bundler to force strict mode and applied a
  // CSP to forbid Function, and you're not willing to fix either of those
  // problems, please detail your unique predicament in a GitHub issue.
  Function("r", "regeneratorRuntime = r")(runtime);
}


/***/ })

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		var cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/compat get default export */
/******/ 	(() => {
/******/ 		// getDefaultExport function for compatibility with non-harmony modules
/******/ 		__webpack_require__.n = (module) => {
/******/ 			var getter = module && module.__esModule ?
/******/ 				() => (module['default']) :
/******/ 				() => (module);
/******/ 			__webpack_require__.d(getter, { a: getter });
/******/ 			return getter;
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/define property getters */
/******/ 	(() => {
/******/ 		// define getter functions for harmony exports
/******/ 		__webpack_require__.d = (exports, definition) => {
/******/ 			for(var key in definition) {
/******/ 				if(__webpack_require__.o(definition, key) && !__webpack_require__.o(exports, key)) {
/******/ 					Object.defineProperty(exports, key, { enumerable: true, get: definition[key] });
/******/ 				}
/******/ 			}
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	(() => {
/******/ 		__webpack_require__.o = (obj, prop) => (Object.prototype.hasOwnProperty.call(obj, prop))
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	(() => {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = (exports) => {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	})();
/******/ 	
/************************************************************************/
var __webpack_exports__ = {};
// This entry need to be wrapped in an IIFE because it need to be in strict mode.
(() => {
"use strict";
/*!**********************************!*\
  !*** ./resources/js/ant-rack.js ***!
  \**********************************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/regenerator */ "./node_modules/@babel/runtime/regenerator/index.js");
/* harmony import */ var _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0__);


function asyncGeneratorStep(gen, resolve, reject, _next, _throw, key, arg) { try { var info = gen[key](arg); var value = info.value; } catch (error) { reject(error); return; } if (info.done) { resolve(value); } else { Promise.resolve(value).then(_next, _throw); } }

function _asyncToGenerator(fn) { return function () { var self = this, args = arguments; return new Promise(function (resolve, reject) { var gen = fn.apply(self, args); function _next(value) { asyncGeneratorStep(gen, resolve, reject, _next, _throw, "next", value); } function _throw(err) { asyncGeneratorStep(gen, resolve, reject, _next, _throw, "throw", err); } _next(undefined); }); }; }

// create a new div with id "ant-rack-app" and append it to document.body
var antRackApp = document.createElement("div");
antRackApp.id = "ant-rack-app";
antRackApp.style = "z-index:2147483647";
document.body.append(antRackApp); // check if the page is products page

var isProductsPage = location.pathname.includes("/products/"); // local currency

var antRackLocalCurrency = window.localStorage.getItem("ant-rack-local-currency");

if (!antRackLocalCurrency) {
  fetch(upsellRockBaseUrl + "/ip?shop=" + upsellRockShopDomain).then(function (response) {
    return response.json();
  }).then(function (json) {
    if (json.geoplugin_currencyCode) {
      antRackLocalCurrency = json.geoplugin_currencyCode;
      window.localStorage.setItem("ant-rack-local-currency", antRackLocalCurrency);
    } else {
      antRackLocalCurrency = shopCurrency;
      window.localStorage.setItem("ant-rack-local-currency", antRackLocalCurrency);
    }
  });
} // find ATC


var addToCartButtons = document.querySelectorAll('form[action^="/cart/add"] [type^="submit"]');

if (addToCartButtons.length > 0) {
  addToCartButtons.forEach(function (addToCartButton) {
    var newAddToCartButton = addToCartButton.cloneNode(true);
    addToCartButton.style.display = "none";
    addToCartButton.classList.add("ant-rack");
    addToCartButton.classList.add("ant-rack-original");
    newAddToCartButton.classList.add("ant-rack");
    newAddToCartButton.classList.add("ant-rack-cloned");
    addToCartButton.parentNode.insertBefore(newAddToCartButton, addToCartButton.nextSibling);
    newAddToCartButton.addEventListener("click", function (e) {
      console.log("newAddToCartButton", "click");
      e.stopPropagation();
      e.preventDefault(); // add current product to cart
      // addCurrentVariantToCart();
      // write ant rack max popup limit

      var antRackMaxPopupLimit = JSON.parse(localStorage.getItem("ant-rack-max-popup-limit"));

      if (!antRackMaxPopupLimit) {
        antRackMaxPopupLimit = 1;
        localStorage.setItem("ant-rack-max-popup-limit", antRackMaxPopupLimit);
      } else if (upsellRockSetting.max_popup_session_views > 0 && upsellRockSetting.max_popup_session_views <= antRackMaxPopupLimit) {
        // original click
        var originalAddToCartButton = document.querySelector(".ant-rack.ant-rack-original");

        if (originalAddToCartButton) {
          originalAddToCartButton.click();
          return;
        }
      } // update ant rack popup localStorage


      antRackMaxPopupLimit++;
      localStorage.setItem("ant-rack-max-popup-limit", antRackMaxPopupLimit); //
      // show antRackApp

      var antBgWrap = document.getElementById("ant-bg-wrap");
      var antBgOverlay = document.getElementById("ant-bg-overlay");
      var antModalPanel = document.getElementById("ant-modal-panel");
      antBgWrap.classList.remove("hidden");
      setTimeout(function () {
        antBgOverlay.classList.remove("opacity-0");
        antBgOverlay.classList.add("opacity-100");
        antModalPanel.classList.remove("opacity-0");
        antModalPanel.classList.remove("translate-y-4");
        antModalPanel.classList.remove("sm:scale-95");
        antModalPanel.classList.remove("sm:translate-y-0");
        antModalPanel.classList.add("opacity-100");
        antModalPanel.classList.add("translate-y-0");
        antModalPanel.classList.add("sm:scale-100");
      }, 200); // track view

      var upsells = JSON.parse(document.getElementById("upsells").innerText);
      var token = localStorage.getItem("ant-rack-token");

      if (token) {
        trackView(token, upsells, "view");
      }
    });
  });
}

function addCurrentVariantToCart() {
  return _addCurrentVariantToCart.apply(this, arguments);
} // load css and js


function _addCurrentVariantToCart() {
  _addCurrentVariantToCart = _asyncToGenerator( /*#__PURE__*/_babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default().mark(function _callee() {
    var addToCartForm, formData, res, json;
    return _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default().wrap(function _callee$(_context) {
      while (1) {
        switch (_context.prev = _context.next) {
          case 0:
            addToCartForm = document.querySelector('form[action="/cart/add"]');
            formData = new FormData(addToCartForm);
            _context.next = 4;
            return fetch("/cart/add.js", {
              method: "POST",
              body: formData
            });

          case 4:
            res = _context.sent;
            _context.next = 7;
            return res.json();

          case 7:
            json = _context.sent;
            return _context.abrupt("return", json);

          case 9:
          case "end":
            return _context.stop();
        }
      }
    }, _callee);
  }));
  return _addCurrentVariantToCart.apply(this, arguments);
}

if (isProductsPage) {
  var tailwindcss = document.createElement("link");
  tailwindcss.href = antRackCssUrl;
  tailwindcss.rel = "stylesheet";
  tailwindcss.type = "text/css";
  document.head.prepend(tailwindcss);
  var js = document.createElement("script");
  js.src = antRackJsUrl;
  js.type = "text/javascript";
  document.body.append(js); // load html

  fetch("/cart.js").then(function (res) {
    return res.json();
  }).then(function (json) {
    localStorage.setItem("ant-rack-token", json.token);
    loadAntRackHtml(json);
    updateAntRackAttribute(json);
  }); // get recommendations

  getCurrentProduct().then(function (product) {
    getShopifyRecommendedProducts(product.id);
  });
}

function loadAntRackHtml(_x) {
  return _loadAntRackHtml.apply(this, arguments);
}

function _loadAntRackHtml() {
  _loadAntRackHtml = _asyncToGenerator( /*#__PURE__*/_babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default().mark(function _callee2(cart) {
    var addToCartForm, formData, variant_id, res, html;
    return _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default().wrap(function _callee2$(_context2) {
      while (1) {
        switch (_context2.prev = _context2.next) {
          case 0:
            // load html
            console.log("loadAntRackHtml"); // get variant_id

            addToCartForm = document.querySelector('form[action="/cart/add"]');
            formData = new FormData(addToCartForm);
            variant_id = 0;
            formData.forEach(function (v, k) {
              if (k == "id") {
                variant_id = v;
              }
            });
            _context2.next = 7;
            return fetch(upsellRockBaseUrl + "/ant_rack", {
              method: "POST",
              headers: {
                "Content-Type": "application/json"
              },
              body: JSON.stringify({
                shop: upsellRockShopDomain,
                cart: cart,
                variant: variant_id
              })
            });

          case 7:
            res = _context2.sent;
            _context2.next = 10;
            return res.text();

          case 10:
            html = _context2.sent;

            if (!(!html || html.length == 0)) {
              _context2.next = 14;
              break;
            }

            resetATCs();
            return _context2.abrupt("return");

          case 14:
            antRackApp.innerHTML = html; //close event

            setTimeout(function () {
              var antBgWrap = document.getElementById("ant-bg-wrap");
              var antBgOverlay = document.getElementById("ant-bg-overlay");
              var antModalPanel = document.getElementById("ant-modal-panel");
              var antCancelBtns = document.querySelectorAll(".ant-cancel-btn");
              antCancelBtns.forEach(function (antCancelBtn) {
                antCancelBtn.addEventListener("click", function (e) {
                  antModalPanel.classList.remove("ease-out");
                  antModalPanel.classList.remove("duration-300");
                  antModalPanel.classList.remove("opacity-100");
                  antModalPanel.classList.remove("translate-y-0");
                  antModalPanel.classList.remove("sm:scale-100");
                  antModalPanel.classList.add("ease-in");
                  antModalPanel.classList.add("duration-200");
                  antModalPanel.classList.add("opacity-0");
                  antModalPanel.classList.add("translate-y-4");
                  antModalPanel.classList.add("sm:translate-y-0");
                  antModalPanel.classList.add("sm:scale-95");
                  antBgOverlay.classList.remove("ease-out");
                  antBgOverlay.classList.remove("duration-300");
                  antBgOverlay.classList.remove("opacity-100");
                  antBgOverlay.classList.add("ease-in");
                  antBgOverlay.classList.add("duration-200");
                  antBgOverlay.classList.add("opacity-0");
                  setTimeout(function () {
                    antBgWrap.classList.add("hidden");
                    console.log("outside cancel"); // dell with close action

                    if (upsellRockSetting.close_action == "") {
                      // add current product to cart
                      var antRackOriginal = document.querySelector(".ant-rack.ant-rack-original");

                      if (antRackOriginal) {
                        antRackOriginal.click();
                      }
                    } else if (upsellRockSetting.close_action == "close") {
                      addCurrentVariantToCart();
                    } else if (upsellRockSetting.close_action == "redirect_to_cart") {
                      addCurrentVariantToCart();
                      setTimeout(function () {
                        location.href = "/cart";
                      }, 200);
                    } else if (upsellRockSetting.close_action == "redirect_to_checkout") {
                      addCurrentVariantToCart();
                      setTimeout(function () {
                        location.href = "/checkout";
                      }, 200);
                    }
                  }, 200);
                });
              });
              var upsells = JSON.parse(document.getElementById("upsells").innerText);
              trackView(cart.token, upsells, "load");
            }, 500);

          case 16:
          case "end":
            return _context2.stop();
        }
      }
    }, _callee2);
  }));
  return _loadAntRackHtml.apply(this, arguments);
}

function resetATCs() {
  var newATCs = document.querySelectorAll(".ant-rack.ant-rack-cloned");
  newATCs.forEach(function (atc) {
    atc.style.display = "none";
  });
  var originalATCs = document.querySelectorAll(".ant-rack.ant-rack-original");
  originalATCs.forEach(function (atc) {
    atc.style.display = "";
  });
}

function trackView(token, upsells, event_type) {
  fetch(upsellRockBaseUrl + "/track", {
    method: "POST",
    headers: {
      "Content-Type": "application/json"
    },
    body: JSON.stringify({
      shop: upsellRockShopDomain,
      cart_token: token,
      event_type: event_type,
      upsell_rocks: upsells.map(function (upsell) {
        return upsell.id;
      }),
      data: {},
      stats_at: new Date().getTime()
    })
  }).then(function (res) {
    return res.json();
  }).then(function (json) {
    console.log(json);
  });
}

var currentProduct = {};

function getCurrentProduct() {
  return _getCurrentProduct.apply(this, arguments);
}

function _getCurrentProduct() {
  _getCurrentProduct = _asyncToGenerator( /*#__PURE__*/_babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default().mark(function _callee3() {
    var res, json, div;
    return _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default().wrap(function _callee3$(_context3) {
      while (1) {
        switch (_context3.prev = _context3.next) {
          case 0:
            _context3.next = 2;
            return fetch(location.pathname + ".js");

          case 2:
            res = _context3.sent;
            _context3.next = 5;
            return res.json();

          case 5:
            json = _context3.sent;
            currentProduct = json;
            div = document.createElement("div");
            div.style.display = "none";
            div.id = "current-product";
            div.innerText = JSON.stringify(json);
            document.body.append(div);
            return _context3.abrupt("return", json);

          case 13:
          case "end":
            return _context3.stop();
        }
      }
    }, _callee3);
  }));
  return _getCurrentProduct.apply(this, arguments);
}

var allRecommendedProducts = [];

function getShopifyRecommendedProducts(_x2) {
  return _getShopifyRecommendedProducts.apply(this, arguments);
}

function _getShopifyRecommendedProducts() {
  _getShopifyRecommendedProducts = _asyncToGenerator( /*#__PURE__*/_babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default().mark(function _callee4(pid) {
    var res, json, products, div;
    return _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default().wrap(function _callee4$(_context4) {
      while (1) {
        switch (_context4.prev = _context4.next) {
          case 0:
            _context4.next = 2;
            return fetch("/recommendations/products.json?product_id=" + pid);

          case 2:
            res = _context4.sent;
            _context4.next = 5;
            return res.json();

          case 5:
            json = _context4.sent;
            products = json.products; // for test
            // if (products.length == 0) {
            //     json = {
            //         products: [
            //             {
            //                 id: 35,
            //                 title: "Gorgeous Silk Coat",
            //                 handle: "gorgeous-silk-coat",
            //                 description: null,
            //                 published_at: "2019-02-26T11:34:58-05:00",
            //                 created_at: "2019-02-26T11:34:58-05:00",
            //                 vendor: "Marge Group",
            //                 type: "Outdoors",
            //                 tags: [],
            //                 price: 380000,
            //                 price_min: 380000,
            //                 price_max: 790000,
            //                 available: true,
            //                 price_varies: true,
            //                 compare_at_price: null,
            //                 compare_at_price_min: 0,
            //                 compare_at_price_max: 0,
            //                 compare_at_price_varies: false,
            //                 variants: [
            //                     {
            //                         id: 69,
            //                         title: "Small Aluminum Knife",
            //                         option1: "Small Aluminum Knife",
            //                         option2: null,
            //                         option3: null,
            //                         sku: "",
            //                         requires_shipping: true,
            //                         taxable: true,
            //                         featured_image: null,
            //                         available: true,
            //                         name: "Gorgeous Silk Coat - Small Aluminum Knife",
            //                         public_title: "Small Aluminum Knife",
            //                         options: ["Small Aluminum Knife"],
            //                         price: 790000,
            //                         weight: 9500,
            //                         compare_at_price: null,
            //                         inventory_management: "shopify",
            //                         barcode: null
            //                     },
            //                     {
            //                         id: 70,
            //                         title: "Heavy Duty Bronze Shoes",
            //                         option1: "Heavy Duty Bronze Shoes",
            //                         option2: null,
            //                         option3: null,
            //                         sku: "",
            //                         requires_shipping: true,
            //                         taxable: true,
            //                         featured_image: null,
            //                         available: true,
            //                         name:
            //                             "Gorgeous Silk Coat - Heavy Duty Bronze Shoes",
            //                         public_title: "Heavy Duty Bronze Shoes",
            //                         options: ["Heavy Duty Bronze Shoes"],
            //                         price: 380000,
            //                         weight: 2200,
            //                         compare_at_price: null,
            //                         inventory_management: "shopify",
            //                         barcode: null
            //                     }
            //                 ],
            //                 images: [],
            //                 featured_image: null,
            //                 options: [
            //                     {
            //                         name: "Color or something",
            //                         position: 1,
            //                         values: [
            //                             "Small Aluminum Knife",
            //                             "Heavy Duty Bronze Shoes"
            //                         ]
            //                     }
            //                 ],
            //                 url:
            //                     "/products/gorgeous-silk-coat?pr_choice=default&pr_prod_strat=copurchase&pr_rec_pid=35&pr_ref_pid=17&pr_seq=alternating"
            //             },
            //             {
            //                 id: 13,
            //                 title: "Gorgeous Wooden Computer",
            //                 handle: "gorgeous-wooden-computer",
            //                 description: null,
            //                 published_at: "2019-02-26T11:34:15-05:00",
            //                 created_at: "2019-02-26T11:34:15-05:00",
            //                 vendor: "Purdy Inc",
            //                 type: "Garden",
            //                 tags: [],
            //                 price: 930000,
            //                 price_min: 930000,
            //                 price_max: 1730000,
            //                 available: true,
            //                 price_varies: true,
            //                 compare_at_price: null,
            //                 compare_at_price_min: 0,
            //                 compare_at_price_max: 0,
            //                 compare_at_price_varies: false,
            //                 variants: [
            //                     {
            //                         id: 25,
            //                         title: "Mediocre Silk Bottle",
            //                         option1: "Mediocre Silk Bottle",
            //                         option2: null,
            //                         option3: null,
            //                         sku: "",
            //                         requires_shipping: true,
            //                         taxable: true,
            //                         featured_image: null,
            //                         available: true,
            //                         name:
            //                             "Gorgeous Wooden Computer - Mediocre Silk Bottle",
            //                         public_title: "Mediocre Silk Bottle",
            //                         options: ["Mediocre Silk Bottle"],
            //                         price: 1730000,
            //                         weight: 5700,
            //                         compare_at_price: null,
            //                         inventory_management: "shopify",
            //                         barcode: null
            //                     },
            //                     {
            //                         id: 26,
            //                         title: "Lightweight Paper Shirt",
            //                         option1: "Lightweight Paper Shirt",
            //                         option2: null,
            //                         option3: null,
            //                         sku: "",
            //                         requires_shipping: true,
            //                         taxable: true,
            //                         featured_image: null,
            //                         available: true,
            //                         name:
            //                             "Gorgeous Wooden Computer - Lightweight Paper Shirt",
            //                         public_title: "Lightweight Paper Shirt",
            //                         options: ["Lightweight Paper Shirt"],
            //                         price: 930000,
            //                         weight: 6600,
            //                         compare_at_price: null,
            //                         inventory_management: "shopify",
            //                         barcode: null
            //                     }
            //                 ],
            //                 images: [],
            //                 featured_image: null,
            //                 options: [
            //                     {
            //                         name: "Color or something",
            //                         position: 1,
            //                         values: [
            //                             "Mediocre Silk Bottle",
            //                             "Lightweight Paper Shirt"
            //                         ]
            //                     }
            //                 ],
            //                 url:
            //                     "/products/gorgeous-wooden-computer?pr_choice=default&pr_prod_strat=description&pr_rec_pid=13&pr_ref_pid=17&pr_seq=alternating"
            //             }
            //         ]
            //     };
            //     products = json.products;
            // }

            if (products.length > 0) {
              allRecommendedProducts = allRecommendedProducts.concat(products);
            }

            console.log("allRecommendedProducts", allRecommendedProducts);
            div = document.createElement("div");
            div.id = "ant-shopify-recommendations";
            div.innerText = JSON.stringify(allRecommendedProducts);
            div.style.display = "none";
            document.body.append(div);

          case 14:
          case "end":
            return _context4.stop();
        }
      }
    }, _callee4);
  }));
  return _getShopifyRecommendedProducts.apply(this, arguments);
}

function updateAntRackAttribute(_x3) {
  return _updateAntRackAttribute.apply(this, arguments);
}

function _updateAntRackAttribute() {
  _updateAntRackAttribute = _asyncToGenerator( /*#__PURE__*/_babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default().mark(function _callee5(cart) {
    var res, json;
    return _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default().wrap(function _callee5$(_context5) {
      while (1) {
        switch (_context5.prev = _context5.next) {
          case 0:
            _context5.next = 2;
            return fetch('/cart/update.js', {
              method: 'POST',
              headers: {
                "Content-Type": "application/json"
              },
              'body': JSON.stringify({
                attributes: {
                  antrack_token: cart.token
                }
              })
            });

          case 2:
            res = _context5.sent;
            _context5.next = 5;
            return res.json();

          case 5:
            json = _context5.sent;
            return _context5.abrupt("return", json);

          case 7:
          case "end":
            return _context5.stop();
        }
      }
    }, _callee5);
  }));
  return _updateAntRackAttribute.apply(this, arguments);
}
})();

/******/ })()
;