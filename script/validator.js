/*
 * This file is auto generated from ryunosuke Validator Library.
 *
 * エラー表示周りには一切関与しない。
 * 入力枠が赤くなるのは css の仕事だし、 validated イベントが発火するので利用側でよしなに設定すれば良い。
 *
 * from:
 * Copyright (c) 2007-2016 Kevin van Zonneveld (http://kvz.io)
 * and Contributors (http://locutus.io/authors)
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy of
 * this software and associated documentation files (the "Software"), to deal in
 * the Software without restriction, including without limitation the rights to
 * use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies
 * of the Software, and to permit persons to whom the Software is furnished to do
 * so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

function Chmonos(form, options) {
    var chmonos = this;

    // locutus が nodejs 仕様なので格納用 module と ダミー require を用意
    var module = {};
    var require = function (path) {
        var parts = path.split('/');
        return chmonos[parts[parts.length - 1]];
    };

    chmonos.phpjs = {};

    /// phpjs のインポート
    /**/
var PREG_SPLIT_NO_EMPTY = this.PREG_SPLIT_NO_EMPTY = this.phpjs.PREG_SPLIT_NO_EMPTY = 1;
/**/
var PREG_UNMATCHED_AS_NULL = this.PREG_UNMATCHED_AS_NULL = this.phpjs.PREG_UNMATCHED_AS_NULL = 512;
/**/
    /**/
var _phpCastString = this._phpCastString = this.phpjs._phpCastString = (function(){
'use strict';

var _typeof = typeof Symbol === "function" && typeof Symbol.iterator === "symbol" ? function (obj) { return typeof obj; } : function (obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; };

module.exports = function _phpCastString(value) {
  // original by: Rafał Kukawski
  //   example 1: _phpCastString(true)
  //   returns 1: '1'
  //   example 2: _phpCastString(false)
  //   returns 2: ''
  //   example 3: _phpCastString('foo')
  //   returns 3: 'foo'
  //   example 4: _phpCastString(0/0)
  //   returns 4: 'NAN'
  //   example 5: _phpCastString(1/0)
  //   returns 5: 'INF'
  //   example 6: _phpCastString(-1/0)
  //   returns 6: '-INF'
  //   example 7: _phpCastString(null)
  //   returns 7: ''
  //   example 8: _phpCastString(undefined)
  //   returns 8: ''
  //   example 9: _phpCastString([])
  //   returns 9: 'Array'
  //   example 10: _phpCastString({})
  //   returns 10: 'Object'
  //   example 11: _phpCastString(0)
  //   returns 11: '0'
  //   example 12: _phpCastString(1)
  //   returns 12: '1'
  //   example 13: _phpCastString(3.14)
  //   returns 13: '3.14'

  var type = typeof value === 'undefined' ? 'undefined' : _typeof(value);

  switch (type) {
    case 'boolean':
      return value ? '1' : '';
    case 'string':
      return value;
    case 'number':
      if (isNaN(value)) {
        return 'NAN';
      }

      if (!isFinite(value)) {
        return (value < 0 ? '-' : '') + 'INF';
      }

      return value + '';
    case 'undefined':
      return '';
    case 'object':
      if (Array.isArray(value)) {
        return 'Array';
      }

      if (value !== null) {
        return 'Object';
      }

      return '';
    case 'function':
    // fall through
    default:
      throw new Error('Unsupported value type');
  }
};
return module.exports;
})();
/**/
var _php_cast_float = this._php_cast_float = this.phpjs._php_cast_float = (function(){
'use strict';

var _typeof = typeof Symbol === "function" && typeof Symbol.iterator === "symbol" ? function (obj) { return typeof obj; } : function (obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; };

module.exports = function _php_cast_float(value) {
  // original by: Rafał Kukawski
  //   example 1: _php_cast_float(false)
  //   returns 1: 0
  //   example 2: _php_cast_float(true)
  //   returns 2: 1
  //   example 3: _php_cast_float(0)
  //   returns 3: 0
  //   example 4: _php_cast_float(1)
  //   returns 4: 1
  //   example 5: _php_cast_float(3.14)
  //   returns 5: 3.14
  //   example 6: _php_cast_float('')
  //   returns 6: 0
  //   example 7: _php_cast_float('0')
  //   returns 7: 0
  //   example 8: _php_cast_float('abc')
  //   returns 8: 0
  //   example 9: _php_cast_float(null)
  //   returns 9: 0
  //  example 10: _php_cast_float(undefined)
  //  returns 10: 0
  //  example 11: _php_cast_float('123abc')
  //  returns 11: 123
  //  example 12: _php_cast_float('123e4')
  //  returns 12: 1230000
  //  example 13: _php_cast_float(0x200000001)
  //  returns 13: 8589934593
  //  example 14: _php_cast_float('3.14abc')
  //  returns 14: 3.14

  var type = typeof value === 'undefined' ? 'undefined' : _typeof(value);

  switch (type) {
    case 'number':
      return value;
    case 'string':
      return parseFloat(value) || 0;
    case 'boolean':
    // fall through
    default:
      // PHP docs state, that for types other than string
      // conversion is {input type}->int->float
      return require('./_php_cast_int')(value);
  }
};
return module.exports;
})();
/**/
var _php_cast_int = this._php_cast_int = this.phpjs._php_cast_int = (function(){
'use strict';

var _typeof = typeof Symbol === "function" && typeof Symbol.iterator === "symbol" ? function (obj) { return typeof obj; } : function (obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; };

module.exports = function _php_cast_int(value) {
  // original by: Rafał Kukawski
  //   example 1: _php_cast_int(false)
  //   returns 1: 0
  //   example 2: _php_cast_int(true)
  //   returns 2: 1
  //   example 3: _php_cast_int(0)
  //   returns 3: 0
  //   example 4: _php_cast_int(1)
  //   returns 4: 1
  //   example 5: _php_cast_int(3.14)
  //   returns 5: 3
  //   example 6: _php_cast_int('')
  //   returns 6: 0
  //   example 7: _php_cast_int('0')
  //   returns 7: 0
  //   example 8: _php_cast_int('abc')
  //   returns 8: 0
  //   example 9: _php_cast_int(null)
  //   returns 9: 0
  //  example 10: _php_cast_int(undefined)
  //  returns 10: 0
  //  example 11: _php_cast_int('123abc')
  //  returns 11: 123
  //  example 12: _php_cast_int('123e4')
  //  returns 12: 123
  //  example 13: _php_cast_int(0x200000001)
  //  returns 13: 8589934593

  var type = typeof value === 'undefined' ? 'undefined' : _typeof(value);

  switch (type) {
    case 'number':
      if (isNaN(value) || !isFinite(value)) {
        // from PHP 7, NaN and Infinity are casted to 0
        return 0;
      }

      return value < 0 ? Math.ceil(value) : Math.floor(value);
    case 'string':
      return parseInt(value, 10) || 0;
    case 'boolean':
    // fall through
    default:
      // Behaviour for types other than float, string, boolean
      // is undefined and can change any time.
      // To not invent complex logic
      // that mimics PHP 7.0 behaviour
      // casting value->bool->number is used
      return +!!value;
  }
};
return module.exports;
})();
/**/
var abs = this.abs = this.phpjs.abs = (function(){
"use strict";

module.exports = function abs(mixedNumber) {
  //  discuss at: https://locutus.io/php/abs/
  // original by: Waldo Malqui Silva (https://waldo.malqui.info)
  // improved by: Karol Kowalski
  // improved by: Kevin van Zonneveld (https://kvz.io)
  // improved by: Jonas Raoni Soares Silva (https://www.jsfromhell.com)
  //   example 1: abs(4.2)
  //   returns 1: 4.2
  //   example 2: abs(-4.2)
  //   returns 2: 4.2
  //   example 3: abs(-5)
  //   returns 3: 5
  //   example 4: abs('_argos')
  //   returns 4: 0

  return Math.abs(mixedNumber) || 0;
};
return module.exports;
})();
/**/
var array_column = this.array_column = this.phpjs.array_column = (function(){
'use strict';

var _typeof = typeof Symbol === "function" && typeof Symbol.iterator === "symbol" ? function (obj) { return typeof obj; } : function (obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; };

module.exports = function array_column(input, ColumnKey) {
  var IndexKey = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : null;

  //   discuss at: https://locutus.io/php/array_column/
  //   original by: Enzo Dañobeytía
  //   example 1: array_column([{name: 'Alex', value: 1}, {name: 'Elvis', value: 2}, {name: 'Michael', value: 3}], 'name')
  //   returns 1: {0: "Alex", 1: "Elvis", 2: "Michael"}
  //   example 2: array_column({0: {name: 'Alex', value: 1}, 1: {name: 'Elvis', value: 2}, 2: {name: 'Michael', value: 3}}, 'name')
  //   returns 2: {0: "Alex", 1: "Elvis", 2: "Michael"}
  //   example 3: array_column([{name: 'Alex', value: 1}, {name: 'Elvis', value: 2}, {name: 'Michael', value: 3}], 'name', 'value')
  //   returns 3: {1: "Alex", 2: "Elvis", 3: "Michael"}
  //   example 4: array_column([{name: 'Alex', value: 1}, {name: 'Elvis', value: 2}, {name: 'Michael', value: 3}], null, 'value')
  //   returns 4: {1: {name: 'Alex', value: 1}, 2: {name: 'Elvis', value: 2}, 3: {name: 'Michael', value: 3}}

  if (input !== null && ((typeof input === 'undefined' ? 'undefined' : _typeof(input)) === 'object' || Array.isArray(input))) {
    var newarray = [];
    if ((typeof input === 'undefined' ? 'undefined' : _typeof(input)) === 'object') {
      var temparray = [];
      var _iteratorNormalCompletion = true;
      var _didIteratorError = false;
      var _iteratorError = undefined;

      try {
        for (var _iterator = Object.keys(input)[Symbol.iterator](), _step; !(_iteratorNormalCompletion = (_step = _iterator.next()).done); _iteratorNormalCompletion = true) {
          var key = _step.value;

          temparray.push(input[key]);
        }
      } catch (err) {
        _didIteratorError = true;
        _iteratorError = err;
      } finally {
        try {
          if (!_iteratorNormalCompletion && _iterator.return) {
            _iterator.return();
          }
        } finally {
          if (_didIteratorError) {
            throw _iteratorError;
          }
        }
      }

      input = temparray;
    }
    if (Array.isArray(input)) {
      var _iteratorNormalCompletion2 = true;
      var _didIteratorError2 = false;
      var _iteratorError2 = undefined;

      try {
        for (var _iterator2 = input.keys()[Symbol.iterator](), _step2; !(_iteratorNormalCompletion2 = (_step2 = _iterator2.next()).done); _iteratorNormalCompletion2 = true) {
          var _key = _step2.value;

          if (IndexKey && input[_key][IndexKey]) {
            if (ColumnKey) {
              newarray[input[_key][IndexKey]] = input[_key][ColumnKey];
            } else {
              newarray[input[_key][IndexKey]] = input[_key];
            }
          } else {
            if (ColumnKey) {
              newarray.push(input[_key][ColumnKey]);
            } else {
              newarray.push(input[_key]);
            }
          }
        }
      } catch (err) {
        _didIteratorError2 = true;
        _iteratorError2 = err;
      } finally {
        try {
          if (!_iteratorNormalCompletion2 && _iterator2.return) {
            _iterator2.return();
          }
        } finally {
          if (_didIteratorError2) {
            throw _iteratorError2;
          }
        }
      }
    }
    return Object.assign({}, newarray);
  }
};
return module.exports;
})();
/**/
var array_combine = this.array_combine = this.phpjs.array_combine = (function(){
'use strict';

var _typeof = typeof Symbol === "function" && typeof Symbol.iterator === "symbol" ? function (obj) { return typeof obj; } : function (obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; };

module.exports = function array_combine(keys, values) {
  //  discuss at: https://locutus.io/php/array_combine/
  // original by: Kevin van Zonneveld (https://kvz.io)
  // improved by: Brett Zamir (https://brett-zamir.me)
  //   example 1: array_combine([0,1,2], ['kevin','van','zonneveld'])
  //   returns 1: {0: 'kevin', 1: 'van', 2: 'zonneveld'}

  var newArray = {};
  var i = 0;

  // input sanitation
  // Only accept arrays or array-like objects
  // Require arrays to have a count
  if ((typeof keys === 'undefined' ? 'undefined' : _typeof(keys)) !== 'object') {
    return false;
  }
  if ((typeof values === 'undefined' ? 'undefined' : _typeof(values)) !== 'object') {
    return false;
  }
  if (typeof keys.length !== 'number') {
    return false;
  }
  if (typeof values.length !== 'number') {
    return false;
  }
  if (!keys.length) {
    return false;
  }

  // number of elements does not match
  if (keys.length !== values.length) {
    return false;
  }

  for (i = 0; i < keys.length; i++) {
    newArray[keys[i]] = values[i];
  }

  return newArray;
};
return module.exports;
})();
/**/
var array_count_values = this.array_count_values = this.phpjs.array_count_values = (function(){
'use strict';

var _typeof = typeof Symbol === "function" && typeof Symbol.iterator === "symbol" ? function (obj) { return typeof obj; } : function (obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; };

module.exports = function array_count_values(array) {
  //  discuss at: https://locutus.io/php/array_count_values/
  // original by: Ates Goral (https://magnetiq.com)
  // improved by: Michael White (https://getsprink.com)
  // improved by: Kevin van Zonneveld (https://kvz.io)
  //    input by: sankai
  //    input by: Shingo
  // bugfixed by: Brett Zamir (https://brett-zamir.me)
  //   example 1: array_count_values([ 3, 5, 3, "foo", "bar", "foo" ])
  //   returns 1: {3:2, 5:1, "foo":2, "bar":1}
  //   example 2: array_count_values({ p1: 3, p2: 5, p3: 3, p4: "foo", p5: "bar", p6: "foo" })
  //   returns 2: {3:2, 5:1, "foo":2, "bar":1}
  //   example 3: array_count_values([ true, 4.2, 42, "fubar" ])
  //   returns 3: {42:1, "fubar":1}

  var tmpArr = {};
  var key = '';
  var t = '';

  var _getType = function _getType(obj) {
    // Objects are php associative arrays.
    var t = typeof obj === 'undefined' ? 'undefined' : _typeof(obj);
    t = t.toLowerCase();
    if (t === 'object') {
      t = 'array';
    }
    return t;
  };

  var _countValue = function _countValue(tmpArr, value) {
    if (typeof value === 'number') {
      if (Math.floor(value) !== value) {
        return;
      }
    } else if (typeof value !== 'string') {
      return;
    }

    if (value in tmpArr && tmpArr.hasOwnProperty(value)) {
      ++tmpArr[value];
    } else {
      tmpArr[value] = 1;
    }
  };

  t = _getType(array);
  if (t === 'array') {
    for (key in array) {
      if (array.hasOwnProperty(key)) {
        _countValue.call(this, tmpArr, array[key]);
      }
    }
  }

  return tmpArr;
};
return module.exports;
})();
/**/
var array_filter = this.array_filter = this.phpjs.array_filter = (function(){
'use strict';

module.exports = function array_filter(arr, func) {
  //  discuss at: https://locutus.io/php/array_filter/
  // original by: Brett Zamir (https://brett-zamir.me)
  //    input by: max4ever
  // improved by: Brett Zamir (https://brett-zamir.me)
  //      note 1: Takes a function as an argument, not a function's name
  //   example 1: var odd = function (num) {return (num & 1);}
  //   example 1: array_filter({"a": 1, "b": 2, "c": 3, "d": 4, "e": 5}, odd)
  //   returns 1: {"a": 1, "c": 3, "e": 5}
  //   example 2: var even = function (num) {return (!(num & 1));}
  //   example 2: array_filter([6, 7, 8, 9, 10, 11, 12], even)
  //   returns 2: [ 6, , 8, , 10, , 12 ]
  //   example 3: array_filter({"a": 1, "b": false, "c": -1, "d": 0, "e": null, "f":'', "g":undefined})
  //   returns 3: {"a":1, "c":-1}

  var retObj = {};
  var k = void 0;

  func = func || function (v) {
    return v;
  };

  // @todo: Issue #73
  if (Object.prototype.toString.call(arr) === '[object Array]') {
    retObj = [];
  }

  for (k in arr) {
    if (func(arr[k])) {
      retObj[k] = arr[k];
    }
  }

  return retObj;
};
return module.exports;
})();
/**/
var array_flip = this.array_flip = this.phpjs.array_flip = (function(){
"use strict";

module.exports = function array_flip(trans) {
  //  discuss at: https://locutus.io/php/array_flip/
  // original by: Kevin van Zonneveld (https://kvz.io)
  // improved by: Pier Paolo Ramon (https://www.mastersoup.com/)
  // improved by: Brett Zamir (https://brett-zamir.me)
  //   example 1: array_flip( {a: 1, b: 1, c: 2} )
  //   returns 1: {1: 'b', 2: 'c'}

  var key = void 0;
  var tmpArr = {};

  for (key in trans) {
    if (!trans.hasOwnProperty(key)) {
      continue;
    }
    tmpArr[trans[key]] = key;
  }

  return tmpArr;
};
return module.exports;
})();
/**/
var array_intersect_key = this.array_intersect_key = this.phpjs.array_intersect_key = (function(){
'use strict';

module.exports = function array_intersect_key(arr1) {
  //  discuss at: https://locutus.io/php/array_intersect_key/
  // original by: Brett Zamir (https://brett-zamir.me)
  //      note 1: These only output associative arrays (would need to be
  //      note 1: all numeric and counting from zero to be numeric)
  //   example 1: var $array1 = {a: 'green', b: 'brown', c: 'blue', 0: 'red'}
  //   example 1: var $array2 = {a: 'green', 0: 'yellow', 1: 'red'}
  //   example 1: array_intersect_key($array1, $array2)
  //   returns 1: {0: 'red', a: 'green'}

  var retArr = {};
  var argl = arguments.length;
  var arglm1 = argl - 1;
  var k1 = '';
  var arr = {};
  var i = 0;
  var k = '';

  arr1keys: for (k1 in arr1) {
    if (!arr1.hasOwnProperty(k1)) {
      continue;
    }
    arrs: for (i = 1; i < argl; i++) {
      arr = arguments[i];
      for (k in arr) {
        if (!arr.hasOwnProperty(k)) {
          continue;
        }
        if (k === k1) {
          if (i === arglm1) {
            retArr[k1] = arr1[k1];
          }
          // If the innermost loop always leads at least once to an equal value,
          // continue the loop until done
          continue arrs; // eslint-disable-line no-labels
        }
      }
      // If it reaches here, it wasn't found in at least one array, so try next value
      continue arr1keys; // eslint-disable-line no-labels
    }
  }

  return retArr;
};
return module.exports;
})();
/**/
var array_keys = this.array_keys = this.phpjs.array_keys = (function(){
'use strict';

module.exports = function array_keys(input, searchValue, argStrict) {
  //  discuss at: https://locutus.io/php/array_keys/
  // original by: Kevin van Zonneveld (https://kvz.io)
  //    input by: Brett Zamir (https://brett-zamir.me)
  //    input by: P
  // bugfixed by: Kevin van Zonneveld (https://kvz.io)
  // bugfixed by: Brett Zamir (https://brett-zamir.me)
  // improved by: jd
  // improved by: Brett Zamir (https://brett-zamir.me)
  //   example 1: array_keys( {firstname: 'Kevin', surname: 'van Zonneveld'} )
  //   returns 1: [ 'firstname', 'surname' ]

  var search = typeof searchValue !== 'undefined';
  var tmpArr = [];
  var strict = !!argStrict;
  var include = true;
  var key = '';

  for (key in input) {
    if (input.hasOwnProperty(key)) {
      include = true;
      if (search) {
        if (strict && input[key] !== searchValue) {
          include = false;
        } else if (input[key] !== searchValue) {
          include = false;
        }
      }

      if (include) {
        tmpArr[tmpArr.length] = key;
      }
    }
  }

  return tmpArr;
};
return module.exports;
})();
/**/
var array_map = this.array_map = this.phpjs.array_map = (function(){
/**
 * array_map
 *
 * locutus の array_map は連想配列に対応していないので自前定義。
 * （php の array_map は挙動が謎過ぎるので完全模倣しなくてもいいのだけど…しばしば登場するので）。
 */
module.exports = function array_map(callback, ...arrays) {
    if (callback == null) {
        throw 'array_map() callback must not be null';
    }
    if (arrays.length === 0) {
        throw 'array_map() expects at least 2 arguments, 1 given';
    }

    if (arrays.length === 1) {
        var result = arrays[0] instanceof Array ? [] : {};
        for (var [k, v] of Object.entries(arrays[0])) {
            result[k] = callback(v);
        }
        return result;
    }
    else {
        var arrayed = arrays.map(array => Object.values(array));
        var max = Math.max(...arrayed.map(array => array.length));
        var result = [];
        for (var i = 0; i < max; i++) {
            result.push(callback(...arrayed.map(array => array[i] ?? null)));
        }
        return result;
    }
};
return module.exports;
})();
/**/
var array_merge = this.array_merge = this.phpjs.array_merge = (function(){
'use strict';

module.exports = function array_merge() {
  //  discuss at: https://locutus.io/php/array_merge/
  // original by: Brett Zamir (https://brett-zamir.me)
  // bugfixed by: Nate
  // bugfixed by: Brett Zamir (https://brett-zamir.me)
  //    input by: josh
  //   example 1: var $arr1 = {"color": "red", 0: 2, 1: 4}
  //   example 1: var $arr2 = {0: "a", 1: "b", "color": "green", "shape": "trapezoid", 2: 4}
  //   example 1: array_merge($arr1, $arr2)
  //   returns 1: {"color": "green", 0: 2, 1: 4, 2: "a", 3: "b", "shape": "trapezoid", 4: 4}
  //   example 2: var $arr1 = []
  //   example 2: var $arr2 = {1: "data"}
  //   example 2: array_merge($arr1, $arr2)
  //   returns 2: {0: "data"}

  var args = Array.prototype.slice.call(arguments);
  var argl = args.length;
  var arg = void 0;
  var retObj = {};
  var k = '';
  var argil = 0;
  var j = 0;
  var i = 0;
  var ct = 0;
  var toStr = Object.prototype.toString;
  var retArr = true;

  for (i = 0; i < argl; i++) {
    if (toStr.call(args[i]) !== '[object Array]') {
      retArr = false;
      break;
    }
  }

  if (retArr) {
    retArr = [];
    for (i = 0; i < argl; i++) {
      retArr = retArr.concat(args[i]);
    }
    return retArr;
  }

  for (i = 0, ct = 0; i < argl; i++) {
    arg = args[i];
    if (toStr.call(arg) === '[object Array]') {
      for (j = 0, argil = arg.length; j < argil; j++) {
        retObj[ct++] = arg[j];
      }
    } else {
      for (k in arg) {
        if (arg.hasOwnProperty(k)) {
          if (parseInt(k, 10) + '' === k) {
            retObj[ct++] = arg[k];
          } else {
            retObj[k] = arg[k];
          }
        }
      }
    }
  }

  return retObj;
};
return module.exports;
})();
/**/
var array_reduce = this.array_reduce = this.phpjs.array_reduce = (function(){
/**
 * array_reduce
 *
 * locutus の array_reduce は initial に対応していないので自前定義。
 * 順番に依存する処理を渡してはならない。
 */
module.exports = function array_reduce(input, callback, initial) {
    if (initial === undefined) {
        initial = null;
    }

    var keys = Object.keys(input);
    for (var i = 0; i < keys.length; i++) {
        initial = callback(initial, input[keys[i]]);
    }
    return initial;
};
return module.exports;
})();
/**/
var array_sum = this.array_sum = this.phpjs.array_sum = (function(){
'use strict';

var _typeof = typeof Symbol === "function" && typeof Symbol.iterator === "symbol" ? function (obj) { return typeof obj; } : function (obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; };

module.exports = function array_sum(array) {
  //  discuss at: https://locutus.io/php/array_sum/
  // original by: Kevin van Zonneveld (https://kvz.io)
  // bugfixed by: Nate
  // bugfixed by: Gilbert
  // improved by: David Pilia (https://www.beteck.it/)
  // improved by: Brett Zamir (https://brett-zamir.me)
  //   example 1: array_sum([4, 9, 182.6])
  //   returns 1: 195.6
  //   example 2: var $total = []
  //   example 2: var $index = 0.1
  //   example 2: for (var $y = 0; $y < 12; $y++){ $total[$y] = $y + $index }
  //   example 2: array_sum($total)
  //   returns 2: 67.2

  var key = void 0;
  var sum = 0;

  // input sanitation
  if ((typeof array === 'undefined' ? 'undefined' : _typeof(array)) !== 'object') {
    return null;
  }

  for (key in array) {
    if (!isNaN(parseFloat(array[key]))) {
      sum += parseFloat(array[key]);
    }
  }

  return sum;
};
return module.exports;
})();
/**/
var array_unique = this.array_unique = this.phpjs.array_unique = (function(){
'use strict';

module.exports = function array_unique(inputArr) {
  //  discuss at: https://locutus.io/php/array_unique/
  // original by: Carlos R. L. Rodrigues (https://www.jsfromhell.com)
  //    input by: duncan
  //    input by: Brett Zamir (https://brett-zamir.me)
  // bugfixed by: Kevin van Zonneveld (https://kvz.io)
  // bugfixed by: Nate
  // bugfixed by: Kevin van Zonneveld (https://kvz.io)
  // bugfixed by: Brett Zamir (https://brett-zamir.me)
  // improved by: Michael Grier
  //      note 1: The second argument, sort_flags is not implemented;
  //      note 1: also should be sorted (asort?) first according to docs
  //   example 1: array_unique(['Kevin','Kevin','van','Zonneveld','Kevin'])
  //   returns 1: {0: 'Kevin', 2: 'van', 3: 'Zonneveld'}
  //   example 2: array_unique({'a': 'green', 0: 'red', 'b': 'green', 1: 'blue', 2: 'red'})
  //   returns 2: {a: 'green', 0: 'red', 1: 'blue'}

  var key = '';
  var tmpArr2 = {};
  var val = '';

  var _arraySearch = function _arraySearch(needle, haystack) {
    var fkey = '';
    for (fkey in haystack) {
      if (haystack.hasOwnProperty(fkey)) {
        if (haystack[fkey] + '' === needle + '') {
          return fkey;
        }
      }
    }
    return false;
  };

  for (key in inputArr) {
    if (inputArr.hasOwnProperty(key)) {
      val = inputArr[key];
      if (_arraySearch(val, tmpArr2) === false) {
        tmpArr2[key] = val;
      }
    }
  }

  return tmpArr2;
};
return module.exports;
})();
/**/
var array_values = this.array_values = this.phpjs.array_values = (function(){
'use strict';

module.exports = function array_values(input) {
  //  discuss at: https://locutus.io/php/array_values/
  // original by: Kevin van Zonneveld (https://kvz.io)
  // improved by: Brett Zamir (https://brett-zamir.me)
  //   example 1: array_values( {firstname: 'Kevin', surname: 'van Zonneveld'} )
  //   returns 1: [ 'Kevin', 'van Zonneveld' ]

  var tmpArr = [];
  var key = '';

  for (key in input) {
    tmpArr[tmpArr.length] = input[key];
  }

  return tmpArr;
};
return module.exports;
})();
/**/
var base64_decode = this.base64_decode = this.phpjs.base64_decode = (function(){
/**
 * base64_decode
 */
module.exports = function base64_decode(string, strict) {
    if (strict === undefined) {
        strict = false;
    }

    try {
        return atob(string);
    }
    catch (e) {
        return false;
    }
};
return module.exports;
})();
/**/
var basename = this.basename = this.phpjs.basename = (function(){
'use strict';

module.exports = function basename(path, suffix) {
  //  discuss at: https://locutus.io/php/basename/
  // original by: Kevin van Zonneveld (https://kvz.io)
  // improved by: Ash Searle (https://hexmen.com/blog/)
  // improved by: Lincoln Ramsay
  // improved by: djmix
  // improved by: Dmitry Gorelenkov
  //   example 1: basename('/www/site/home.htm', '.htm')
  //   returns 1: 'home'
  //   example 2: basename('ecra.php?p=1')
  //   returns 2: 'ecra.php?p=1'
  //   example 3: basename('/some/path/')
  //   returns 3: 'path'
  //   example 4: basename('/some/path_ext.ext/','.ext')
  //   returns 4: 'path_ext'

  var b = path;
  var lastChar = b.charAt(b.length - 1);

  if (lastChar === '/' || lastChar === '\\') {
    b = b.slice(0, -1);
  }

  b = b.replace(/^.*[/\\]/g, '');

  if (typeof suffix === 'string' && b.substr(b.length - suffix.length) === suffix) {
    b = b.substr(0, b.length - suffix.length);
  }

  return b;
};
return module.exports;
})();
/**/
var count = this.count = this.phpjs.count = (function(){
'use strict';

module.exports = function count(mixedVar, mode) {
  //  discuss at: https://locutus.io/php/count/
  // original by: Kevin van Zonneveld (https://kvz.io)
  //    input by: Waldo Malqui Silva (https://waldo.malqui.info)
  //    input by: merabi
  // bugfixed by: Soren Hansen
  // bugfixed by: Olivier Louvignes (https://mg-crea.com/)
  // improved by: Brett Zamir (https://brett-zamir.me)
  //   example 1: count([[0,0],[0,-4]], 'COUNT_RECURSIVE')
  //   returns 1: 6
  //   example 2: count({'one' : [1,2,3,4,5]}, 'COUNT_RECURSIVE')
  //   returns 2: 6

  var key = void 0;
  var cnt = 0;

  if (mixedVar === null || typeof mixedVar === 'undefined') {
    return 0;
  } else if (mixedVar.constructor !== Array && mixedVar.constructor !== Object) {
    return 1;
  }

  if (mode === 'COUNT_RECURSIVE') {
    mode = 1;
  }
  if (mode !== 1) {
    mode = 0;
  }

  for (key in mixedVar) {
    if (mixedVar.hasOwnProperty(key)) {
      cnt++;
      if (mode === 1 && mixedVar[key] && (mixedVar[key].constructor === Array || mixedVar[key].constructor === Object)) {
        cnt += count(mixedVar[key], 1);
      }
    }
  }

  return cnt;
};
return module.exports;
})();
/**/
var ctype_digit = this.ctype_digit = this.phpjs.ctype_digit = (function(){
'use strict';

module.exports = function ctype_digit(text) {
  //  discuss at: https://locutus.io/php/ctype_digit/
  // original by: Brett Zamir (https://brett-zamir.me)
  //   example 1: ctype_digit('150')
  //   returns 1: true

  var setlocale = require('../strings/setlocale');
  if (typeof text !== 'string') {
    return false;
  }
  // ensure setup of localization variables takes place
  setlocale('LC_ALL', 0);

  var $global = typeof window !== 'undefined' ? window : global;
  $global.$locutus = $global.$locutus || {};
  var $locutus = $global.$locutus;
  var p = $locutus.php;

  return text.search(p.locales[p.localeCategories.LC_CTYPE].LC_CTYPE.dg) !== -1;
};
return module.exports;
})();
/**/
var date = this.date = this.phpjs.date = (function(){
'use strict';

module.exports = function date(format, timestamp) {
  //  discuss at: https://locutus.io/php/date/
  // original by: Carlos R. L. Rodrigues (https://www.jsfromhell.com)
  // original by: gettimeofday
  //    parts by: Peter-Paul Koch (https://www.quirksmode.org/js/beat.html)
  // improved by: Kevin van Zonneveld (https://kvz.io)
  // improved by: MeEtc (https://yass.meetcweb.com)
  // improved by: Brad Touesnard
  // improved by: Tim Wiel
  // improved by: Bryan Elliott
  // improved by: David Randall
  // improved by: Theriault (https://github.com/Theriault)
  // improved by: Theriault (https://github.com/Theriault)
  // improved by: Brett Zamir (https://brett-zamir.me)
  // improved by: Theriault (https://github.com/Theriault)
  // improved by: Thomas Beaucourt (https://www.webapp.fr)
  // improved by: JT
  // improved by: Theriault (https://github.com/Theriault)
  // improved by: Rafał Kukawski (https://blog.kukawski.pl)
  // improved by: Theriault (https://github.com/Theriault)
  //    input by: Brett Zamir (https://brett-zamir.me)
  //    input by: majak
  //    input by: Alex
  //    input by: Martin
  //    input by: Alex Wilson
  //    input by: Haravikk
  // bugfixed by: Kevin van Zonneveld (https://kvz.io)
  // bugfixed by: majak
  // bugfixed by: Kevin van Zonneveld (https://kvz.io)
  // bugfixed by: Brett Zamir (https://brett-zamir.me)
  // bugfixed by: omid (https://locutus.io/php/380:380#comment_137122)
  // bugfixed by: Chris (https://www.devotis.nl/)
  //      note 1: Uses global: locutus to store the default timezone
  //      note 1: Although the function potentially allows timezone info
  //      note 1: (see notes), it currently does not set
  //      note 1: per a timezone specified by date_default_timezone_set(). Implementers might use
  //      note 1: $locutus.currentTimezoneOffset and
  //      note 1: $locutus.currentTimezoneDST set by that function
  //      note 1: in order to adjust the dates in this function
  //      note 1: (or our other date functions!) accordingly
  //   example 1: date('H:m:s \\m \\i\\s \\m\\o\\n\\t\\h', 1062402400)
  //   returns 1: '07:09:40 m is month'
  //   example 2: date('F j, Y, g:i a', 1062462400)
  //   returns 2: 'September 2, 2003, 12:26 am'
  //   example 3: date('Y W o', 1062462400)
  //   returns 3: '2003 36 2003'
  //   example 4: var $x = date('Y m d', (new Date()).getTime() / 1000)
  //   example 4: $x = $x + ''
  //   example 4: var $result = $x.length // 2009 01 09
  //   returns 4: 10
  //   example 5: date('W', 1104534000)
  //   returns 5: '52'
  //   example 6: date('B t', 1104534000)
  //   returns 6: '999 31'
  //   example 7: date('W U', 1293750000.82); // 2010-12-31
  //   returns 7: '52 1293750000'
  //   example 8: date('W', 1293836400); // 2011-01-01
  //   returns 8: '52'
  //   example 9: date('W Y-m-d', 1293974054); // 2011-01-02
  //   returns 9: '52 2011-01-02'
  //        test: skip-1 skip-2 skip-5

  var jsdate = void 0,
      f = void 0;
  // Keep this here (works, but for code commented-out below for file size reasons)
  // var tal= [];
  var txtWords = ['Sun', 'Mon', 'Tues', 'Wednes', 'Thurs', 'Fri', 'Satur', 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
  // trailing backslash -> (dropped)
  // a backslash followed by any character (including backslash) -> the character
  // empty string -> empty string
  var formatChr = /\\?(.?)/gi;
  var formatChrCb = function formatChrCb(t, s) {
    return f[t] ? f[t]() : s;
  };
  var _pad = function _pad(n, c) {
    n = String(n);
    while (n.length < c) {
      n = '0' + n;
    }
    return n;
  };
  f = {
    // Day
    d: function d() {
      // Day of month w/leading 0; 01..31
      return _pad(f.j(), 2);
    },
    D: function D() {
      // Shorthand day name; Mon...Sun
      return f.l().slice(0, 3);
    },
    j: function j() {
      // Day of month; 1..31
      return jsdate.getDate();
    },
    l: function l() {
      // Full day name; Monday...Sunday
      return txtWords[f.w()] + 'day';
    },
    N: function N() {
      // ISO-8601 day of week; 1[Mon]..7[Sun]
      return f.w() || 7;
    },
    S: function S() {
      // Ordinal suffix for day of month; st, nd, rd, th
      var j = f.j();
      var i = j % 10;
      if (i <= 3 && parseInt(j % 100 / 10, 10) === 1) {
        i = 0;
      }
      return ['st', 'nd', 'rd'][i - 1] || 'th';
    },
    w: function w() {
      // Day of week; 0[Sun]..6[Sat]
      return jsdate.getDay();
    },
    z: function z() {
      // Day of year; 0..365
      var a = new Date(f.Y(), f.n() - 1, f.j());
      var b = new Date(f.Y(), 0, 1);
      return Math.round((a - b) / 864e5);
    },

    // Week
    W: function W() {
      // ISO-8601 week number
      var a = new Date(f.Y(), f.n() - 1, f.j() - f.N() + 3);
      var b = new Date(a.getFullYear(), 0, 4);
      return _pad(1 + Math.round((a - b) / 864e5 / 7), 2);
    },

    // Month
    F: function F() {
      // Full month name; January...December
      return txtWords[6 + f.n()];
    },
    m: function m() {
      // Month w/leading 0; 01...12
      return _pad(f.n(), 2);
    },
    M: function M() {
      // Shorthand month name; Jan...Dec
      return f.F().slice(0, 3);
    },
    n: function n() {
      // Month; 1...12
      return jsdate.getMonth() + 1;
    },
    t: function t() {
      // Days in month; 28...31
      return new Date(f.Y(), f.n(), 0).getDate();
    },

    // Year
    L: function L() {
      // Is leap year?; 0 or 1
      var j = f.Y();
      return j % 4 === 0 & j % 100 !== 0 | j % 400 === 0;
    },
    o: function o() {
      // ISO-8601 year
      var n = f.n();
      var W = f.W();
      var Y = f.Y();
      return Y + (n === 12 && W < 9 ? 1 : n === 1 && W > 9 ? -1 : 0);
    },
    Y: function Y() {
      // Full year; e.g. 1980...2010
      return jsdate.getFullYear();
    },
    y: function y() {
      // Last two digits of year; 00...99
      return f.Y().toString().slice(-2);
    },

    // Time
    a: function a() {
      // am or pm
      return jsdate.getHours() > 11 ? 'pm' : 'am';
    },
    A: function A() {
      // AM or PM
      return f.a().toUpperCase();
    },
    B: function B() {
      // Swatch Internet time; 000..999
      var H = jsdate.getUTCHours() * 36e2;
      // Hours
      var i = jsdate.getUTCMinutes() * 60;
      // Minutes
      // Seconds
      var s = jsdate.getUTCSeconds();
      return _pad(Math.floor((H + i + s + 36e2) / 86.4) % 1e3, 3);
    },
    g: function g() {
      // 12-Hours; 1..12
      return f.G() % 12 || 12;
    },
    G: function G() {
      // 24-Hours; 0..23
      return jsdate.getHours();
    },
    h: function h() {
      // 12-Hours w/leading 0; 01..12
      return _pad(f.g(), 2);
    },
    H: function H() {
      // 24-Hours w/leading 0; 00..23
      return _pad(f.G(), 2);
    },
    i: function i() {
      // Minutes w/leading 0; 00..59
      return _pad(jsdate.getMinutes(), 2);
    },
    s: function s() {
      // Seconds w/leading 0; 00..59
      return _pad(jsdate.getSeconds(), 2);
    },
    u: function u() {
      // Microseconds; 000000-999000
      return _pad(jsdate.getMilliseconds() * 1000, 6);
    },

    // Timezone
    e: function e() {
      // Timezone identifier; e.g. Atlantic/Azores, ...
      // The following works, but requires inclusion of the very large
      // timezone_abbreviations_list() function.
      /*              return that.date_default_timezone_get();
       */
      var msg = 'Not supported (see source code of date() for timezone on how to add support)';
      throw new Error(msg);
    },
    I: function I() {
      // DST observed?; 0 or 1
      // Compares Jan 1 minus Jan 1 UTC to Jul 1 minus Jul 1 UTC.
      // If they are not equal, then DST is observed.
      var a = new Date(f.Y(), 0);
      // Jan 1
      var c = Date.UTC(f.Y(), 0);
      // Jan 1 UTC
      var b = new Date(f.Y(), 6);
      // Jul 1
      // Jul 1 UTC
      var d = Date.UTC(f.Y(), 6);
      return a - c !== b - d ? 1 : 0;
    },
    O: function O() {
      // Difference to GMT in hour format; e.g. +0200
      var tzo = jsdate.getTimezoneOffset();
      var a = Math.abs(tzo);
      return (tzo > 0 ? '-' : '+') + _pad(Math.floor(a / 60) * 100 + a % 60, 4);
    },
    P: function P() {
      // Difference to GMT w/colon; e.g. +02:00
      var O = f.O();
      return O.substr(0, 3) + ':' + O.substr(3, 2);
    },
    T: function T() {
      // The following works, but requires inclusion of the very
      // large timezone_abbreviations_list() function.
      /*              var abbr, i, os, _default;
      if (!tal.length) {
        tal = that.timezone_abbreviations_list();
      }
      if ($locutus && $locutus.default_timezone) {
        _default = $locutus.default_timezone;
        for (abbr in tal) {
          for (i = 0; i < tal[abbr].length; i++) {
            if (tal[abbr][i].timezone_id === _default) {
              return abbr.toUpperCase();
            }
          }
        }
      }
      for (abbr in tal) {
        for (i = 0; i < tal[abbr].length; i++) {
          os = -jsdate.getTimezoneOffset() * 60;
          if (tal[abbr][i].offset === os) {
            return abbr.toUpperCase();
          }
        }
      }
      */
      return 'UTC';
    },
    Z: function Z() {
      // Timezone offset in seconds (-43200...50400)
      return -jsdate.getTimezoneOffset() * 60;
    },

    // Full Date/Time
    c: function c() {
      // ISO-8601 date.
      return 'Y-m-d\\TH:i:sP'.replace(formatChr, formatChrCb);
    },
    r: function r() {
      // RFC 2822
      return 'D, d M Y H:i:s O'.replace(formatChr, formatChrCb);
    },
    U: function U() {
      // Seconds since UNIX epoch
      return jsdate / 1000 | 0;
    }
  };

  var _date = function _date(format, timestamp) {
    jsdate = timestamp === undefined ? new Date() // Not provided
    : timestamp instanceof Date ? new Date(timestamp) // JS Date()
    : new Date(timestamp * 1000); // UNIX timestamp (auto-convert to int)
    return format.replace(formatChr, formatChrCb);
  };

  return _date(format, timestamp);
};
return module.exports;
})();
/**/
var explode = this.explode = this.phpjs.explode = (function(){
'use strict';

var _typeof = typeof Symbol === "function" && typeof Symbol.iterator === "symbol" ? function (obj) { return typeof obj; } : function (obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; };

module.exports = function explode(delimiter, string, limit) {
  //  discuss at: https://locutus.io/php/explode/
  // original by: Kevin van Zonneveld (https://kvz.io)
  //   example 1: explode(' ', 'Kevin van Zonneveld')
  //   returns 1: [ 'Kevin', 'van', 'Zonneveld' ]

  if (arguments.length < 2 || typeof delimiter === 'undefined' || typeof string === 'undefined') {
    return null;
  }
  if (delimiter === '' || delimiter === false || delimiter === null) {
    return false;
  }
  if (typeof delimiter === 'function' || (typeof delimiter === 'undefined' ? 'undefined' : _typeof(delimiter)) === 'object' || typeof string === 'function' || (typeof string === 'undefined' ? 'undefined' : _typeof(string)) === 'object') {
    return {
      0: ''
    };
  }
  if (delimiter === true) {
    delimiter = '1';
  }

  // Here we go...
  delimiter += '';
  string += '';

  var s = string.split(delimiter);

  if (typeof limit === 'undefined') return s;

  // Support for limit
  if (limit === 0) limit = 1;

  // Positive limit
  if (limit > 0) {
    if (limit >= s.length) {
      return s;
    }
    return s.slice(0, limit - 1).concat([s.slice(limit - 1).join(delimiter)]);
  }

  // Negative limit
  if (-limit >= s.length) {
    return [];
  }

  s.splice(s.length + limit);
  return s;
};
return module.exports;
})();
/**/
var filesize = this.filesize = this.phpjs.filesize = (function(){
/**
 * filesize
 *
 * ローカルファイルへのアクセスは出来ないので、file を引数に取る。
 */
module.exports = function filesize(file) {
    if (!file) {
        return;
    }
    return file.size;
};
return module.exports;
})();
/**/
var getenv = this.getenv = this.phpjs.getenv = (function(){
'use strict';

module.exports = function getenv(varname) {
  //  discuss at: https://locutus.io/php/getenv/
  // original by: Brett Zamir (https://brett-zamir.me)
  //   example 1: getenv('LC_ALL')
  //   returns 1: false

  if (typeof process !== 'undefined' || !process.env || !process.env[varname]) {
    return false;
  }

  return process.env[varname];
};
return module.exports;
})();
/**/
var getimagesize = this.getimagesize = this.phpjs.getimagesize = (function(){
/**
 * getimagesize
 *
 * 同期は無理なので Promise を返す。
 */
module.exports = function getimagesize(file) {
    if (!file) {
        return;
    }

    return new Promise(function (resolve) {
        try {
            var url = URL.createObjectURL(file);
            var img = new Image();
            img.addEventListener('load', function () {
                resolve([img.width, img.height]);
                URL.revokeObjectURL(img.src);
            });
            img.addEventListener('error', function () {
                resolve(false);
                URL.revokeObjectURL(img.src);
            });
            img.src = url;
        }
        catch (e) {
            console.log(e);
            resolve(false);
        }
    });
};
return module.exports;
})();
/**/
var gettype = this.gettype = this.phpjs.gettype = (function(){
'use strict';

var _typeof = typeof Symbol === "function" && typeof Symbol.iterator === "symbol" ? function (obj) { return typeof obj; } : function (obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; };

module.exports = function gettype(mixedVar) {
  //  discuss at: https://locutus.io/php/gettype/
  // original by: Paulo Freitas
  // improved by: Kevin van Zonneveld (https://kvz.io)
  // improved by: Douglas Crockford (https://javascript.crockford.com)
  // improved by: Brett Zamir (https://brett-zamir.me)
  //    input by: KELAN
  //      note 1: 1.0 is simplified to 1 before it can be accessed by the function, this makes
  //      note 1: it different from the PHP implementation. We can't fix this unfortunately.
  //   example 1: gettype(1)
  //   returns 1: 'integer'
  //   example 2: gettype(undefined)
  //   returns 2: 'undefined'
  //   example 3: gettype({0: 'Kevin van Zonneveld'})
  //   returns 3: 'object'
  //   example 4: gettype('foo')
  //   returns 4: 'string'
  //   example 5: gettype({0: function () {return false;}})
  //   returns 5: 'object'
  //   example 6: gettype({0: 'test', length: 1, splice: function () {}})
  //   returns 6: 'object'
  //   example 7: gettype(['test'])
  //   returns 7: 'array'

  var isFloat = require('../var/is_float');

  var s = typeof mixedVar === 'undefined' ? 'undefined' : _typeof(mixedVar);
  var name = void 0;
  var _getFuncName = function _getFuncName(fn) {
    var name = /\W*function\s+([\w$]+)\s*\(/.exec(fn);
    if (!name) {
      return '(Anonymous)';
    }
    return name[1];
  };

  if (s === 'object') {
    if (mixedVar !== null) {
      // From: https://javascript.crockford.com/remedial.html
      // @todo: Break up this lengthy if statement
      if (typeof mixedVar.length === 'number' && !mixedVar.propertyIsEnumerable('length') && typeof mixedVar.splice === 'function') {
        s = 'array';
      } else if (mixedVar.constructor && _getFuncName(mixedVar.constructor)) {
        name = _getFuncName(mixedVar.constructor);
        if (name === 'Date') {
          // not in PHP
          s = 'date';
        } else if (name === 'RegExp') {
          // not in PHP
          s = 'regexp';
        } else if (name === 'LOCUTUS_Resource') {
          // Check against our own resource constructor
          s = 'resource';
        }
      }
    } else {
      s = 'null';
    }
  } else if (s === 'number') {
    s = isFloat(mixedVar) ? 'double' : 'integer';
  }

  return s;
};
return module.exports;
})();
/**/
var implode = this.implode = this.phpjs.implode = (function(){
'use strict';

var _typeof = typeof Symbol === "function" && typeof Symbol.iterator === "symbol" ? function (obj) { return typeof obj; } : function (obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; };

module.exports = function implode(glue, pieces) {
  //  discuss at: https://locutus.io/php/implode/
  // original by: Kevin van Zonneveld (https://kvz.io)
  // improved by: Waldo Malqui Silva (https://waldo.malqui.info)
  // improved by: Itsacon (https://www.itsacon.net/)
  // bugfixed by: Brett Zamir (https://brett-zamir.me)
  //   example 1: implode(' ', ['Kevin', 'van', 'Zonneveld'])
  //   returns 1: 'Kevin van Zonneveld'
  //   example 2: implode(' ', {first:'Kevin', last: 'van Zonneveld'})
  //   returns 2: 'Kevin van Zonneveld'

  var i = '';
  var retVal = '';
  var tGlue = '';

  if (arguments.length === 1) {
    pieces = glue;
    glue = '';
  }

  if ((typeof pieces === 'undefined' ? 'undefined' : _typeof(pieces)) === 'object') {
    if (Object.prototype.toString.call(pieces) === '[object Array]') {
      return pieces.join(glue);
    }
    for (i in pieces) {
      retVal += tGlue + pieces[i];
      tGlue = glue;
    }
    return retVal;
  }

  return pieces;
};
return module.exports;
})();
/**/
var in_array = this.in_array = this.phpjs.in_array = (function(){
'use strict';

module.exports = function in_array(needle, haystack, argStrict) {
  //  discuss at: https://locutus.io/php/in_array/
  // original by: Kevin van Zonneveld (https://kvz.io)
  // improved by: vlado houba
  // improved by: Jonas Sciangula Street (Joni2Back)
  //    input by: Billy
  // bugfixed by: Brett Zamir (https://brett-zamir.me)
  //   example 1: in_array('van', ['Kevin', 'van', 'Zonneveld'])
  //   returns 1: true
  //   example 2: in_array('vlado', {0: 'Kevin', vlado: 'van', 1: 'Zonneveld'})
  //   returns 2: false
  //   example 3: in_array(1, ['1', '2', '3'])
  //   example 3: in_array(1, ['1', '2', '3'], false)
  //   returns 3: true
  //   returns 3: true
  //   example 4: in_array(1, ['1', '2', '3'], true)
  //   returns 4: false

  var key = '';
  var strict = !!argStrict;

  // we prevent the double check (strict && arr[key] === ndl) || (!strict && arr[key] === ndl)
  // in just one for, in order to improve the performance
  // deciding wich type of comparation will do before walk array
  if (strict) {
    for (key in haystack) {
      if (haystack[key] === needle) {
        return true;
      }
    }
  } else {
    for (key in haystack) {
      // eslint-disable-next-line eqeqeq
      if (haystack[key] == needle) {
        return true;
      }
    }
  }

  return false;
};
return module.exports;
})();
/**/
var ini_get = this.ini_get = this.phpjs.ini_get = (function(){
'use strict';

module.exports = function ini_get(varname) {
  //  discuss at: https://locutus.io/php/ini_get/
  // original by: Brett Zamir (https://brett-zamir.me)
  //      note 1: The ini values must be set by ini_set or manually within an ini file
  //   example 1: ini_set('date.timezone', 'Asia/Hong_Kong')
  //   example 1: ini_get('date.timezone')
  //   returns 1: 'Asia/Hong_Kong'

  var $global = typeof window !== 'undefined' ? window : global;
  $global.$locutus = $global.$locutus || {};
  var $locutus = $global.$locutus;
  $locutus.php = $locutus.php || {};
  $locutus.php.ini = $locutus.php.ini || {};

  if ($locutus.php.ini[varname] && $locutus.php.ini[varname].local_value !== undefined) {
    if ($locutus.php.ini[varname].local_value === null) {
      return '';
    }
    return $locutus.php.ini[varname].local_value;
  }

  return '';
};
return module.exports;
})();
/**/
var ini_parse_quantity = this.ini_parse_quantity = this.phpjs.ini_parse_quantity = (function(){
/**
 * ini_parse_quantity
 *
 * 思ったよりややこしかったので超簡易実装（どうせ kmg しか使わん）。
 */
module.exports = function ini_parse_quantity(quantity) {
    quantity = ('' + quantity).toLowerCase();
    var unit = quantity.slice(-1);
    var size = parseInt(quantity || 0);
    if (unit === 'k') {
        size *= 1024;
    }
    if (unit === 'm') {
        size *= 1024 * 1024;
    }
    if (unit === 'g') {
        size *= 1024 * 1024 * 1024;
    }
    return size;
};
return module.exports;
})();
/**/
var intval = this.intval = this.phpjs.intval = (function(){
'use strict';

var _typeof = typeof Symbol === "function" && typeof Symbol.iterator === "symbol" ? function (obj) { return typeof obj; } : function (obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; };

module.exports = function intval(mixedVar, base) {
  //  discuss at: https://locutus.io/php/intval/
  // original by: Kevin van Zonneveld (https://kvz.io)
  // improved by: stensi
  // bugfixed by: Kevin van Zonneveld (https://kvz.io)
  // bugfixed by: Brett Zamir (https://brett-zamir.me)
  // bugfixed by: Rafał Kukawski (https://blog.kukawski.pl)
  //    input by: Matteo
  //   example 1: intval('Kevin van Zonneveld')
  //   returns 1: 0
  //   example 2: intval(4.2)
  //   returns 2: 4
  //   example 3: intval(42, 8)
  //   returns 3: 42
  //   example 4: intval('09')
  //   returns 4: 9
  //   example 5: intval('1e', 16)
  //   returns 5: 30
  //   example 6: intval(0x200000001)
  //   returns 6: 8589934593
  //   example 7: intval('0xff', 0)
  //   returns 7: 255
  //   example 8: intval('010', 0)
  //   returns 8: 8

  var tmp = void 0,
      match = void 0;

  var type = typeof mixedVar === 'undefined' ? 'undefined' : _typeof(mixedVar);

  if (type === 'boolean') {
    return +mixedVar;
  } else if (type === 'string') {
    if (base === 0) {
      match = mixedVar.match(/^\s*0(x?)/i);
      base = match ? match[1] ? 16 : 8 : 10;
    }
    tmp = parseInt(mixedVar, base || 10);
    return isNaN(tmp) || !isFinite(tmp) ? 0 : tmp;
  } else if (type === 'number' && isFinite(mixedVar)) {
    return mixedVar < 0 ? Math.ceil(mixedVar) : Math.floor(mixedVar);
  } else {
    return 0;
  }
};
return module.exports;
})();
/**/
var is_array = this.is_array = this.phpjs.is_array = (function(){
'use strict';

var _typeof = typeof Symbol === "function" && typeof Symbol.iterator === "symbol" ? function (obj) { return typeof obj; } : function (obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; };

module.exports = function is_array(mixedVar) {
  //  discuss at: https://locutus.io/php/is_array/
  // original by: Kevin van Zonneveld (https://kvz.io)
  // improved by: Legaev Andrey
  // improved by: Onno Marsman (https://twitter.com/onnomarsman)
  // improved by: Brett Zamir (https://brett-zamir.me)
  // improved by: Nathan Sepulveda
  // improved by: Brett Zamir (https://brett-zamir.me)
  // bugfixed by: Cord
  // bugfixed by: Manish
  // bugfixed by: Brett Zamir (https://brett-zamir.me)
  //      note 1: In Locutus, javascript objects are like php associative arrays,
  //      note 1: thus JavaScript objects will also
  //      note 1: return true in this function (except for objects which inherit properties,
  //      note 1: being thus used as objects),
  //      note 1: unless you do ini_set('locutus.objectsAsArrays', 0),
  //      note 1: in which case only genuine JavaScript arrays
  //      note 1: will return true
  //   example 1: is_array(['Kevin', 'van', 'Zonneveld'])
  //   returns 1: true
  //   example 2: is_array('Kevin van Zonneveld')
  //   returns 2: false
  //   example 3: is_array({0: 'Kevin', 1: 'van', 2: 'Zonneveld'})
  //   returns 3: true
  //   example 4: ini_set('locutus.objectsAsArrays', 0)
  //   example 4: is_array({0: 'Kevin', 1: 'van', 2: 'Zonneveld'})
  //   returns 4: false
  //   example 5: is_array(function tmp_a (){ this.name = 'Kevin' })
  //   returns 5: false

  var _getFuncName = function _getFuncName(fn) {
    var name = /\W*function\s+([\w$]+)\s*\(/.exec(fn);
    if (!name) {
      return '(Anonymous)';
    }
    return name[1];
  };
  var _isArray = function _isArray(mixedVar) {
    // return Object.prototype.toString.call(mixedVar) === '[object Array]';
    // The above works, but let's do the even more stringent approach:
    // (since Object.prototype.toString could be overridden)
    // Null, Not an object, no length property so couldn't be an Array (or String)
    if (!mixedVar || (typeof mixedVar === 'undefined' ? 'undefined' : _typeof(mixedVar)) !== 'object' || typeof mixedVar.length !== 'number') {
      return false;
    }
    var len = mixedVar.length;
    mixedVar[mixedVar.length] = 'bogus';
    // The only way I can think of to get around this (or where there would be trouble)
    // would be to have an object defined
    // with a custom "length" getter which changed behavior on each call
    // (or a setter to mess up the following below) or a custom
    // setter for numeric properties, but even that would need to listen for
    // specific indexes; but there should be no false negatives
    // and such a false positive would need to rely on later JavaScript
    // innovations like __defineSetter__
    if (len !== mixedVar.length) {
      // We know it's an array since length auto-changed with the addition of a
      // numeric property at its length end, so safely get rid of our bogus element
      mixedVar.length -= 1;
      return true;
    }
    // Get rid of the property we added onto a non-array object; only possible
    // side-effect is if the user adds back the property later, it will iterate
    // this property in the older order placement in IE (an order which should not
    // be depended on anyways)
    delete mixedVar[mixedVar.length];
    return false;
  };

  if (!mixedVar || (typeof mixedVar === 'undefined' ? 'undefined' : _typeof(mixedVar)) !== 'object') {
    return false;
  }

  var isArray = _isArray(mixedVar);

  if (isArray) {
    return true;
  }

  var iniVal = (typeof require !== 'undefined' ? require('../info/ini_get')('locutus.objectsAsArrays') : undefined) || 'on';
  if (iniVal === 'on') {
    var asString = Object.prototype.toString.call(mixedVar);
    var asFunc = _getFuncName(mixedVar.constructor);

    if (asString === '[object Object]' && asFunc === 'Object') {
      // Most likely a literal and intended as assoc. array
      return true;
    }
  }

  return false;
};
return module.exports;
})();
/**/
var is_float = this.is_float = this.phpjs.is_float = (function(){
"use strict";

module.exports = function is_float(mixedVar) {
  //  discuss at: https://locutus.io/php/is_float/
  // original by: Paulo Freitas
  // bugfixed by: Brett Zamir (https://brett-zamir.me)
  // improved by: WebDevHobo (https://webdevhobo.blogspot.com/)
  // improved by: Rafał Kukawski (https://blog.kukawski.pl)
  //      note 1: 1.0 is simplified to 1 before it can be accessed by the function, this makes
  //      note 1: it different from the PHP implementation. We can't fix this unfortunately.
  //   example 1: is_float(186.31)
  //   returns 1: true

  return +mixedVar === mixedVar && (!isFinite(mixedVar) || !!(mixedVar % 1));
};
return module.exports;
})();
/**/
var is_int = this.is_int = this.phpjs.is_int = (function(){
"use strict";

module.exports = function is_int(mixedVar) {
  //  discuss at: https://locutus.io/php/is_int/
  // original by: Alex
  // improved by: Kevin van Zonneveld (https://kvz.io)
  // improved by: WebDevHobo (https://webdevhobo.blogspot.com/)
  // improved by: Rafał Kukawski (https://blog.kukawski.pl)
  //  revised by: Matt Bradley
  // bugfixed by: Kevin van Zonneveld (https://kvz.io)
  //      note 1: 1.0 is simplified to 1 before it can be accessed by the function, this makes
  //      note 1: it different from the PHP implementation. We can't fix this unfortunately.
  //   example 1: is_int(23)
  //   returns 1: true
  //   example 2: is_int('23')
  //   returns 2: false
  //   example 3: is_int(23.5)
  //   returns 3: false
  //   example 4: is_int(true)
  //   returns 4: false

  return mixedVar === +mixedVar && isFinite(mixedVar) && !(mixedVar % 1);
};
return module.exports;
})();
/**/
var is_null = this.is_null = this.phpjs.is_null = (function(){
"use strict";

module.exports = function is_null(mixedVar) {
  //  discuss at: https://locutus.io/php/is_null/
  // original by: Kevin van Zonneveld (https://kvz.io)
  //   example 1: is_null('23')
  //   returns 1: false
  //   example 2: is_null(null)
  //   returns 2: true

  return mixedVar === null;
};
return module.exports;
})();
/**/
var is_string = this.is_string = this.phpjs.is_string = (function(){
'use strict';

module.exports = function is_string(mixedVar) {
  //  discuss at: https://locutus.io/php/is_string/
  // original by: Kevin van Zonneveld (https://kvz.io)
  //   example 1: is_string('23')
  //   returns 1: true
  //   example 2: is_string(23.5)
  //   returns 2: false

  return typeof mixedVar === 'string';
};
return module.exports;
})();
/**/
var isset = this.isset = this.phpjs.isset = (function(){
'use strict';

module.exports = function isset() {
  //  discuss at: https://locutus.io/php/isset/
  // original by: Kevin van Zonneveld (https://kvz.io)
  // improved by: FremyCompany
  // improved by: Onno Marsman (https://twitter.com/onnomarsman)
  // improved by: Rafał Kukawski (https://blog.kukawski.pl)
  //   example 1: isset( undefined, true)
  //   returns 1: false
  //   example 2: isset( 'Kevin van Zonneveld' )
  //   returns 2: true

  var a = arguments;
  var l = a.length;
  var i = 0;
  var undef = void 0;

  if (l === 0) {
    throw new Error('Empty isset');
  }

  while (i !== l) {
    if (a[i] === undef || a[i] === null) {
      return false;
    }
    i++;
  }

  return true;
};
return module.exports;
})();
/**/
var join = this.join = this.phpjs.join = (function(){
'use strict';

module.exports = function join(glue, pieces) {
  //  discuss at: https://locutus.io/php/join/
  // original by: Kevin van Zonneveld (https://kvz.io)
  //   example 1: join(' ', ['Kevin', 'van', 'Zonneveld'])
  //   returns 1: 'Kevin van Zonneveld'

  var implode = require('../strings/implode');
  return implode(glue, pieces);
};
return module.exports;
})();
/**/
var json_decode = this.json_decode = this.phpjs.json_decode = (function(){
'use strict';

var _typeof = typeof Symbol === "function" && typeof Symbol.iterator === "symbol" ? function (obj) { return typeof obj; } : function (obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; };

module.exports = function json_decode(strJson) {
  //       discuss at: https://phpjs.org/functions/json_decode/
  //      original by: Public Domain (https://www.json.org/json2.js)
  // reimplemented by: Kevin van Zonneveld (https://kevin.vanzonneveld.net)
  //      improved by: T.J. Leahy
  //      improved by: Michael White
  //           note 1: If node or the browser does not offer JSON.parse,
  //           note 1: this function falls backslash
  //           note 1: to its own implementation using eval, and hence should be considered unsafe
  //        example 1: json_decode('[ 1 ]')
  //        returns 1: [1]

  /*
    https://www.JSON.org/json2.js
    2008-11-19
    Public Domain.
    NO WARRANTY EXPRESSED OR IMPLIED. USE AT YOUR OWN RISK.
    See https://www.JSON.org/js.html
  */

  var $global = typeof window !== 'undefined' ? window : global;
  $global.$locutus = $global.$locutus || {};
  var $locutus = $global.$locutus;
  $locutus.php = $locutus.php || {};

  var json = $global.JSON;
  if ((typeof json === 'undefined' ? 'undefined' : _typeof(json)) === 'object' && typeof json.parse === 'function') {
    try {
      return json.parse(strJson);
    } catch (err) {
      if (!(err instanceof SyntaxError)) {
        throw new Error('Unexpected error type in json_decode()');
      }

      // usable by json_last_error()
      $locutus.php.last_error_json = 4;
      return null;
    }
  }

  var chars = ['\0', '\xAD', '\u0600-\u0604', '\u070F', '\u17B4', '\u17B5', '\u200C-\u200F', '\u2028-\u202F', '\u2060-\u206F', '\uFEFF', '\uFFF0-\uFFFF'].join('');
  var cx = new RegExp('[' + chars + ']', 'g');
  var j = void 0;
  var text = strJson;

  // Parsing happens in four stages. In the first stage, we replace certain
  // Unicode characters with escape sequences. JavaScript handles many characters
  // incorrectly, either silently deleting them, or treating them as line endings.
  cx.lastIndex = 0;
  if (cx.test(text)) {
    text = text.replace(cx, function (a) {
      return '\\u' + ('0000' + a.charCodeAt(0).toString(16)).slice(-4);
    });
  }

  // In the second stage, we run the text against regular expressions that look
  // for non-JSON patterns. We are especially concerned with '()' and 'new'
  // because they can cause invocation, and '=' because it can cause mutation.
  // But just to be safe, we want to reject all unexpected forms.
  // We split the second stage into 4 regexp operations in order to work around
  // crippling inefficiencies in IE's and Safari's regexp engines. First we
  // replace the JSON backslash pairs with '@' (a non-JSON character). Second, we
  // replace all simple value tokens with ']' characters. Third, we delete all
  // open brackets that follow a colon or comma or that begin the text. Finally,
  // we look to see that the remaining characters are only whitespace or ']' or
  // ',' or ':' or '{' or '}'. If that is so, then the text is safe for eval.

  var m = /^[\],:{}\s]*$/.test(text.replace(/\\(?:["\\/bfnrt]|u[0-9a-fA-F]{4})/g, '@').replace(/"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+-]?\d+)?/g, ']').replace(/(?:^|:|,)(?:\s*\[)+/g, ''));

  if (m) {
    // In the third stage we use the eval function to compile the text into a
    // JavaScript structure. The '{' operator is subject to a syntactic ambiguity
    // in JavaScript: it can begin a block or an object literal. We wrap the text
    // in parens to eliminate the ambiguity.
    j = eval('(' + text + ')'); // eslint-disable-line no-eval
    return j;
  }

  // usable by json_last_error()
  $locutus.php.last_error_json = 4;
  return null;
};
return module.exports;
})();
/**/
var log = this.log = this.phpjs.log = (function(){
'use strict';

module.exports = function log(arg, base) {
  //  discuss at: https://locutus.io/php/log/
  // original by: Onno Marsman (https://twitter.com/onnomarsman)
  // improved by: Brett Zamir (https://brett-zamir.me)
  //   example 1: log(8723321.4, 7)
  //   returns 1: 8.212871815082147

  return typeof base === 'undefined' ? Math.log(arg) : Math.log(arg) / Math.log(base);
};
return module.exports;
})();
/**/
var ltrim = this.ltrim = this.phpjs.ltrim = (function(){
'use strict';

module.exports = function ltrim(str, charlist) {
  //  discuss at: https://locutus.io/php/ltrim/
  // original by: Kevin van Zonneveld (https://kvz.io)
  //    input by: Erkekjetter
  // improved by: Kevin van Zonneveld (https://kvz.io)
  // bugfixed by: Onno Marsman (https://twitter.com/onnomarsman)
  //   example 1: ltrim('    Kevin van Zonneveld    ')
  //   returns 1: 'Kevin van Zonneveld    '

  charlist = !charlist ? ' \\s\xA0' : (charlist + '').replace(/([[\]().?/*{}+$^:])/g, '$1');

  var re = new RegExp('^[' + charlist + ']+', 'g');

  return (str + '').replace(re, '');
};
return module.exports;
})();
/**/
var mb_str_split = this.mb_str_split = this.phpjs.mb_str_split = (function(){
/**
 * mb_str_split
 *
 * unicode 環境のみ。
 */
module.exports = function mb_str_split(string) {
    return [...string];
};
return module.exports;
})();
/**/
var mb_strlen = this.mb_strlen = this.phpjs.mb_strlen = (function(){
/**
 * mb_strlen
 *
 * unicode 環境のみ。
 */
module.exports = function mb_strlen(str) {
    return str.length;
};
return module.exports;
})();
/**/
var mime_content_type = this.mime_content_type = this.phpjs.mime_content_type = (function(){
/**
 * mime_content_type
 *
 * ローカルファイルへのアクセスは出来ないので、file を引数に取る。
 */
module.exports = function mime_content_type(file) {
    if (!file) {
        return;
    }
    return file.type;
};
return module.exports;
})();
/**/
var parse_str = this.parse_str = this.phpjs.parse_str = (function(){
'use strict';

module.exports = function parse_str(str, array) {
  //       discuss at: https://locutus.io/php/parse_str/
  //      original by: Cagri Ekin
  //      improved by: Michael White (https://getsprink.com)
  //      improved by: Jack
  //      improved by: Brett Zamir (https://brett-zamir.me)
  //      bugfixed by: Onno Marsman (https://twitter.com/onnomarsman)
  //      bugfixed by: Brett Zamir (https://brett-zamir.me)
  //      bugfixed by: stag019
  //      bugfixed by: Brett Zamir (https://brett-zamir.me)
  //      bugfixed by: MIO_KODUKI (https://mio-koduki.blogspot.com/)
  // reimplemented by: stag019
  //         input by: Dreamer
  //         input by: Zaide (https://zaidesthings.com/)
  //         input by: David Pesta (https://davidpesta.com/)
  //         input by: jeicquest
  //      bugfixed by: Rafał Kukawski
  //           note 1: When no argument is specified, will put variables in global scope.
  //           note 1: When a particular argument has been passed, and the
  //           note 1: returned value is different parse_str of PHP.
  //           note 1: For example, a=b=c&d====c
  //        example 1: var $arr = {}
  //        example 1: parse_str('first=foo&second=bar', $arr)
  //        example 1: var $result = $arr
  //        returns 1: { first: 'foo', second: 'bar' }
  //        example 2: var $arr = {}
  //        example 2: parse_str('str_a=Jack+and+Jill+didn%27t+see+the+well.', $arr)
  //        example 2: var $result = $arr
  //        returns 2: { str_a: "Jack and Jill didn't see the well." }
  //        example 3: var $abc = {3:'a'}
  //        example 3: parse_str('a[b]["c"]=def&a[q]=t+5', $abc)
  //        example 3: var $result = $abc
  //        returns 3: {"3":"a","a":{"b":{"c":"def"},"q":"t 5"}}
  //        example 4: var $arr = {}
  //        example 4: parse_str('a[][]=value', $arr)
  //        example 4: var $result = $arr
  //        returns 4: {"a":{"0":{"0":"value"}}}
  //        example 5: var $arr = {}
  //        example 5: parse_str('a=1&a[]=2', $arr)
  //        example 5: var $result = $arr
  //        returns 5: {"a":{"0":"2"}}

  var strArr = String(str).replace(/^&/, '').replace(/&$/, '').split('&');
  var sal = strArr.length;
  var i = void 0;
  var j = void 0;
  var ct = void 0;
  var p = void 0;
  var lastObj = void 0;
  var obj = void 0;
  var chr = void 0;
  var tmp = void 0;
  var key = void 0;
  var value = void 0;
  var postLeftBracketPos = void 0;
  var keys = void 0;
  var keysLen = void 0;

  var _fixStr = function _fixStr(str) {
    return decodeURIComponent(str.replace(/\+/g, '%20'));
  };

  var $global = typeof window !== 'undefined' ? window : global;
  $global.$locutus = $global.$locutus || {};
  var $locutus = $global.$locutus;
  $locutus.php = $locutus.php || {};

  if (!array) {
    array = $global;
  }

  for (i = 0; i < sal; i++) {
    tmp = strArr[i].split('=');
    key = _fixStr(tmp[0]);
    value = tmp.length < 2 ? '' : _fixStr(tmp[1]);

    if (key.includes('__proto__') || key.includes('constructor') || key.includes('prototype')) {
      break;
    }

    while (key.charAt(0) === ' ') {
      key = key.slice(1);
    }

    if (key.indexOf('\x00') > -1) {
      key = key.slice(0, key.indexOf('\x00'));
    }

    if (key && key.charAt(0) !== '[') {
      keys = [];
      postLeftBracketPos = 0;

      for (j = 0; j < key.length; j++) {
        if (key.charAt(j) === '[' && !postLeftBracketPos) {
          postLeftBracketPos = j + 1;
        } else if (key.charAt(j) === ']') {
          if (postLeftBracketPos) {
            if (!keys.length) {
              keys.push(key.slice(0, postLeftBracketPos - 1));
            }

            keys.push(key.substr(postLeftBracketPos, j - postLeftBracketPos));
            postLeftBracketPos = 0;

            if (key.charAt(j + 1) !== '[') {
              break;
            }
          }
        }
      }

      if (!keys.length) {
        keys = [key];
      }

      for (j = 0; j < keys[0].length; j++) {
        chr = keys[0].charAt(j);

        if (chr === ' ' || chr === '.' || chr === '[') {
          keys[0] = keys[0].substr(0, j) + '_' + keys[0].substr(j + 1);
        }

        if (chr === '[') {
          break;
        }
      }

      obj = array;

      for (j = 0, keysLen = keys.length; j < keysLen; j++) {
        key = keys[j].replace(/^['"]/, '').replace(/['"]$/, '');
        lastObj = obj;

        if ((key === '' || key === ' ') && j !== 0) {
          // Insert new dimension
          ct = -1;

          for (p in obj) {
            if (obj.hasOwnProperty(p)) {
              if (+p > ct && p.match(/^\d+$/g)) {
                ct = +p;
              }
            }
          }

          key = ct + 1;
        }

        // if primitive value, replace with object
        if (Object(obj[key]) !== obj[key]) {
          obj[key] = {};
        }

        obj = obj[key];
      }

      lastObj[key] = value;
    }
  }
};
return module.exports;
})();
/**/
var parse_url = this.parse_url = this.phpjs.parse_url = (function(){
'use strict';

module.exports = function parse_url(str, component) {
  //       discuss at: https://locutus.io/php/parse_url/
  //      original by: Steven Levithan (https://blog.stevenlevithan.com)
  // reimplemented by: Brett Zamir (https://brett-zamir.me)
  //         input by: Lorenzo Pisani
  //         input by: Tony
  //      improved by: Brett Zamir (https://brett-zamir.me)
  //           note 1: original by https://stevenlevithan.com/demo/parseuri/js/assets/parseuri.js
  //           note 1: blog post at https://blog.stevenlevithan.com/archives/parseuri
  //           note 1: demo at https://stevenlevithan.com/demo/parseuri/js/assets/parseuri.js
  //           note 1: Does not replace invalid characters with '_' as in PHP,
  //           note 1: nor does it return false with
  //           note 1: a seriously malformed URL.
  //           note 1: Besides function name, is essentially the same as parseUri as
  //           note 1: well as our allowing
  //           note 1: an extra slash after the scheme/protocol (to allow file:/// as in PHP)
  //        example 1: parse_url('https://user:pass@host/path?a=v#a')
  //        returns 1: {scheme: 'https', host: 'host', user: 'user', pass: 'pass', path: '/path', query: 'a=v', fragment: 'a'}
  //        example 2: parse_url('https://en.wikipedia.org/wiki/%22@%22_%28album%29')
  //        returns 2: {scheme: 'https', host: 'en.wikipedia.org', path: '/wiki/%22@%22_%28album%29'}
  //        example 3: parse_url('https://host.domain.tld/a@b.c/folder')
  //        returns 3: {scheme: 'https', host: 'host.domain.tld', path: '/a@b.c/folder'}
  //        example 4: parse_url('https://gooduser:secretpassword@www.example.com/a@b.c/folder?foo=bar')
  //        returns 4: { scheme: 'https', host: 'www.example.com', path: '/a@b.c/folder', query: 'foo=bar', user: 'gooduser', pass: 'secretpassword' }

  var query = void 0;

  var mode = (typeof require !== 'undefined' ? require('../info/ini_get')('locutus.parse_url.mode') : undefined) || 'php';

  var key = ['source', 'scheme', 'authority', 'userInfo', 'user', 'pass', 'host', 'port', 'relative', 'path', 'directory', 'file', 'query', 'fragment'];

  // For loose we added one optional slash to post-scheme to catch file:/// (should restrict this)
  var parser = {
    php: new RegExp(['(?:([^:\\/?#]+):)?', '(?:\\/\\/()(?:(?:()(?:([^:@\\/]*):?([^:@\\/]*))?@)?([^:\\/?#]*)(?::(\\d*))?))?', '()', '(?:(()(?:(?:[^?#\\/]*\\/)*)()(?:[^?#]*))(?:\\?([^#]*))?(?:#(.*))?)'].join('')),
    strict: new RegExp(['(?:([^:\\/?#]+):)?', '(?:\\/\\/((?:(([^:@\\/]*):?([^:@\\/]*))?@)?([^:\\/?#]*)(?::(\\d*))?))?', '((((?:[^?#\\/]*\\/)*)([^?#]*))(?:\\?([^#]*))?(?:#(.*))?)'].join('')),
    loose: new RegExp(['(?:(?![^:@]+:[^:@\\/]*@)([^:\\/?#.]+):)?', '(?:\\/\\/\\/?)?', '((?:(([^:@\\/]*):?([^:@\\/]*))?@)?([^:\\/?#]*)(?::(\\d*))?)', '(((\\/(?:[^?#](?![^?#\\/]*\\.[^?#\\/.]+(?:[?#]|$)))*\\/?)?([^?#\\/]*))', '(?:\\?([^#]*))?(?:#(.*))?)'].join(''))
  };

  var m = parser[mode].exec(str);
  var uri = {};
  var i = 14;

  while (i--) {
    if (m[i]) {
      uri[key[i]] = m[i];
    }
  }

  if (component) {
    return uri[component.replace('PHP_URL_', '').toLowerCase()];
  }

  if (mode !== 'php') {
    var name = (typeof require !== 'undefined' ? require('../info/ini_get')('locutus.parse_url.queryKey') : undefined) || 'queryKey';
    parser = /(?:^|&)([^&=]*)=?([^&]*)/g;
    uri[name] = {};
    query = uri[key[12]] || '';
    query.replace(parser, function ($0, $1, $2) {
      if ($1) {
        uri[name][$1] = $2;
      }
    });
  }

  delete uri.source;
  return uri;
};
return module.exports;
})();
/**/
var pathinfo = this.pathinfo = this.phpjs.pathinfo = (function(){
'use strict';

module.exports = function pathinfo(path, options) {
  //  discuss at: https://locutus.io/php/pathinfo/
  // original by: Nate
  //  revised by: Kevin van Zonneveld (https://kvz.io)
  // improved by: Brett Zamir (https://brett-zamir.me)
  // improved by: Dmitry Gorelenkov
  //    input by: Timo
  //      note 1: Inspired by actual PHP source: php5-5.2.6/ext/standard/string.c line #1559
  //      note 1: The way the bitwise arguments are handled allows for greater flexibility
  //      note 1: & compatability. We might even standardize this
  //      note 1: code and use a similar approach for
  //      note 1: other bitwise PHP functions
  //      note 1: Locutus tries very hard to stay away from a core.js
  //      note 1: file with global dependencies, because we like
  //      note 1: that you can just take a couple of functions and be on your way.
  //      note 1: But by way we implemented this function,
  //      note 1: if you want you can still declare the PATHINFO_*
  //      note 1: yourself, and then you can use:
  //      note 1: pathinfo('/www/index.html', PATHINFO_BASENAME | PATHINFO_EXTENSION);
  //      note 1: which makes it fully compliant with PHP syntax.
  //   example 1: pathinfo('/www/htdocs/index.html', 1)
  //   returns 1: '/www/htdocs'
  //   example 2: pathinfo('/www/htdocs/index.html', 'PATHINFO_BASENAME')
  //   returns 2: 'index.html'
  //   example 3: pathinfo('/www/htdocs/index.html', 'PATHINFO_EXTENSION')
  //   returns 3: 'html'
  //   example 4: pathinfo('/www/htdocs/index.html', 'PATHINFO_FILENAME')
  //   returns 4: 'index'
  //   example 5: pathinfo('/www/htdocs/index.html', 2 | 4)
  //   returns 5: {basename: 'index.html', extension: 'html'}
  //   example 6: pathinfo('/www/htdocs/index.html', 'PATHINFO_ALL')
  //   returns 6: {dirname: '/www/htdocs', basename: 'index.html', extension: 'html', filename: 'index'}
  //   example 7: pathinfo('/www/htdocs/index.html')
  //   returns 7: {dirname: '/www/htdocs', basename: 'index.html', extension: 'html', filename: 'index'}

  var basename = require('../filesystem/basename');
  var opt = '';
  var realOpt = '';
  var optName = '';
  var optTemp = 0;
  var tmpArr = {};
  var cnt = 0;
  var i = 0;
  var haveBasename = false;
  var haveExtension = false;
  var haveFilename = false;

  // Input defaulting & sanitation
  if (!path) {
    return false;
  }
  if (!options) {
    options = 'PATHINFO_ALL';
  }

  // Initialize binary arguments. Both the string & integer (constant) input is
  // allowed
  var OPTS = {
    PATHINFO_DIRNAME: 1,
    PATHINFO_BASENAME: 2,
    PATHINFO_EXTENSION: 4,
    PATHINFO_FILENAME: 8,
    PATHINFO_ALL: 0
    // PATHINFO_ALL sums up all previously defined PATHINFOs (could just pre-calculate)
  };for (optName in OPTS) {
    if (OPTS.hasOwnProperty(optName)) {
      OPTS.PATHINFO_ALL = OPTS.PATHINFO_ALL | OPTS[optName];
    }
  }
  if (typeof options !== 'number') {
    // Allow for a single string or an array of string flags
    options = [].concat(options);
    for (i = 0; i < options.length; i++) {
      // Resolve string input to bitwise e.g. 'PATHINFO_EXTENSION' becomes 4
      if (OPTS[options[i]]) {
        optTemp = optTemp | OPTS[options[i]];
      }
    }
    options = optTemp;
  }

  // Internal Functions
  var _getExt = function _getExt(path) {
    var str = path + '';
    var dotP = str.lastIndexOf('.') + 1;
    return !dotP ? false : dotP !== str.length ? str.substr(dotP) : '';
  };

  // Gather path infos
  if (options & OPTS.PATHINFO_DIRNAME) {
    var dirName = path.replace(/\\/g, '/').replace(/\/[^/]*\/?$/, ''); // dirname
    tmpArr.dirname = dirName === path ? '.' : dirName;
  }

  if (options & OPTS.PATHINFO_BASENAME) {
    if (haveBasename === false) {
      haveBasename = basename(path);
    }
    tmpArr.basename = haveBasename;
  }

  if (options & OPTS.PATHINFO_EXTENSION) {
    if (haveBasename === false) {
      haveBasename = basename(path);
    }
    if (haveExtension === false) {
      haveExtension = _getExt(haveBasename);
    }
    if (haveExtension !== false) {
      tmpArr.extension = haveExtension;
    }
  }

  if (options & OPTS.PATHINFO_FILENAME) {
    if (haveBasename === false) {
      haveBasename = basename(path);
    }
    if (haveExtension === false) {
      haveExtension = _getExt(haveBasename);
    }
    if (haveFilename === false) {
      haveFilename = haveBasename.slice(0, haveBasename.length - (haveExtension ? haveExtension.length + 1 : haveExtension === false ? 0 : 1));
    }

    tmpArr.filename = haveFilename;
  }

  // If array contains only 1 element: return string
  cnt = 0;
  for (opt in tmpArr) {
    if (tmpArr.hasOwnProperty(opt)) {
      cnt++;
      realOpt = opt;
    }
  }
  if (cnt === 1) {
    return tmpArr[realOpt];
  }

  // Return full-blown array
  return tmpArr;
};
return module.exports;
})();
/**/
var pow = this.pow = this.phpjs.pow = (function(){
"use strict";

module.exports = function pow(base, exp) {
  //  discuss at: https://locutus.io/php/pow/
  // original by: Onno Marsman (https://twitter.com/onnomarsman)
  // improved by: Waldo Malqui Silva (https://fayr.us/waldo/)
  //   example 1: pow(8723321.4, 7)
  //   returns 1: 3.8439091680779e+48

  return Number(Math.pow(base, exp).toPrecision(15));
};
return module.exports;
})();
/**/
var preg_match = this.preg_match = this.phpjs.preg_match = (function(){
/**
 * preg_match
 *
 * flags は一部のみ対応。
 */
module.exports = function preg_match(pattern, subject, matches, flags) {
    // flags は PREG_UNMATCHED_AS_NULL のみ対応
    if (flags && flags !== PREG_UNMATCHED_AS_NULL) {
        throw 'flags supports PREG_UNMATCHED_AS_NULL only.';
    }
    // match が指定されたらそれは Array でなければならない
    if (arguments.length >= 3 && !(matches instanceof Array)) {
        throw 'matches is not array.';
    }

    subject = "" + strval(subject ?? "");

    // 表現とフラグをセパレート
    var meta = pattern.charAt(0);
    var exp = new RegExp(meta + '(.*)' + meta + '([imsu]*)', 's');
    var eaf = pattern.match(exp);

    // マッチング
    var regexp = new RegExp(eaf[1], eaf[2]);
    var match = subject.match(regexp);

    if (!match) {
        return 0;
    }

    if (typeof (matches) !== 'undefined') {
        matches.splice(0, matches.length);
        for (var i = 0; i < match.length; i++) {
            if (flags & PREG_UNMATCHED_AS_NULL) {
                match[i] = match[i] ?? null;
            }
            else {
                match[i] = match[i] ?? '';
            }
            matches.push(match[i]);
        }
    }

    return 1;
};
return module.exports;
})();
/**/
var preg_split = this.preg_split = this.phpjs.preg_split = (function(){
/**
 * preg_split
 *
 * flags は一部のみ対応。
 */
module.exports = function preg_split(pattern, subject, limit, flags) {
    // flags は PREG_SPLIT_NO_EMPTY のみ対応
    if (flags && flags !== PREG_SPLIT_NO_EMPTY) {
        throw 'flags supports PREG_SPLIT_NO_EMPTY only.';
    }
    limit = limit ?? 0;

    subject = "" + strval(subject ?? "");

    // 表現とフラグをセパレート
    var meta = pattern.charAt(0);
    var exp = new RegExp(meta + '(.*)' + meta + '([imsu]*)', 's');
    var eaf = pattern.match(exp);

    // マッチング
    var regexp = new RegExp(eaf[1], eaf[2] + 'gd');
    var match = subject.matchAll(regexp);

    var current = 0;
    var result = [];
    var append = function (part) {
        if (!(flags & PREG_SPLIT_NO_EMPTY) || part.length > 0) {
            result.push(part);
        }
    };
    for (var m of match) {
        if (limit > 0 && result.length >= limit - 1) {
            break;
        }

        append(subject.substring(current, m.indices[0][0]));
        current = m.indices[0][1];
    }

    append(subject.substring(current));
    return result;
};
return module.exports;
})();
/**/
var round = this.round = this.phpjs.round = (function(){
'use strict';

function roundToInt(value, mode) {
  var tmp = Math.floor(Math.abs(value) + 0.5);

  if (mode === 'PHP_ROUND_HALF_DOWN' && value === tmp - 0.5 || mode === 'PHP_ROUND_HALF_EVEN' && value === 0.5 + 2 * Math.floor(tmp / 2) || mode === 'PHP_ROUND_HALF_ODD' && value === 0.5 + 2 * Math.floor(tmp / 2) - 1) {
    tmp -= 1;
  }

  return value < 0 ? -tmp : tmp;
}

module.exports = function round(value) {
  var precision = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : 0;
  var mode = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : 'PHP_ROUND_HALF_UP';

  //  discuss at: https://locutus.io/php/round/
  // original by: Philip Peterson
  //  revised by: Onno Marsman (https://twitter.com/onnomarsman)
  //  revised by: T.Wild
  //  revised by: Rafał Kukawski (https://blog.kukawski.pl)
  //    input by: Greenseed
  //    input by: meo
  //    input by: William
  //    input by: Josep Sanz (https://www.ws3.es/)
  // bugfixed by: Brett Zamir (https://brett-zamir.me)
  //  revised by: Rafał Kukawski
  //   example 1: round(1241757, -3)
  //   returns 1: 1242000
  //   example 2: round(3.6)
  //   returns 2: 4
  //   example 3: round(2.835, 2)
  //   returns 3: 2.84
  //   example 4: round(1.1749999999999, 2)
  //   returns 4: 1.17
  //   example 5: round(58551.799999999996, 2)
  //   returns 5: 58551.8
  //   example 6: round(4096.485, 2)
  //   returns 6: 4096.49

  var floatCast = require('../_helpers/_php_cast_float');
  var intCast = require('../_helpers/_php_cast_int');
  var p = void 0;

  // the code is heavily based on the native PHP implementation
  // https://github.com/php/php-src/blob/PHP-7.4/ext/standard/math.c#L355

  value = floatCast(value);
  precision = intCast(precision);
  p = Math.pow(10, precision);

  if (isNaN(value) || !isFinite(value)) {
    return value;
  }

  // if value already integer and positive precision
  // then nothing to do, return early
  if (Math.trunc(value) === value && precision >= 0) {
    return value;
  }

  // PHP does a pre-rounding before rounding to desired precision
  // https://wiki.php.net/rfc/rounding#pre-rounding_to_the_value_s_precision_if_possible
  var preRoundPrecision = 14 - Math.floor(Math.log10(Math.abs(value)));

  if (preRoundPrecision > precision && preRoundPrecision - 15 < precision) {
    value = roundToInt(value * Math.pow(10, preRoundPrecision), mode);
    value /= Math.pow(10, Math.abs(precision - preRoundPrecision));
  } else {
    value *= p;
  }

  value = roundToInt(value, mode);

  return value / p;
};
return module.exports;
})();
/**/
var setlocale = this.setlocale = this.phpjs.setlocale = (function(){
'use strict';

var _typeof = typeof Symbol === "function" && typeof Symbol.iterator === "symbol" ? function (obj) { return typeof obj; } : function (obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; };

module.exports = function setlocale(category, locale) {
  //  discuss at: https://locutus.io/php/setlocale/
  // original by: Brett Zamir (https://brett-zamir.me)
  // original by: Blues (https://hacks.bluesmoon.info/strftime/strftime.js)
  // original by: YUI Library (https://developer.yahoo.com/yui/docs/YAHOO.util.DateLocale.html)
  //      note 1: Is extensible, but currently only implements locales en,
  //      note 1: en_US, en_GB, en_AU, fr, and fr_CA for LC_TIME only; C for LC_CTYPE;
  //      note 1: C and en for LC_MONETARY/LC_NUMERIC; en for LC_COLLATE
  //      note 1: Uses global: locutus to store locale info
  //      note 1: Consider using https://demo.icu-project.org/icu-bin/locexp as basis for localization (as in i18n_loc_set_default())
  //      note 2: This function tries to establish the locale via the `window` global.
  //      note 2: This feature will not work in Node and hence is Browser-only
  //   example 1: setlocale('LC_ALL', 'en_US')
  //   returns 1: 'en_US'

  var getenv = require('../info/getenv');

  var categ = '';
  var cats = [];
  var i = 0;

  var _copy = function _copy(orig) {
    if (orig instanceof RegExp) {
      return new RegExp(orig);
    } else if (orig instanceof Date) {
      return new Date(orig);
    }
    var newObj = {};
    for (var _i in orig) {
      if (_typeof(orig[_i]) === 'object') {
        newObj[_i] = _copy(orig[_i]);
      } else {
        newObj[_i] = orig[_i];
      }
    }
    return newObj;
  };

  // Function usable by a ngettext implementation (apparently not an accessible part of setlocale(),
  // but locale-specific) See https://www.gnu.org/software/gettext/manual/gettext.html#Plural-forms
  // though amended with others from https://developer.mozilla.org/En/Localization_and_Plurals (new
  // categories noted with "MDC" below, though not sure of whether there is a convention for the
  // relative order of these newer groups as far as ngettext) The function name indicates the number
  // of plural forms (nplural) Need to look into https://cldr.unicode.org/ (maybe future JavaScript);
  // Dojo has some functions (under new BSD), including JSON conversions of LDML XML from CLDR:
  // https://bugs.dojotoolkit.org/browser/dojo/trunk/cldr and docs at
  // https://api.dojotoolkit.org/jsdoc/HEAD/dojo.cldr

  // var _nplurals1 = function (n) {
  //   // e.g., Japanese
  //   return 0
  // }
  var _nplurals2a = function _nplurals2a(n) {
    // e.g., English
    return n !== 1 ? 1 : 0;
  };
  var _nplurals2b = function _nplurals2b(n) {
    // e.g., French
    return n > 1 ? 1 : 0;
  };

  var $global = typeof window !== 'undefined' ? window : global;
  $global.$locutus = $global.$locutus || {};
  var $locutus = $global.$locutus;
  $locutus.php = $locutus.php || {};

  // Reconcile Windows vs. *nix locale names?
  // Allow different priority orders of languages, esp. if implement gettext as in
  // LANGUAGE env. var.? (e.g., show German if French is not available)
  if (!$locutus.php.locales || !$locutus.php.locales.fr_CA || !$locutus.php.locales.fr_CA.LC_TIME || !$locutus.php.locales.fr_CA.LC_TIME.x) {
    // Can add to the locales
    $locutus.php.locales = {};

    $locutus.php.locales.en = {
      LC_COLLATE: function LC_COLLATE(str1, str2) {
        // @todo: This one taken from strcmp, but need for other locales; we don't use localeCompare
        // since its locale is not settable
        return str1 === str2 ? 0 : str1 > str2 ? 1 : -1;
      },
      LC_CTYPE: {
        // Need to change any of these for English as opposed to C?
        an: /^[A-Za-z\d]+$/g,
        al: /^[A-Za-z]+$/g,
        ct: /^[\u0000-\u001F\u007F]+$/g,
        dg: /^[\d]+$/g,
        gr: /^[\u0021-\u007E]+$/g,
        lw: /^[a-z]+$/g,
        pr: /^[\u0020-\u007E]+$/g,
        pu: /^[\u0021-\u002F\u003A-\u0040\u005B-\u0060\u007B-\u007E]+$/g,
        sp: /^[\f\n\r\t\v ]+$/g,
        up: /^[A-Z]+$/g,
        xd: /^[A-Fa-f\d]+$/g,
        CODESET: 'UTF-8',
        // Used by sql_regcase
        lower: 'abcdefghijklmnopqrstuvwxyz',
        upper: 'ABCDEFGHIJKLMNOPQRSTUVWXYZ'
      },
      LC_TIME: {
        // Comments include nl_langinfo() constant equivalents and any
        // changes from Blues' implementation
        a: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
        // ABDAY_
        A: ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],
        // DAY_
        b: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        // ABMON_
        B: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
        // MON_
        c: '%a %d %b %Y %r %Z',
        // D_T_FMT // changed %T to %r per results
        p: ['AM', 'PM'],
        // AM_STR/PM_STR
        P: ['am', 'pm'],
        // Not available in nl_langinfo()
        r: '%I:%M:%S %p',
        // T_FMT_AMPM (Fixed for all locales)
        x: '%m/%d/%Y',
        // D_FMT // switched order of %m and %d; changed %y to %Y (C uses %y)
        X: '%r',
        // T_FMT // changed from %T to %r  (%T is default for C, not English US)
        // Following are from nl_langinfo() or https://www.cptec.inpe.br/sx4/sx4man2/g1ab02e/strftime.4.html
        alt_digits: '',
        // e.g., ordinal
        ERA: '',
        ERA_YEAR: '',
        ERA_D_T_FMT: '',
        ERA_D_FMT: '',
        ERA_T_FMT: ''
      },
      // Assuming distinction between numeric and monetary is thus:
      // See below for C locale
      LC_MONETARY: {
        // based on Windows "english" (English_United States.1252) locale
        int_curr_symbol: 'USD',
        currency_symbol: '$',
        mon_decimal_point: '.',
        mon_thousands_sep: ',',
        mon_grouping: [3],
        // use mon_thousands_sep; "" for no grouping; additional array members
        // indicate successive group lengths after first group
        // (e.g., if to be 1,23,456, could be [3, 2])
        positive_sign: '',
        negative_sign: '-',
        int_frac_digits: 2,
        // Fractional digits only for money defaults?
        frac_digits: 2,
        p_cs_precedes: 1,
        // positive currency symbol follows value = 0; precedes value = 1
        p_sep_by_space: 0,
        // 0: no space between curr. symbol and value; 1: space sep. them unless symb.
        // and sign are adjacent then space sep. them from value; 2: space sep. sign
        // and value unless symb. and sign are adjacent then space separates
        n_cs_precedes: 1,
        // see p_cs_precedes
        n_sep_by_space: 0,
        // see p_sep_by_space
        p_sign_posn: 3,
        // 0: parentheses surround quantity and curr. symbol; 1: sign precedes them;
        // 2: sign follows them; 3: sign immed. precedes curr. symbol; 4: sign immed.
        // succeeds curr. symbol
        n_sign_posn: 0 // see p_sign_posn
      },
      LC_NUMERIC: {
        // based on Windows "english" (English_United States.1252) locale
        decimal_point: '.',
        thousands_sep: ',',
        grouping: [3] // see mon_grouping, but for non-monetary values (use thousands_sep)
      },
      LC_MESSAGES: {
        YESEXPR: '^[yY].*',
        NOEXPR: '^[nN].*',
        YESSTR: '',
        NOSTR: ''
      },
      nplurals: _nplurals2a
    };
    $locutus.php.locales.en_US = _copy($locutus.php.locales.en);
    $locutus.php.locales.en_US.LC_TIME.c = '%a %d %b %Y %r %Z';
    $locutus.php.locales.en_US.LC_TIME.x = '%D';
    $locutus.php.locales.en_US.LC_TIME.X = '%r';
    // The following are based on *nix settings
    $locutus.php.locales.en_US.LC_MONETARY.int_curr_symbol = 'USD ';
    $locutus.php.locales.en_US.LC_MONETARY.p_sign_posn = 1;
    $locutus.php.locales.en_US.LC_MONETARY.n_sign_posn = 1;
    $locutus.php.locales.en_US.LC_MONETARY.mon_grouping = [3, 3];
    $locutus.php.locales.en_US.LC_NUMERIC.thousands_sep = '';
    $locutus.php.locales.en_US.LC_NUMERIC.grouping = [];

    $locutus.php.locales.en_GB = _copy($locutus.php.locales.en);
    $locutus.php.locales.en_GB.LC_TIME.r = '%l:%M:%S %P %Z';

    $locutus.php.locales.en_AU = _copy($locutus.php.locales.en_GB);
    // Assume C locale is like English (?) (We need C locale for LC_CTYPE)
    $locutus.php.locales.C = _copy($locutus.php.locales.en);
    $locutus.php.locales.C.LC_CTYPE.CODESET = 'ANSI_X3.4-1968';
    $locutus.php.locales.C.LC_MONETARY = {
      int_curr_symbol: '',
      currency_symbol: '',
      mon_decimal_point: '',
      mon_thousands_sep: '',
      mon_grouping: [],
      p_cs_precedes: 127,
      p_sep_by_space: 127,
      n_cs_precedes: 127,
      n_sep_by_space: 127,
      p_sign_posn: 127,
      n_sign_posn: 127,
      positive_sign: '',
      negative_sign: '',
      int_frac_digits: 127,
      frac_digits: 127
    };
    $locutus.php.locales.C.LC_NUMERIC = {
      decimal_point: '.',
      thousands_sep: '',
      grouping: []
      // D_T_FMT
    };$locutus.php.locales.C.LC_TIME.c = '%a %b %e %H:%M:%S %Y';
    // D_FMT
    $locutus.php.locales.C.LC_TIME.x = '%m/%d/%y';
    // T_FMT
    $locutus.php.locales.C.LC_TIME.X = '%H:%M:%S';
    $locutus.php.locales.C.LC_MESSAGES.YESEXPR = '^[yY]';
    $locutus.php.locales.C.LC_MESSAGES.NOEXPR = '^[nN]';

    $locutus.php.locales.fr = _copy($locutus.php.locales.en);
    $locutus.php.locales.fr.nplurals = _nplurals2b;
    $locutus.php.locales.fr.LC_TIME.a = ['dim', 'lun', 'mar', 'mer', 'jeu', 'ven', 'sam'];
    $locutus.php.locales.fr.LC_TIME.A = ['dimanche', 'lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi'];
    $locutus.php.locales.fr.LC_TIME.b = ['jan', 'f\xE9v', 'mar', 'avr', 'mai', 'jun', 'jui', 'ao\xFB', 'sep', 'oct', 'nov', 'd\xE9c'];
    $locutus.php.locales.fr.LC_TIME.B = ['janvier', 'f\xE9vrier', 'mars', 'avril', 'mai', 'juin', 'juillet', 'ao\xFBt', 'septembre', 'octobre', 'novembre', 'd\xE9cembre'];
    $locutus.php.locales.fr.LC_TIME.c = '%a %d %b %Y %T %Z';
    $locutus.php.locales.fr.LC_TIME.p = ['', ''];
    $locutus.php.locales.fr.LC_TIME.P = ['', ''];
    $locutus.php.locales.fr.LC_TIME.x = '%d.%m.%Y';
    $locutus.php.locales.fr.LC_TIME.X = '%T';

    $locutus.php.locales.fr_CA = _copy($locutus.php.locales.fr);
    $locutus.php.locales.fr_CA.LC_TIME.x = '%Y-%m-%d';
  }
  if (!$locutus.php.locale) {
    $locutus.php.locale = 'en_US';
    // Try to establish the locale via the `window` global
    if (typeof window !== 'undefined' && window.document) {
      var d = window.document;
      var NS_XHTML = 'https://www.w3.org/1999/xhtml';
      var NS_XML = 'https://www.w3.org/XML/1998/namespace';
      if (d.getElementsByTagNameNS && d.getElementsByTagNameNS(NS_XHTML, 'html')[0]) {
        if (d.getElementsByTagNameNS(NS_XHTML, 'html')[0].getAttributeNS && d.getElementsByTagNameNS(NS_XHTML, 'html')[0].getAttributeNS(NS_XML, 'lang')) {
          $locutus.php.locale = d.getElementsByTagName(NS_XHTML, 'html')[0].getAttributeNS(NS_XML, 'lang');
        } else if (d.getElementsByTagNameNS(NS_XHTML, 'html')[0].lang) {
          // XHTML 1.0 only
          $locutus.php.locale = d.getElementsByTagNameNS(NS_XHTML, 'html')[0].lang;
        }
      } else if (d.getElementsByTagName('html')[0] && d.getElementsByTagName('html')[0].lang) {
        $locutus.php.locale = d.getElementsByTagName('html')[0].lang;
      }
    }
  }
  // PHP-style
  $locutus.php.locale = $locutus.php.locale.replace('-', '_');
  // @todo: locale if declared locale hasn't been defined
  if (!($locutus.php.locale in $locutus.php.locales)) {
    if ($locutus.php.locale.replace(/_[a-zA-Z]+$/, '') in $locutus.php.locales) {
      $locutus.php.locale = $locutus.php.locale.replace(/_[a-zA-Z]+$/, '');
    }
  }

  if (!$locutus.php.localeCategories) {
    $locutus.php.localeCategories = {
      LC_COLLATE: $locutus.php.locale,
      // for string comparison, see strcoll()
      LC_CTYPE: $locutus.php.locale,
      // for character classification and conversion, for example strtoupper()
      LC_MONETARY: $locutus.php.locale,
      // for localeconv()
      LC_NUMERIC: $locutus.php.locale,
      // for decimal separator (See also localeconv())
      LC_TIME: $locutus.php.locale,
      // for date and time formatting with strftime()
      // for system responses (available if PHP was compiled with libintl):
      LC_MESSAGES: $locutus.php.locale
    };
  }

  if (locale === null || locale === '') {
    locale = getenv(category) || getenv('LANG');
  } else if (Object.prototype.toString.call(locale) === '[object Array]') {
    for (i = 0; i < locale.length; i++) {
      if (!(locale[i] in $locutus.php.locales)) {
        if (i === locale.length - 1) {
          // none found
          return false;
        }
        continue;
      }
      locale = locale[i];
      break;
    }
  }

  // Just get the locale
  if (locale === '0' || locale === 0) {
    if (category === 'LC_ALL') {
      for (categ in $locutus.php.localeCategories) {
        // Add ".UTF-8" or allow ".@latint", etc. to the end?
        cats.push(categ + '=' + $locutus.php.localeCategories[categ]);
      }
      return cats.join(';');
    }
    return $locutus.php.localeCategories[category];
  }

  if (!(locale in $locutus.php.locales)) {
    // Locale not found
    return false;
  }

  // Set and get locale
  if (category === 'LC_ALL') {
    for (categ in $locutus.php.localeCategories) {
      $locutus.php.localeCategories[categ] = locale;
    }
  } else {
    $locutus.php.localeCategories[category] = locale;
  }

  return locale;
};
return module.exports;
})();
/**/
var split = this.split = this.phpjs.split = (function(){
'use strict';

module.exports = function split(delimiter, string) {
  //  discuss at: https://locutus.io/php/split/
  // original by: Kevin van Zonneveld (https://kvz.io)
  //   example 1: split(' ', 'Kevin van Zonneveld')
  //   returns 1: ['Kevin', 'van', 'Zonneveld']

  var explode = require('../strings/explode');
  return explode(delimiter, string);
};
return module.exports;
})();
/**/
var sprintf = this.sprintf = this.phpjs.sprintf = (function(){
'use strict';

module.exports = function sprintf() {
  //  discuss at: https://locutus.io/php/sprintf/
  // original by: Ash Searle (https://hexmen.com/blog/)
  // improved by: Michael White (https://getsprink.com)
  // improved by: Jack
  // improved by: Kevin van Zonneveld (https://kvz.io)
  // improved by: Kevin van Zonneveld (https://kvz.io)
  // improved by: Kevin van Zonneveld (https://kvz.io)
  // improved by: Dj
  // improved by: Allidylls
  //    input by: Paulo Freitas
  //    input by: Brett Zamir (https://brett-zamir.me)
  // improved by: Rafał Kukawski (https://kukawski.pl)
  //   example 1: sprintf("%01.2f", 123.1)
  //   returns 1: '123.10'
  //   example 2: sprintf("[%10s]", 'monkey')
  //   returns 2: '[    monkey]'
  //   example 3: sprintf("[%'#10s]", 'monkey')
  //   returns 3: '[####monkey]'
  //   example 4: sprintf("%d", 123456789012345)
  //   returns 4: '123456789012345'
  //   example 5: sprintf('%-03s', 'E')
  //   returns 5: 'E00'
  //   example 6: sprintf('%+010d', 9)
  //   returns 6: '+000000009'
  //   example 7: sprintf('%+0\'@10d', 9)
  //   returns 7: '@@@@@@@@+9'
  //   example 8: sprintf('%.f', 3.14)
  //   returns 8: '3.140000'
  //   example 9: sprintf('%% %2$d', 1, 2)
  //   returns 9: '% 2'

  var regex = /%%|%(?:(\d+)\$)?((?:[-+#0 ]|'[\s\S])*)(\d+)?(?:\.(\d*))?([\s\S])/g;
  var args = arguments;
  var i = 0;
  var format = args[i++];

  var _pad = function _pad(str, len, chr, leftJustify) {
    if (!chr) {
      chr = ' ';
    }
    var padding = str.length >= len ? '' : new Array(1 + len - str.length >>> 0).join(chr);
    return leftJustify ? str + padding : padding + str;
  };

  var justify = function justify(value, prefix, leftJustify, minWidth, padChar) {
    var diff = minWidth - value.length;
    if (diff > 0) {
      // when padding with zeros
      // on the left side
      // keep sign (+ or -) in front
      if (!leftJustify && padChar === '0') {
        value = [value.slice(0, prefix.length), _pad('', diff, '0', true), value.slice(prefix.length)].join('');
      } else {
        value = _pad(value, minWidth, padChar, leftJustify);
      }
    }
    return value;
  };

  var _formatBaseX = function _formatBaseX(value, base, leftJustify, minWidth, precision, padChar) {
    // Note: casts negative numbers to positive ones
    var number = value >>> 0;
    value = _pad(number.toString(base), precision || 0, '0', false);
    return justify(value, '', leftJustify, minWidth, padChar);
  };

  // _formatString()
  var _formatString = function _formatString(value, leftJustify, minWidth, precision, customPadChar) {
    if (precision !== null && precision !== undefined) {
      value = value.slice(0, precision);
    }
    return justify(value, '', leftJustify, minWidth, customPadChar);
  };

  // doFormat()
  var doFormat = function doFormat(substring, argIndex, modifiers, minWidth, precision, specifier) {
    var number = void 0,
        prefix = void 0,
        method = void 0,
        textTransform = void 0,
        value = void 0;

    if (substring === '%%') {
      return '%';
    }

    // parse modifiers
    var padChar = ' '; // pad with spaces by default
    var leftJustify = false;
    var positiveNumberPrefix = '';
    var j = void 0,
        l = void 0;

    for (j = 0, l = modifiers.length; j < l; j++) {
      switch (modifiers.charAt(j)) {
        case ' ':
        case '0':
          padChar = modifiers.charAt(j);
          break;
        case '+':
          positiveNumberPrefix = '+';
          break;
        case '-':
          leftJustify = true;
          break;
        case "'":
          if (j + 1 < l) {
            padChar = modifiers.charAt(j + 1);
            j++;
          }
          break;
      }
    }

    if (!minWidth) {
      minWidth = 0;
    } else {
      minWidth = +minWidth;
    }

    if (!isFinite(minWidth)) {
      throw new Error('Width must be finite');
    }

    if (!precision) {
      precision = specifier === 'd' ? 0 : 'fFeE'.indexOf(specifier) > -1 ? 6 : undefined;
    } else {
      precision = +precision;
    }

    if (argIndex && +argIndex === 0) {
      throw new Error('Argument number must be greater than zero');
    }

    if (argIndex && +argIndex >= args.length) {
      throw new Error('Too few arguments');
    }

    value = argIndex ? args[+argIndex] : args[i++];

    switch (specifier) {
      case '%':
        return '%';
      case 's':
        return _formatString(value + '', leftJustify, minWidth, precision, padChar);
      case 'c':
        return _formatString(String.fromCharCode(+value), leftJustify, minWidth, precision, padChar);
      case 'b':
        return _formatBaseX(value, 2, leftJustify, minWidth, precision, padChar);
      case 'o':
        return _formatBaseX(value, 8, leftJustify, minWidth, precision, padChar);
      case 'x':
        return _formatBaseX(value, 16, leftJustify, minWidth, precision, padChar);
      case 'X':
        return _formatBaseX(value, 16, leftJustify, minWidth, precision, padChar).toUpperCase();
      case 'u':
        return _formatBaseX(value, 10, leftJustify, minWidth, precision, padChar);
      case 'i':
      case 'd':
        number = +value || 0;
        // Plain Math.round doesn't just truncate
        number = Math.round(number - number % 1);
        prefix = number < 0 ? '-' : positiveNumberPrefix;
        value = prefix + _pad(String(Math.abs(number)), precision, '0', false);

        if (leftJustify && padChar === '0') {
          // can't right-pad 0s on integers
          padChar = ' ';
        }
        return justify(value, prefix, leftJustify, minWidth, padChar);
      case 'e':
      case 'E':
      case 'f': // @todo: Should handle locales (as per setlocale)
      case 'F':
      case 'g':
      case 'G':
        number = +value;
        prefix = number < 0 ? '-' : positiveNumberPrefix;
        method = ['toExponential', 'toFixed', 'toPrecision']['efg'.indexOf(specifier.toLowerCase())];
        textTransform = ['toString', 'toUpperCase']['eEfFgG'.indexOf(specifier) % 2];
        value = prefix + Math.abs(number)[method](precision);
        return justify(value, prefix, leftJustify, minWidth, padChar)[textTransform]();
      default:
        // unknown specifier, consume that char and return empty
        return '';
    }
  };

  try {
    return format.replace(regex, doFormat);
  } catch (err) {
    return false;
  }
};
return module.exports;
})();
/**/
var str_split = this.str_split = this.phpjs.str_split = (function(){
'use strict';

module.exports = function str_split(string, splitLength) {
  //  discuss at: https://locutus.io/php/str_split/
  // original by: Martijn Wieringa
  // improved by: Brett Zamir (https://brett-zamir.me)
  // bugfixed by: Onno Marsman (https://twitter.com/onnomarsman)
  //  revised by: Theriault (https://github.com/Theriault)
  //  revised by: Rafał Kukawski (https://blog.kukawski.pl)
  //    input by: Bjorn Roesbeke (https://www.bjornroesbeke.be/)
  //   example 1: str_split('Hello Friend', 3)
  //   returns 1: ['Hel', 'lo ', 'Fri', 'end']

  if (splitLength === null) {
    splitLength = 1;
  }
  if (string === null || splitLength < 1) {
    return false;
  }

  string += '';
  var chunks = [];
  var pos = 0;
  var len = string.length;

  while (pos < len) {
    chunks.push(string.slice(pos, pos += splitLength));
  }

  return chunks;
};
return module.exports;
})();
/**/
var strlen = this.strlen = this.phpjs.strlen = (function(){
/**
 * strlen
 */
module.exports = function strlen(string) {
    const encoder = new TextEncoder();
    return encoder.encode(string).length;
};
return module.exports;
})();
/**/
var strtolower = this.strtolower = this.phpjs.strtolower = (function(){
'use strict';

module.exports = function strtolower(str) {
  //  discuss at: https://locutus.io/php/strtolower/
  // original by: Kevin van Zonneveld (https://kvz.io)
  // improved by: Onno Marsman (https://twitter.com/onnomarsman)
  //   example 1: strtolower('Kevin van Zonneveld')
  //   returns 1: 'kevin van zonneveld'

  return (str + '').toLowerCase();
};
return module.exports;
})();
/**/
var strtotime = this.strtotime = this.phpjs.strtotime = (function(){
'use strict';

var reSpace = '[ \\t]+';
var reSpaceOpt = '[ \\t]*';
var reMeridian = '(?:([ap])\\.?m\\.?([\\t ]|$))';
var reHour24 = '(2[0-4]|[01]?[0-9])';
var reHour24lz = '([01][0-9]|2[0-4])';
var reHour12 = '(0?[1-9]|1[0-2])';
var reMinute = '([0-5]?[0-9])';
var reMinutelz = '([0-5][0-9])';
var reSecond = '(60|[0-5]?[0-9])';
var reSecondlz = '(60|[0-5][0-9])';
var reFrac = '(?:\\.([0-9]+))';

var reDayfull = 'sunday|monday|tuesday|wednesday|thursday|friday|saturday';
var reDayabbr = 'sun|mon|tue|wed|thu|fri|sat';
var reDaytext = reDayfull + '|' + reDayabbr + '|weekdays?';

var reReltextnumber = 'first|second|third|fourth|fifth|sixth|seventh|eighth?|ninth|tenth|eleventh|twelfth';
var reReltexttext = 'next|last|previous|this';
var reReltextunit = '(?:second|sec|minute|min|hour|day|fortnight|forthnight|month|year)s?|weeks|' + reDaytext;

var reYear = '([0-9]{1,4})';
var reYear2 = '([0-9]{2})';
var reYear4 = '([0-9]{4})';
var reYear4withSign = '([+-]?[0-9]{4})';
var reMonth = '(1[0-2]|0?[0-9])';
var reMonthlz = '(0[0-9]|1[0-2])';
var reDay = '(?:(3[01]|[0-2]?[0-9])(?:st|nd|rd|th)?)';
var reDaylz = '(0[0-9]|[1-2][0-9]|3[01])';

var reMonthFull = 'january|february|march|april|may|june|july|august|september|october|november|december';
var reMonthAbbr = 'jan|feb|mar|apr|may|jun|jul|aug|sept?|oct|nov|dec';
var reMonthroman = 'i[vx]|vi{0,3}|xi{0,2}|i{1,3}';
var reMonthText = '(' + reMonthFull + '|' + reMonthAbbr + '|' + reMonthroman + ')';

var reTzCorrection = '((?:GMT)?([+-])' + reHour24 + ':?' + reMinute + '?)';
var reTzAbbr = '\\(?([a-zA-Z]{1,6})\\)?';
var reDayOfYear = '(00[1-9]|0[1-9][0-9]|[12][0-9][0-9]|3[0-5][0-9]|36[0-6])';
var reWeekOfYear = '(0[1-9]|[1-4][0-9]|5[0-3])';

var reDateNoYear = reMonthText + '[ .\\t-]*' + reDay + '[,.stndrh\\t ]*';

function processMeridian(hour, meridian) {
  meridian = meridian && meridian.toLowerCase();

  switch (meridian) {
    case 'a':
      hour += hour === 12 ? -12 : 0;
      break;
    case 'p':
      hour += hour !== 12 ? 12 : 0;
      break;
  }

  return hour;
}

function processYear(yearStr) {
  var year = +yearStr;

  if (yearStr.length < 4 && year < 100) {
    year += year < 70 ? 2000 : 1900;
  }

  return year;
}

function lookupMonth(monthStr) {
  return {
    jan: 0,
    january: 0,
    i: 0,
    feb: 1,
    february: 1,
    ii: 1,
    mar: 2,
    march: 2,
    iii: 2,
    apr: 3,
    april: 3,
    iv: 3,
    may: 4,
    v: 4,
    jun: 5,
    june: 5,
    vi: 5,
    jul: 6,
    july: 6,
    vii: 6,
    aug: 7,
    august: 7,
    viii: 7,
    sep: 8,
    sept: 8,
    september: 8,
    ix: 8,
    oct: 9,
    october: 9,
    x: 9,
    nov: 10,
    november: 10,
    xi: 10,
    dec: 11,
    december: 11,
    xii: 11
  }[monthStr.toLowerCase()];
}

function lookupWeekday(dayStr) {
  var desiredSundayNumber = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : 0;

  var dayNumbers = {
    mon: 1,
    monday: 1,
    tue: 2,
    tuesday: 2,
    wed: 3,
    wednesday: 3,
    thu: 4,
    thursday: 4,
    fri: 5,
    friday: 5,
    sat: 6,
    saturday: 6,
    sun: 0,
    sunday: 0
  };

  return dayNumbers[dayStr.toLowerCase()] || desiredSundayNumber;
}

function lookupRelative(relText) {
  var relativeNumbers = {
    last: -1,
    previous: -1,
    this: 0,
    first: 1,
    next: 1,
    second: 2,
    third: 3,
    fourth: 4,
    fifth: 5,
    sixth: 6,
    seventh: 7,
    eight: 8,
    eighth: 8,
    ninth: 9,
    tenth: 10,
    eleventh: 11,
    twelfth: 12
  };

  var relativeBehavior = {
    this: 1
  };

  var relTextLower = relText.toLowerCase();

  return {
    amount: relativeNumbers[relTextLower],
    behavior: relativeBehavior[relTextLower] || 0
  };
}

function processTzCorrection(tzOffset, oldValue) {
  var reTzCorrectionLoose = /(?:GMT)?([+-])(\d+)(:?)(\d{0,2})/i;
  tzOffset = tzOffset && tzOffset.match(reTzCorrectionLoose);

  if (!tzOffset) {
    return oldValue;
  }

  var sign = tzOffset[1] === '-' ? -1 : 1;
  var hours = +tzOffset[2];
  var minutes = +tzOffset[4];

  if (!tzOffset[4] && !tzOffset[3]) {
    minutes = Math.floor(hours % 100);
    hours = Math.floor(hours / 100);
  }

  // timezone offset in seconds
  return sign * (hours * 60 + minutes) * 60;
}

// tz abbrevation : tz offset in seconds
var tzAbbrOffsets = {
  acdt: 37800,
  acst: 34200,
  addt: -7200,
  adt: -10800,
  aedt: 39600,
  aest: 36000,
  ahdt: -32400,
  ahst: -36000,
  akdt: -28800,
  akst: -32400,
  amt: -13840,
  apt: -10800,
  ast: -14400,
  awdt: 32400,
  awst: 28800,
  awt: -10800,
  bdst: 7200,
  bdt: -36000,
  bmt: -14309,
  bst: 3600,
  cast: 34200,
  cat: 7200,
  cddt: -14400,
  cdt: -18000,
  cemt: 10800,
  cest: 7200,
  cet: 3600,
  cmt: -15408,
  cpt: -18000,
  cst: -21600,
  cwt: -18000,
  chst: 36000,
  dmt: -1521,
  eat: 10800,
  eddt: -10800,
  edt: -14400,
  eest: 10800,
  eet: 7200,
  emt: -26248,
  ept: -14400,
  est: -18000,
  ewt: -14400,
  ffmt: -14660,
  fmt: -4056,
  gdt: 39600,
  gmt: 0,
  gst: 36000,
  hdt: -34200,
  hkst: 32400,
  hkt: 28800,
  hmt: -19776,
  hpt: -34200,
  hst: -36000,
  hwt: -34200,
  iddt: 14400,
  idt: 10800,
  imt: 25025,
  ist: 7200,
  jdt: 36000,
  jmt: 8440,
  jst: 32400,
  kdt: 36000,
  kmt: 5736,
  kst: 30600,
  lst: 9394,
  mddt: -18000,
  mdst: 16279,
  mdt: -21600,
  mest: 7200,
  met: 3600,
  mmt: 9017,
  mpt: -21600,
  msd: 14400,
  msk: 10800,
  mst: -25200,
  mwt: -21600,
  nddt: -5400,
  ndt: -9052,
  npt: -9000,
  nst: -12600,
  nwt: -9000,
  nzdt: 46800,
  nzmt: 41400,
  nzst: 43200,
  pddt: -21600,
  pdt: -25200,
  pkst: 21600,
  pkt: 18000,
  plmt: 25590,
  pmt: -13236,
  ppmt: -17340,
  ppt: -25200,
  pst: -28800,
  pwt: -25200,
  qmt: -18840,
  rmt: 5794,
  sast: 7200,
  sdmt: -16800,
  sjmt: -20173,
  smt: -13884,
  sst: -39600,
  tbmt: 10751,
  tmt: 12344,
  uct: 0,
  utc: 0,
  wast: 7200,
  wat: 3600,
  wemt: 7200,
  west: 3600,
  wet: 0,
  wib: 25200,
  wita: 28800,
  wit: 32400,
  wmt: 5040,
  yddt: -25200,
  ydt: -28800,
  ypt: -28800,
  yst: -32400,
  ywt: -28800,
  a: 3600,
  b: 7200,
  c: 10800,
  d: 14400,
  e: 18000,
  f: 21600,
  g: 25200,
  h: 28800,
  i: 32400,
  k: 36000,
  l: 39600,
  m: 43200,
  n: -3600,
  o: -7200,
  p: -10800,
  q: -14400,
  r: -18000,
  s: -21600,
  t: -25200,
  u: -28800,
  v: -32400,
  w: -36000,
  x: -39600,
  y: -43200,
  z: 0
};

var formats = {
  yesterday: {
    regex: /^yesterday/i,
    name: 'yesterday',
    callback: function callback() {
      this.rd -= 1;
      return this.resetTime();
    }
  },

  now: {
    regex: /^now/i,
    name: 'now'
    // do nothing
  },

  noon: {
    regex: /^noon/i,
    name: 'noon',
    callback: function callback() {
      return this.resetTime() && this.time(12, 0, 0, 0);
    }
  },

  midnightOrToday: {
    regex: /^(midnight|today)/i,
    name: 'midnight | today',
    callback: function callback() {
      return this.resetTime();
    }
  },

  tomorrow: {
    regex: /^tomorrow/i,
    name: 'tomorrow',
    callback: function callback() {
      this.rd += 1;
      return this.resetTime();
    }
  },

  timestamp: {
    regex: /^@(-?\d+)/i,
    name: 'timestamp',
    callback: function callback(match, timestamp) {
      this.rs += +timestamp;
      this.y = 1970;
      this.m = 0;
      this.d = 1;
      this.dates = 0;

      return this.resetTime() && this.zone(0);
    }
  },

  firstOrLastDay: {
    regex: /^(first|last) day of/i,
    name: 'firstdayof | lastdayof',
    callback: function callback(match, day) {
      if (day.toLowerCase() === 'first') {
        this.firstOrLastDayOfMonth = 1;
      } else {
        this.firstOrLastDayOfMonth = -1;
      }
    }
  },

  backOrFrontOf: {
    regex: RegExp('^(back|front) of ' + reHour24 + reSpaceOpt + reMeridian + '?', 'i'),
    name: 'backof | frontof',
    callback: function callback(match, side, hours, meridian) {
      var back = side.toLowerCase() === 'back';
      var hour = +hours;
      var minute = 15;

      if (!back) {
        hour -= 1;
        minute = 45;
      }

      hour = processMeridian(hour, meridian);

      return this.resetTime() && this.time(hour, minute, 0, 0);
    }
  },

  weekdayOf: {
    regex: RegExp('^(' + reReltextnumber + '|' + reReltexttext + ')' + reSpace + '(' + reDayfull + '|' + reDayabbr + ')' + reSpace + 'of', 'i'),
    name: 'weekdayof'
    // todo
  },

  mssqltime: {
    regex: RegExp('^' + reHour12 + ':' + reMinutelz + ':' + reSecondlz + '[:.]([0-9]+)' + reMeridian, 'i'),
    name: 'mssqltime',
    callback: function callback(match, hour, minute, second, frac, meridian) {
      return this.time(processMeridian(+hour, meridian), +minute, +second, +frac.substr(0, 3));
    }
  },

  oracledate: {
    regex: /^(\d{2})-([A-Z]{3})-(\d{2})$/i,
    name: 'd-M-y',
    callback: function callback(match, day, monthText, year) {
      var month = {
        JAN: 0,
        FEB: 1,
        MAR: 2,
        APR: 3,
        MAY: 4,
        JUN: 5,
        JUL: 6,
        AUG: 7,
        SEP: 8,
        OCT: 9,
        NOV: 10,
        DEC: 11
      }[monthText.toUpperCase()];
      return this.ymd(2000 + parseInt(year, 10), month, parseInt(day, 10));
    }
  },

  timeLong12: {
    regex: RegExp('^' + reHour12 + '[:.]' + reMinute + '[:.]' + reSecondlz + reSpaceOpt + reMeridian, 'i'),
    name: 'timelong12',
    callback: function callback(match, hour, minute, second, meridian) {
      return this.time(processMeridian(+hour, meridian), +minute, +second, 0);
    }
  },

  timeShort12: {
    regex: RegExp('^' + reHour12 + '[:.]' + reMinutelz + reSpaceOpt + reMeridian, 'i'),
    name: 'timeshort12',
    callback: function callback(match, hour, minute, meridian) {
      return this.time(processMeridian(+hour, meridian), +minute, 0, 0);
    }
  },

  timeTiny12: {
    regex: RegExp('^' + reHour12 + reSpaceOpt + reMeridian, 'i'),
    name: 'timetiny12',
    callback: function callback(match, hour, meridian) {
      return this.time(processMeridian(+hour, meridian), 0, 0, 0);
    }
  },

  soap: {
    regex: RegExp('^' + reYear4 + '-' + reMonthlz + '-' + reDaylz + 'T' + reHour24lz + ':' + reMinutelz + ':' + reSecondlz + reFrac + reTzCorrection + '?', 'i'),
    name: 'soap',
    callback: function callback(match, year, month, day, hour, minute, second, frac, tzCorrection) {
      return this.ymd(+year, month - 1, +day) && this.time(+hour, +minute, +second, +frac.substr(0, 3)) && this.zone(processTzCorrection(tzCorrection));
    }
  },

  wddx: {
    regex: RegExp('^' + reYear4 + '-' + reMonth + '-' + reDay + 'T' + reHour24 + ':' + reMinute + ':' + reSecond),
    name: 'wddx',
    callback: function callback(match, year, month, day, hour, minute, second) {
      return this.ymd(+year, month - 1, +day) && this.time(+hour, +minute, +second, 0);
    }
  },

  exif: {
    regex: RegExp('^' + reYear4 + ':' + reMonthlz + ':' + reDaylz + ' ' + reHour24lz + ':' + reMinutelz + ':' + reSecondlz, 'i'),
    name: 'exif',
    callback: function callback(match, year, month, day, hour, minute, second) {
      return this.ymd(+year, month - 1, +day) && this.time(+hour, +minute, +second, 0);
    }
  },

  xmlRpc: {
    regex: RegExp('^' + reYear4 + reMonthlz + reDaylz + 'T' + reHour24 + ':' + reMinutelz + ':' + reSecondlz),
    name: 'xmlrpc',
    callback: function callback(match, year, month, day, hour, minute, second) {
      return this.ymd(+year, month - 1, +day) && this.time(+hour, +minute, +second, 0);
    }
  },

  xmlRpcNoColon: {
    regex: RegExp('^' + reYear4 + reMonthlz + reDaylz + '[Tt]' + reHour24 + reMinutelz + reSecondlz),
    name: 'xmlrpcnocolon',
    callback: function callback(match, year, month, day, hour, minute, second) {
      return this.ymd(+year, month - 1, +day) && this.time(+hour, +minute, +second, 0);
    }
  },

  clf: {
    regex: RegExp('^' + reDay + '/(' + reMonthAbbr + ')/' + reYear4 + ':' + reHour24lz + ':' + reMinutelz + ':' + reSecondlz + reSpace + reTzCorrection, 'i'),
    name: 'clf',
    callback: function callback(match, day, month, year, hour, minute, second, tzCorrection) {
      return this.ymd(+year, lookupMonth(month), +day) && this.time(+hour, +minute, +second, 0) && this.zone(processTzCorrection(tzCorrection));
    }
  },

  iso8601long: {
    regex: RegExp('^t?' + reHour24 + '[:.]' + reMinute + '[:.]' + reSecond + reFrac, 'i'),
    name: 'iso8601long',
    callback: function callback(match, hour, minute, second, frac) {
      return this.time(+hour, +minute, +second, +frac.substr(0, 3));
    }
  },

  dateTextual: {
    regex: RegExp('^' + reMonthText + '[ .\\t-]*' + reDay + '[,.stndrh\\t ]+' + reYear, 'i'),
    name: 'datetextual',
    callback: function callback(match, month, day, year) {
      return this.ymd(processYear(year), lookupMonth(month), +day);
    }
  },

  pointedDate4: {
    regex: RegExp('^' + reDay + '[.\\t-]' + reMonth + '[.-]' + reYear4),
    name: 'pointeddate4',
    callback: function callback(match, day, month, year) {
      return this.ymd(+year, month - 1, +day);
    }
  },

  pointedDate2: {
    regex: RegExp('^' + reDay + '[.\\t]' + reMonth + '\\.' + reYear2),
    name: 'pointeddate2',
    callback: function callback(match, day, month, year) {
      return this.ymd(processYear(year), month - 1, +day);
    }
  },

  timeLong24: {
    regex: RegExp('^t?' + reHour24 + '[:.]' + reMinute + '[:.]' + reSecond),
    name: 'timelong24',
    callback: function callback(match, hour, minute, second) {
      return this.time(+hour, +minute, +second, 0);
    }
  },

  dateNoColon: {
    regex: RegExp('^' + reYear4 + reMonthlz + reDaylz),
    name: 'datenocolon',
    callback: function callback(match, year, month, day) {
      return this.ymd(+year, month - 1, +day);
    }
  },

  pgydotd: {
    regex: RegExp('^' + reYear4 + '\\.?' + reDayOfYear),
    name: 'pgydotd',
    callback: function callback(match, year, day) {
      return this.ymd(+year, 0, +day);
    }
  },

  timeShort24: {
    regex: RegExp('^t?' + reHour24 + '[:.]' + reMinute, 'i'),
    name: 'timeshort24',
    callback: function callback(match, hour, minute) {
      return this.time(+hour, +minute, 0, 0);
    }
  },

  iso8601noColon: {
    regex: RegExp('^t?' + reHour24lz + reMinutelz + reSecondlz, 'i'),
    name: 'iso8601nocolon',
    callback: function callback(match, hour, minute, second) {
      return this.time(+hour, +minute, +second, 0);
    }
  },

  iso8601dateSlash: {
    // eventhough the trailing slash is optional in PHP
    // here it's mandatory and inputs without the slash
    // are handled by dateslash
    regex: RegExp('^' + reYear4 + '/' + reMonthlz + '/' + reDaylz + '/'),
    name: 'iso8601dateslash',
    callback: function callback(match, year, month, day) {
      return this.ymd(+year, month - 1, +day);
    }
  },

  dateSlash: {
    regex: RegExp('^' + reYear4 + '/' + reMonth + '/' + reDay),
    name: 'dateslash',
    callback: function callback(match, year, month, day) {
      return this.ymd(+year, month - 1, +day);
    }
  },

  american: {
    regex: RegExp('^' + reMonth + '/' + reDay + '/' + reYear),
    name: 'american',
    callback: function callback(match, month, day, year) {
      return this.ymd(processYear(year), month - 1, +day);
    }
  },

  americanShort: {
    regex: RegExp('^' + reMonth + '/' + reDay),
    name: 'americanshort',
    callback: function callback(match, month, day) {
      return this.ymd(this.y, month - 1, +day);
    }
  },

  gnuDateShortOrIso8601date2: {
    // iso8601date2 is complete subset of gnudateshort
    regex: RegExp('^' + reYear + '-' + reMonth + '-' + reDay),
    name: 'gnudateshort | iso8601date2',
    callback: function callback(match, year, month, day) {
      return this.ymd(processYear(year), month - 1, +day);
    }
  },

  iso8601date4: {
    regex: RegExp('^' + reYear4withSign + '-' + reMonthlz + '-' + reDaylz),
    name: 'iso8601date4',
    callback: function callback(match, year, month, day) {
      return this.ymd(+year, month - 1, +day);
    }
  },

  gnuNoColon: {
    regex: RegExp('^t?' + reHour24lz + reMinutelz, 'i'),
    name: 'gnunocolon',
    callback: function callback(match, hour, minute) {
      // this rule is a special case
      // if time was already set once by any preceding rule, it sets the captured value as year
      switch (this.times) {
        case 0:
          return this.time(+hour, +minute, 0, this.f);
        case 1:
          this.y = hour * 100 + +minute;
          this.times++;

          return true;
        default:
          return false;
      }
    }
  },

  gnuDateShorter: {
    regex: RegExp('^' + reYear4 + '-' + reMonth),
    name: 'gnudateshorter',
    callback: function callback(match, year, month) {
      return this.ymd(+year, month - 1, 1);
    }
  },

  pgTextReverse: {
    // note: allowed years are from 32-9999
    // years below 32 should be treated as days in datefull
    regex: RegExp('^' + '(\\d{3,4}|[4-9]\\d|3[2-9])-(' + reMonthAbbr + ')-' + reDaylz, 'i'),
    name: 'pgtextreverse',
    callback: function callback(match, year, month, day) {
      return this.ymd(processYear(year), lookupMonth(month), +day);
    }
  },

  dateFull: {
    regex: RegExp('^' + reDay + '[ \\t.-]*' + reMonthText + '[ \\t.-]*' + reYear, 'i'),
    name: 'datefull',
    callback: function callback(match, day, month, year) {
      return this.ymd(processYear(year), lookupMonth(month), +day);
    }
  },

  dateNoDay: {
    regex: RegExp('^' + reMonthText + '[ .\\t-]*' + reYear4, 'i'),
    name: 'datenoday',
    callback: function callback(match, month, year) {
      return this.ymd(+year, lookupMonth(month), 1);
    }
  },

  dateNoDayRev: {
    regex: RegExp('^' + reYear4 + '[ .\\t-]*' + reMonthText, 'i'),
    name: 'datenodayrev',
    callback: function callback(match, year, month) {
      return this.ymd(+year, lookupMonth(month), 1);
    }
  },

  pgTextShort: {
    regex: RegExp('^(' + reMonthAbbr + ')-' + reDaylz + '-' + reYear, 'i'),
    name: 'pgtextshort',
    callback: function callback(match, month, day, year) {
      return this.ymd(processYear(year), lookupMonth(month), +day);
    }
  },

  dateNoYear: {
    regex: RegExp('^' + reDateNoYear, 'i'),
    name: 'datenoyear',
    callback: function callback(match, month, day) {
      return this.ymd(this.y, lookupMonth(month), +day);
    }
  },

  dateNoYearRev: {
    regex: RegExp('^' + reDay + '[ .\\t-]*' + reMonthText, 'i'),
    name: 'datenoyearrev',
    callback: function callback(match, day, month) {
      return this.ymd(this.y, lookupMonth(month), +day);
    }
  },

  isoWeekDay: {
    regex: RegExp('^' + reYear4 + '-?W' + reWeekOfYear + '(?:-?([0-7]))?'),
    name: 'isoweekday | isoweek',
    callback: function callback(match, year, week, day) {
      day = day ? +day : 1;

      if (!this.ymd(+year, 0, 1)) {
        return false;
      }

      // get day of week for Jan 1st
      var dayOfWeek = new Date(this.y, this.m, this.d).getDay();

      // and use the day to figure out the offset for day 1 of week 1
      dayOfWeek = 0 - (dayOfWeek > 4 ? dayOfWeek - 7 : dayOfWeek);

      this.rd += dayOfWeek + (week - 1) * 7 + day;
    }
  },

  relativeText: {
    regex: RegExp('^(' + reReltextnumber + '|' + reReltexttext + ')' + reSpace + '(' + reReltextunit + ')', 'i'),
    name: 'relativetext',
    callback: function callback(match, relValue, relUnit) {
      // todo: implement handling of 'this time-unit'
      // eslint-disable-next-line no-unused-vars
      var _lookupRelative = lookupRelative(relValue),
          amount = _lookupRelative.amount,
          behavior = _lookupRelative.behavior;

      switch (relUnit.toLowerCase()) {
        case 'sec':
        case 'secs':
        case 'second':
        case 'seconds':
          this.rs += amount;
          break;
        case 'min':
        case 'mins':
        case 'minute':
        case 'minutes':
          this.ri += amount;
          break;
        case 'hour':
        case 'hours':
          this.rh += amount;
          break;
        case 'day':
        case 'days':
          this.rd += amount;
          break;
        case 'fortnight':
        case 'fortnights':
        case 'forthnight':
        case 'forthnights':
          this.rd += amount * 14;
          break;
        case 'week':
        case 'weeks':
          this.rd += amount * 7;
          break;
        case 'month':
        case 'months':
          this.rm += amount;
          break;
        case 'year':
        case 'years':
          this.ry += amount;
          break;
        case 'mon':
        case 'monday':
        case 'tue':
        case 'tuesday':
        case 'wed':
        case 'wednesday':
        case 'thu':
        case 'thursday':
        case 'fri':
        case 'friday':
        case 'sat':
        case 'saturday':
        case 'sun':
        case 'sunday':
          this.resetTime();
          this.weekday = lookupWeekday(relUnit, 7);
          this.weekdayBehavior = 1;
          this.rd += (amount > 0 ? amount - 1 : amount) * 7;
          break;
        case 'weekday':
        case 'weekdays':
          // todo
          break;
      }
    }
  },

  relative: {
    regex: RegExp('^([+-]*)[ \\t]*(\\d+)' + reSpaceOpt + '(' + reReltextunit + '|week)', 'i'),
    name: 'relative',
    callback: function callback(match, signs, relValue, relUnit) {
      var minuses = signs.replace(/[^-]/g, '').length;

      var amount = +relValue * Math.pow(-1, minuses);

      switch (relUnit.toLowerCase()) {
        case 'sec':
        case 'secs':
        case 'second':
        case 'seconds':
          this.rs += amount;
          break;
        case 'min':
        case 'mins':
        case 'minute':
        case 'minutes':
          this.ri += amount;
          break;
        case 'hour':
        case 'hours':
          this.rh += amount;
          break;
        case 'day':
        case 'days':
          this.rd += amount;
          break;
        case 'fortnight':
        case 'fortnights':
        case 'forthnight':
        case 'forthnights':
          this.rd += amount * 14;
          break;
        case 'week':
        case 'weeks':
          this.rd += amount * 7;
          break;
        case 'month':
        case 'months':
          this.rm += amount;
          break;
        case 'year':
        case 'years':
          this.ry += amount;
          break;
        case 'mon':
        case 'monday':
        case 'tue':
        case 'tuesday':
        case 'wed':
        case 'wednesday':
        case 'thu':
        case 'thursday':
        case 'fri':
        case 'friday':
        case 'sat':
        case 'saturday':
        case 'sun':
        case 'sunday':
          this.resetTime();
          this.weekday = lookupWeekday(relUnit, 7);
          this.weekdayBehavior = 1;
          this.rd += (amount > 0 ? amount - 1 : amount) * 7;
          break;
        case 'weekday':
        case 'weekdays':
          // todo
          break;
      }
    }
  },

  dayText: {
    regex: RegExp('^(' + reDaytext + ')', 'i'),
    name: 'daytext',
    callback: function callback(match, dayText) {
      this.resetTime();
      this.weekday = lookupWeekday(dayText, 0);

      if (this.weekdayBehavior !== 2) {
        this.weekdayBehavior = 1;
      }
    }
  },

  relativeTextWeek: {
    regex: RegExp('^(' + reReltexttext + ')' + reSpace + 'week', 'i'),
    name: 'relativetextweek',
    callback: function callback(match, relText) {
      this.weekdayBehavior = 2;

      switch (relText.toLowerCase()) {
        case 'this':
          this.rd += 0;
          break;
        case 'next':
          this.rd += 7;
          break;
        case 'last':
        case 'previous':
          this.rd -= 7;
          break;
      }

      if (isNaN(this.weekday)) {
        this.weekday = 1;
      }
    }
  },

  monthFullOrMonthAbbr: {
    regex: RegExp('^(' + reMonthFull + '|' + reMonthAbbr + ')', 'i'),
    name: 'monthfull | monthabbr',
    callback: function callback(match, month) {
      return this.ymd(this.y, lookupMonth(month), this.d);
    }
  },

  tzCorrection: {
    regex: RegExp('^' + reTzCorrection, 'i'),
    name: 'tzcorrection',
    callback: function callback(tzCorrection) {
      return this.zone(processTzCorrection(tzCorrection));
    }
  },

  tzAbbr: {
    regex: RegExp('^' + reTzAbbr),
    name: 'tzabbr',
    callback: function callback(match, abbr) {
      var offset = tzAbbrOffsets[abbr.toLowerCase()];

      if (isNaN(offset)) {
        return false;
      }

      return this.zone(offset);
    }
  },

  ago: {
    regex: /^ago/i,
    name: 'ago',
    callback: function callback() {
      this.ry = -this.ry;
      this.rm = -this.rm;
      this.rd = -this.rd;
      this.rh = -this.rh;
      this.ri = -this.ri;
      this.rs = -this.rs;
      this.rf = -this.rf;
    }
  },

  year4: {
    regex: RegExp('^' + reYear4),
    name: 'year4',
    callback: function callback(match, year) {
      this.y = +year;
      return true;
    }
  },

  whitespace: {
    regex: /^[ .,\t]+/,
    name: 'whitespace'
    // do nothing
  },

  dateShortWithTimeLong: {
    regex: RegExp('^' + reDateNoYear + 't?' + reHour24 + '[:.]' + reMinute + '[:.]' + reSecond, 'i'),
    name: 'dateshortwithtimelong',
    callback: function callback(match, month, day, hour, minute, second) {
      return this.ymd(this.y, lookupMonth(month), +day) && this.time(+hour, +minute, +second, 0);
    }
  },

  dateShortWithTimeLong12: {
    regex: RegExp('^' + reDateNoYear + reHour12 + '[:.]' + reMinute + '[:.]' + reSecondlz + reSpaceOpt + reMeridian, 'i'),
    name: 'dateshortwithtimelong12',
    callback: function callback(match, month, day, hour, minute, second, meridian) {
      return this.ymd(this.y, lookupMonth(month), +day) && this.time(processMeridian(+hour, meridian), +minute, +second, 0);
    }
  },

  dateShortWithTimeShort: {
    regex: RegExp('^' + reDateNoYear + 't?' + reHour24 + '[:.]' + reMinute, 'i'),
    name: 'dateshortwithtimeshort',
    callback: function callback(match, month, day, hour, minute) {
      return this.ymd(this.y, lookupMonth(month), +day) && this.time(+hour, +minute, 0, 0);
    }
  },

  dateShortWithTimeShort12: {
    regex: RegExp('^' + reDateNoYear + reHour12 + '[:.]' + reMinutelz + reSpaceOpt + reMeridian, 'i'),
    name: 'dateshortwithtimeshort12',
    callback: function callback(match, month, day, hour, minute, meridian) {
      return this.ymd(this.y, lookupMonth(month), +day) && this.time(processMeridian(+hour, meridian), +minute, 0, 0);
    }
  }
};

var resultProto = {
  // date
  y: NaN,
  m: NaN,
  d: NaN,
  // time
  h: NaN,
  i: NaN,
  s: NaN,
  f: NaN,

  // relative shifts
  ry: 0,
  rm: 0,
  rd: 0,
  rh: 0,
  ri: 0,
  rs: 0,
  rf: 0,

  // weekday related shifts
  weekday: NaN,
  weekdayBehavior: 0,

  // first or last day of month
  // 0 none, 1 first, -1 last
  firstOrLastDayOfMonth: 0,

  // timezone correction in minutes
  z: NaN,

  // counters
  dates: 0,
  times: 0,
  zones: 0,

  // helper functions
  ymd: function ymd(y, m, d) {
    if (this.dates > 0) {
      return false;
    }

    this.dates++;
    this.y = y;
    this.m = m;
    this.d = d;
    return true;
  },
  time: function time(h, i, s, f) {
    if (this.times > 0) {
      return false;
    }

    this.times++;
    this.h = h;
    this.i = i;
    this.s = s;
    this.f = f;

    return true;
  },
  resetTime: function resetTime() {
    this.h = 0;
    this.i = 0;
    this.s = 0;
    this.f = 0;
    this.times = 0;

    return true;
  },
  zone: function zone(minutes) {
    if (this.zones <= 1) {
      this.zones++;
      this.z = minutes;
      return true;
    }

    return false;
  },
  toDate: function toDate(relativeTo) {
    if (this.dates && !this.times) {
      this.h = this.i = this.s = this.f = 0;
    }

    // fill holes
    if (isNaN(this.y)) {
      this.y = relativeTo.getFullYear();
    }

    if (isNaN(this.m)) {
      this.m = relativeTo.getMonth();
    }

    if (isNaN(this.d)) {
      this.d = relativeTo.getDate();
    }

    if (isNaN(this.h)) {
      this.h = relativeTo.getHours();
    }

    if (isNaN(this.i)) {
      this.i = relativeTo.getMinutes();
    }

    if (isNaN(this.s)) {
      this.s = relativeTo.getSeconds();
    }

    if (isNaN(this.f)) {
      this.f = relativeTo.getMilliseconds();
    }

    // adjust special early
    switch (this.firstOrLastDayOfMonth) {
      case 1:
        this.d = 1;
        break;
      case -1:
        this.d = 0;
        this.m += 1;
        break;
    }

    if (!isNaN(this.weekday)) {
      var date = new Date(relativeTo.getTime());
      date.setFullYear(this.y, this.m, this.d);
      date.setHours(this.h, this.i, this.s, this.f);

      var dow = date.getDay();

      if (this.weekdayBehavior === 2) {
        // To make "this week" work, where the current day of week is a "sunday"
        if (dow === 0 && this.weekday !== 0) {
          this.weekday = -6;
        }

        // To make "sunday this week" work, where the current day of week is not a "sunday"
        if (this.weekday === 0 && dow !== 0) {
          this.weekday = 7;
        }

        this.d -= dow;
        this.d += this.weekday;
      } else {
        var diff = this.weekday - dow;

        // some PHP magic
        if (this.rd < 0 && diff < 0 || this.rd >= 0 && diff <= -this.weekdayBehavior) {
          diff += 7;
        }

        if (this.weekday >= 0) {
          this.d += diff;
        } else {
          this.d -= 7 - (Math.abs(this.weekday) - dow);
        }

        this.weekday = NaN;
      }
    }

    // adjust relative
    this.y += this.ry;
    this.m += this.rm;
    this.d += this.rd;

    this.h += this.rh;
    this.i += this.ri;
    this.s += this.rs;
    this.f += this.rf;

    this.ry = this.rm = this.rd = 0;
    this.rh = this.ri = this.rs = this.rf = 0;

    var result = new Date(relativeTo.getTime());
    // since Date constructor treats years <= 99 as 1900+
    // it can't be used, thus this weird way
    result.setFullYear(this.y, this.m, this.d);
    result.setHours(this.h, this.i, this.s, this.f);

    // note: this is done twice in PHP
    // early when processing special relatives
    // and late
    // todo: check if the logic can be reduced
    // to just one time action
    switch (this.firstOrLastDayOfMonth) {
      case 1:
        result.setDate(1);
        break;
      case -1:
        result.setMonth(result.getMonth() + 1, 0);
        break;
    }

    // adjust timezone
    if (!isNaN(this.z) && result.getTimezoneOffset() !== this.z) {
      result.setUTCFullYear(result.getFullYear(), result.getMonth(), result.getDate());

      result.setUTCHours(result.getHours(), result.getMinutes(), result.getSeconds() - this.z, result.getMilliseconds());
    }

    return result;
  }
};

module.exports = function strtotime(str, now) {
  //       discuss at: https://locutus.io/php/strtotime/
  //      original by: Caio Ariede (https://caioariede.com)
  //      improved by: Kevin van Zonneveld (https://kvz.io)
  //      improved by: Caio Ariede (https://caioariede.com)
  //      improved by: A. Matías Quezada (https://amatiasq.com)
  //      improved by: preuter
  //      improved by: Brett Zamir (https://brett-zamir.me)
  //      improved by: Mirko Faber
  //         input by: David
  //      bugfixed by: Wagner B. Soares
  //      bugfixed by: Artur Tchernychev
  //      bugfixed by: Stephan Bösch-Plepelits (https://github.com/plepe)
  // reimplemented by: Rafał Kukawski
  //           note 1: Examples all have a fixed timestamp to prevent
  //           note 1: tests to fail because of variable time(zones)
  //        example 1: strtotime('+1 day', 1129633200)
  //        returns 1: 1129719600
  //        example 2: strtotime('+1 week 2 days 4 hours 2 seconds', 1129633200)
  //        returns 2: 1130425202
  //        example 3: strtotime('last month', 1129633200)
  //        returns 3: 1127041200
  //        example 4: strtotime('2009-05-04 08:30:00+00')
  //        returns 4: 1241425800
  //        example 5: strtotime('2009-05-04 08:30:00+02:00')
  //        returns 5: 1241418600
  //        example 6: strtotime('2009-05-04 08:30:00 YWT')
  //        returns 6: 1241454600
  //        example 7: strtotime('10-JUL-17')
  //        returns 7: 1499644800

  if (now == null) {
    now = Math.floor(Date.now() / 1000);
  }

  // the rule order is important
  // if multiple rules match, the longest match wins
  // if multiple rules match the same string, the first match wins
  var rules = [formats.yesterday, formats.now, formats.noon, formats.midnightOrToday, formats.tomorrow, formats.timestamp, formats.firstOrLastDay, formats.backOrFrontOf,
  // formats.weekdayOf, // not yet implemented
  formats.timeTiny12, formats.timeShort12, formats.timeLong12, formats.mssqltime, formats.oracledate, formats.timeShort24, formats.timeLong24, formats.iso8601long, formats.gnuNoColon, formats.iso8601noColon, formats.americanShort, formats.american, formats.iso8601date4, formats.iso8601dateSlash, formats.dateSlash, formats.gnuDateShortOrIso8601date2, formats.gnuDateShorter, formats.dateFull, formats.pointedDate4, formats.pointedDate2, formats.dateNoDay, formats.dateNoDayRev, formats.dateTextual, formats.dateNoYear, formats.dateNoYearRev, formats.dateNoColon, formats.xmlRpc, formats.xmlRpcNoColon, formats.soap, formats.wddx, formats.exif, formats.pgydotd, formats.isoWeekDay, formats.pgTextShort, formats.pgTextReverse, formats.clf, formats.year4, formats.ago, formats.dayText, formats.relativeTextWeek, formats.relativeText, formats.monthFullOrMonthAbbr, formats.tzCorrection, formats.tzAbbr, formats.dateShortWithTimeShort12, formats.dateShortWithTimeLong12, formats.dateShortWithTimeShort, formats.dateShortWithTimeLong, formats.relative, formats.whitespace];

  var result = Object.create(resultProto);

  while (str.length) {
    var longestMatch = null;
    var finalRule = null;

    for (var i = 0, l = rules.length; i < l; i++) {
      var format = rules[i];

      var match = str.match(format.regex);

      if (match) {
        if (!longestMatch || match[0].length > longestMatch[0].length) {
          longestMatch = match;
          finalRule = format;
        }
      }
    }

    if (!finalRule || finalRule.callback && finalRule.callback.apply(result, longestMatch) === false) {
      return false;
    }

    str = str.substr(longestMatch[0].length);
    finalRule = null;
    longestMatch = null;
  }

  return Math.floor(result.toDate(new Date(now * 1000)) / 1000);
};
return module.exports;
})();
/**/
var strtoupper = this.strtoupper = this.phpjs.strtoupper = (function(){
'use strict';

module.exports = function strtoupper(str) {
  //  discuss at: https://locutus.io/php/strtoupper/
  // original by: Kevin van Zonneveld (https://kvz.io)
  // improved by: Onno Marsman (https://twitter.com/onnomarsman)
  //   example 1: strtoupper('Kevin van Zonneveld')
  //   returns 1: 'KEVIN VAN ZONNEVELD'

  return (str + '').toUpperCase();
};
return module.exports;
})();
/**/
var strval = this.strval = this.phpjs.strval = (function(){
'use strict';

module.exports = function strval(str) {
  //  discuss at: https://locutus.io/php/strval/
  // original by: Brett Zamir (https://brett-zamir.me)
  // improved by: Kevin van Zonneveld (https://kvz.io)
  // bugfixed by: Brett Zamir (https://brett-zamir.me)
  //   example 1: strval({red: 1, green: 2, blue: 3, white: 4})
  //   returns 1: 'Object'

  var gettype = require('../var/gettype');
  var type = '';

  if (str === null) {
    return '';
  }

  type = gettype(str);

  // Comment out the entire switch if you want JS-like
  // behavior instead of PHP behavior
  switch (type) {
    case 'boolean':
      if (str === true) {
        return '1';
      }
      return '';
    case 'array':
      return 'Array';
    case 'object':
      return 'Object';
  }

  return str;
};
return module.exports;
})();
/**/
var substr = this.substr = this.phpjs.substr = (function(){
'use strict';

module.exports = function substr(input, start, len) {
  //  discuss at: https://locutus.io/php/substr/
  // original by: Martijn Wieringa
  // bugfixed by: T.Wild
  // improved by: Onno Marsman (https://twitter.com/onnomarsman)
  // improved by: Brett Zamir (https://brett-zamir.me)
  //  revised by: Theriault (https://github.com/Theriault)
  //  revised by: Rafał Kukawski
  //      note 1: Handles rare Unicode characters if 'unicode.semantics' ini (PHP6) is set to 'on'
  //   example 1: substr('abcdef', 0, -1)
  //   returns 1: 'abcde'
  //   example 2: substr(2, 0, -6)
  //   returns 2: false
  //   example 3: ini_set('unicode.semantics', 'on')
  //   example 3: substr('a\uD801\uDC00', 0, -1)
  //   returns 3: 'a'
  //   example 4: ini_set('unicode.semantics', 'on')
  //   example 4: substr('a\uD801\uDC00', 0, 2)
  //   returns 4: 'a\uD801\uDC00'
  //   example 5: ini_set('unicode.semantics', 'on')
  //   example 5: substr('a\uD801\uDC00', -1, 1)
  //   returns 5: '\uD801\uDC00'
  //   example 6: ini_set('unicode.semantics', 'on')
  //   example 6: substr('a\uD801\uDC00z\uD801\uDC00', -3, 2)
  //   returns 6: '\uD801\uDC00z'
  //   example 7: ini_set('unicode.semantics', 'on')
  //   example 7: substr('a\uD801\uDC00z\uD801\uDC00', -3, -1)
  //   returns 7: '\uD801\uDC00z'
  //        test: skip-3 skip-4 skip-5 skip-6 skip-7

  var _php_cast_string = require('../_helpers/_phpCastString'); // eslint-disable-line camelcase

  input = _php_cast_string(input);

  var ini_get = require('../info/ini_get'); // eslint-disable-line camelcase
  var multibyte = ini_get('unicode.semantics') === 'on';

  if (multibyte) {
    input = input.match(/[\uD800-\uDBFF][\uDC00-\uDFFF]|[\s\S]/g) || [];
  }

  var inputLength = input.length;
  var end = inputLength;

  if (start < 0) {
    start += end;
  }

  if (typeof len !== 'undefined') {
    if (len < 0) {
      end = len + end;
    } else {
      end = len + start;
    }
  }

  if (start > inputLength || start < 0 || start > end) {
    return false;
  }

  if (multibyte) {
    return input.slice(start, end).join('');
  }

  return input.slice(start, end);
};
return module.exports;
})();
/**/
var trim = this.trim = this.phpjs.trim = (function(){
'use strict';

module.exports = function trim(str, charlist) {
  //  discuss at: https://locutus.io/php/trim/
  // original by: Kevin van Zonneveld (https://kvz.io)
  // improved by: mdsjack (https://www.mdsjack.bo.it)
  // improved by: Alexander Ermolaev (https://snippets.dzone.com/user/AlexanderErmolaev)
  // improved by: Kevin van Zonneveld (https://kvz.io)
  // improved by: Steven Levithan (https://blog.stevenlevithan.com)
  // improved by: Jack
  //    input by: Erkekjetter
  //    input by: DxGx
  // bugfixed by: Onno Marsman (https://twitter.com/onnomarsman)
  //   example 1: trim('    Kevin van Zonneveld    ')
  //   returns 1: 'Kevin van Zonneveld'
  //   example 2: trim('Hello World', 'Hdle')
  //   returns 2: 'o Wor'
  //   example 3: trim(16, 1)
  //   returns 3: '6'

  var whitespace = [' ', '\n', '\r', '\t', '\f', '\x0b', '\xa0', '\u2000', '\u2001', '\u2002', '\u2003', '\u2004', '\u2005', '\u2006', '\u2007', '\u2008', '\u2009', '\u200A', '\u200B', '\u2028', '\u2029', '\u3000'].join('');
  var l = 0;
  var i = 0;
  str += '';

  if (charlist) {
    whitespace = (charlist + '').replace(/([[\]().?/*{}+$^:])/g, '$1');
  }

  l = str.length;
  for (i = 0; i < l; i++) {
    if (whitespace.indexOf(str.charAt(i)) === -1) {
      str = str.substring(i);
      break;
    }
  }

  l = str.length;
  for (i = l - 1; i >= 0; i--) {
    if (whitespace.indexOf(str.charAt(i)) === -1) {
      str = str.substring(0, i + 1);
      break;
    }
  }

  return whitespace.indexOf(str.charAt(0)) === -1 ? str : '';
};
return module.exports;
})();
/**/
var vsprintf = this.vsprintf = this.phpjs.vsprintf = (function(){
'use strict';

module.exports = function vsprintf(format, args) {
  //  discuss at: https://locutus.io/php/vsprintf/
  // original by: ejsanders
  //   example 1: vsprintf('%04d-%02d-%02d', [1988, 8, 1])
  //   returns 1: '1988-08-01'

  var sprintf = require('../strings/sprintf');

  return sprintf.apply(this, [format].concat(args));
};
return module.exports;
})();
/**/

    /// 検証ルールのインポート
    /**/
this.condition = {"Ajax":async function(input, $value, $fields, $params, $consts, $error, $context, e) {var $message;
            (function() {
                $params.request.caches = $params.request.caches ?? {};
                if ($value && $params.request.url) {
                    function request() {
                        var url = new URL($params.request.url, location.href);
                        var formdata = undefined;
                        var body = $params.request.method === 'GET' ? url.searchParams : formdata = new FormData();

                        body.append(input.name, $value);
                        var keys = Object.keys($fields);
                        for (var i = 0; i < keys.length; i++) {
                            body.append(keys[i], $fields[keys[i]]);
                        }

                        if ($params.request.method === 'GET') {
                            if ($params.request.caches[url]) {
                                if ($params.request.caches[url].expire > (new Date()).getTime()) {
                                    $error($params.request.caches[url].data);
                                    return Promise.resolve(null);
                                }
                                else {
                                    delete $params.request.caches[url];
                                }
                            }
                        }

                        var handler = function (response) {
                            var data = ($params.request.handler || function(response){return response})(response);
                            if ($params.request.method === 'GET') {
                                $params.request.caches[url] = {
                                    expire: (new Date()).getTime() + $params.request.expire * 1000,
                                    data: data,
                                };
                            }
                            return data;
                        };

                        if ($params.request.api === 'xhr') {
                            return new Promise(function(resolve, reject) {
                                var xhr = new XMLHttpRequest();
                                xhr.open($params.request.method, url);
                                Object.keys($params.request.headers).forEach(function(header) {
                                    xhr.setRequestHeader(header, $params.request.headers[header]);
                                });
                                xhr.responseType = 'json';
                                xhr.withCredentials = $params.request.credentials !== 'omit';
                                xhr.timeout = $params.request.timeout || 0;
                                xhr.addEventListener('load', function(e) {
                                    if (this.status !== 200){
                                        console.log(e);
                                        return;
                                    }
                                    $error(handler(this.response));
                                    resolve();
                                });
                                xhr.addEventListener('error', function(e) {
                                    reject(e);
                                });
                                xhr.send(formdata);
                            });
                        }
                        if ($params.request.api === 'fetch') {
                            return window.fetch(url, Object.assign({body: formdata}, $params.request)).then(function(response) {
                                if (!response.ok) {
                                    throw response;
                                }
                                return response.json();
                            }).then(function(json){
                                $error(handler(json));
                            }).catch(function(e) {
                                console.log(e);
                            });
                        }
                        
                        return new Promise(function(resolve, reject) {
                            window[$params.request.api](Object.assign({url: url, body: formdata}, $params.request)).then(function(response) {
                                $error(response);
                                resolve();
                            }).catch(function(e) {
                                reject(e);
                            });
                        });
                    }

                    if (e.type === 'submit') {
                        $error(request());
                    }
                    else {
                        if (!input.validationAjaxDebounce) {
                            $error(request());
                            input.validationAjaxDebounce = setTimeout(function() {
                                input.validationAjaxDebounce = null;
                            }, 1500);
                        }
                        else {
                            clearTimeout(input.validationAjaxDebounce);
                            $error(new Promise(function (resolve) {
                                input.validationAjaxDebounce = setTimeout(function() {
                                    request().then(resolve);
                                    input.validationAjaxDebounce = null;
                                }, 1000);
                            }));
                        }
                    }
                }
            })();},"AlphaDigit":async function(input, $value, $fields, $params, $consts, $error, $context, e) {// 

        if (!preg_match($params['regex'], $value)) {
            $error($consts['INVALID_ALPHADIGIT'], []);
            return;
        }

        if (!$params['first_number'] && ctype_digit(substr($value, 0, 1))) {
            $error($consts['INVALID_FIRST_NUMBER'], []);
        }
        if ($params['case'] === false && strtoupper($value) !== $value) {
            $error($consts['INVALID_LOWERCASE'], []);
        }
        if ($params['case'] === true && strtolower($value) !== $value) {
            $error($consts['INVALID_UPPERCASE'], []);
        }},"ArrayExclusion":async function(input, $value, $fields, $params, $consts, $error, $context, e) {// 

        if (count(array_intersect_key(array_flip($value), $params['set'])) > 1) {
            $error($consts['INVALID_INCLUSION'], []);
        }},"ArrayLength":async function(input, $value, $fields, $params, $consts, $error, $context, e) {var $length;
// 

        $length = count($value);

        if (!is_null($params['max']) && !is_null($params['min']) && ($length > $params['max'] || $length < $params['min'])) {
            $error($consts['SHORTLONG'], []);
        }
        else if (is_null($params['max']) && !is_null($params['min']) && $length < $params['min']) {
            $error($consts['TOO_SHORT'], []);
        }
        else if (is_null($params['min']) && !is_null($params['max']) && $length > $params['max']) {
            $error($consts['TOO_LONG'], []);
        }},"Aruiha":async function(input, $value, $fields, $params, $consts, $error, $context, e) {(function() {
                var keys = Object.keys($params['condition']);
                for (var i = 0; i < keys.length; i++) {
                    var condition = $params['condition'][keys[i]];
                    var ok = true;
                    chmonos.condition[condition.class](input, $value, $fields, condition.param, $consts, function() { ok = false }, $context, e);
                    if (ok) {
                        return;
                    }
                }
                $error($consts['INVALID_ARUIHA'], []);
            })();},"Callback":async function(input, $value, $fields, $params, $consts, $error, $context, e) {var $callee;
// 

        $callee = $context['lang'] === 'php' ? $params['closure'] : $params['function'];
        $callee($value, $error, $fields, $params['userdata'], $context);},"Compare":async function(input, $value, $fields, $params, $consts, $error, $context, e) {var $field1, $field2;
// 

        $field1 = $value;
        $field2 = $params['direct'] ? $params['operand'] : $fields[$params['operand']];

        if (strlen($field2 ?? '') === 0) {
            return;
        }

        if (strlen($params['filter'])) {
            $field1 = ($context['chmonos'][$params['filter']])($field1);
            $field2 = ($context['chmonos'][$params['filter']])($field2);
        }

        if ($params['offset']) {
            $field1 = $params['offset'] + $field1 - $field2;
            $field2 = 0;
        }

        if ($params['operator'] === '==' && $field1 != $field2) {
            return $error($consts['EQUAL'], []);
        }
        if ($params['operator'] === '===' && $field1 !== $field2) {
            return $error($consts['EQUAL'], []);
        }
        if ($params['operator'] === '!=' && $field1 == $field2) {
            return $error($consts['NOT_EQUAL'], []);
        }
        if ($params['operator'] === '!==' && $field1 === $field2) {
            return $error($consts['NOT_EQUAL'], []);
        }
        if ($params['operator'] === '<' && $field1 >= $field2) {
            return $error($consts['LESS_THAN'], []);
        }
        if ($params['operator'] === '<=' && $field1 > $field2) {
            return $error($consts['LESS_THAN'], []);
        }
        if ($params['operator'] === '>' && $field1 <= $field2) {
            return $error($consts['GREATER_THAN'], []);
        }
        if ($params['operator'] === '>=' && $field1 < $field2) {
            return $error($consts['GREATER_THAN'], []);
        }},"DataUri":async function(input, $value, $fields, $params, $consts, $error, $context, e) {var $matches, $decoded;
// 

        $matches = [];

        if (!preg_match('#^data:(.+?/.+?)?(;charset=.+?)?(;base64)?,#iu', $value, $matches)) {
            return $error($consts['INVALID'], []);
        }

        $decoded = base64_decode(substr($value, strlen($matches[0])), true);

        if ($decoded === false) {
            return $error($consts['INVALID'], []);
        }

        if ($params['size'] && ini_parse_quantity($params['size']) < strlen($decoded)) {
            $error($consts['INVALID_SIZE'], []);
        }

        if ($params['type'] && !in_array($matches[1], $params['allowTypes'], true)) {
            $error($consts['INVALID_TYPE'], []);
        }},"Date":async function(input, $value, $fields, $params, $consts, $error, $context, e) {var $value00, $time;
// 

        // datetime-local で秒が 00 の場合、00が省略される場合があるので補完する
        if ($params['member']['s']) {
            $value00 = $context['str_concat']($value, ':00');
            if (strtotime($value00) !== false) {
                $value = $value00;
            }
        }

        $time = strtotime($value);

        // 時刻のみの場合を考慮して年月日を付加して再チャレンジ
        if ($time === false) {
            $time = strtotime($context['str_concat']('2000/10/10 ', $value));
        }

        if ($time === false) {
            $error($consts['INVALID_DATE'], []);
        }
        else if (date($params['format'], $time) !== $value) {
            $error($consts['FALSEFORMAT'], []);
        }},"Decimal":async function(input, $value, $fields, $params, $consts, $error, $context, e) {var $match;
// 

        $match = [];

        if (!preg_match('#^-?([1-9]\\d*|0)(\\.\\d+)?$#u', $value, $match)) {
            return $error($consts['INVALID'], []);
        }

        $match[2] = (isset($match[2])) ? $match[2] : '';
        if (strlen($match[1]) > $params['int'] && strlen($match[2]) > $params['dec'] + 1) {
            $error($consts['INVALID_INTDEC'], []);
        }
        else if (strlen($match[1]) > $params['int']) {
            $error($consts['INVALID_INT'], []);
        }
        else if (strlen($match[2]) > $params['dec'] + 1) {
            $error($consts['INVALID_DEC'], []);
        }},"Digits":async function(input, $value, $fields, $params, $consts, $error, $context, e) {// 

        $value = ltrim($value, $params['sign']);

        if (!ctype_digit($value)) {
            $error($consts['NOT_DIGITS'], []);
            return;
        }
        if ($params['mustDigit'] && $params['digit'] !== null && $params['digit'] !== strlen($value)) {
            $error($consts['INVALID_DIGIT'], []);
            return;
        }
        if (!$params['mustDigit'] && $params['digit'] !== null && $params['digit'] < strlen($value)) {
            $error($consts['INVALID_DIGIT'], []);
            return;
        }},"Distinct":async function(input, $value, $fields, $params, $consts, $error, $context, e) {// 

        $value = preg_split($params['delimiter'], $value, -1, PREG_SPLIT_NO_EMPTY);

        if (count($value) !== count(array_unique($value))) {
            $error($consts['NO_DISTINCT'], []);
        }},"EmailAddress":async function(input, $value, $fields, $params, $consts, $error, $context, e) {var $key;
// 

        if ($params['delimiter'] === null) {
            $value = $context['cast']('array', $value);
        }
        else {
            $value = preg_split($params['delimiter'], $value, -1, PREG_SPLIT_NO_EMPTY);
        }

        $context['foreach']($value, function ($key, $value, $params, $error, $consts) {
            if (!preg_match($params['regex'], $value)) {
                $error($consts['INVALID_FORMAT'], []);
                return false;
            }
        }, $params, $error, $consts);},"FileName":async function(input, $value, $fields, $params, $consts, $error, $context, e) {var $pathinfo;
// 

        if (!preg_match($params['regex'], $value)) {
            $error($consts['INVALID_FILENAME_STR'], []);
            return;
        }

        $pathinfo = pathinfo($value);
        $pathinfo['extension'] = isset($pathinfo['extension']) ? $pathinfo['extension'] : '';
        $pathinfo['filename'] = isset($pathinfo['filename']) ? $pathinfo['filename'] : '';

        if (count($params['extensions']) && !in_array($pathinfo['extension'], $params['extensions'])) {
            $error($consts['INVALID_FILENAME_EXT'], []);
            return;
        }

        if (count($params['reserved']) && in_array(strtoupper($pathinfo['filename']), $params['reserved'])) {
            $error($consts['INVALID_FILENAME_RESERVED'], []);
            return;
        }},"FileSize":async function(input, $value, $fields, $params, $consts, $error, $context, e) {var $size;
// 

        $size = filesize($value);

        if (!$size) {
            $error($consts['INVALID'], []);
        }

        if ($size > ini_parse_quantity($params['maxsize'])) {
            $error($consts['INVALID_OVER'], []);
        }},"FileType":async function(input, $value, $fields, $params, $consts, $error, $context, e) {var $mimetype;
// 

        $mimetype = mime_content_type($value);

        if (!$mimetype && !in_array('*', $params['mimetype'])) {
            $error($consts['INVALID'], []);
        }

        if (!in_array($mimetype, $params['mimetype'])) {
            $error($consts['INVALID_TYPE'], []);
        }},"Hostname":async function(input, $value, $fields, $params, $consts, $error, $context, e) {var $checkport, $port, $require_port, $key, $matches;
// 

        $checkport = function ($port, $require_port, $error, $consts) {
            if (strlen($port)) {
                if ($require_port === false) {
                    $error($consts['INVALID'], []);
                    return false;
                }
                if ($port > 65535) {
                    $error($consts['INVALID_PORT'], []);
                    return false;
                }
            }
            else {
                if ($require_port === true) {
                    $error($consts['INVALID_PORT'], []);
                    return false;
                }
            }
            return true;
        };

        if ($params['delimiter'] === null) {
            $value = $context['cast']('array', $value);
        }
        else {
            $value = preg_split($params['delimiter'], $value, -1, PREG_SPLIT_NO_EMPTY);
        }

        $context['foreach']($value, function ($key, $value, $params, $checkport, $error, $consts) {
            $matches = [];

            if (in_array('', $params['types']) && preg_match('#^(([a-z0-9])|([a-z0-9][a-z0-9-]{0,61}[a-z0-9])|((([a-z0-9])|([a-z0-9][a-z0-9-]{0,61}[a-z0-9]))\\.)+[a-z]+)(:([1-9][0-9]{0,4}))?$#i', $value, $matches)) {
                return $checkport(isset($matches[9]) ? $matches[9] : '', $params['require_port'], $error, $consts);
            }
            if (in_array('cidr', $params['types']) && preg_match('#^(?:25[0-5]|2[0-4]\\d|1\\d\\d|\\d\\d|\\d)(?:\.(?:25[0-5]|2[0-4]\\d|1\\d\\d|\\d\\d|\\d)){3}/([0-9]|[1-2][0-9]|3[0-2])(:([1-9][0-9]{0,4}))?$#i', $value, $matches)) {
                return $checkport(isset($matches[3]) ? $matches[3] : '', $params['require_port'], $error, $consts);
            }
            if (in_array(4, $params['types']) && preg_match('#^(?:25[0-5]|2[0-4]\\d|1\\d\\d|\\d\\d|\\d)(?:\.(?:25[0-5]|2[0-4]\\d|1\\d\\d|\\d\\d|\\d)){3}(:([1-9][0-9]{0,4}))?$#i', $value, $matches)) {
                return $checkport(isset($matches[2]) ? $matches[2] : '', $params['require_port'], $error, $consts);
            }
            if (in_array(6, $params['types']) && preg_match('#^::$#i', $value, $matches)) {
                return true;
            }

            $error($consts['INVALID'], []);
            return false;
        }, $params, $checkport, $error, $consts);},"ImageSize":async function(input, $value, $fields, $params, $consts, $error, $context, e) {var $size;
// 

        $size = await getimagesize($value);

        if ($size === false) {
            $error($consts['INVALID'], []);
            return;
        }

        if (!is_null($params['width']) && $params['width'] < $size[0]) {
            $error($consts['INVALID_WIDTH'], []);
        }

        if (!is_null($params['height']) && $params['height'] < $size[1]) {
            $error($consts['INVALID_HEIGHT'], []);
        }},"InArray":async function(input, $value, $fields, $params, $consts, $error, $context, e) {// 

        if ($params['strict'] === null) {
            if (!isset($params['haystack'][$value])) {
                $error($consts['NOT_IN_ARRAY'], []);
            }
        }
        else {
            if (!in_array($value, $params['haystack'], $params['strict'])) {
                $error($consts['NOT_IN_ARRAY'], []);
            }
        }},"Json":async function(input, $value, $fields, $params, $consts, $error, $context, e) {var $decode;
// 

        $decode = json_decode($value, true);
        if ($decode === null && strtolower(trim($value)) !== 'null') {
            $error($consts['INVALID'], []);
            return;
        }},"NotInArray":async function(input, $value, $fields, $params, $consts, $error, $context, e) {// 

        if ($params['strict'] === null) {
            if (isset($params['haystack'][$value])) {
                $error($consts['VALUE_IN_ARRAY'], [['current', $value]]);
            }
        }
        else {
            if (in_array($value, $params['haystack'], $params['strict'])) {
                $error($consts['VALUE_IN_ARRAY'], [['current', $value]]);
            }
        }},"Number":async function(input, $value, $fields, $params, $consts, $error, $context, e) {var $match;
// 

        $match = [];

        if (!preg_match('#^-?([1-9]\\d*|0)(\\.\\d+)?$#u', $value, $match)) {
            return $error($consts['INVALID'], []);
        }

        $match[2] = (isset($match[2])) ? $match[2] : '';
        if (strlen($match[1]) > $params['int'] && strlen($match[2]) > $params['dec'] + 1) {
            return $error($consts['INVALID_INTDEC'], []);
        }
        else if (strlen($match[1]) > $params['int']) {
            return $error($consts['INVALID_INT'], []);
        }
        else if (strlen($match[2]) > $params['dec'] + 1) {
            return $error($consts['INVALID_DEC'], []);
        }

        if (!(+$params['min'] <= +$value && +$value <= +$params['max'])) {
            return $error($consts['INVALID_MINMAX'], []);
        }},"Password":async function(input, $value, $fields, $params, $consts, $error, $context, e) {var $fulfill, $key, $regex, $counts;
// 

        $fulfill = $context['foreach']($params['regexes'], function ($key, $regex, $value, $error, $consts) {
            if (!preg_match($regex, $value)) {
                $error($consts['INVALID_PASSWORD_LESS'], []);
                return false;
            }
        }, $value, $error, $consts);

        if (!$fulfill) {
            return;
        }

        $counts = array_count_values(str_split($value, 1));
        if (count($counts) < count($params['regexes']) * $params['repeat']) {
            $error($consts['INVALID_PASSWORD_WEAK'], []);
        }},"Range":async function(input, $value, $fields, $params, $consts, $error, $context, e) {// 

        if ((!is_null($params['min']) && !is_null($params['max'])) && !($params['min'] <= $value && $value <= $params['max'])) {
            $error($consts['INVALID_MINMAX'], []);
        }
        else if ((!is_null($params['min']) && is_null($params['max'])) && ($params['min'] > $value)) {
            $error($consts['INVALID_MIN'], []);
        }
        else if ((is_null($params['min']) && !is_null($params['max'])) && ($value > $params['max'])) {
            $error($consts['INVALID_MAX'], []);
        }},"Regex":async function(input, $value, $fields, $params, $consts, $error, $context, e) {var $status;
// 

        if (!is_string($value) && !is_int($value) && !is_float($value)) {
            return $error($consts['INVALID'], []);
        }

        $status = preg_match($params['pattern'], $value);
        if (false === $status) {
            $error($consts['ERROROUS'], []);
        }
        else if (!$params['negation'] && !$status) {
            $error($consts['NOT_MATCH'], []);
        }
        else if ($params['negation'] && $status) {
            $error($consts['NEGATION'], []);
        }},"Requires":async function(input, $value, $fields, $params, $consts, $error, $context, e) {var $nofify, $getDepend, $name, $carry, $statement, $field, $operator, $operand, $dvalue, $intersect;
// 

        $nofify = function ($value, $error, $consts) {
            if (!is_array($value) && strval($value) === '') {
                $error($consts['INVALID_TEXT'], []);
            }
            else if (is_array($value) && count($value) === 0) {
                $error($consts['INVALID_MULTIPLE'], []);
            }
        };

        if (count($params['statements']) === 0) {
            return $nofify($value, $error, $consts);
        }

        $getDepend = $context['function'](function ($name, $fields) { return $fields[$name]; }, $fields);

        if (array_reduce($params['statements'], $context['function'](function ($carry, $statement, $getDepend, $context) {
            if ($carry === true) {
                return true;
            }
            return array_reduce(array_keys($statement), $context['function'](function ($carry, $field, $statement, $getDepend, $context) {
                if ($carry === false) {
                    return false;
                }
                $operator = $statement[$field][0];
                $operand = $statement[$field][1];
                $dvalue = $getDepend($field);

                // for scalar
                if ($operator === '==') {
                    return $dvalue == $operand;
                }
                if ($operator === '===') {
                    return $dvalue === $operand;
                }
                if ($operator === '!=') {
                    return $dvalue != $operand;
                }
                if ($operator === '!==') {
                    return $dvalue !== $operand;
                }
                if ($operator === '<') {
                    return $dvalue < $operand;
                }
                if ($operator === '<=') {
                    return $dvalue <= $operand;
                }
                if ($operator === '>') {
                    return $dvalue > $operand;
                }
                if ($operator === '>=') {
                    return $dvalue >= $operand;
                }

                // for array
                $intersect = array_intersect_key(
                    array_flip($context['cast']('array', $dvalue)),
                    array_flip($operand)
                );
                if ($operator === 'any') {
                    return !!count($intersect);
                }
                if ($operator === 'notany') {
                    return !count($intersect);
                }
                if ($operator === 'in') {
                    return count($intersect) === count($operand);
                }
                if ($operator === 'notin') {
                    return count($intersect) !== count($operand);
                }
            }, $statement, $getDepend, $context), true);
        }, $getDepend, $context), false)) {
            $nofify($value, $error, $consts);
        }},"RequiresChild":async function(input, $value, $fields, $params, $consts, $error, $context, e) {var $flipped, $cols, $v, $col, $cb, $carry, $c, $name, $values, $inputs, $operator, $operands, $intersect;
// 

        $flipped = array_combine(array_values($params['children']), array_values($params['children']));
        $cols = array_map($context['function'](function ($v, $value, $context) {
            $col = array_column($value, $v);
            $cb = ($carry, $c) => array_merge($carry, $context['cast']('array', $c));
            return array_reduce($col, $context['function']($cb, $context), []);
        }, $value, $context), $flipped);

        $context['foreach']($cols, function ($name, $values, $inputs, $consts, $error, $context) {
            $operator = $inputs[$name][0];
            $operands = $inputs[$name][1];

            $intersect = array_intersect_key(
                array_flip($context['cast']('array', $values)),
                array_flip($context['cast']('array', $operands)),
            );

            if ($operator === 'any' && !count($intersect)) {
                $error($consts['NOT_CONTAIN'], []);
            }
            if ($operator === 'all' && count($intersect) !== count($operands)) {
                $error($consts['NOT_CONTAIN'], []);
            }
        }, $params['inputs'], $consts, $error, $context);},"Step":async function(input, $value, $fields, $params, $consts, $error, $context, e) {var $match;
// 

        $match = [];
        if (isset($params['timeunit']['h']) && isset($params['timeunit']['i'])) {
            if (!preg_match('#(\\d{1,2}):?(\\d{1,2})(:?(\\d{1,2}))?$#u', $value, $match)) {
                return $error($consts['INVALID'], []);
            }
            $value = (3600 * $match[1]) + (60 * $match[2]) + intval($match[4] ?? 0);
        }
        else if (isset($params['timeunit']['i']) && isset($params['timeunit']['s'])) {
            if (!preg_match('#(\\d{1,2}):?(\\d{1,2})$#u', $value, $match)) {
                return $error($consts['INVALID'], []);
            }
            $value = (60 * $match[1]) + intval($match[2] ?? 0);
        }
        else {
            if (!preg_match('#^-?([1-9]\\d*|0)(\\.\\d+)?$#u', $value)) {
                return $error($consts['INVALID'], []);
            }
        }
        if (abs(round($value / $params['step']) * $params['step'] - $value) > pow(2, -52)) {
            if (count($params['timeunit'])) {
                $error($consts['INVALID_TIME'], []);
            }
            else {
                $error($consts['INVALID_STEP'], []);
            }
        }},"StringLength":async function(input, $value, $fields, $params, $consts, $error, $context, e) {var $length;
// 

        $length = mb_strlen($value);

        if (!is_null($params['max']) && !is_null($params['min']) && ($length > $params['max'] || $length < $params['min'])) {
            if ($params['min'] === $params['max']) {
                $error($consts['DIFFERENT'], []);
            }
            else {
                $error($consts['SHORTLONG'], []);
            }
        }
        else if (is_null($params['max']) && !is_null($params['min']) && $length < $params['min']) {
            $error($consts['TOO_SHORT'], []);
        }
        else if (is_null($params['min']) && !is_null($params['max']) && $length > $params['max']) {
            $error($consts['TOO_LONG'], []);
        }},"StringWidth":async function(input, $value, $fields, $params, $consts, $error, $context, e) {var $length, $c;
// 

        $length = array_sum(array_map(function ($c) {
            if ($c === "‍") {
                return -1;
            }
            return strlen($c) === 1 ? 1 : 2;
        }, mb_str_split($value)));

        if (!is_null($params['max']) && !is_null($params['min']) && ($length > $params['max'] || $length < $params['min'])) {
            if ($params['min'] === $params['max']) {
                $error($consts['DIFFERENT'], []);
            }
            else {
                $error($consts['SHORTLONG'], []);
            }
        }
        else if (is_null($params['max']) && !is_null($params['min']) && $length < $params['min']) {
            $error($consts['TOO_SHORT'], []);
        }
        else if (is_null($params['min']) && !is_null($params['max']) && $length > $params['max']) {
            $error($consts['TOO_LONG'], []);
        }},"Telephone":async function(input, $value, $fields, $params, $consts, $error, $context, e) {var $key;
// 

        if ($params['delimiter'] === null) {
            $value = $context['cast']('array', $value);
        }
        else {
            $value = preg_split($params['delimiter'], $value, -1, PREG_SPLIT_NO_EMPTY);
        }

        $context['foreach']($value, function ($key, $value, $params, $error, $consts) {
            // 明らかに電話番号っぽくない場合のチェック
            if (mb_strlen($value) > $params['maxlength']) {
                $error($consts['INVALID'], []);
                return false;
            }

            // 電話番号っぽいが細部がおかしい場合
            if (!preg_match($params['pattern'], $value)) {
                if ($params['hyphen'] === null) {
                    $error($consts['INVALID_TELEPHONE'], []);
                    return false;
                }
                else if ($params['hyphen'] === true) {
                    $error($consts['INVALID_WITH_HYPHEN'], []);
                    return false;
                }
                else if ($params['hyphen'] === false) {
                    $error($consts['INVALID_NONE_HYPHEN'], []);
                    return false;
                }
            }
        }, $params, $error, $consts);},"Unique":async function(input, $value, $fields, $params, $consts, $error, $context, e) {var $acv;
            (function() {
                $context['values'] = {};
                var regexp = new RegExp($params.root + '/(-?\\d+)/' + $params.name);
                var values = chmonos.values();
                var keys = Object.keys(values);
                for(var i = 0; i < keys.length; i++){
                    var name = keys[i];
                    var m = regexp.exec(name);
                    if (m) {
                        $context['values'][m[1]] = $params.strict ? values[name] : (values[name] + '').toLowerCase();
                    }
                }
                // 

        $acv = array_count_values($context['values']);
        if ($acv[$params['strict'] ? $value : strtolower($value)] > 1) {
            $error($consts['NO_UNIQUE'], []);
            return false;
        }
    
            })();},"UniqueChild":async function(input, $value, $fields, $params, $consts, $error, $context, e) {var $rows, $row, $children, $cb, $v;
// 

        $rows = array_map($context['function'](function ($row, $children, $context) {
            $row = array_intersect_key($row, $children);
            $cb = ($v, $context) => implode("\x1f", $context['cast']('array', $v));
            $row = array_map($context['function']($cb, $context), $row);
            return implode("\x1e", $row);
        }, array_flip($params['children']), $context), $value);

        if ($params['ignore_empty']) {
            $rows = array_filter($rows, ($row) => strlen(trim($row, "\x1e")));
        }

        if (count($rows) !== count(array_unique($rows))) {
            $error($consts['NO_UNIQUE'], []);
        }},"Uri":async function(input, $value, $fields, $params, $consts, $error, $context, e) {var $key, $parsed;
// 

        if ($params['delimiter'] === null) {
            $value = $context['cast']('array', $value);
        }
        else {
            $value = preg_split($params['delimiter'], $value, -1, PREG_SPLIT_NO_EMPTY);
        }

        $context['foreach']($value, function ($key, $value, $params, $error, $consts) {
            $parsed = parse_url($value);

            if (!$parsed || !isset($parsed['scheme'])) {
                $error($consts['INVALID'], []);
                return false;
            }
            else if (count($params['schemes']) && !in_array($parsed['scheme'], $params['schemes'])) {
                $error($consts['INVALID_SCHEME'], []);
                return false;
            }
            else if (!isset($parsed['host'])) {
                $error($consts['INVALID_HOST'], []);
                return false;
            }
        }, $params, $error, $consts);}};/*
*/

    /// エラー定数のインポート
    /**/
this.constants = {"Ajax":{"INVALID":"AjaxInvalid"},"AlphaDigit":{"INVALID_ALPHADIGIT":"AlphaNumericInvalid","INVALID_FIRST_NUMBER":"AlphaNumericFirstNumber","INVALID_UPPERCASE":"AlphaNumericUpperCase","INVALID_LOWERCASE":"AlphaNumericLowerCase","INVALID":"InvalidAbstract"},"ArrayExclusion":{"INVALID_INCLUSION":"ArrayExclusionInclusion","INVALID":"InvalidAbstract"},"ArrayLength":{"INVALID":"ArrayLengthInvalidLength","TOO_SHORT":"ArrayLengthInvalidMin","TOO_LONG":"ArrayLengthInvalidMax","SHORTLONG":"ArrayLengthInvalidMinMax"},"Aruiha":{"INVALID_ARUIHA":"AruihaInvalid","INVALID":"InvalidAbstract"},"Callback":{"INVALID":"CallbackInvalid"},"Compare":{"INVALID":"compareInvalid","EQUAL":"compareEqual","NOT_EQUAL":"compareNotEqual","LESS_THAN":"compareLessThan","GREATER_THAN":"compareGreaterThan","SIMILAR":"compareSimilar"},"DataUri":{"INVALID":"dataUriInvalid","INVALID_SIZE":"dataUriInvalidSize","INVALID_TYPE":"dataUriInvalidType"},"Date":{"INVALID":"dateInvalid","INVALID_DATE":"dateInvalidDate","FALSEFORMAT":"dateFalseFormat"},"Decimal":{"INVALID":"DecimalInvalid","INVALID_INT":"DecimalInvalidInt","INVALID_DEC":"DecimalInvalidDec","INVALID_INTDEC":"DecimalInvalidIntDec"},"Digits":{"INVALID":"notDigits","NOT_DIGITS":"digitsInvalid","INVALID_DIGIT":"digitsInvalidDigit"},"Distinct":{"INVALID":"DistinctInvalid","NO_DISTINCT":"DistinctNoDistinct"},"EmailAddress":{"INVALID":"emailAddressInvalid","INVALID_FORMAT":"emailAddressInvalidFormat"},"FileName":{"INVALID":"InvalidFileName","INVALID_FILENAME_STR":"InvalidFileNameStr","INVALID_FILENAME_EXT":"InvalidFileNameExt","INVALID_FILENAME_RESERVED":"InvalidFileNameReserved"},"FileSize":{"INVALID":"FileSizeInvalid","INVALID_OVER":"FileSizeInvalidOver"},"FileType":{"INVALID":"FileTypeInvalid","INVALID_TYPE":"FileTypeInvalidType"},"Hostname":{"INVALID":"InvalidHostname","INVALID_PORT":"InvalidHostnamePort"},"ImageSize":{"INVALID":"ImageFileInvalid","INVALID_WIDTH":"ImageFileInvalidWidth","INVALID_HEIGHT":"ImageFileInvalidHeight"},"InArray":{"INVALID":"InvalidInArray","NOT_IN_ARRAY":"notInArray"},"Json":{"INVALID":"JsonInvalid","INVALID_INVALID_SCHEMA":"JsonInvalidSchema"},"NotInArray":{"INVALID":"InvalidNotInArray","VALUE_IN_ARRAY":"valueInArray"},"Number":{"INVALID":"NumberInvalid","INVALID_INT":"NumberInvalidInt","INVALID_DEC":"NumberInvalidDec","INVALID_INTDEC":"NumberInvalidIntDec","INVALID_MIN":"NumberMin","INVALID_MAX":"NumberMax","INVALID_MINMAX":"NumberMinMax"},"Password":{"INVALID":"InvalidPassword","INVALID_PASSWORD_LESS":"InvalidPasswordLess","INVALID_PASSWORD_WEAK":"InvalidPasswordWeak"},"Range":{"INVALID":"RangeInvalid","INVALID_MIN":"RangeInvalidMin","INVALID_MAX":"RangeInvalidMax","INVALID_MINMAX":"RangeInvalidMinMax"},"Regex":{"INVALID":"regexInvalid","ERROROUS":"regexErrorous","NOT_MATCH":"regexNotMatch","NEGATION":"regexNegation"},"Requires":{"INVALID":"RequireInvalid","INVALID_TEXT":"RequireInvalidText","INVALID_MULTIPLE":"RequireInvalidSelectSingle"},"RequiresChild":{"INVALID":"RequiresChildInvalid","NOT_CONTAIN":"RequiresChildNotContain"},"Step":{"INVALID":"StepInvalid","INVALID_STEP":"StepInvalidInt","INVALID_TIME":"StepInvalidTime"},"StringLength":{"INVALID":"StringLengthInvalidLength","TOO_SHORT":"StringLengthInvalidMin","TOO_LONG":"StringLengthInvalidMax","SHORTLONG":"StringLengthInvalidMinMax","DIFFERENT":"StringLengthInvalidDifferenr"},"StringWidth":{"INVALID":"StringWidthInvalidLength","TOO_SHORT":"StringWidthInvalidMin","TOO_LONG":"StringWidthInvalidMax","SHORTLONG":"StringWidthInvalidMinMax","DIFFERENT":"StringWidthInvalidDifferenr"},"Telephone":{"INVALID":"InvalidTelephone","INVALID_TELEPHONE":"InvalidTelephoneNumber","INVALID_WITH_HYPHEN":"InvalidTelephoneWithHyphen","INVALID_NONE_HYPHEN":"InvalidTelephoneNoneHyphen"},"Unique":{"INVALID":"UniqueInvalid","NO_UNIQUE":"UniqueNoUnique"},"UniqueChild":{"INVALID":"UniqueChildInvalid","NO_UNIQUE":"UniqueChildNoUnique"},"Uri":{"INVALID":"UriInvalid","INVALID_SCHEME":"UriInvalidScheme","INVALID_HOST":"UriInvalidHost","INVALID_PORT":"UriInvalidPort"}};/*
*/

    /// エラー文言のインポート
    /**/
this.messages = {"Ajax":[],"AlphaDigit":{"AlphaNumericInvalid":"使用できない文字が含まれています","AlphaNumericFirstNumber":"先頭に数値は使えません","AlphaNumericUpperCase":"大文字は使えません","AlphaNumericLowerCase":"小文字は使えません"},"ArrayExclusion":{"ArrayExclusionInclusion":"${implode(\",\", _set)}は同時選択できません"},"ArrayLength":{"ArrayLengthInvalidLength":"Invalid value given","ArrayLengthInvalidMin":"${_min}件以上は入力してください","ArrayLengthInvalidMax":"${_max}件以下で入力して下さい","ArrayLengthInvalidMinMax":"${_min}件～${_max}件を入力して下さい"},"Aruiha":{"AruihaInvalid":"必ず呼び出し元で再宣言する"},"Callback":{"CallbackInvalid":"クロージャの戻り値で上書きされる"},"Compare":{"compareInvalid":"Invalid value given","compareEqual":"「${$resolveTitle(_operand)}」と同じ値を入力してください","compareNotEqual":"「${$resolveTitle(_operand)}」と異なる値を入力してください","compareLessThan":"「${$resolveTitle(_operand)}」より小さい値を入力してください","compareGreaterThan":"「${$resolveTitle(_operand)}」より大きい値を入力してください"},"DataUri":{"dataUriInvalid":"Invalid value given","dataUriInvalidSize":"${_size}B以下で入力してください","dataUriInvalidType":"${implode(\",\", _type)}形式で入力してください"},"Date":{"dateInvalid":"Invalid value given","dateInvalidDate":"有効な日付を入力してください","dateFalseFormat":"${_format}形式で入力してください"},"Decimal":{"DecimalInvalid":"小数値を入力してください","DecimalInvalidInt":"整数部分を${_int}桁以下で入力してください","DecimalInvalidDec":"小数部分を${_dec}桁以下で入力してください","DecimalInvalidIntDec":"整数部分を${_int}桁、小数部分を${_dec}桁以下で入力してください"},"Digits":{"notDigits":"Invalid value given","digitsInvalid":"整数を入力してください","digitsInvalidDigit":"${_digit}桁で入力してください"},"Distinct":{"DistinctInvalid":"Invalid value given","DistinctNoDistinct":"重複した値が含まれています"},"EmailAddress":{"emailAddressInvalid":"Invalid value given","emailAddressInvalidFormat":"メールアドレスを正しく入力してください"},"FileName":{"InvalidFileName":"Invalid value given","InvalidFileNameStr":"有効なファイル名を入力してください","InvalidFileNameExt":"${implode(\",\", _extensions)}のファイル名を入力してください","InvalidFileNameReserved":"使用できないファイル名です"},"FileSize":{"FileSizeInvalid":"入力ファイルが不正です","FileSizeInvalidOver":"${_maxsize}B以下のファイルを選択してください"},"FileType":{"FileTypeInvalid":"入力ファイルが不正です","FileTypeInvalidType":"${implode(\",\", array_keys(_allowTypes))}形式のファイルを選択して下さい"},"Hostname":{"InvalidHostname":"ホスト名を正しく入力してください","InvalidHostnamePort":"ポート番号を正しく入力してください"},"ImageSize":{"ImageFileInvalid":"画像ファイルを入力してください","ImageFileInvalidWidth":"横サイズは${_width}ピクセル以下で選択してください","ImageFileInvalidHeight":"縦サイズは${_height}ピクセル以下で選択してください"},"InArray":{"InvalidInArray":"Invalid value given","notInArray":"選択値が不正です"},"Json":{"JsonInvalid":"JSON文字列が不正です","JsonInvalidSchema":"キーが不正です"},"NotInArray":{"InvalidNotInArray":"Invalid value given","valueInArray":"${$resolveLabel(current)}は不正です"},"Number":{"NumberInvalid":"数値を入力してください","NumberInvalidInt":"整数部分を${_int}桁以下で入力してください","NumberInvalidDec":"小数部分を${_dec}桁以下で入力してください","NumberInvalidIntDec":"整数部分を${_int}桁、小数部分を${_dec}桁以下で入力してください","NumberMin":"${_min}以上で入力して下さい","NumberMax":"${_max}以下で入力して下さい","NumberMinMax":"${_min}以上${_max}以下で入力して下さい"},"Password":{"InvalidPassword":"Invalid value given","InvalidPasswordLess":"${implode(\",\", array_keys(_charlists))}を含めてください","InvalidPasswordWeak":"${implode(\",\", array_keys(_charlists))}のいずれかを${_repeat}文字以上含めてください"},"Range":{"RangeInvalid":"Invalid value given","RangeInvalidMin":"${_min}以上で入力して下さい","RangeInvalidMax":"${_max}以下で入力して下さい","RangeInvalidMinMax":"${_min}以上${_max}以下で入力して下さい"},"Regex":{"regexInvalid":"Invalid value given","regexErrorous":"There was${_pattern}","regexNotMatch":"パターンに一致しません","regexNegation":"使用できない文字が含まれています"},"Requires":{"RequireInvalid":"Invalid value given","RequireInvalidText":"入力必須です","RequireInvalidSelectSingle":"選択してください"},"RequiresChild":{"RequiresChildInvalid":"Invalid value given","RequiresChildNotContain":"必須項目を含んでいません"},"Step":{"StepInvalid":"Invalid value given","StepInvalidInt":"${_step}の倍数で入力してください","StepInvalidTime":"${_timemessage}単位で入力してください"},"StringLength":{"StringLengthInvalidLength":"Invalid value given","StringLengthInvalidMin":"${_min}文字以上で入力して下さい","StringLengthInvalidMax":"${_max}文字以下で入力して下さい","StringLengthInvalidMinMax":"${_min}文字～${_max}文字で入力して下さい","StringLengthInvalidDifferenr":"${_min}文字で入力して下さい"},"StringWidth":{"StringWidthInvalidLength":"Invalid value given","StringWidthInvalidMin":"${_min}文字以上で入力して下さい","StringWidthInvalidMax":"${_max}文字以下で入力して下さい","StringWidthInvalidMinMax":"${_min}文字～${_max}文字で入力して下さい","StringWidthInvalidDifferenr":"${_min}文字で入力して下さい"},"Telephone":{"InvalidTelephone":"電話番号を正しく入力してください","InvalidTelephoneNumber":"電話番号を入力してください","InvalidTelephoneWithHyphen":"ハイフン付きで電話番号を入力してください","InvalidTelephoneNoneHyphen":"ハイフン無しで電話番号を入力してください"},"Unique":{"UniqueInvalid":"Invalid value given","UniqueNoUnique":"${value}が重複しています"},"UniqueChild":{"UniqueChildInvalid":"Invalid value given","UniqueChildNoUnique":"値が重複しています"},"Uri":{"UriInvalid":"URLをスキームから正しく入力してください","UriInvalidScheme":"スキームが不正です(${implode(\",\", _schemes)}のみ)","UriInvalidHost":"ホスト名が不正です","UriInvalidPort":"ポート番号が不正です"}};/*
*/

    /// 初期化（コンストラクション）

    // noinspection JSUnresolvedFunction
    setlocale('LC_CTYPE', 'en_US'); // for ctype_digit()

    /// 内部用

    function isPlainObject(obj) {
        if (typeof (obj) !== 'object' || obj.nodeType || obj === obj.window) {
            return false;
        }
        return !(obj.constructor && !{}.hasOwnProperty.call(obj.constructor.prototype, 'isPrototypeOf'));
    }

    function resolveDepend(input, contains, already) {
        contains.group = contains.group ?? true;
        contains.phantom = contains.phantom ?? true;
        contains.propagate = contains.propagate ?? false;

        already = already ?? new Set();

        var elemName = input.dataset.vinputClass;
        var rule = options.allrules[elemName];

        const add = function (input) {
            if (already.has(input)) {
                return;
            }
            already.add(input);
            resolveDepend(input, contains, already);
        }

        add(input);

        if (contains.group) {
            if (input.type === 'radio' || input.type === 'checkbox') {
                for (const e of form.querySelectorAll("input[name='" + input.name + "'].validatable")) {
                    add(e);
                }
            }
        }
        if (contains.phantom) {
            rule?.phantom?.forEach(function (phantom) {
                for (const e of chmonos.brother(input, phantom)) {
                    add(e);
                }
            });
        }
        if (contains.propagate) {
            rule?.propagate?.forEach(function (propagate) {
                for (const e of chmonos.brother(input, propagate)) {
                    add(e);
                }
            });
        }

        return already;
    }

    chmonos.functionCache ??= new Map();
    function templateFunction(vars) {
        var entries = Object.entries(vars);
        var args = entries.map(e => e[0]);
        var vals = entries.map(e => e[1]);
        var argstring = args.join(',');

        return function (template, tag) {
            try {
                const cachekey = `${tag}@${template}(${argstring})`;
                if (!chmonos.functionCache.has(cachekey)) {
                    chmonos.functionCache.set(cachekey, new Function(...args, 'return ' + (tag ?? '') + '`' + template + '`'));
                }
                return chmonos.functionCache.get(cachekey).call(vars, ...vals);
            }
            catch (e) {
                console.error(e);
            }
        };
    }

    function addError(input, result) {
        const inputs = resolveDepend(input, {
            group: true,
            phantom: true,
            propagate: false,
        });
        var warningTypes = result.warning ?? {};
        var errorTypes = result.error ?? {};
        var isWarning = Object.keys(warningTypes).length;
        var isError = Object.keys(errorTypes).length;

        input.validationWarnings = Object.assign(input.validationWarnings ?? {}, warningTypes);
        input.validationErrors = Object.assign(input.validationErrors ?? {}, errorTypes);
        inputs.forEach(function (input) {
            input.validationWarnings = Object.assign(input.validationWarnings ?? {}, isWarning ? {"": []} : {});
            input.validationErrors = Object.assign(input.validationErrors ?? {}, isError ? {"": []} : {});
        });

        if (isError) {
            return true;
        }
        if (isWarning) {
            return null;
        }
        return false;
    }

    function notifyError(input, okclass) {
        const inputs = resolveDepend(input, {
            group: true,
            phantom: true,
            propagate: true,
        });
        inputs.forEach(function (input) {
            var isWarning = Object.keys(input.validationWarnings ?? {}).length;
            var isError = Object.keys(input.validationErrors ?? {}).length;
            if (isWarning) {
                input.classList.add('validation_warning');
            }
            if (isError) {
                input.classList.add('validation_error');
            }
            if (isWarning || isError) {
                input.classList.remove('validation_ok');
            }
            else {
                input.classList.remove('validation_warning');
                input.classList.remove('validation_error');
                input.classList.remove('validation_ok');
                if (okclass && chmonos.value(input)?.length > 0) {
                    input.classList.add('validation_ok');
                }
            }
        });
        // {condition: {errortype: message}} => {errortype: message} in future scope
        [input.validationWarnings ?? {}, input.validationErrors ?? {}].forEach(function (types) {
            if (!types.hasOwnProperty('toArray')) {
                Object.defineProperty(types, 'toArray', {
                    value: function () {
                        var collectMessage = function (errors) {
                            var message = [];
                            Object.keys(errors).forEach(function (e) {
                                if (typeof (errors[e]) === 'string') {
                                    message.push(errors[e]);
                                }
                                else {
                                    message = message.concat(collectMessage(errors[e]));
                                }
                            });
                            return message;
                        };
                        return collectMessage(this);
                    },
                });
            }
        });
        input.dispatchEvent(new CustomEvent('validated', {
            bubbles: true,
            detail: {
                title: input.dataset.validationTitle ?? null,
                warningTypes: input.validationWarnings ?? {},
                errorTypes: input.validationErrors ?? {},
                phantoms: [...inputs],
            },
        }));
    }

    function validateInputs(inputs, evt) {
        if (chmonos.validationDisabled) {
            return Promise.resolve([]);
        }

        const validation_id = new Date().getTime();
        const promises = [];

        form.validationValues = undefined;

        inputs.forEach(function (input) {
            input.validationWarnings = {};
            input.validationErrors = {};
        });
        inputs.forEach(function (input) {
            // propagate などで同一要素に検証が走ることがあるので一意な ID を持たせて、同一 ID ならスルーするようにする
            var vid = input.validationId;
            if (vid !== undefined && vid === validation_id) {
                return;
            }
            input.validationId = validation_id;

            var elemName = input.dataset.vinputClass;
            var rule = options.allrules[elemName];
            if (rule === undefined) {
                return;
            }

            var phantom = rule['phantom'];
            if (phantom.length) {
                var flag = true;
                var brothers = [];
                for (var i = 1; i < phantom.length; i++) {
                    var inputs = chmonos.brother(input, phantom[i]);
                    var target = chmonos.value(inputs[0]);
                    if (target.length === 0) {
                        flag = false;
                        break;
                    }
                    brothers.push(target);
                }
                // noinspection JSUnresolvedFunction
                input.value = flag ? vsprintf(phantom[0], brothers) : '';
            }

            var fields = chmonos.fields(input);
            chmonos.required(input, fields);

            if (!rule['invisible'] && input.type !== 'hidden' && input.offsetParent === null) {
                return;
            }

            if (input.disabled) {
                return;
            }

            var condition = rule['condition'];
            var value = chmonos.value(input);
            var errorTypes = {warning: {}, error: {}};
            var asyncs = [];
            var keys = Object.keys(condition);
            for (var k = 0; k < keys.length; k++) {
                let cond = condition[keys[k]];
                let cname = cond.cname;
                let level = cond.level;
                var error = function (err, vars) {
                    if (evt.chmonosSubtypes) {
                        if (evt.chmonosSubtypes.includes('noerror')) {
                            return;
                        }
                        if (evt.chmonosSubtypes.includes('norequire') && cname === 'Requires' && input !== evt.target && evt.target.tagName !== 'FORM') {
                            return;
                        }
                    }

                    if (err === undefined) {
                        if (input.validationErrors && input.validationErrors[cname]) {
                            errorTypes[level][cname] = input.validationErrors[cname];
                        }
                    }
                    else if (err === null) {
                        delete errorTypes[level][cname];
                    }
                    else if (err instanceof Promise) {
                        asyncs.push(err);
                    }
                    else if (isPlainObject(err)) {
                        if (errorTypes[level][cname] === undefined) {
                            errorTypes[level][cname] = {};
                        }
                        Object.keys(err).forEach(function (mk) {
                            errorTypes[level][cname][mk] = err[mk];
                        });
                    }
                    else {
                        var ret;
                        if (cond['message'][err] !== undefined) {
                            ret = cond['message'][err];
                        }
                        else if (chmonos.messages[cname][err] !== undefined) {
                            ret = chmonos.messages[cname][err];
                        }
                        else {
                            ret = err;
                        }
                        if (errorTypes[level][cname] === undefined) {
                            errorTypes[level][cname] = {};
                        }

                        const values = Object.assign({
                            "$resolveTitle": function (member, target) {
                                target = target ?? input;
                                return chmonos.brother(target, member)[0]?.dataset?.validationTitle ?? member;
                            },
                            "$resolveLabel": function (value, target) {
                                target = target ?? input;
                                if (target.tagName === 'SELECT') {
                                    return target.querySelector('option[value="' + value + '"]')?.label ?? '';
                                }
                                else {
                                    return form.querySelector('input[name="' + target.name + '"][value="' + value + '"]')?.labels[0]?.textContent ?? '';
                                }
                            },
                            'value': value,
                        }, chmonos.phpjs, Object.fromEntries(vars ?? []), Object.fromEntries(Object.entries(cond['param']).map(kv => ['_' + kv[0], kv[1]])));
                        errorTypes[level][cname][err] = templateFunction(values)(ret);
                    }
                };
                // 値が空の場合は Requires しか検証しない（空かどうかの制御を他の condition に任せたくない）
                // value は必ず length を持つように制御してるので「空・未入力」の判定は length === 0 で OK
                if (value.length > 0 || cname === 'Requires') {
                    var values = cond['arrayable'] ? [value] : chmonos.context.cast('array', value);
                    Object.keys(values).forEach(function (v) {
                        try {
                            asyncs.push(chmonos.condition[cname](input, values[v], fields, cond['param'], chmonos.constants[cname], error, chmonos.context, evt));
                        }
                        catch (e) {
                            error(chmonos.constants[cname]['INVALID']);
                            console.error(e);
                        }
                    });
                }
            }

            // ラジオボタンやチェックボックスなどはこれ以上無駄なので検証 ID を放り込んでおく
            if (input.type === 'radio' || input.type === 'checkbox') {
                form.querySelectorAll("input[name='" + input.name + "'].validatable").forEach(function (e) {
                    e.validationId = validation_id;
                });
            }

            promises.push(new Promise(function (resolve) {
                Promise.all(asyncs).then(function () {
                    resolve(addError(input, errorTypes));
                });
            }));
        });

        return Promise.all(promises).then(function (results) {
            inputs.forEach(function (input) {
                notifyError(input, true);
            });
            return results;
        });
    }

    /// 外部用

    chmonos.context = {
        lang: 'javascript',
        chmonos: chmonos,
        "function": function (callback) {
            var arg = Array.from(arguments).slice(1);
            return function () {
                return callback.apply(null, Array.from(arguments).concat(arg));
            };
        },
        "foreach": function (array, callback) {
            var arg = Array.from(arguments);
            var keys = Object.keys(array);
            for (var i = 0; i < keys.length; i++) {
                arg[0] = keys[i];
                arg[1] = array[keys[i]];
                if (callback.apply(null, arg) === false) {
                    return false;
                }
            }
            return true;
        },
        "cast": function (type, value) {
            // 現状は array のみ実装
            if (type === 'array') {
                if (value === null) {
                    return [];
                }
                if (value instanceof Array || isPlainObject(value)) {
                    return value;
                }
                return [value];
            }
            throw "invalid cast type";
        },
        "str_concat": function () {
            return Array.from(arguments).join('');
        },
    };

    chmonos.valuesMap ??= new WeakMap();
    chmonos.vnodesMap ??= new WeakMap();
    chmonos.customValidation = {
        before: [],
        after: [],
        warning: [],
    };

    chmonos.initialize = function (values) {
        // js レンダリング
        Object.keys(values || {}).forEach(function (tname) {
            var curValues = values[tname] || {};
            var eventArgs = {
                detail: {
                    values: curValues,
                },
            };
            var template = form.querySelector('[data-vtemplate-name="' + tname + '"]');
            template.dispatchEvent(new CustomEvent('spawnBegin', eventArgs));
            Object.keys(curValues).forEach(function (index) {
                chmonos.spawn(template, null, curValues[index], index);
            });
            template.dispatchEvent(new CustomEvent('spawnEnd', eventArgs));
        });

        // サーバー側の結果を表示
        if (Object.keys(options.errors).length) {
            chmonos.setErrors(options.errors);
        }

        // 必須マーク
        form.querySelectorAll('.validatable:is(input, textarea, select):enabled').forEach(function (input) {
            chmonos.required(input);
        });

        // イベントをバインド
        var handler = function (e) {
            // keyup における Tab はすでに項目が遷移している
            if (e.type === 'keyup' && e.keyCode === 9) {
                return;
            }
            var elemName = e.target.dataset.vinputClass;
            if (options.allrules[elemName] === undefined) {
                return;
            }
            for (var i = 0; i < options.allrules[elemName]['event'].length; i++) {
                var eventName = options.allrules[elemName]['event'][i];
                var parts = eventName.split('.');
                if (e.type === parts[0]) {
                    e.chmonosSubtypes = parts.slice(1);
                    const inputs = resolveDepend(e.target, {
                        group: true,
                        phantom: true,
                        propagate: true,
                    });
                    form.dispatchEvent(new CustomEvent('validation-start', {
                        bubbles: true,
                        detail: {
                            inputs: inputs,
                        },
                    }));
                    validateInputs(inputs, e);
                    form.dispatchEvent(new CustomEvent('validation-end', {
                        bubbles: true,
                        detail: {
                            inputs: inputs,
                        },
                    }));
                    break;
                }
            }
        };
        // ありそうなイベントを全て listen して呼び出し時に要素単位でチェックする。この候補は割と気軽に追加して良い
        ['change', 'keyup', 'keydown', 'input', 'click', 'focusin', 'focusout'].forEach(function (event) {
            form.addEventListener(event, handler);
        });
        // ファイルドロップイベントも組み込みで実装する
        form.addEventListener('dragenter', function (e) {
            if (e.target.matches('.vfile-dropzone')) {
                e.preventDefault();

                e.target.classList.add('vfile-dragging');
            }
        });
        form.addEventListener('dragover', function (e) {
            if (e.target.matches('.vfile-dropzone')) {
                e.preventDefault();

                const file = e.target.querySelector('input[type=file]') ?? document.getElementById(e.target.getAttribute('for'));
                if (!file || file.multiple || e.dataTransfer.items.length <= 1) {
                    e.dataTransfer.dropEffect = e.target.dataset.dropEffect ?? 'copy';
                }
                else {
                    e.dataTransfer.dropEffect = 'none';
                }
            }
        });
        form.addEventListener('dragleave', function (e) {
            if (e.target.matches('.vfile-dropzone')) {
                e.preventDefault();

                e.target.classList.remove('vfile-dragging');
            }
        });
        form.addEventListener('drop', function (e) {
            if (e.target.matches('.vfile-dropzone')) {
                e.preventDefault();

                e.target.classList.remove('vfile-dragging');

                e.target.dispatchEvent(new CustomEvent('filedrop', {
                    bubbles: true,
                    detail: {
                        files: e.dataTransfer.files,
                    },
                }));

                const file = e.target.querySelector('input[type=file]') ?? document.getElementById(e.target.getAttribute('for'));
                if (file && (file.multiple || e.dataTransfer.items.length <= 1)) {
                    file.files = e.dataTransfer.files;
                    file.dispatchEvent(new Event('change', {bubbles: true}));
                }
            }
        });

        // サブミット時にバリデーション
        form.addEventListener('submit', function submit(e) {
            try {
                chmonos.validate(e).then(function (result) {
                    var done = function () {
                        var submittingEvent = new CustomEvent('submitting', {
                            bubbles: true,
                            cancelable: true,
                            detail: {
                                submitter: e.submitter ?? null,
                            },
                        });
                        var submittedEvent = new CustomEvent('submitted', {
                            bubbles: true,
                            detail: {
                                submitter: e.submitter ?? null,
                            },
                        });
                        var array = (e.submitter?.getAttribute('formenctype') ?? '').includes('array=delimitable');
                        if (!array && !e.submitter?.hasAttribute('formenctype')) {
                            array = (form.getAttribute('enctype') ?? '').includes('array=delimitable');
                        }
                        if (array && (e.submitter?.formMethod || form.method) === 'get') {
                            var target = e.submitter?.formTarget || form.target;
                            if (target) {
                                window.open(chmonos.url(e.submitter), target);
                            }
                            else {
                                location.href = chmonos.url(e.submitter);
                            }
                            return;
                        }
                        setTimeout(function () {
                            // @see https://developer.mozilla.org/ja/docs/Web/API/HTMLFormElement/submit
                            form.removeEventListener('submit', submit);
                            if (form.dispatchEvent(submittingEvent)) {
                                if (e.submitter) {
                                    e.submitter.click();
                                }
                                else {
                                    form.submit();
                                }
                            }
                            form.dispatchEvent(submittedEvent);
                            form.addEventListener('submit', submit);
                        }, 0);
                    };
                    if (result.indexOf(true) === -1) {
                        if (chmonos.customValidation.warning.length && result.indexOf(null) !== -1) {
                            var promises = [];
                            if (!chmonos.customValidation.warning.some(function (f) { return f.call(form, promises) === false })) {
                                Promise.all(promises).then(function (result) {
                                    if (result.indexOf(true) === -1) {
                                        done();
                                    }
                                });
                            }
                        }
                        else {
                            done();
                        }
                    }
                });
            }
            catch (ex) {
                console.error(ex);
            }
            e.preventDefault();
            return false;
        });
    };

    chmonos.addCustomValidation = function (validation, timing) {
        timing = timing || 'after';
        chmonos.customValidation[timing].push(validation);
    };

    chmonos.validate = function (evt, selector, inputs) {
        form.validationValues = undefined;
        evt = evt || new CustomEvent('vatidation');

        inputs ??= form.querySelectorAll('.validatable:is(input, textarea, select)');
        if (selector) {
            inputs = Array.from(inputs).filter((e) => e.matches(selector));
        }

        var promises = [];
        if (chmonos.customValidation.before.some(function (f) { return f.call(form, promises) === false })) {
            promises.push(true);
            return Promise.all(promises);
        }

        form.dispatchEvent(new CustomEvent('validation-start', {
            bubbles: true,
            detail: {
                inputs: inputs,
            },
        }));
        promises.push(validateInputs(inputs, evt));
        form.dispatchEvent(new CustomEvent('validation-end', {
            bubbles: true,
            detail: {
                inputs: inputs,
            },
        }));

        if (chmonos.customValidation.after.some(function (f) { return f.call(form, promises) === false })) {
            promises.push(true);
        }

        return Promise.all(promises).then(results => [...results].flat());
    };

    chmonos.getValues = function (fragment) {
        fragment ??= form;
        var values = {};
        fragment.querySelectorAll('.validatable:is(input, textarea, select):enabled').forEach(function (e) {
            var klass = e.dataset.vinputClass;
            if (klass === undefined) {
                return;
            }
            var value = chmonos.value(e);
            if (value === undefined) {
                return;
            }

            var parts = klass.split('/');
            values[parts[1] ?? parts[0]] = value;
        });
        return values;
    };

    chmonos.setValues = function (fragment, values) {
        fragment ??= form;
        Object.keys(values).forEach(function (key) {
            var index = 0;
            if (values[key] !== null) {
                fragment.querySelectorAll('.validatable:is([data-vinput-id="' + key + '"], [data-vinput-id$="/' + key + '"])').forEach(function (e) {
                    if (e.type === 'file') {
                        return;
                    }
                    if (e.type === 'checkbox' || e.type === 'radio') {
                        var vv = (values[key] instanceof Array ? values[key] : [values[key]]).map(function (x) {return '' + (+x)});
                        e.checked = vv.indexOf(e.value) >= 0;
                    }
                    else if (e.type === 'select-multiple') {
                        var vv = (values[key] instanceof Array ? values[key] : [values[key]]).map(function (x) {return '' + x});
                        e.querySelectorAll('option').forEach(function (o) {
                            o.selected = vv.indexOf(o.value) >= 0;
                        });
                    }
                    else {
                        if (values[key] instanceof Array) {
                            e.value = values[key][index++] || '';
                        }
                        else {
                            e.value = values[key];
                        }
                    }
                });
            }
        });
    };

    chmonos.setErrors = function (emessages) {
        var errorTypes = {};
        // {input: {condition: {errortype: message}}} => {input: {errortype: message}} in future scope
        var flatize = function (current, parent, child, grand) {
            Object.keys(current).forEach(function (key) {
                var value = current[key];
                if (typeof (value) === 'string') {
                    if (errorTypes[child] === undefined) {
                        errorTypes[child] = {};
                    }
                    if (errorTypes[child][grand] === undefined) {
                        errorTypes[child][grand] = {};
                    }
                    errorTypes[child][grand][key] = value;
                }
                else {
                    flatize(value, (parent ? parent + '/' : '') + key, parent, key);
                }
            });
        };
        flatize(emessages, '', '', '');

        const inputs = form.querySelectorAll('.validatable:is(input, textarea, select)');
        inputs.forEach(function (input) {
            addError(input, {error: errorTypes[input.dataset.vinputId] || {}});
        });
        form.dispatchEvent(new CustomEvent('validation-start', {
            bubbles: true,
            detail: {
                inputs: inputs,
            },
        }));
        inputs.forEach(function (input) {
            notifyError(input);
        });
        form.dispatchEvent(new CustomEvent('validation-end', {
            bubbles: true,
            detail: {
                inputs: inputs,
            },
        }));
    };

    chmonos.clearErrors = function () {
        const inputs = form.querySelectorAll('.validatable:is(input, textarea, select)');
        inputs.forEach(function (input) {
            input.validationWarnings = {};
            input.validationErrors = {};
            input.classList.remove('validation_warning');
            input.classList.remove('validation_error');
        });
        inputs.forEach(function (input) {
            notifyError(input);
        });
    };

    /**
     * Array 要素を新しく生成して返す
     *
     * @param template string|template|script
     * @param values 初期値
     * @param index 追加するインデックス
     */
    chmonos.birth = function (template, values, index) {
        var rootTag;
        var fragment;
        if ('content' in template) {
            fragment = template.content.cloneNode(true);
            rootTag = fragment.firstElementChild.tagName;
        }
        else {
            var parent;
            fragment = document.createDocumentFragment();
            if (typeof (template) === 'string') {
                parent = document.createElement('div');
                parent.innerHTML = template;
                rootTag = template.match(/<([a-z0-9_-]+)/i)[1];
            }
            else {
                parent = document.createElement(template.parentNode.tagName);
                parent.innerHTML = template.textContent;
                rootTag = template.textContent.match(/<([a-z0-9_-]+)/i)[1];
            }
            while (parent.firstChild) {
                fragment.appendChild(parent.firstChild);
            }
        }

        if (template.dataset) {
            var template_name = template.dataset.vtemplateName;
            if (template_name && index === undefined) {
                index = -(chmonos.sibling(template_name).size + 1);
            }
        }

        function resetIndex(node, name, index) {
            var newval = (node.getAttribute(name) || '').replace(/__index/, index);
            if (newval) {
                node.setAttribute(name, newval);
            }
        }

        Array.from(fragment.querySelectorAll('[for*=__index]')).forEach(function (e) {
            resetIndex(e, 'for', index);
            resetIndex(e, 'data-vlabel-id', index);
            resetIndex(e, 'data-vlabel-index', index);
        });
        Array.from(fragment.querySelectorAll('[name*=__index]')).forEach(function (e) {
            resetIndex(e, 'id', index);
            resetIndex(e, 'name', index);
            resetIndex(e, 'data-vinput-id', index);
            resetIndex(e, 'data-vinput-index', index);
        });
        Array.from(fragment.querySelectorAll('[data-vinput-wrapper],[data-vinput-group]')).forEach(function (e) {
            resetIndex(e, 'data-vinput-wrapper', index);
            resetIndex(e, 'data-vinput-group', index);
        });
        if (values) {
            chmonos.setValues(fragment, values);
        }
        Array.from(fragment.querySelectorAll('.validatable:is(input, textarea, select):enabled')).forEach(function (e) {
            chmonos.required(e, undefined, fragment);
        });

        var node = fragment.querySelector(rootTag);
        node.dataset.vinputIndex = index;
        chmonos.valuesMap.set(node, values ?? {});
        return node;
    };

    /**
     * 子要素を生み出す（新規追加）
     *
     * @param template Array 要素名か
     * @param callback 追加処理
     * @param values 初期値
     * @param index 追加するインデックス
     */
    chmonos.spawn = function (template, callback, values, index) {
        if (typeof (template) === 'string') {
            template = form.querySelector('[data-vtemplate-name="' + template + '"]')
        }

        var node = chmonos.birth(template, values, index);
        if (values) {
            const F = templateFunction(values);
            node.querySelectorAll('[data-vnode]').forEach(function (e) {
                try {
                    e.insertAdjacentHTML('afterend', F(e.outerHTML, e.dataset.vnode));
                    chmonos.vnodesMap.set(e.nextElementSibling, e);
                    e.remove();
                }
                catch (e) {
                    console.error(e);
                }
            });
        }

        template.dispatchEvent(new CustomEvent('spawn', {
            detail: {
                node: node,
                index: index ?? +node.dataset.vinputIndex,
                values: values ?? {},
            },
        }));

        callback = callback || function (node) {this.parentNode.appendChild(node)};
        callback.call(template, node);
        return node;
    };

    /**
     * 子要素を生み出す（ベース NODE 指定）
     *
     * @param template Array 要素名か
     * @param callback 追加処理
     * @param values 初期値
     * @param baseNode ベース NODE
     */
    chmonos.respawn = function (template, callback, values, baseNode) {
        var node = chmonos.spawn(template, () => null, Object.assign({}, chmonos.valuesMap.get(baseNode) ?? {}, chmonos.getValues(baseNode), values));
        callback = callback || function (node, base) {base.after(node)};
        callback.call(template, node, baseNode);
        return node;
    };

    /**
     * 子要素を再設定する
     *
     * @param baseNode ベース NODE
     * @param values 初期値
     */
    chmonos.rebirth = function (baseNode, values) {
        chmonos.setValues(baseNode, values);

        const F = templateFunction(values);
        baseNode.querySelectorAll('[data-vnode]').forEach(function (e) {
            try {
                const vnode = chmonos.vnodesMap.get(e);
                e.insertAdjacentHTML('afterend', F(vnode.outerHTML, vnode.dataset.vnode));
                chmonos.vnodesMap.set(e.nextElementSibling, vnode);
                e.remove();
            }
            catch (e) {
                console.error(e);
            }
        });
    };

    /**
     * spawn で生み出したノードを削除する
     *
     * @param template テンプレート script
     * @param node 削除する子要素
     * @param callback 削除処理
     */
    chmonos.cull = function (template, node, callback) {
        if (typeof (template) === 'string') {
            template = form.querySelector('[data-vtemplate-name="' + template + '"]')
        }

        template.dispatchEvent(new CustomEvent('cull', {
            detail: {
                node: node,
            },
        }));

        callback = callback || function (node) {this.parentNode.removeChild(node)};
        callback.call(template, node);
        return node;
    };

    /**
     * Array 要素の兄弟要素を返す
     *
     * @param id input-id のプレフィックス
     */
    chmonos.sibling = function (id) {
        var siblings = new Map();
        form.querySelectorAll('[data-vinput-class^="' + id + '/"].validatable').forEach(function (e) {
            var index = e.dataset.vinputIndex;
            var klass = e.dataset.vinputClass;
            var elems = siblings.get(index) || {};
            if (elems[klass] === undefined) {
                elems[klass] = e;
            }
            else {
                if (!(elems[klass] instanceof Array)) {
                    elems[klass] = [elems[klass]];
                }
                elems[klass].push(e);
            }
            siblings.set(index, elems);
        });
        return siblings;
    };

    /**
     * 自身の兄弟要素を返す
     *
     * @todo 改修に次ぐ改修でカオスになってるので要修正
     *
     * @param input 自身
     * @param target 兄弟 class 名
     */
    chmonos.brother = function (input, target) {
        if (typeof (input) === 'string') {
            input = form.querySelector('[data-vinput-id="' + input + '"]');
        }
        if (target.charAt(0) === '/') {
            return form.querySelectorAll('[data-vinput-class="' + target.substring(1) + '"].validatable');
        }
        var index = input.dataset.vinputIndex;
        var klass = input.dataset.vinputClass;
        var parent = klass.substring(0, klass.indexOf('/') + 1);
        if (index === '') {
            return form.querySelectorAll('[data-vinput-class="' + parent + target + '"].validatable');
        }
        else {
            return form.querySelectorAll('[data-vinput-id="' + parent + index + '/' + target + '"].validatable');
        }
    };

    /**
     * 要素の必須状態を設定する
     *
     * @param input 自身
     * @param fields 依存フィールド
     * @param holder input の所持者
     */
    chmonos.required = function (input, fields, holder) {
        holder = holder || form;
        if (typeof (input) === 'string') {
            input = holder.querySelector('[data-vinput-id="' + input + '"]');
        }
        var elemName = input.dataset.vinputClass;
        var condition = options.allrules[elemName]['condition'];
        var needless = options.allrules[elemName]['needless'];
        var rkey = Object.keys(condition).find(function (key) {
            return condition[key].cname === 'Requires';
        });
        if (rkey) {
            fields = fields || chmonos.fields(input);
            var label = holder.querySelector('[data-vlabel-id="' + input.dataset.vinputId + '"]') || document.createElement('span');
            input.setAttribute('data-vlevel', condition[rkey]['level']);
            label.setAttribute('data-vlevel', condition[rkey]['level']);
            input.classList.remove('required');
            label.classList.remove('required');
            Object.entries(needless).forEach(([attrname, attrvalue]) => input.setAttribute(attrname, attrvalue));
            chmonos.condition['Requires'](input, '', fields, condition[rkey]['param'], chmonos.constants['Requires'], function () {
                input.classList.add('required');
                label.classList.add('required');
                Object.keys(needless).forEach(attrname => input.removeAttribute(attrname));
            }, chmonos.context);
        }
    };

    /**
     * 要素の依存値を返す
     *
     * @param input 依存値が欲しい要素
     */
    chmonos.fields = function (input) {
        if (typeof (input) === 'string') {
            input = form.querySelector('[data-vinput-id="' + input + '"]');
        }
        var elemName = input.dataset.vinputClass;
        var condition = options.allrules[elemName]['condition'];
        var fields = [];
        Object.keys(condition).forEach(function (key) {
            fields.push.apply(fields, condition[key]['fields']);
        });
        if (!fields.length) {
            return {};
        }
        var depends = {};
        var values = chmonos.values();
        for (var i = 0; i < fields.length; i++) {
            depends[fields[i]] = values[fields[i]] !== undefined ? values[fields[i]] : '';
        }
        form.querySelectorAll('[data-vinput-index="' + input.dataset.vinputIndex + '"].validatable').forEach(function (e) {
            var klass = e.dataset.vinputClass;
            if (klass === undefined) {
                return;
            }
            var name = klass.substring(klass.indexOf('/') + 1);
            if (fields.indexOf(name) === -1) {
                return;
            }
            var value = chmonos.value(e);
            if (value === undefined) {
                return;
            }
            depends[name] = value;
        });
        return depends;
    };

    /**
     * 要素の値を返す
     *
     * @param input 値が欲しい要素
     */
    chmonos.value = function (input) {
        if (typeof (input) === 'string') {
            input = form.querySelector('[data-vinput-id="' + input + '"]');
        }
        var elemName = input.dataset.vinputClass;
        if (elemName == null) {
            return undefined;
        }
        var type = input.type;
        if (type === 'file') {
            if (input.multiple) {
                return Array.from(input.files);
            }
            if (input.files[0]) {
                // noinspection JSUndefinedPropertyAssignment
                input.files[0].length = 1;
                return input.files[0];
            }
            return '';
        }
        if (type === 'checkbox') {
            if (!input.name.match(/\[]$/)) {
                return input.checked ? input.value : '';
            }
            return Array.from(form.querySelectorAll('[name="' + input.name + '"].validatable:checked'), function (e) {
                return e.value;
            });
        }
        if (type === 'radio') {
            var checked = form.querySelector('[name="' + input.name + '"].validatable:checked');
            return checked ? checked.value : '';
        }
        if (type === 'select-multiple') {
            return Array.from(input.options).filter(function (e) {
                return e.selected;
            }).map(function (e) {
                return e.value;
            });
        }

        if (input.getAttribute('type') === 'dummy') {
            var dummy = [];
            chmonos.sibling(input.dataset.vinputId).forEach(function (v, k) {
                var inputs = {};
                Object.keys(v).forEach(function (k) {
                    var vs = chmonos.context.cast('array', v[k]);
                    inputs[k.substring(k.indexOf('/') + 1)] = chmonos.value(vs[0]);
                });
                dummy.push(inputs);
            });
            return dummy;
        }

        const getValue = function (e) {
            var value = e.value;
            if (options.allrules[elemName]['trimming']) {
                value = value.trim();
            }
            // number/date 用の特別処理
            if (value.length === 0 && e.validity.badInput && Number.isNaN(e.valueAsNumber)) {
                value = 'bad'; // 数としても日付として不正なら何でもいい（ただし maxlength に引っかかるので短めが良い）
            }
            return value;
        };

        if (input.name.match(/\[]$/)) {
            return Array.from(form.querySelectorAll('[name="' + input.name + '"].validatable:enabled'), function (e) {
                return getValue(e);
            });
        }

        return getValue(input);
    };

    /**
     * フォームの値を返す
     */
    chmonos.values = function () {
        if (form.validationValues) {
            return form.validationValues;
        }

        var values = {};
        form.querySelectorAll('.validatable:is(input, textarea, select):enabled').forEach(function (e) {
            var id = e.dataset.vinputId;
            if (id === undefined || values['/' + id] !== undefined) {
                return;
            }
            var value = chmonos.value(e);
            if (value === undefined) {
                return;
            }
            if (['checkbox', 'radio'].indexOf(e.type) >= 0 && !e.checked) {
                return;
            }
            values['/' + id] = value;
        });

        return form.validationValues = values;
    };

    /**
     * フォームデータを返す
     */
    chmonos.data = function () {
        return new FormData(form);
    };

    /**
     * パラメータを返す
     */
    chmonos.params = async function (filemanage) {
        filemanage = (function (filemanage) {
            if (filemanage === 'string') {
                return async file => file.text();
            }
            if (filemanage === 'binary') {
                return async file => [...new Uint8Array(await file.arrayBuffer())].map(c => String.fromCharCode(c)).join('');
            }
            if (filemanage === 'base64') {
                return async file => btoa([...new Uint8Array(await file.arrayBuffer())].map(c => String.fromCharCode(c)).join(''));
            }
            return filemanage;
        })(filemanage ?? 'base64');

        var params = new URLSearchParams();
        for (var [name, value] of this.data().entries()) {
            if (value instanceof File) {
                params.append(name, await filemanage(value));
            }
            else {
                params.append(name, value);
            }
        }
        return params;
    };

    /**
     * フォームの値を URL で返す
     *
     * @param submitter
     */
    chmonos.url = function (submitter) {
        var url = new URL(submitter?.formAction || form.action);
        url.search = ''; // form の action もパラメータが無視される

        var arrays = {};
        for (var [k, v] of chmonos.data().entries()) {
            var matches = k.match(/(.+?)(\[.+\]\[(.+)\])?\[\]$/) ?? [];
            var elemName = (matches[1] ?? '') + (matches[3] ? '/' + (matches[3] ?? '') : '');
            if (matches.length && options.allrules[elemName]?.delimiter) {
                var name = (matches[1] ?? '') + (matches[2] ?? '') + (matches[3] ?? '')
                arrays[name] = arrays[name] ?? [];
                arrays[name].push(v);
            }
            else {
                url.searchParams.append(k, v);
            }
        }
        for (var [k, vv] of Object.entries(arrays)) {
            url.searchParams.append(k, vv.join(options.allrules[k]?.delimiter));
        }
        if (submitter && submitter.name) {
            // type=image の場合は？ -> 対応しない
            url.searchParams.append(submitter.name, submitter.value);
        }

        return url;
    };

    /**
     * フォームの値をオブジェクトで返す
     *
     * @param filemanage file 要素をどう扱うか？ string|binary|base64
     */
    chmonos.object = async function (filemanage) {
        var result = {};
        chmonos.parse_str((await chmonos.params(filemanage)).toString(), result);
        return result;
    };

    /**
     * フォームの値を html で返す
     *
     * 装飾は一切しない。class は当てるので利用側で好きにすればよい。
     *
     * @param filemanage file 要素をどう扱うか？ filename|object
     * @param delimiter string 値の区切り文字
     */
    chmonos.html = async function (filemanage, delimiter) {
        delimiter = delimiter ?? ',';
        filemanage = (function (filemanage) {
            if (filemanage === 'filename') {
                return file => Array.from(file.files, file => file.name).join(delimiter);
            }
            if (filemanage === 'object') {
                return async file => (await Promise.all(Array.from(file.files, file => new Promise(function (resolve, reject) {
                    var reader = new FileReader();
                    reader.readAsDataURL(file);
                    reader.addEventListener('load', () => resolve(`<object data="${reader.result}"></object>`));
                    reader.addEventListener('error', () => reject(reader.error));
                })))).join('');
            }
            return filemanage;
        })(filemanage ?? 'object');

        var E = function (string) {
            return ('' + string).replace(/[&'`"<>]/g, function (match) {
                return {
                    '&': '&amp;',
                    "'": '&#x27;',
                    '`': '&#x60;',
                    '"': '&quot;',
                    '<': '&lt;',
                    '>': '&gt;',
                }[match]
            });
        };
        var V = async function (inputs) {
            var result = [];
            for (var input of inputs) {
                var type = input.type;
                if (type === undefined) {
                    //
                }
                else if (type === 'file') {
                    result.push(await filemanage(input));
                }
                else if (type === 'checkbox' || type === 'radio') {
                    if (input.checked) {
                        result.push(...Array.from(input.labels).map(label => E(label.textContent)));
                    }
                }
                else if (type === 'select-one' || type === 'select-multiple') {
                    result.push(...Array.from(input.options).filter(e => e.selected).map(e => E(e.textContent)));
                }
                else {
                    result.push(E(input.value));
                }
            }
            return result;
        };

        var inputs = {};
        form.querySelectorAll('.validatable:is(input, textarea, select):enabled').forEach(function (e) {
            var klass = e.dataset.vinputClass ?? '';
            if (e.matches('[type="dummy"]')) {
                inputs[klass] = inputs[klass] ?? {};
                inputs[klass][''] = [e];
            }
            else if (klass.includes('/')) {
                var index = e.dataset.vinputIndex ?? '';
                var kindex = 'k' + index; // 順序維持のためプレフィックスを付ける
                var [parent, local] = klass.split('/');
                inputs[parent] = inputs[parent] ?? {};
                inputs[parent][kindex] = inputs[parent][kindex] ?? {};
                inputs[parent][kindex][local] = Array.from(form.querySelectorAll(`[data-vinput-class="${klass}"][data-vinput-index="${index}"]`));
            }
            else {
                inputs[klass] = Array.from(form.querySelectorAll(`[data-vinput-class="${klass}"]`));
            }
        });

        var dldtdd = async function (inputs, klass) {
            var result = [];
            for (var input of Object.values(inputs)) {
                if (Array.isArray(input)) {
                    var target = input;
                    var ids = [...new Set(input.map(e => e.dataset.vinputId ?? ''))].join('|');
                    var values = (await V(input)).filter(v => v.length);
                    var delimiter2 = delimiter;
                }
                else {
                    var target = input[''] ?? [];
                    delete input[''];
                    var ids = [...new Set(target.map(e => e.dataset.vinputId ?? ''))].join('|');
                    var values = await Promise.all(Object.entries(input).map(([k, children]) => dldtdd(children, ids +'/'+ k.substring(1))));
                    var delimiter2 = '';
                }
                var title = [...new Set(target.map(e => e.dataset.validationTitle ?? ''))].join('|');
                // ラベルを優先する（validationTitle は固定的だが label は html 上で指定されていることもありそっちの方が精度が高い）
                // …がタイトルのないチェックボックスは単一で存在しがち（「同意する」とか）なので特別扱い
                if (title.length === 0 && target.length === 1 && target[0].matches('[type=checkbox]')) {
                    title = Array.from(target[0].labels, (label) => label.textContent).join('|');
                    values = [target[0].checked ? "✓" : ""];
                }
                else {
                    // checkbox,radio の label は「項目のラベル」ではないことが多い
                    var label = [...new Set(target.filter(e => !e.matches('[type=checkbox],[type=radio]')).map(e => Array.from(e.labels).map(l => l.textContent).join('|')))].join('|');
                    title = label || title;
                }
                result.push(`<div class="chmonos-output-row" data-voutput-id="${E(ids)}"><dt>${E(title)}</dt><dd>${values.join(delimiter2)}</dd></div>`);
            }
            return `<dl class="chmonos-output" data-voutput-class="${E(klass)}">${result.join('')}</dl>`;
        };

        return await dldtdd(inputs, "");
    };
}
