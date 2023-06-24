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

    /// phpjs のインポート
    /**/
var array_count_values = this.array_count_values = (function(){
'use strict';

var _typeof = typeof Symbol === "function" && typeof Symbol.iterator === "symbol" ? function (obj) { return typeof obj; } : function (obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; };

module.exports = function array_count_values(array) {
  // eslint-disable-line camelcase
  //  discuss at: http://locutus.io/php/array_count_values/
  // original by: Ates Goral (http://magnetiq.com)
  // improved by: Michael White (http://getsprink.com)
  // improved by: Kevin van Zonneveld (http://kvz.io)
  //    input by: sankai
  //    input by: Shingo
  // bugfixed by: Brett Zamir (http://brett-zamir.me)
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
var array_flip = this.array_flip = (function(){
"use strict";

module.exports = function array_flip(trans) {
  // eslint-disable-line camelcase
  //  discuss at: http://locutus.io/php/array_flip/
  // original by: Kevin van Zonneveld (http://kvz.io)
  // improved by: Pier Paolo Ramon (http://www.mastersoup.com/)
  // improved by: Brett Zamir (http://brett-zamir.me)
  //   example 1: array_flip( {a: 1, b: 1, c: 2} )
  //   returns 1: {1: 'b', 2: 'c'}

  var key;
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
var array_intersect_key = this.array_intersect_key = (function(){
'use strict';

module.exports = function array_intersect_key(arr1) {
  // eslint-disable-line camelcase
  //  discuss at: http://locutus.io/php/array_intersect_key/
  // original by: Brett Zamir (http://brett-zamir.me)
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
    // eslint-disable-line no-labels
    if (!arr1.hasOwnProperty(k1)) {
      continue;
    }
    arrs: for (i = 1; i < argl; i++) {
      // eslint-disable-line no-labels
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
var array_keys = this.array_keys = (function(){
'use strict';

module.exports = function array_keys(input, searchValue, argStrict) {
  // eslint-disable-line camelcase
  //  discuss at: http://locutus.io/php/array_keys/
  // original by: Kevin van Zonneveld (http://kvz.io)
  //    input by: Brett Zamir (http://brett-zamir.me)
  //    input by: P
  // bugfixed by: Kevin van Zonneveld (http://kvz.io)
  // bugfixed by: Brett Zamir (http://brett-zamir.me)
  // improved by: jd
  // improved by: Brett Zamir (http://brett-zamir.me)
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
var array_reduce = this.array_reduce = (function(){
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
var count = this.count = (function(){
'use strict';

module.exports = function count(mixedVar, mode) {
  //  discuss at: http://locutus.io/php/count/
  // original by: Kevin van Zonneveld (http://kvz.io)
  //    input by: Waldo Malqui Silva (http://waldo.malqui.info)
  //    input by: merabi
  // bugfixed by: Soren Hansen
  // bugfixed by: Olivier Louvignes (http://mg-crea.com/)
  // improved by: Brett Zamir (http://brett-zamir.me)
  //   example 1: count([[0,0],[0,-4]], 'COUNT_RECURSIVE')
  //   returns 1: 6
  //   example 2: count({'one' : [1,2,3,4,5]}, 'COUNT_RECURSIVE')
  //   returns 2: 6

  var key;
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
var end = this.end = (function(){
'use strict';

module.exports = function end(arr) {
  //  discuss at: http://locutus.io/php/end/
  // original by: Kevin van Zonneveld (http://kvz.io)
  // bugfixed by: Legaev Andrey
  //  revised by: J A R
  //  revised by: Brett Zamir (http://brett-zamir.me)
  // improved by: Kevin van Zonneveld (http://kvz.io)
  // improved by: Kevin van Zonneveld (http://kvz.io)
  //      note 1: Uses global: locutus to store the array pointer
  //   example 1: end({0: 'Kevin', 1: 'van', 2: 'Zonneveld'})
  //   returns 1: 'Zonneveld'
  //   example 2: end(['Kevin', 'van', 'Zonneveld'])
  //   returns 2: 'Zonneveld'

  var $global = typeof window !== 'undefined' ? window : global;
  $global.$locutus = $global.$locutus || {};
  var $locutus = $global.$locutus;
  $locutus.php = $locutus.php || {};
  $locutus.php.pointers = $locutus.php.pointers || [];
  var pointers = $locutus.php.pointers;

  var indexOf = function indexOf(value) {
    for (var i = 0, length = this.length; i < length; i++) {
      if (this[i] === value) {
        return i;
      }
    }
    return -1;
  };

  if (!pointers.indexOf) {
    pointers.indexOf = indexOf;
  }
  if (pointers.indexOf(arr) === -1) {
    pointers.push(arr, 0);
  }
  var arrpos = pointers.indexOf(arr);
  if (Object.prototype.toString.call(arr) !== '[object Array]') {
    var ct = 0;
    var val;
    for (var k in arr) {
      ct++;
      val = arr[k];
    }
    if (ct === 0) {
      // Empty
      return false;
    }
    pointers[arrpos + 1] = ct - 1;
    return val;
  }
  if (arr.length === 0) {
    return false;
  }
  pointers[arrpos + 1] = arr.length - 1;
  return arr[pointers[arrpos + 1]];
};
return module.exports;
})();
/**/
var in_array = this.in_array = (function(){
'use strict';

module.exports = function in_array(needle, haystack, argStrict) {
  // eslint-disable-line camelcase
  //  discuss at: http://locutus.io/php/in_array/
  // original by: Kevin van Zonneveld (http://kvz.io)
  // improved by: vlado houba
  // improved by: Jonas Sciangula Street (Joni2Back)
  //    input by: Billy
  // bugfixed by: Brett Zamir (http://brett-zamir.me)
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
      if (haystack[key] == needle) {
        // eslint-disable-line eqeqeq
        return true;
      }
    }
  }

  return false;
};
return module.exports;
})();
/**/
var key = this.key = (function(){
'use strict';

module.exports = function key(arr) {
  //  discuss at: http://locutus.io/php/key/
  // original by: Brett Zamir (http://brett-zamir.me)
  //    input by: Riddler (http://www.frontierwebdev.com/)
  // bugfixed by: Brett Zamir (http://brett-zamir.me)
  //      note 1: Uses global: locutus to store the array pointer
  //   example 1: var $array = {fruit1: 'apple', 'fruit2': 'orange'}
  //   example 1: key($array)
  //   returns 1: 'fruit1'

  var $global = typeof window !== 'undefined' ? window : global;
  $global.$locutus = $global.$locutus || {};
  var $locutus = $global.$locutus;
  $locutus.php = $locutus.php || {};
  $locutus.php.pointers = $locutus.php.pointers || [];
  var pointers = $locutus.php.pointers;

  var indexOf = function indexOf(value) {
    for (var i = 0, length = this.length; i < length; i++) {
      if (this[i] === value) {
        return i;
      }
    }
    return -1;
  };

  if (!pointers.indexOf) {
    pointers.indexOf = indexOf;
  }

  if (pointers.indexOf(arr) === -1) {
    pointers.push(arr, 0);
  }
  var cursor = pointers[pointers.indexOf(arr) + 1];
  if (Object.prototype.toString.call(arr) !== '[object Array]') {
    var ct = 0;
    for (var k in arr) {
      if (ct === cursor) {
        return k;
      }
      ct++;
    }
    // Empty
    return false;
  }
  if (arr.length === 0) {
    return false;
  }

  return cursor;
};
return module.exports;
})();
/**/
var ctype_digit = this.ctype_digit = (function(){
'use strict';

module.exports = function ctype_digit(text) {
  // eslint-disable-line camelcase
  //  discuss at: http://locutus.io/php/ctype_digit/
  // original by: Brett Zamir (http://brett-zamir.me)
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
var setlocale = this.setlocale = (function(){
'use strict';

var _typeof = typeof Symbol === "function" && typeof Symbol.iterator === "symbol" ? function (obj) { return typeof obj; } : function (obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; };

module.exports = function setlocale(category, locale) {
  //  discuss at: http://locutus.io/php/setlocale/
  // original by: Brett Zamir (http://brett-zamir.me)
  // original by: Blues (http://hacks.bluesmoon.info/strftime/strftime.js)
  // original by: YUI Library (http://developer.yahoo.com/yui/docs/YAHOO.util.DateLocale.html)
  //      note 1: Is extensible, but currently only implements locales en,
  //      note 1: en_US, en_GB, en_AU, fr, and fr_CA for LC_TIME only; C for LC_CTYPE;
  //      note 1: C and en for LC_MONETARY/LC_NUMERIC; en for LC_COLLATE
  //      note 1: Uses global: locutus to store locale info
  //      note 1: Consider using http://demo.icu-project.org/icu-bin/locexp as basis for localization (as in i18n_loc_set_default())
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
    for (var i in orig) {
      if (_typeof(orig[i]) === 'object') {
        newObj[i] = _copy(orig[i]);
      } else {
        newObj[i] = orig[i];
      }
    }
    return newObj;
  };

  // Function usable by a ngettext implementation (apparently not an accessible part of setlocale(),
  // but locale-specific) See http://www.gnu.org/software/gettext/manual/gettext.html#Plural-forms
  // though amended with others from https://developer.mozilla.org/En/Localization_and_Plurals (new
  // categories noted with "MDC" below, though not sure of whether there is a convention for the
  // relative order of these newer groups as far as ngettext) The function name indicates the number
  // of plural forms (nplural) Need to look into http://cldr.unicode.org/ (maybe future JavaScript);
  // Dojo has some functions (under new BSD), including JSON conversions of LDML XML from CLDR:
  // http://bugs.dojotoolkit.org/browser/dojo/trunk/cldr and docs at
  // http://api.dojotoolkit.org/jsdoc/HEAD/dojo.cldr

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
      'LC_COLLATE': function LC_COLLATE(str1, str2) {
        // @todo: This one taken from strcmp, but need for other locales; we don't use localeCompare
        // since its locale is not settable
        return str1 === str2 ? 0 : str1 > str2 ? 1 : -1;
      },
      'LC_CTYPE': {
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
      'LC_TIME': {
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
        // Following are from nl_langinfo() or http://www.cptec.inpe.br/sx4/sx4man2/g1ab02e/strftime.4.html
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
      'LC_MONETARY': {
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
      'LC_NUMERIC': {
        // based on Windows "english" (English_United States.1252) locale
        decimal_point: '.',
        thousands_sep: ',',
        grouping: [3] // see mon_grouping, but for non-monetary values (use thousands_sep)
      },
      'LC_MESSAGES': {
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
    };
    // D_T_FMT
    $locutus.php.locales.C.LC_TIME.c = '%a %b %e %H:%M:%S %Y';
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
      var NS_XHTML = 'http://www.w3.org/1999/xhtml';
      var NS_XML = 'http://www.w3.org/XML/1998/namespace';
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
      'LC_COLLATE': $locutus.php.locale,
      // for string comparison, see strcoll()
      'LC_CTYPE': $locutus.php.locale,
      // for character classification and conversion, for example strtoupper()
      'LC_MONETARY': $locutus.php.locale,
      // for localeconv()
      'LC_NUMERIC': $locutus.php.locale,
      // for decimal separator (See also localeconv())
      'LC_TIME': $locutus.php.locale,
      // for date and time formatting with strftime()
      // for system responses (available if PHP was compiled with libintl):
      'LC_MESSAGES': $locutus.php.locale
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
var date = this.date = (function(){
'use strict';

module.exports = function date(format, timestamp) {
  //  discuss at: http://locutus.io/php/date/
  // original by: Carlos R. L. Rodrigues (http://www.jsfromhell.com)
  // original by: gettimeofday
  //    parts by: Peter-Paul Koch (http://www.quirksmode.org/js/beat.html)
  // improved by: Kevin van Zonneveld (http://kvz.io)
  // improved by: MeEtc (http://yass.meetcweb.com)
  // improved by: Brad Touesnard
  // improved by: Tim Wiel
  // improved by: Bryan Elliott
  // improved by: David Randall
  // improved by: Theriault (https://github.com/Theriault)
  // improved by: Theriault (https://github.com/Theriault)
  // improved by: Brett Zamir (http://brett-zamir.me)
  // improved by: Theriault (https://github.com/Theriault)
  // improved by: Thomas Beaucourt (http://www.webapp.fr)
  // improved by: JT
  // improved by: Theriault (https://github.com/Theriault)
  // improved by: Rafał Kukawski (http://blog.kukawski.pl)
  // improved by: Theriault (https://github.com/Theriault)
  //    input by: Brett Zamir (http://brett-zamir.me)
  //    input by: majak
  //    input by: Alex
  //    input by: Martin
  //    input by: Alex Wilson
  //    input by: Haravikk
  // bugfixed by: Kevin van Zonneveld (http://kvz.io)
  // bugfixed by: majak
  // bugfixed by: Kevin van Zonneveld (http://kvz.io)
  // bugfixed by: Brett Zamir (http://brett-zamir.me)
  // bugfixed by: omid (http://locutus.io/php/380:380#comment_137122)
  // bugfixed by: Chris (http://www.devotis.nl/)
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

  var jsdate, f;
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
    : new Date(timestamp * 1000) // UNIX timestamp (auto-convert to int)
    ;
    return format.replace(formatChr, formatChrCb);
  };

  return _date(format, timestamp);
};
return module.exports;
})();
/**/
var idate = this.idate = (function(){
'use strict';

module.exports = function idate(format, timestamp) {
  //  discuss at: http://locutus.io/php/idate/
  // original by: Brett Zamir (http://brett-zamir.me)
  // original by: date
  // original by: gettimeofday
  //    input by: Alex
  // bugfixed by: Brett Zamir (http://brett-zamir.me)
  // improved by: Theriault (https://github.com/Theriault)
  //   example 1: idate('y', 1255633200)
  //   returns 1: 9

  if (format === undefined) {
    throw new Error('idate() expects at least 1 parameter, 0 given');
  }
  if (!format.length || format.length > 1) {
    throw new Error('idate format is one char');
  }

  // @todo: Need to allow date_default_timezone_set() (check for $locutus.default_timezone and use)
  var _date = typeof timestamp === 'undefined' ? new Date() : timestamp instanceof Date ? new Date(timestamp) : new Date(timestamp * 1000);
  var a;

  switch (format) {
    case 'B':
      return Math.floor((_date.getUTCHours() * 36e2 + _date.getUTCMinutes() * 60 + _date.getUTCSeconds() + 36e2) / 86.4) % 1e3;
    case 'd':
      return _date.getDate();
    case 'h':
      return _date.getHours() % 12 || 12;
    case 'H':
      return _date.getHours();
    case 'i':
      return _date.getMinutes();
    case 'I':
      // capital 'i'
      // Logic original by getimeofday().
      // Compares Jan 1 minus Jan 1 UTC to Jul 1 minus Jul 1 UTC.
      // If they are not equal, then DST is observed.
      a = _date.getFullYear();
      return 0 + (new Date(a, 0) - Date.UTC(a, 0) !== new Date(a, 6) - Date.UTC(a, 6));
    case 'L':
      a = _date.getFullYear();
      return !(a & 3) && (a % 1e2 || !(a % 4e2)) ? 1 : 0;
    case 'm':
      return _date.getMonth() + 1;
    case 's':
      return _date.getSeconds();
    case 't':
      return new Date(_date.getFullYear(), _date.getMonth() + 1, 0).getDate();
    case 'U':
      return Math.round(_date.getTime() / 1000);
    case 'w':
      return _date.getDay();
    case 'W':
      a = new Date(_date.getFullYear(), _date.getMonth(), _date.getDate() - (_date.getDay() || 7) + 3);
      return 1 + Math.round((a - new Date(a.getFullYear(), 0, 4)) / 864e5 / 7);
    case 'y':
      return parseInt((_date.getFullYear() + '').slice(2), 10); // This function returns an integer, unlike _date()
    case 'Y':
      return _date.getFullYear();
    case 'z':
      return Math.floor((_date - new Date(_date.getFullYear(), 0, 1)) / 864e5);
    case 'Z':
      return -_date.getTimezoneOffset() * 60;
    default:
      throw new Error('Unrecognized _date format token');
  }
};
return module.exports;
})();
/**/
var strtotime = this.strtotime = (function(){
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
var reDayOfYear = '(00[1-9]|0[1-9][0-9]|[12][0-9][0-9]|3[0-5][0-9]|36[0-6])';
var reWeekOfYear = '(0[1-9]|[1-4][0-9]|5[0-3])';

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

  var sign = tzOffset[1] === '-' ? 1 : -1;
  var hours = +tzOffset[2];
  var minutes = +tzOffset[4];

  if (!tzOffset[4] && !tzOffset[3]) {
    minutes = Math.floor(hours % 100);
    hours = Math.floor(hours / 100);
  }

  return sign * (hours * 60 + minutes);
}

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
    regex: RegExp('^t' + reHour24lz + reMinutelz, 'i'),
    name: 'gnunocolon',
    callback: function callback(match, hour, minute) {
      return this.time(+hour, +minute, 0, this.f);
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
    regex: RegExp('^' + reMonthText + '[ .\\t-]*' + reDay + '[,.stndrh\\t ]*', 'i'),
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
        case 'mon':case 'monday':
        case 'tue':case 'tuesday':
        case 'wed':case 'wednesday':
        case 'thu':case 'thursday':
        case 'fri':case 'friday':
        case 'sat':case 'saturday':
        case 'sun':case 'sunday':
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
        case 'mon':case 'monday':
        case 'tue':case 'tuesday':
        case 'wed':case 'wednesday':
        case 'thu':case 'thursday':
        case 'fri':case 'friday':
        case 'sat':case 'saturday':
        case 'sun':case 'sunday':
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

  gnuNoColon2: {
    // second instance of gnunocolon, without leading 't'
    // it's down here, because it is very generic (4 digits in a row)
    // thus conflicts with many rules above
    // only year4 should come afterwards
    regex: RegExp('^' + reHour24lz + reMinutelz, 'i'),
    name: 'gnunocolon',
    callback: function callback(match, hour, minute) {
      return this.time(+hour, +minute, 0, this.f);
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

  any: {
    regex: /^[\s\S]+/,
    name: 'any',
    callback: function callback() {
      return false;
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

      result.setUTCHours(result.getHours(), result.getMinutes() + this.z, result.getSeconds(), result.getMilliseconds());
    }

    return result;
  }
};

module.exports = function strtotime(str, now) {
  //       discuss at: http://locutus.io/php/strtotime/
  //      original by: Caio Ariede (http://caioariede.com)
  //      improved by: Kevin van Zonneveld (http://kvz.io)
  //      improved by: Caio Ariede (http://caioariede.com)
  //      improved by: A. Matías Quezada (http://amatiasq.com)
  //      improved by: preuter
  //      improved by: Brett Zamir (http://brett-zamir.me)
  //      improved by: Mirko Faber
  //         input by: David
  //      bugfixed by: Wagner B. Soares
  //      bugfixed by: Artur Tchernychev
  //      bugfixed by: Stephan Bösch-Plepelits (http://github.com/plepe)
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
  if (now == null) {
    now = Math.floor(Date.now() / 1000);
  }

  // the rule order is very fragile
  // as many formats are similar to others
  // so small change can cause
  // input misinterpretation
  var rules = [formats.yesterday, formats.now, formats.noon, formats.midnightOrToday, formats.tomorrow, formats.timestamp, formats.firstOrLastDay, formats.backOrFrontOf,
  // formats.weekdayOf, // not yet implemented
  formats.mssqltime, formats.timeLong12, formats.timeShort12, formats.timeTiny12, formats.soap, formats.wddx, formats.exif, formats.xmlRpc, formats.xmlRpcNoColon, formats.clf, formats.iso8601long, formats.dateTextual, formats.pointedDate4, formats.pointedDate2, formats.timeLong24, formats.dateNoColon, formats.pgydotd, formats.timeShort24, formats.iso8601noColon,
  // iso8601dateSlash needs to come before dateSlash
  formats.iso8601dateSlash, formats.dateSlash, formats.american, formats.americanShort, formats.gnuDateShortOrIso8601date2, formats.iso8601date4, formats.gnuNoColon, formats.gnuDateShorter, formats.pgTextReverse, formats.dateFull, formats.dateNoDay, formats.dateNoDayRev, formats.pgTextShort, formats.dateNoYear, formats.dateNoYearRev, formats.isoWeekDay, formats.relativeText, formats.relative, formats.dayText, formats.relativeTextWeek, formats.monthFullOrMonthAbbr, formats.tzCorrection, formats.ago, formats.gnuNoColon2, formats.year4,
  // note: the two rules below
  // should always come last
  formats.whitespace, formats.any];

  var result = Object.create(resultProto);

  while (str.length) {
    for (var i = 0, l = rules.length; i < l; i++) {
      var format = rules[i];

      var match = str.match(format.regex);

      if (match) {
        // care only about false results. Ignore other values
        if (format.callback && format.callback.apply(result, match) === false) {
          return false;
        }

        str = str.substr(match[0].length);
        break;
      }
    }
  }

  return Math.floor(result.toDate(new Date(now * 1000)) / 1000);
};
return module.exports;
})();
/**/
var time = this.time = (function(){
"use strict";

module.exports = function time() {
  //  discuss at: http://locutus.io/php/time/
  // original by: GeekFG (http://geekfg.blogspot.com)
  // improved by: Kevin van Zonneveld (http://kvz.io)
  // improved by: metjay
  // improved by: HKM
  //   example 1: var $timeStamp = time()
  //   example 1: var $result = $timeStamp > 1000000000 && $timeStamp < 2000000000
  //   returns 1: true

  return Math.floor(new Date().getTime() / 1000);
};
return module.exports;
})();
/**/
var pathinfo = this.pathinfo = (function(){
'use strict';

module.exports = function pathinfo(path, options) {
  //  discuss at: http://locutus.io/php/pathinfo/
  // original by: Nate
  //  revised by: Kevin van Zonneveld (http://kvz.io)
  // improved by: Brett Zamir (http://brett-zamir.me)
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
    'PATHINFO_DIRNAME': 1,
    'PATHINFO_BASENAME': 2,
    'PATHINFO_EXTENSION': 4,
    'PATHINFO_FILENAME': 8,
    'PATHINFO_ALL': 0
  };
  // PATHINFO_ALL sums up all previously defined PATHINFOs (could just pre-calculate)
  for (optName in OPTS) {
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
var basename = this.basename = (function(){
'use strict';

module.exports = function basename(path, suffix) {
  //  discuss at: http://locutus.io/php/basename/
  // original by: Kevin van Zonneveld (http://kvz.io)
  // improved by: Ash Searle (http://hexmen.com/blog/)
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
var json_decode = this.json_decode = (function(){
'use strict';

var _typeof = typeof Symbol === "function" && typeof Symbol.iterator === "symbol" ? function (obj) { return typeof obj; } : function (obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; };

module.exports = function json_decode(strJson) {
  // eslint-disable-line camelcase
  //       discuss at: http://phpjs.org/functions/json_decode/
  //      original by: Public Domain (http://www.json.org/json2.js)
  // reimplemented by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  //      improved by: T.J. Leahy
  //      improved by: Michael White
  //           note 1: If node or the browser does not offer JSON.parse,
  //           note 1: this function falls backslash
  //           note 1: to its own implementation using eval, and hence should be considered unsafe
  //        example 1: json_decode('[ 1 ]')
  //        returns 1: [1]

  /*
    http://www.JSON.org/json2.js
    2008-11-19
    Public Domain.
    NO WARRANTY EXPRESSED OR IMPLIED. USE AT YOUR OWN RISK.
    See http://www.JSON.org/js.html
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
  var j;
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
var abs = this.abs = (function(){
"use strict";

module.exports = function abs(mixedNumber) {
  //  discuss at: http://locutus.io/php/abs/
  // original by: Waldo Malqui Silva (http://waldo.malqui.info)
  // improved by: Karol Kowalski
  // improved by: Kevin van Zonneveld (http://kvz.io)
  // improved by: Jonas Raoni Soares Silva (http://www.jsfromhell.com)
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
var log = this.log = (function(){
'use strict';

module.exports = function log(arg, base) {
  //  discuss at: http://locutus.io/php/log/
  // original by: Onno Marsman (https://twitter.com/onnomarsman)
  // improved by: Brett Zamir (http://brett-zamir.me)
  //   example 1: log(8723321.4, 7)
  //   returns 1: 8.212871815082147

  return typeof base === 'undefined' ? Math.log(arg) : Math.log(arg) / Math.log(base);
};
return module.exports;
})();
/**/
var pow = this.pow = (function(){
"use strict";

module.exports = function pow(base, exp) {
  //  discuss at: http://locutus.io/php/pow/
  // original by: Onno Marsman (https://twitter.com/onnomarsman)
  // improved by: Waldo Malqui Silva (https://fayr.us/waldo/)
  //   example 1: pow(8723321.4, 7)
  //   returns 1: 3.8439091680779e+48

  return Number(Math.pow(base, exp).toPrecision(15));
};
return module.exports;
})();
/**/
var round = this.round = (function(){
'use strict';

module.exports = function round(value, precision, mode) {
  //  discuss at: http://locutus.io/php/round/
  // original by: Philip Peterson
  //  revised by: Onno Marsman (https://twitter.com/onnomarsman)
  //  revised by: T.Wild
  //  revised by: Rafał Kukawski (http://blog.kukawski.pl)
  //    input by: Greenseed
  //    input by: meo
  //    input by: William
  //    input by: Josep Sanz (http://www.ws3.es/)
  // bugfixed by: Brett Zamir (http://brett-zamir.me)
  //      note 1: Great work. Ideas for improvement:
  //      note 1: - code more compliant with developer guidelines
  //      note 1: - for implementing PHP constant arguments look at
  //      note 1: the pathinfo() function, it offers the greatest
  //      note 1: flexibility & compatibility possible
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

  var m, f, isHalf, sgn; // helper variables
  // making sure precision is integer
  precision |= 0;
  m = Math.pow(10, precision);
  value *= m;
  // sign of the number
  sgn = value > 0 | -(value < 0);
  isHalf = value % 1 === 0.5 * sgn;
  f = Math.floor(value);

  if (isHalf) {
    switch (mode) {
      case 'PHP_ROUND_HALF_DOWN':
        // rounds .5 toward zero
        value = f + (sgn < 0);
        break;
      case 'PHP_ROUND_HALF_EVEN':
        // rouds .5 towards the next even integer
        value = f + f % 2 * sgn;
        break;
      case 'PHP_ROUND_HALF_ODD':
        // rounds .5 towards the next odd integer
        value = f + !(f % 2);
        break;
      default:
        // rounds .5 away from zero
        value = f + (sgn > 0);
    }
  }

  return (isHalf ? value : Math.round(value)) / m;
};
return module.exports;
})();
/**/
var preg_match = this.preg_match = (function(){
/**
 * preg_match
 *
 * 引数4つ以上は未対応。
 */
module.exports = function preg_match(pattern, subject, matches) {
    // 引数4つ以上は未対応
    if (arguments.length >= 4) {
        throw 'arguments is too long.';
    }
    // match が指定されたらそれは Array でなければならない
    if (arguments.length >= 3 && !(matches instanceof Array)) {
        throw 'matches is not array.';
    }

    // 表現とフラグをセパレート
    var meta = pattern.charAt(0);
    var exp = new RegExp(meta + '(.*)' + meta + '([im]*)');
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
            matches.push(match[i]);
        }
    }

    return 1;
};
return module.exports;
})();
/**/
var join = this.join = (function(){
'use strict';

module.exports = function join(glue, pieces) {
  //  discuss at: http://locutus.io/php/join/
  // original by: Kevin van Zonneveld (http://kvz.io)
  //   example 1: join(' ', ['Kevin', 'van', 'Zonneveld'])
  //   returns 1: 'Kevin van Zonneveld'

  var implode = require('../strings/implode');
  return implode(glue, pieces);
};
return module.exports;
})();
/**/
var implode = this.implode = (function(){
'use strict';

var _typeof = typeof Symbol === "function" && typeof Symbol.iterator === "symbol" ? function (obj) { return typeof obj; } : function (obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; };

module.exports = function implode(glue, pieces) {
  //  discuss at: http://locutus.io/php/implode/
  // original by: Kevin van Zonneveld (http://kvz.io)
  // improved by: Waldo Malqui Silva (http://waldo.malqui.info)
  // improved by: Itsacon (http://www.itsacon.net/)
  // bugfixed by: Brett Zamir (http://brett-zamir.me)
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
var ltrim = this.ltrim = (function(){
'use strict';

module.exports = function ltrim(str, charlist) {
  //  discuss at: http://locutus.io/php/ltrim/
  // original by: Kevin van Zonneveld (http://kvz.io)
  //    input by: Erkekjetter
  // improved by: Kevin van Zonneveld (http://kvz.io)
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
var parse_str = this.parse_str = (function(){
'use strict';

module.exports = function parse_str(str, array) {
  // eslint-disable-line camelcase
  //       discuss at: http://locutus.io/php/parse_str/
  //      original by: Cagri Ekin
  //      improved by: Michael White (http://getsprink.com)
  //      improved by: Jack
  //      improved by: Brett Zamir (http://brett-zamir.me)
  //      bugfixed by: Onno Marsman (https://twitter.com/onnomarsman)
  //      bugfixed by: Brett Zamir (http://brett-zamir.me)
  //      bugfixed by: stag019
  //      bugfixed by: Brett Zamir (http://brett-zamir.me)
  //      bugfixed by: MIO_KODUKI (http://mio-koduki.blogspot.com/)
  // reimplemented by: stag019
  //         input by: Dreamer
  //         input by: Zaide (http://zaidesthings.com/)
  //         input by: David Pesta (http://davidpesta.com/)
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
  var i;
  var j;
  var ct;
  var p;
  var lastObj;
  var obj;
  var chr;
  var tmp;
  var key;
  var value;
  var postLeftBracketPos;
  var keys;
  var keysLen;

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
var printf = this.printf = (function(){
'use strict';

module.exports = function printf() {
  //  discuss at: http://locutus.io/php/printf/
  // original by: Ash Searle (http://hexmen.com/blog/)
  // improved by: Michael White (http://getsprink.com)
  // improved by: Brett Zamir (http://brett-zamir.me)
  //   example 1: printf("%01.2f", 123.1)
  //   returns 1: 6

  var sprintf = require('../strings/sprintf');
  var echo = require('../strings/echo');
  var ret = sprintf.apply(this, arguments);
  echo(ret);
  return ret.length;
};
return module.exports;
})();
/**/
var sprintf = this.sprintf = (function(){
'use strict';

module.exports = function sprintf() {
  //  discuss at: http://locutus.io/php/sprintf/
  // original by: Ash Searle (http://hexmen.com/blog/)
  // improved by: Michael White (http://getsprink.com)
  // improved by: Jack
  // improved by: Kevin van Zonneveld (http://kvz.io)
  // improved by: Kevin van Zonneveld (http://kvz.io)
  // improved by: Kevin van Zonneveld (http://kvz.io)
  // improved by: Dj
  // improved by: Allidylls
  //    input by: Paulo Freitas
  //    input by: Brett Zamir (http://brett-zamir.me)
  // improved by: Rafał Kukawski (http://kukawski.pl)
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
    var number, prefix, method, textTransform, value;

    if (substring === '%%') {
      return '%';
    }

    // parse modifiers
    var padChar = ' '; // pad with spaces by default
    var leftJustify = false;
    var positiveNumberPrefix = '';
    var j, l;

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
var getenv = this.getenv = (function(){
'use strict';

module.exports = function getenv(varname) {
  //  discuss at: http://locutus.io/php/getenv/
  // original by: Brett Zamir (http://brett-zamir.me)
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
var split = this.split = (function(){
'use strict';

module.exports = function split(delimiter, string) {
  //  discuss at: http://locutus.io/php/split/
  // original by: Kevin van Zonneveld (http://kvz.io)
  //   example 1: split(' ', 'Kevin van Zonneveld')
  //   returns 1: ['Kevin', 'van', 'Zonneveld']

  var explode = require('../strings/explode');
  return explode(delimiter, string);
};
return module.exports;
})();
/**/
var explode = this.explode = (function(){
'use strict';

var _typeof = typeof Symbol === "function" && typeof Symbol.iterator === "symbol" ? function (obj) { return typeof obj; } : function (obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; };

module.exports = function explode(delimiter, string, limit) {
  //  discuss at: http://locutus.io/php/explode/
  // original by: Kevin van Zonneveld (http://kvz.io)
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
var str_split = this.str_split = (function(){
'use strict';

module.exports = function str_split(string, splitLength) {
  // eslint-disable-line camelcase
  //  discuss at: http://locutus.io/php/str_split/
  // original by: Martijn Wieringa
  // improved by: Brett Zamir (http://brett-zamir.me)
  // bugfixed by: Onno Marsman (https://twitter.com/onnomarsman)
  //  revised by: Theriault (https://github.com/Theriault)
  //  revised by: Rafał Kukawski (http://blog.kukawski.pl)
  //    input by: Bjorn Roesbeke (http://www.bjornroesbeke.be/)
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
var strlen = this.strlen = (function(){
'use strict';

module.exports = function strlen(string) {
  //  discuss at: http://locutus.io/php/strlen/
  // original by: Kevin van Zonneveld (http://kvz.io)
  // improved by: Sakimori
  // improved by: Kevin van Zonneveld (http://kvz.io)
  //    input by: Kirk Strobeck
  // bugfixed by: Onno Marsman (https://twitter.com/onnomarsman)
  //  revised by: Brett Zamir (http://brett-zamir.me)
  //      note 1: May look like overkill, but in order to be truly faithful to handling all Unicode
  //      note 1: characters and to this function in PHP which does not count the number of bytes
  //      note 1: but counts the number of characters, something like this is really necessary.
  //   example 1: strlen('Kevin van Zonneveld')
  //   returns 1: 19
  //   example 2: ini_set('unicode.semantics', 'on')
  //   example 2: strlen('A\ud87e\udc04Z')
  //   returns 2: 3

  var str = string + '';

  var iniVal = (typeof require !== 'undefined' ? require('../info/ini_get')('unicode.semantics') : undefined) || 'off';
  if (iniVal === 'off') {
    return str.length;
  }

  var i = 0;
  var lgth = 0;

  var getWholeChar = function getWholeChar(str, i) {
    var code = str.charCodeAt(i);
    var next = '';
    var prev = '';
    if (code >= 0xD800 && code <= 0xDBFF) {
      // High surrogate (could change last hex to 0xDB7F to
      // treat high private surrogates as single characters)
      if (str.length <= i + 1) {
        throw new Error('High surrogate without following low surrogate');
      }
      next = str.charCodeAt(i + 1);
      if (next < 0xDC00 || next > 0xDFFF) {
        throw new Error('High surrogate without following low surrogate');
      }
      return str.charAt(i) + str.charAt(i + 1);
    } else if (code >= 0xDC00 && code <= 0xDFFF) {
      // Low surrogate
      if (i === 0) {
        throw new Error('Low surrogate without preceding high surrogate');
      }
      prev = str.charCodeAt(i - 1);
      if (prev < 0xD800 || prev > 0xDBFF) {
        // (could change last hex to 0xDB7F to treat high private surrogates
        // as single characters)
        throw new Error('Low surrogate without preceding high surrogate');
      }
      // We can pass over low surrogates now as the second
      // component in a pair which we have already processed
      return false;
    }
    return str.charAt(i);
  };

  for (i = 0, lgth = 0; i < str.length; i++) {
    if (getWholeChar(str, i) === false) {
      continue;
    }
    // Adapt this line at the top of any loop, passing in the whole string and
    // the current iteration and returning a variable to represent the individual character;
    // purpose is to treat the first part of a surrogate pair as the whole character and then
    // ignore the second part
    lgth++;
  }

  return lgth;
};
return module.exports;
})();
/**/
var ini_get = this.ini_get = (function(){
'use strict';

module.exports = function ini_get(varname) {
  // eslint-disable-line camelcase
  //  discuss at: http://locutus.io/php/ini_get/
  // original by: Brett Zamir (http://brett-zamir.me)
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
var strtolower = this.strtolower = (function(){
'use strict';

module.exports = function strtolower(str) {
  //  discuss at: http://locutus.io/php/strtolower/
  // original by: Kevin van Zonneveld (http://kvz.io)
  // improved by: Onno Marsman (https://twitter.com/onnomarsman)
  //   example 1: strtolower('Kevin van Zonneveld')
  //   returns 1: 'kevin van zonneveld'

  return (str + '').toLowerCase();
};
return module.exports;
})();
/**/
var strtoupper = this.strtoupper = (function(){
'use strict';

module.exports = function strtoupper(str) {
  //  discuss at: http://locutus.io/php/strtoupper/
  // original by: Kevin van Zonneveld (http://kvz.io)
  // improved by: Onno Marsman (https://twitter.com/onnomarsman)
  //   example 1: strtoupper('Kevin van Zonneveld')
  //   returns 1: 'KEVIN VAN ZONNEVELD'

  return (str + '').toUpperCase();
};
return module.exports;
})();
/**/
var substr = this.substr = (function(){
'use strict';

module.exports = function substr(str, start, len) {
  //  discuss at: http://locutus.io/php/substr/
  // original by: Martijn Wieringa
  // bugfixed by: T.Wild
  // improved by: Onno Marsman (https://twitter.com/onnomarsman)
  // improved by: Brett Zamir (http://brett-zamir.me)
  //  revised by: Theriault (https://github.com/Theriault)
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

  str += '';
  var end = str.length;

  var iniVal = (typeof require !== 'undefined' ? require('../info/ini_get')('unicode.emantics') : undefined) || 'off';

  if (iniVal === 'off') {
    // assumes there are no non-BMP characters;
    // if there may be such characters, then it is best to turn it on (critical in true XHTML/XML)
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

    // PHP returns false if start does not fall within the string.
    // PHP returns false if the calculated end comes before the calculated start.
    // PHP returns an empty string if start and end are the same.
    // Otherwise, PHP returns the portion of the string from start to end.
    if (start >= str.length || start < 0 || start > end) {
      return false;
    }

    return str.slice(start, end);
  }

  // Full-blown Unicode including non-Basic-Multilingual-Plane characters
  var i = 0;
  var allBMP = true;
  var es = 0;
  var el = 0;
  var se = 0;
  var ret = '';

  for (i = 0; i < str.length; i++) {
    if (/[\uD800-\uDBFF]/.test(str.charAt(i)) && /[\uDC00-\uDFFF]/.test(str.charAt(i + 1))) {
      allBMP = false;
      break;
    }
  }

  if (!allBMP) {
    if (start < 0) {
      for (i = end - 1, es = start += end; i >= es; i--) {
        if (/[\uDC00-\uDFFF]/.test(str.charAt(i)) && /[\uD800-\uDBFF]/.test(str.charAt(i - 1))) {
          start--;
          es--;
        }
      }
    } else {
      var surrogatePairs = /[\uD800-\uDBFF][\uDC00-\uDFFF]/g;
      while (surrogatePairs.exec(str) !== null) {
        var li = surrogatePairs.lastIndex;
        if (li - 2 < start) {
          start++;
        } else {
          break;
        }
      }
    }

    if (start >= end || start < 0) {
      return false;
    }
    if (len < 0) {
      for (i = end - 1, el = end += len; i >= el; i--) {
        if (/[\uDC00-\uDFFF]/.test(str.charAt(i)) && /[\uD800-\uDBFF]/.test(str.charAt(i - 1))) {
          end--;
          el--;
        }
      }
      if (start > end) {
        return false;
      }
      return str.slice(start, end);
    } else {
      se = start + len;
      for (i = start; i < se; i++) {
        ret += str.charAt(i);
        if (/[\uD800-\uDBFF]/.test(str.charAt(i)) && /[\uDC00-\uDFFF]/.test(str.charAt(i + 1))) {
          // Go one further, since one of the "characters" is part of a surrogate pair
          se++;
        }
      }
      return ret;
    }
  }
};
return module.exports;
})();
/**/
var trim = this.trim = (function(){
'use strict';

module.exports = function trim(str, charlist) {
  //  discuss at: http://locutus.io/php/trim/
  // original by: Kevin van Zonneveld (http://kvz.io)
  // improved by: mdsjack (http://www.mdsjack.bo.it)
  // improved by: Alexander Ermolaev (http://snippets.dzone.com/user/AlexanderErmolaev)
  // improved by: Kevin van Zonneveld (http://kvz.io)
  // improved by: Steven Levithan (http://blog.stevenlevithan.com)
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
var vsprintf = this.vsprintf = (function(){
'use strict';

module.exports = function vsprintf(format, args) {
  //  discuss at: http://locutus.io/php/vsprintf/
  // original by: ejsanders
  //   example 1: vsprintf('%04d-%02d-%02d', [1988, 8, 1])
  //   returns 1: '1988-08-01'

  var sprintf = require('../strings/sprintf');

  return sprintf.apply(this, [format].concat(args));
};
return module.exports;
})();
/**/
var base64_decode = this.base64_decode = (function(){
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
var parse_url = this.parse_url = (function(){
'use strict';

module.exports = function parse_url(str, component) {
  // eslint-disable-line camelcase
  //       discuss at: http://locutus.io/php/parse_url/
  //      original by: Steven Levithan (http://blog.stevenlevithan.com)
  // reimplemented by: Brett Zamir (http://brett-zamir.me)
  //         input by: Lorenzo Pisani
  //         input by: Tony
  //      improved by: Brett Zamir (http://brett-zamir.me)
  //           note 1: original by http://stevenlevithan.com/demo/parseuri/js/assets/parseuri.js
  //           note 1: blog post at http://blog.stevenlevithan.com/archives/parseuri
  //           note 1: demo at http://stevenlevithan.com/demo/parseuri/js/assets/parseuri.js
  //           note 1: Does not replace invalid characters with '_' as in PHP,
  //           note 1: nor does it return false with
  //           note 1: a seriously malformed URL.
  //           note 1: Besides function name, is essentially the same as parseUri as
  //           note 1: well as our allowing
  //           note 1: an extra slash after the scheme/protocol (to allow file:/// as in PHP)
  //        example 1: parse_url('http://user:pass@host/path?a=v#a')
  //        returns 1: {scheme: 'http', host: 'host', user: 'user', pass: 'pass', path: '/path', query: 'a=v', fragment: 'a'}
  //        example 2: parse_url('http://en.wikipedia.org/wiki/%22@%22_%28album%29')
  //        returns 2: {scheme: 'http', host: 'en.wikipedia.org', path: '/wiki/%22@%22_%28album%29'}
  //        example 3: parse_url('https://host.domain.tld/a@b.c/folder')
  //        returns 3: {scheme: 'https', host: 'host.domain.tld', path: '/a@b.c/folder'}
  //        example 4: parse_url('https://gooduser:secretpassword@www.example.com/a@b.c/folder?foo=bar')
  //        returns 4: { scheme: 'https', host: 'www.example.com', path: '/a@b.c/folder', query: 'foo=bar', user: 'gooduser', pass: 'secretpassword' }

  var query;

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
var is_array = this.is_array = (function(){
'use strict';

var _typeof = typeof Symbol === "function" && typeof Symbol.iterator === "symbol" ? function (obj) { return typeof obj; } : function (obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; };

module.exports = function is_array(mixedVar) {
  // eslint-disable-line camelcase
  //  discuss at: http://locutus.io/php/is_array/
  // original by: Kevin van Zonneveld (http://kvz.io)
  // improved by: Legaev Andrey
  // improved by: Onno Marsman (https://twitter.com/onnomarsman)
  // improved by: Brett Zamir (http://brett-zamir.me)
  // improved by: Nathan Sepulveda
  // improved by: Brett Zamir (http://brett-zamir.me)
  // bugfixed by: Cord
  // bugfixed by: Manish
  // bugfixed by: Brett Zamir (http://brett-zamir.me)
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
var is_float = this.is_float = (function(){
"use strict";

module.exports = function is_float(mixedVar) {
  // eslint-disable-line camelcase
  //  discuss at: http://locutus.io/php/is_float/
  // original by: Paulo Freitas
  // bugfixed by: Brett Zamir (http://brett-zamir.me)
  // improved by: WebDevHobo (http://webdevhobo.blogspot.com/)
  // improved by: Rafał Kukawski (http://blog.kukawski.pl)
  //      note 1: 1.0 is simplified to 1 before it can be accessed by the function, this makes
  //      note 1: it different from the PHP implementation. We can't fix this unfortunately.
  //   example 1: is_float(186.31)
  //   returns 1: true

  return +mixedVar === mixedVar && (!isFinite(mixedVar) || !!(mixedVar % 1));
};
return module.exports;
})();
/**/
var is_int = this.is_int = (function(){
"use strict";

module.exports = function is_int(mixedVar) {
  // eslint-disable-line camelcase
  //  discuss at: http://locutus.io/php/is_int/
  // original by: Alex
  // improved by: Kevin van Zonneveld (http://kvz.io)
  // improved by: WebDevHobo (http://webdevhobo.blogspot.com/)
  // improved by: Rafał Kukawski (http://blog.kukawski.pl)
  //  revised by: Matt Bradley
  // bugfixed by: Kevin van Zonneveld (http://kvz.io)
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
var is_null = this.is_null = (function(){
"use strict";

module.exports = function is_null(mixedVar) {
  // eslint-disable-line camelcase
  //  discuss at: http://locutus.io/php/is_null/
  // original by: Kevin van Zonneveld (http://kvz.io)
  //   example 1: is_null('23')
  //   returns 1: false
  //   example 2: is_null(null)
  //   returns 2: true

  return mixedVar === null;
};
return module.exports;
})();
/**/
var is_string = this.is_string = (function(){
'use strict';

module.exports = function is_string(mixedVar) {
  // eslint-disable-line camelcase
  //  discuss at: http://locutus.io/php/is_string/
  // original by: Kevin van Zonneveld (http://kvz.io)
  //   example 1: is_string('23')
  //   returns 1: true
  //   example 2: is_string(23.5)
  //   returns 2: false

  return typeof mixedVar === 'string';
};
return module.exports;
})();
/**/
var isset = this.isset = (function(){
'use strict';

module.exports = function isset() {
  //  discuss at: http://locutus.io/php/isset/
  // original by: Kevin van Zonneveld (http://kvz.io)
  // improved by: FremyCompany
  // improved by: Onno Marsman (https://twitter.com/onnomarsman)
  // improved by: Rafał Kukawski (http://blog.kukawski.pl)
  //   example 1: isset( undefined, true)
  //   returns 1: false
  //   example 2: isset( 'Kevin van Zonneveld' )
  //   returns 2: true

  var a = arguments;
  var l = a.length;
  var i = 0;
  var undef;

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
var strval = this.strval = (function(){
'use strict';

module.exports = function strval(str) {
  //  discuss at: http://locutus.io/php/strval/
  // original by: Brett Zamir (http://brett-zamir.me)
  // improved by: Kevin van Zonneveld (http://kvz.io)
  // bugfixed by: Brett Zamir (http://brett-zamir.me)
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
var gettype = this.gettype = (function(){
'use strict';

var _typeof = typeof Symbol === "function" && typeof Symbol.iterator === "symbol" ? function (obj) { return typeof obj; } : function (obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; };

module.exports = function gettype(mixedVar) {
  //  discuss at: http://locutus.io/php/gettype/
  // original by: Paulo Freitas
  // improved by: Kevin van Zonneveld (http://kvz.io)
  // improved by: Douglas Crockford (http://javascript.crockford.com)
  // improved by: Brett Zamir (http://brett-zamir.me)
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
  var name;
  var _getFuncName = function _getFuncName(fn) {
    var name = /\W*function\s+([\w$]+)\s*\(/.exec(fn);
    if (!name) {
      return '(Anonymous)';
    }
    return name[1];
  };

  if (s === 'object') {
    if (mixedVar !== null) {
      // From: http://javascript.crockford.com/remedial.html
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
var filesize = this.filesize = (function(){
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
var getimagesize = this.getimagesize = (function(){
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
var mb_strlen = this.mb_strlen = (function(){
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
var mb_strwidth = this.mb_strwidth = (function(){
/**
 * mb_strwidth
 *
 * unicode 環境のみ。
 */
module.exports = function mb_strwidth(str) {
    // https://www.php.net/manual/ja/function.mb-strwidth.php
    var fullwidth_points = [
        [0x1100, 0x115F],
        [0x11A3, 0x11A7],
        [0x11FA, 0x11FF],
        [0x2329, 0x232A],
        [0x2E80, 0x2E99],
        [0x2E9B, 0x2EF3],
        [0x2F00, 0x2FD5],
        [0x2FF0, 0x2FFB],
        [0x3000, 0x303E],
        [0x3041, 0x3096],
        [0x3099, 0x30FF],
        [0x3105, 0x312D],
        [0x3131, 0x318E],
        [0x3190, 0x31BA],
        [0x31C0, 0x31E3],
        [0x31F0, 0x321E],
        [0x3220, 0x3247],
        [0x3250, 0x32FE],
        [0x3300, 0x4DBF],
        [0x4E00, 0xA48C],
        [0xA490, 0xA4C6],
        [0xA960, 0xA97C],
        [0xAC00, 0xD7A3],
        [0xD7B0, 0xD7C6],
        [0xD7CB, 0xD7FB],
        [0xF900, 0xFAFF],
        [0xFE10, 0xFE19],
        [0xFE30, 0xFE52],
        [0xFE54, 0xFE66],
        [0xFE68, 0xFE6B],
        [0xFF01, 0xFF60],
        [0xFFE0, 0xFFE6],
        [0x1B000, 0x1B001],
        [0x1F200, 0x1F202],
        [0x1F210, 0x1F23A],
        [0x1F240, 0x1F248],
        [0x1F250, 0x1F251],
        [0x20000, 0x2FFFD],
        [0x30000, 0x3FFFD],
    ];

    var str_width = 0;
    for (var i = 0; i < str.length; i++) {
        var char_code = str.charCodeAt(i);
        if (0xD800 <= char_code && char_code <= 0xDBFF) {
            char_code = ((char_code - 0xD800) * 0x400) + (str.charCodeAt(++i) - 0xDC00) + 0x10000;
        }

        str_width++;
        for (var n = 0; n < fullwidth_points.length; n++) {
            if (fullwidth_points[n][0] <= char_code && char_code <= fullwidth_points[n][1]) {
                str_width++;
                break;
            }
        }
    }

    return str_width;
};
return module.exports;
})();
/**/
var mime_content_type = this.mime_content_type = (function(){
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

    /// 検証ルールのインポート
    /**/
this.condition = {"Ajax":function(input, $value, $fields, $params, $consts, $error, $context, e) {var $message;
            (function() {
                if ($value && $params.request.url) {
                    function request() {
                        var formdata = new FormData();
                        formdata.append(input.name, $value);
                        var keys = Object.keys($fields);
                        for (var i = 0; i < keys.length; i++) {
                            formdata.append(keys[i], $fields[keys[i]]);
                        }
    
                        var handler = $params.request.handler || function(response){return response};
                        if ($params.request.api === 'xhr') {
                            return new Promise(function(resolve, reject) {
                                var xhr = new XMLHttpRequest();
                                xhr.open($params.request.method, $params.request.url);
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
                            var request = new Request($params.request.url, Object.assign({body: formdata}, $params.request));
                            return window.fetch(request).then(function(response) {
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
                            window[$params.request.api](Object.assign({body: formdata}, $params.request)).then(function(response) {
                                $error(response);
                                resolve();
                            }).catch(function(e) {
                                reject(e);
                            });
                        });
                    }

                    if (e.type === 'submit') {
                        if (input.validationErrors) {
                            $error();
                            input.validationAjaxStop = false;
                        }
                        else {
                            if (!input.validationAjaxStop) {
                                $error(request());
                            }
                            input.validationAjaxStop = true;
                        }
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
            })();},"ArrayLength":function(input, $value, $fields, $params, $consts, $error, $context, e) {var $length;
// 

        $length = count($value);

        if (!is_null($params['max']) && !is_null($params['min']) && ($length > $params['max'] || $length < $params['min'])) {
            $error($consts['SHORTLONG']);
        }
        else if (is_null($params['max']) && !is_null($params['min']) && $length < $params['min']) {
            $error($consts['TOO_SHORT']);
        }
        else if (is_null($params['min']) && !is_null($params['max']) && $length > $params['max']) {
            $error($consts['TOO_LONG']);
        }},"Aruiha":function(input, $value, $fields, $params, $consts, $error, $context, e) {(function() {
                var keys = Object.keys($params['condition']);
                for (var i = 0; i < keys.length; i++) {
                    var condition = $params['condition'][keys[i]];
                    var ok = true;
                    chmonos.condition[condition.class](input, $value, $fields, condition.param, $consts, function() { ok = false }, $context, e);
                    if (ok) {
                        return;
                    }
                }
                $error($consts['INVALID']);
            })();},"Callback":function(input, $value, $fields, $params, $consts, $error, $context, e) {var $callee;
// 

        $callee = $context['lang'] === 'php' ? $params['closure'] : $params['function'];
        $callee($value, $error, $fields, $params['userdata'], $context);},"Compare":function(input, $value, $fields, $params, $consts, $error, $context, e) {var $field1, $field2;
// 

        $field1 = $value;
        $field2 = $params['direct'] ? $params['operand'] : $fields[$params['operand']];

        if (strlen($field2) === 0) {
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
            return $error($consts['EQUAL']);
        }
        if ($params['operator'] === '===' && $field1 !== $field2) {
            return $error($consts['EQUAL']);
        }
        if ($params['operator'] === '!=' && $field1 == $field2) {
            return $error($consts['NOT_EQUAL']);
        }
        if ($params['operator'] === '!==' && $field1 === $field2) {
            return $error($consts['NOT_EQUAL']);
        }
        if ($params['operator'] === '<' && $field1 >= $field2) {
            return $error($consts['LESS_THAN']);
        }
        if ($params['operator'] === '<=' && $field1 > $field2) {
            return $error($consts['LESS_THAN']);
        }
        if ($params['operator'] === '>' && $field1 <= $field2) {
            return $error($consts['GREATER_THAN']);
        }
        if ($params['operator'] === '>=' && $field1 < $field2) {
            return $error($consts['GREATER_THAN']);
        }},"DataUri":function(input, $value, $fields, $params, $consts, $error, $context, e) {var $matches, $decoded;
// 

        $matches = [];

        if (!preg_match('#^data:(.+?/.+?)?(;charset=+?)?(;base64)?,#iu', $value, $matches)) {
            return $error($consts['INVALID']);
        }

        $decoded = base64_decode(substr($value, strlen($matches[0])), true);

        if ($decoded === false) {
            return $error($consts['INVALID']);
        }

        if ($params['size'] && $params['size'] < strlen($decoded)) {
            $error($consts['INVALID_SIZE']);
        }

        if ($params['type'] && !in_array($matches[1], $params['allowTypes'], true)) {
            $error($consts['INVALID_TYPE']);
        }},"Date":function(input, $value, $fields, $params, $consts, $error, $context, e) {var $time;
// 

        $time = strtotime($value);

        // 時刻のみの場合を考慮して年月日を付加して再チャレンジ
        if ($time === false) {
            $time = strtotime($context['str_concat']('2000/10/10 ', $value));
        }

        if ($time === false) {
            $error($consts['INVALID_DATE']);
        }
        else if (date($params['format'], $time) !== $value) {
            $error($consts['FALSEFORMAT']);
        }},"Decimal":function(input, $value, $fields, $params, $consts, $error, $context, e) {var $match;
// 

        $match = [];

        if (!preg_match('#^-?([1-9]\\d*|0)(\\.\\d+)?$#u', $value, $match)) {
            return $error($consts['INVALID']);
        }

        $match[2] = (isset($match[2])) ? $match[2] : '';
        if (strlen($match[1]) > $params['int'] && strlen($match[2]) > $params['dec'] + 1) {
            $error($consts['INVALID_INTDEC']);
        }
        else if (strlen($match[1]) > $params['int']) {
            $error($consts['INVALID_INT']);
        }
        else if (strlen($match[2]) > $params['dec'] + 1) {
            $error($consts['INVALID_DEC']);
        }},"Digits":function(input, $value, $fields, $params, $consts, $error, $context, e) {// 

        if (!ctype_digit(ltrim($value, '-+'))) {
            $error($consts['NOT_DIGITS']);
        }},"EmailAddress":function(input, $value, $fields, $params, $consts, $error, $context, e) {// 

        if (!preg_match($params['regex'], $value)) {
            $error($consts['INVALID_FORMAT']);
        }},"FileName":function(input, $value, $fields, $params, $consts, $error, $context, e) {var $pathinfo;
// 

        if (!preg_match($params['regex'], $value)) {
            $error($consts['INVALID_FILENAME_STR']);
            return;
        }

        $pathinfo = pathinfo($value);
        $pathinfo['extension'] = isset($pathinfo['extension']) ? $pathinfo['extension'] : '';
        $pathinfo['filename'] = isset($pathinfo['filename']) ? $pathinfo['filename'] : '';

        if (count($params['extensions']) && !in_array($pathinfo['extension'], $params['extensions'])) {
            $error($consts['INVALID_FILENAME_EXT']);
            return;
        }

        if (count($params['reserved']) && in_array(strtoupper($pathinfo['filename']), $params['reserved'])) {
            $error($consts['INVALID_FILENAME_RESERVED']);
            return;
        }},"FileSize":function(input, $value, $fields, $params, $consts, $error, $context, e) {var $size;
// 

        $size = filesize($value);

        if (!$size) {
            $error($consts['INVALID']);
        }

        if ($size > $params['maxsize']) {
            $error($consts['INVALID_OVER']);
        }},"FileType":function(input, $value, $fields, $params, $consts, $error, $context, e) {var $mimetype;
// 

        $mimetype = mime_content_type($value);

        if (!$mimetype && !in_array('*', $params['mimetype'])) {
            $error($consts['INVALID']);
        }

        if (!in_array($mimetype, $params['mimetype'])) {
            $error($consts['INVALID_TYPE']);
        }},"Hostname":function(input, $value, $fields, $params, $consts, $error, $context, e) {var $checkport, $port, $require_port, $matches;
// 

        $checkport = function ($port, $require_port, $error, $consts) {
            if (strlen($port)) {
                if ($require_port === false) {
                    $error($consts['INVALID']);
                }
                if ($port > 65535) {
                    $error($consts['INVALID_PORT']);
                }
            }
            else {
                if ($require_port === true) {
                    $error($consts['INVALID_PORT']);
                }
            }
        };

        $matches = [];

        if (in_array('', $params['types']) && preg_match('#^(([a-z0-9])|([a-z0-9][a-z0-9-]{0,61}[a-z0-9])|((([a-z0-9])|([a-z0-9][a-z0-9-]{0,61}[a-z0-9]))\\.)+[a-z]+)(:([1-9][0-9]{0,4}))?$#i', $value, $matches)) {
            $checkport(isset($matches[9]) ? $matches[9] : '', $params['require_port'], $error, $consts);
            return;
        }
        if (in_array('cidr', $params['types']) && preg_match('#^(?:25[0-5]|2[0-4]\\d|1\\d\\d|\\d\\d|\\d)(?:\.(?:25[0-5]|2[0-4]\\d|1\\d\\d|\\d\\d|\\d)){3}/([0-9]|[1-2][0-9]|3[0-2])(:([1-9][0-9]{0,4}))?$#i', $value, $matches)) {
            $checkport(isset($matches[3]) ? $matches[3] : '', $params['require_port'], $error, $consts);
            return;
        }
        if (in_array(4, $params['types']) && preg_match('#^(?:25[0-5]|2[0-4]\\d|1\\d\\d|\\d\\d|\\d)(?:\.(?:25[0-5]|2[0-4]\\d|1\\d\\d|\\d\\d|\\d)){3}(:([1-9][0-9]{0,4}))?$#i', $value, $matches)) {
            $checkport(isset($matches[2]) ? $matches[2] : '', $params['require_port'], $error, $consts);
            return;
        }
        if (in_array(6, $params['types']) && preg_match('#^::$#i', $value, $matches)) {
            return;
        }

        $error($consts['INVALID']);},"ImageSize":function(input, $value, $fields, $params, $consts, $error, $context, e) {var $size;
            (function() {
                $error(getimagesize($value).then(function($size) {
                    // 

        /** @var $size */

        if ($context['lang'] === 'php') {
            $size = getimagesize($value);
        }

        if ($size === false) {
            $error($consts['INVALID']);
            return;
        }

        if (!is_null($params['width']) && $params['width'] < $size[0]) {
            $error($consts['INVALID_WIDTH']);
        }

        if (!is_null($params['height']) && $params['height'] < $size[1]) {
            $error($consts['INVALID_HEIGHT']);
        }
    
                }));
            })();},"InArray":function(input, $value, $fields, $params, $consts, $error, $context, e) {// 

        if ($params['strict'] === null) {
            if (!isset($params['haystack'][$value])) {
                $error($consts['NOT_IN_ARRAY']);
            }
        }
        else {
            if (!in_array($value, $params['haystack'], $params['strict'])) {
                $error($consts['NOT_IN_ARRAY']);
            }
        }},"Json":function(input, $value, $fields, $params, $consts, $error, $context, e) {var $decode;
// 

        $decode = json_decode($value, true);
        if ($decode === null && strtolower(trim($value)) !== 'null') {
            $error($consts['INVALID']);
            return;
        }},"NotInArray":function(input, $value, $fields, $params, $consts, $error, $context, e) {// 

        if ($params['strict'] === null) {
            if (isset($params['haystack'][$value])) {
                $error($consts['VALUE_IN_ARRAY']);
            }
        }
        else {
            if (in_array($value, $params['haystack'], $params['strict'])) {
                $error($consts['VALUE_IN_ARRAY']);
            }
        }},"Password":function(input, $value, $fields, $params, $consts, $error, $context, e) {var $fulfill, $key, $regex, $counts;
// 

        $fulfill = $context['foreach']($params['regexes'], function ($key, $regex, $value, $error, $consts) {
            if (!preg_match($regex, $value)) {
                $error($consts['INVALID_PASSWORD_LESS']);
                return false;
            }
        }, $value, $error, $consts);

        if (!$fulfill) {
            return;
        }

        $counts = array_count_values(str_split($value, 1));
        if (count($counts) < count($params['regexes']) * $params['repeat']) {
            $error($consts['INVALID_PASSWORD_WEAK']);
        }},"Range":function(input, $value, $fields, $params, $consts, $error, $context, e) {// 

        if ((!is_null($params['min']) && !is_null($params['max'])) && !($params['min'] <= $value && $value <= $params['max'])) {
            $error($consts['INVALID_MINMAX']);
        }
        else if ((!is_null($params['min']) && is_null($params['max'])) && ($params['min'] > $value)) {
            $error($consts['INVALID_MIN']);
        }
        else if ((is_null($params['min']) && !is_null($params['max'])) && ($value > $params['max'])) {
            $error($consts['INVALID_MAX']);
        }},"Regex":function(input, $value, $fields, $params, $consts, $error, $context, e) {var $status;
// 

        if (!is_string($value) && !is_int($value) && !is_float($value)) {
            return $error($consts['INVALID']);
        }

        $status = preg_match($params['pattern'], $value);
        if (false === $status) {
            $error($consts['ERROROUS']);
        }
        else if (!$params['negation'] && !$status) {
            $error($consts['NOT_MATCH']);
        }
        else if ($params['negation'] && $status) {
            $error($consts['NEGATION']);
        }},"Requires":function(input, $value, $fields, $params, $consts, $error, $context, e) {var $nofify, $getDepend, $name, $carry, $statement, $field, $operator, $operand, $dvalue, $intersect;
// 

        $nofify = function ($value, $error, $consts) {
            if (!is_array($value) && strval($value) === '') {
                $error($consts['INVALID_TEXT']);
            }
            else if (is_array($value) && count($value) === 0) {
                $error($consts['INVALID_MULTIPLE']);
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
        }},"Step":function(input, $value, $fields, $params, $consts, $error, $context, e) {// 

        if (!preg_match('#^-?([1-9]\\d*|0)(\\.\\d+)?$#u', $value)) {
            return $error($consts['INVALID']);
        }
        if (abs(round($value / $params['step']) * $params['step'] - $value) > pow(2, -52)) {
            $error($consts['INVALID_STEP']);
        }},"StringLength":function(input, $value, $fields, $params, $consts, $error, $context, e) {var $length;
// 

        $length = mb_strlen($value);

        if (!is_null($params['max']) && !is_null($params['min']) && ($length > $params['max'] || $length < $params['min'])) {
            if ($params['min'] === $params['max']) {
                $error($consts['DIFFERENT']);
            }
            else {
                $error($consts['SHORTLONG']);
            }
        }
        else if (is_null($params['max']) && !is_null($params['min']) && $length < $params['min']) {
            $error($consts['TOO_SHORT']);
        }
        else if (is_null($params['min']) && !is_null($params['max']) && $length > $params['max']) {
            $error($consts['TOO_LONG']);
        }},"StringWidth":function(input, $value, $fields, $params, $consts, $error, $context, e) {var $length;
// 

        $length = mb_strwidth($value);

        if (!is_null($params['max']) && !is_null($params['min']) && ($length > $params['max'] || $length < $params['min'])) {
            if ($params['min'] === $params['max']) {
                $error($consts['DIFFERENT']);
            }
            else {
                $error($consts['SHORTLONG']);
            }
        }
        else if (is_null($params['max']) && !is_null($params['min']) && $length < $params['min']) {
            $error($consts['TOO_SHORT']);
        }
        else if (is_null($params['min']) && !is_null($params['max']) && $length > $params['max']) {
            $error($consts['TOO_LONG']);
        }},"Telephone":function(input, $value, $fields, $params, $consts, $error, $context, e) {// 

        // 明らかに電話番号っぽくない場合のチェック
        if (mb_strlen($value) > $params['maxlength']) {
            return $error($consts['INVALID']);
        }

        // 電話番号っぽいが細部がおかしい場合
        if (!preg_match($params['pattern'], $value)) {
            if ($params['hyphen'] === null) {
                $error($consts['INVALID_TELEPHONE']);
            }
            else if ($params['hyphen'] === true) {
                $error($consts['INVALID_WITH_HYPHEN']);
            }
            else if ($params['hyphen'] === false) {
                $error($consts['INVALID_NONE_HYPHEN']);
            }
        }},"Unique":function(input, $value, $fields, $params, $consts, $error, $context, e) {var $acv;
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
            $error($consts['NO_UNIQUE']);
            return false;
        }
    
            })();},"Uri":function(input, $value, $fields, $params, $consts, $error, $context, e) {var $parsed;
// 

        $parsed = parse_url($value);

        if (!$parsed || !isset($parsed['scheme'])) {
            $error($consts['INVALID']);
        }
        else if (count($params['schemes']) && !in_array($parsed['scheme'], $params['schemes'])) {
            $error($consts['INVALID_SCHEME']);
        }
        else if (!isset($parsed['host'])) {
            $error($consts['INVALID_HOST']);
        }}};/*
*/

    /// エラー定数のインポート
    /**/
this.constants = {"Ajax":{"INVALID":"AjaxInvalid"},"ArrayLength":{"INVALID":"ArrayLengthInvalidLength","TOO_SHORT":"ArrayLengthInvalidMin","TOO_LONG":"ArrayLengthInvalidMax","SHORTLONG":"ArrayLengthInvalidMinMax"},"Aruiha":{"INVALID":"AruihaInvalid"},"Callback":{"INVALID":"CallbackInvalid"},"Compare":{"INVALID":"compareInvalid","EQUAL":"compareEqual","NOT_EQUAL":"compareNotEqual","LESS_THAN":"compareLessThan","GREATER_THAN":"compareGreaterThan","SIMILAR":"compareSimilar"},"DataUri":{"INVALID":"dataUriInvalid","INVALID_SIZE":"dataUriInvalidSize","INVALID_TYPE":"dataUriInvalidType"},"Date":{"INVALID":"dateInvalid","INVALID_DATE":"dateInvalidDate","FALSEFORMAT":"dateFalseFormat","AUTO":1,"ACTIVE":2,"INACTIVE":3,"DISABLED":4},"Decimal":{"INVALID":"DecimalInvalid","INVALID_INT":"DecimalInvalidInt","INVALID_DEC":"DecimalInvalidDec","INVALID_INTDEC":"DecimalInvalidIntDec","AUTO":1,"ACTIVE":2,"INACTIVE":3,"DISABLED":4},"Digits":{"INVALID":"notDigits","NOT_DIGITS":"digitsInvalid","AUTO":1,"ACTIVE":2,"INACTIVE":3,"DISABLED":4},"EmailAddress":{"INVALID":"emailAddressInvalid","INVALID_FORMAT":"emailAddressInvalidFormat","AUTO":1,"ACTIVE":2,"INACTIVE":3,"DISABLED":4},"FileName":{"INVALID":"InvalidFileName","INVALID_FILENAME_STR":"InvalidFileNameStr","INVALID_FILENAME_EXT":"InvalidFileNameExt","INVALID_FILENAME_RESERVED":"InvalidFileNameReserved","AUTO":1,"ACTIVE":2,"INACTIVE":3,"DISABLED":4},"FileSize":{"INVALID":"FileSizeInvalid","INVALID_OVER":"FileSizeInvalidOver"},"FileType":{"INVALID":"FileTypeInvalid","INVALID_TYPE":"FileTypeInvalidType"},"Hostname":{"INVALID":"InvalidHostname","INVALID_PORT":"InvalidHostnamePort","AUTO":1,"ACTIVE":2,"INACTIVE":3,"DISABLED":4},"ImageSize":{"INVALID":"ImageFileInvalid","INVALID_WIDTH":"ImageFileInvalidWidth","INVALID_HEIGHT":"ImageFileInvalidHeight"},"InArray":{"INVALID":"InvalidInArray","NOT_IN_ARRAY":"notInArray"},"Json":{"INVALID":"JsonInvalid","INVALID_INVALID_SCHEMA":"JsonInvalidSchema"},"NotInArray":{"INVALID":"InvalidNotInArray","VALUE_IN_ARRAY":"valueInArray"},"Password":{"INVALID":"InvalidPassword","INVALID_PASSWORD_LESS":"InvalidPasswordLess","INVALID_PASSWORD_WEAK":"InvalidPasswordWeak","AUTO":1,"ACTIVE":2,"INACTIVE":3,"DISABLED":4},"Range":{"INVALID":"RangeInvalid","INVALID_MIN":"RangeInvalidMin","INVALID_MAX":"RangeInvalidMax","INVALID_MINMAX":"RangeInvalidMinMax","AUTO":1,"ACTIVE":2,"INACTIVE":3,"DISABLED":4},"Regex":{"INVALID":"regexInvalid","ERROROUS":"regexErrorous","NOT_MATCH":"regexNotMatch","NEGATION":"regexNegation"},"Requires":{"INVALID":"RequireInvalid","INVALID_TEXT":"RequireInvalidText","INVALID_MULTIPLE":"RequireInvalidSelectSingle"},"Step":{"INVALID":"StepInvalid","INVALID_STEP":"StepInvalidInt","AUTO":1,"ACTIVE":2,"INACTIVE":3,"DISABLED":4},"StringLength":{"INVALID":"StringLengthInvalidLength","TOO_SHORT":"StringLengthInvalidMin","TOO_LONG":"StringLengthInvalidMax","SHORTLONG":"StringLengthInvalidMinMax","DIFFERENT":"StringLengthInvalidDifferenr"},"StringWidth":{"INVALID":"StringWidthInvalidLength","TOO_SHORT":"StringWidthInvalidMin","TOO_LONG":"StringWidthInvalidMax","SHORTLONG":"StringWidthInvalidMinMax","DIFFERENT":"StringWidthInvalidDifferenr"},"Telephone":{"INVALID":"InvalidTelephone","INVALID_TELEPHONE":"InvalidTelephoneNumber","INVALID_WITH_HYPHEN":"InvalidTelephoneWithHyphen","INVALID_NONE_HYPHEN":"InvalidTelephoneNoneHyphen","AUTO":1,"ACTIVE":2,"INACTIVE":3,"DISABLED":4},"Unique":{"INVALID":"UniqueInvalid","NO_UNIQUE":"UniqueNoUnique"},"Uri":{"INVALID":"UriInvalid","INVALID_SCHEME":"UriInvalidScheme","INVALID_HOST":"UriInvalidHost","INVALID_PORT":"UriInvalidPort","AUTO":1,"ACTIVE":2,"INACTIVE":3,"DISABLED":4}};/*
*/

    /// エラー文言のインポート
    /**/
this.messages = {"Ajax":[],"ArrayLength":{"ArrayLengthInvalidLength":"Invalid value given","ArrayLengthInvalidMin":"%min%件以上は入力してください","ArrayLengthInvalidMax":"%max%件以下で入力して下さい","ArrayLengthInvalidMinMax":"%min%件～%max%件を入力して下さい"},"Aruiha":{"AruihaInvalid":"必ず呼び出し元で再宣言する"},"Callback":{"CallbackInvalid":"クロージャの戻り値で上書きされる"},"Compare":{"compareInvalid":"Invalid value given","compareEqual":"%operand%と同じ値を入力してください","compareNotEqual":"%operand%と異なる値を入力してください","compareLessThan":"%operand%より小さい値を入力してください","compareGreaterThan":"%operand%より大きい値を入力してください"},"DataUri":{"dataUriInvalid":"Invalid value given","dataUriInvalidSize":"%size_message%以下で入力してください","dataUriInvalidType":"%type_message%形式で入力してください"},"Date":{"dateInvalid":"Invalid value given","dateInvalidDate":"有効な日付を入力してください","dateFalseFormat":"%format%形式で入力してください"},"Decimal":{"DecimalInvalid":"小数値を入力してください","DecimalInvalidInt":"整数部分を%int%桁以下で入力してください","DecimalInvalidDec":"小数部分を%dec%桁以下で入力してください","DecimalInvalidIntDec":"整数部分を%int%桁、小数部分を%dec%桁以下で入力してください"},"Digits":{"notDigits":"Invalid value given","digitsInvalid":"整数を入力してください"},"EmailAddress":{"emailAddressInvalid":"Invalid value given","emailAddressInvalidFormat":"メールアドレスを正しく入力してください"},"FileName":{"InvalidFileName":"Invalid value given","InvalidFileNameStr":"有効なファイル名を入力してください","InvalidFileNameExt":"%extensions%ファイル名を入力してください","InvalidFileNameReserved":"使用できないファイル名です"},"FileSize":{"FileSizeInvalid":"入力ファイルが不正です","FileSizeInvalidOver":"ファイルサイズが大きすぎます。%message%以下のファイルを選択してください"},"FileType":{"FileTypeInvalid":"入力ファイルが不正です","FileTypeInvalidType":"%type%形式のファイルを選択して下さい"},"Hostname":{"InvalidHostname":"ホスト名を正しく入力してください","InvalidHostnamePort":"ポート番号を正しく入力してください"},"ImageSize":{"ImageFileInvalid":"画像ファイルを入力してください","ImageFileInvalidWidth":"横サイズは%width%ピクセル以下で選択してください","ImageFileInvalidHeight":"縦サイズは%height%ピクセル以下で選択してください"},"InArray":{"InvalidInArray":"Invalid value given","notInArray":"選択値が不正です"},"Json":{"JsonInvalid":"JSON文字列が不正です","JsonInvalidSchema":"キーが不正です"},"NotInArray":{"InvalidNotInArray":"Invalid value given","valueInArray":"選択値が不正です"},"Password":{"InvalidPassword":"Invalid value given","InvalidPasswordLess":"%char_types%を含めてください","InvalidPasswordWeak":"%char_types%のいずれかを%repeat%文字以上含めてください"},"Range":{"RangeInvalid":"Invalid value given","RangeInvalidMin":"%min%以上で入力して下さい","RangeInvalidMax":"%max%以下で入力して下さい","RangeInvalidMinMax":"%min%以上%max%以下で入力して下さい"},"Regex":{"regexInvalid":"Invalid value given","regexErrorous":"There was%pattern%'","regexNotMatch":"パターンに一致しません","regexNegation":"使用できない文字が含まれています"},"Requires":{"RequireInvalid":"Invalid value given","RequireInvalidText":"入力必須です","RequireInvalidSelectSingle":"選択してください"},"Step":{"StepInvalid":"Invalid value given","StepInvalidInt":"%step%の倍数で入力してください"},"StringLength":{"StringLengthInvalidLength":"Invalid value given","StringLengthInvalidMin":"%min%文字以上で入力して下さい","StringLengthInvalidMax":"%max%文字以下で入力して下さい","StringLengthInvalidMinMax":"%min%文字～%max%文字で入力して下さい","StringLengthInvalidDifferenr":"%min%文字で入力して下さい"},"StringWidth":{"StringWidthInvalidLength":"Invalid value given","StringWidthInvalidMin":"%min%文字以上で入力して下さい","StringWidthInvalidMax":"%max%文字以下で入力して下さい","StringWidthInvalidMinMax":"%min%文字～%max%文字で入力して下さい","StringWidthInvalidDifferenr":"%min%文字で入力して下さい"},"Telephone":{"InvalidTelephone":"電話番号を正しく入力してください","InvalidTelephoneNumber":"電話番号を入力してください","InvalidTelephoneWithHyphen":"ハイフン付きで電話番号を入力してください","InvalidTelephoneNoneHyphen":"ハイフン無しで電話番号を入力してください"},"Unique":{"UniqueInvalid":"Invalid value given","UniqueNoUnique":"値が重複しています"},"Uri":{"UriInvalid":"URLをスキームから正しく入力してください","UriInvalidScheme":"スキームが不正です(%schemes%のみ)","UriInvalidHost":"ホスト名が不正です","UriInvalidPort":"ポート番号が不正です"}};/*
*/

    /// 初期化（コンストラクション）

    // noinspection JSUnresolvedFunction
    setlocale('LC_CTYPE', 'en_US'); // for ctype_digit()

    /// 内部用

    function core_validate(input, validation_id, evt) {
        var result = [];

        if (chmonos.validationDisabled) {
            return result;
        }

        // propagate などで同一要素に検証が走ることがあるので一意な ID を持たせて、同一 ID ならスルーするようにする
        var vid = input.validationId;
        if (vid !== undefined && vid === validation_id) {
            return result;
        }
        input.validationId = validation_id;

        var elemName = input.dataset.vinputClass;
        var rule = options.allrules[elemName];
        if (rule === undefined) {
            return result;
        }

        var phantom = rule['phantom'];
        if (phantom.length) {
            var flag = true;
            var brothers = [];
            for (var i = 1; i < phantom.length; i++) {
                var target = chmonos.value(chmonos.brother(input, phantom[i])[0]);
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
            fireError(input, {}, true);
            return result;
        }

        if (input.disabled) {
            return result;
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
            var error = function (err) {
                if (evt.chmonosSubtypes) {
                    if (evt.chmonosSubtypes.includes('noerror')) {
                        return;
                    }
                    if (evt.chmonosSubtypes.includes('norequire') && input !== evt.target && evt.target.tagName !== 'FORM') {
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
                    errorTypes[level][cname][err] = ret.replace(/%(.+?)%/g, function (p0, p1) {
                        if (cond['param'][p1] !== undefined) {
                            return cond['param'][p1];
                        }
                        return p0;
                    });
                }
            };
            // 値が空の場合は Requires しか検証しない（空かどうかの制御を他の condition に任せたくない）
            // value は必ず length を持つように制御してるので「空・未入力」の判定は length === 0 で OK
            if (value.length > 0 || cname === 'Requires') {
                var values = cond['arrayable'] ? [value] : chmonos.context.cast('array', value);
                Object.keys(values).forEach(function (v) {
                    try {
                        chmonos.condition[cname](input, values[v], fields, cond['param'], chmonos.constants[cname], error, chmonos.context, evt);
                    }
                    catch (e) {
                        error(chmonos.constants[cname]['INVALID']);
                        console.error(e);
                    }
                });
            }
        }

        result.push(new Promise(function (resolve) {
            Promise.all(asyncs).then(function () {
                resolve(fireError(input, errorTypes, value.length > 0));
            });
        }));

        // ラジオボタンやチェックボックスなどはこれ以上無駄なので検証 ID を放り込んでおく
        if (input.type === 'radio' || input.type === 'checkbox') {
            form.querySelectorAll("input[name='" + input.name + "'].validatable").forEach(function (e) {
                e.validationId = validation_id;
            });
        }

        // 伝播先へ伝播
        rule['propagate'].forEach(function (propagate) {
            chmonos.brother(input, propagate).forEach(function (elm) {
                result.push.apply(result, core_validate(elm, validation_id, evt));
            });
        });

        return result;
    }

    function isPlainObject(obj) {
        if (typeof (obj) !== 'object' || obj.nodeType || obj === obj.window) {
            return false;
        }
        return !(obj.constructor && !{}.hasOwnProperty.call(obj.constructor.prototype, 'isPrototypeOf'));
    }

    function fireError(input, result, okclass) {
        var warningTypes = result.warning || {};
        var errorTypes = result.error || {};
        var isWarning = !!Object.keys(warningTypes).length;
        var isError = !!Object.keys(errorTypes).length;
        if (input.type === 'radio' || input.type === 'checkbox') {
            input = form.querySelectorAll("input[name='" + input.name + "'].validatable");
        }
        else {
            input = [input];
        }

        [warningTypes, errorTypes].forEach(function (types) {
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

        input.forEach(function (e) {
            if (isWarning) {
                e.classList.add('validation_warning');
                e.validationWarnings = warningTypes;
            }
            if (isError) {
                e.classList.add('validation_error');
                e.validationErrors = errorTypes;
            }
            if (isWarning || isError) {
                e.classList.remove('validation_ok');
            }
            else {
                e.classList.remove('validation_warning');
                e.classList.remove('validation_error');
                e.classList.remove('validation_ok');
                if (okclass) {
                    e.classList.add('validation_ok');
                }
                e.validationWarnings = undefined;
                e.validationErrors = undefined;
            }
            e.dispatchEvent(new CustomEvent('validated', {
                detail: {
                    warningTypes: warningTypes,
                    errorTypes: errorTypes,
                }, bubbles: true
            }));
        });
        if (isError) {
            return true;
        }
        if (isWarning) {
            return null;
        }
        return false;
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
        form.querySelectorAll('.validatable:enabled').forEach(function (input) {
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
                    form.validationValues = undefined;
                    core_validate(e.target, new Date().getTime(), e);
                    break;
                }
            }
        };
        // ありそうなイベントを全て listen して呼び出し時に要素単位でチェックする。この候補は割と気軽に追加して良い
        ['change', 'keyup', 'keydown', 'input', 'click', 'focusin', 'focusout'].forEach(function (event) {
            form.addEventListener(event, handler);
        });

        // サブミット時にバリデーション
        form.addEventListener('submit', function submit(e) {
            try {
                chmonos.validate(e).then(function (result) {
                    var done = function () {
                        if (options.alternativeSubmit && e.submitter) {
                            setTimeout(function () {
                                form.removeEventListener('submit', submit);
                                e.submitter.click();
                                form.addEventListener('submit', submit);
                            }, 0);
                        }
                        else {
                            // @see https://developer.mozilla.org/ja/docs/Web/API/HTMLFormElement/submit
                            // 発火するしないは規定されていないらしいので念の為に付け外す
                            form.removeEventListener('submit', submit);
                            form.submit();
                            form.addEventListener('submit', submit);
                        }
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
            e.stopPropagation();
            return false;
        });
    };

    chmonos.addCustomValidation = function (validation, timing) {
        timing = timing || 'after';
        chmonos.customValidation[timing].push(validation);
    };

    chmonos.validate = function (evt) {
        form.validationValues = undefined;
        evt = evt || new CustomEvent('vatidation');
        var validation_id = new Date().getTime();

        var promises = [];
        if (chmonos.customValidation.before.some(function (f) { return f.call(form, promises) === false })) {
            promises.push(true);
            return Promise.all(promises);
        }
        form.querySelectorAll('.validatable').forEach(function (e) {
            promises.push.apply(promises, core_validate(e, validation_id, evt));
        });
        if (chmonos.customValidation.after.some(function (f) { return f.call(form, promises) === false })) {
            promises.push(true);
            return Promise.all(promises);
        }
        return Promise.all(promises);
    };

    chmonos.setErrors = function (emessages) {
        var errorTypes = {};
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

        form.querySelectorAll('.validatable').forEach(function (input) {
            fireError(input, {error: errorTypes[input.dataset.vinputId] || {}}, false);
        });
    };

    chmonos.clearErrors = function () {
        form.querySelectorAll('.validatable').forEach(function (input) {
            fireError(input, {}, false);
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
            e.disabled = false;
        });
        Array.from(fragment.querySelectorAll('[data-vinput-wrapper],[data-vinput-group]')).forEach(function (e) {
            resetIndex(e, 'data-vinput-wrapper', index);
            resetIndex(e, 'data-vinput-group', index);
            e.disabled = false;
        });
        if (values) {
            Object.keys(values).forEach(function (key) {
                var index = 0;
                if (values[key] !== null) {
                    fragment.querySelectorAll('[data-vinput-id$="/' + key + '"].validatable').forEach(function (e) {
                        if (e.type === 'file') {
                            return;
                        }
                        if (e.type === 'checkbox' || e.type === 'radio') {
                            var vv = (values[key] instanceof Array ? values[key] : [values[key]]).map(function (x) {return '' + x});
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
        }
        Array.from(fragment.querySelectorAll('.validatable:enabled')).forEach(function (e) {
            chmonos.required(e, undefined, fragment);
        });

        return fragment.querySelector(rootTag);
    };

    /**
     * 子要素を生み出す
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
        template.dispatchEvent(new CustomEvent('spawn', {
            detail: {
                node: node,
                index: index,
                values: values,
            },
        }));

        callback = callback || function (node) {this.parentNode.appendChild(node)};
        callback.call(template, node);
        return node;
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
        var rkey = Object.keys(condition).find(function (key) {
            return condition[key].cname === 'Requires';
        });
        if (rkey) {
            fields = fields || chmonos.fields(input);
            var label = holder.querySelector('[data-vlabel-id="' + input.dataset.vinputId + '"]') || document.createElement('span');
            input.classList.remove('required');
            label.classList.remove('required');
            chmonos.condition['Requires'](input, '', fields, condition[rkey]['param'], chmonos.constants['Requires'], function () {
                input.classList.add('required');
                label.classList.add('required');
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

        if (input.name.match(/\[]$/)) {
            return Array.from(form.querySelectorAll('[name="' + input.name + '"].validatable:enabled'), function (e) {
                return e.value;
            });
        }

        var val = input.value;
        if (options.allrules[elemName]['trimming']) {
            val = val.trim();
        }
        return val;
    };

    /**
     * フォームの値を返す
     */
    chmonos.values = function () {
        if (form.validationValues) {
            return form.validationValues;
        }

        var values = {};
        form.querySelectorAll('.validatable:enabled').forEach(function (e) {
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
            return filemanage(file);
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
     * フォームの値をオブジェクトで返す
     *
     * @param filemanage file 要素をどう扱うか？ string|binary|base64
     */
    chmonos.object = async function (filemanage) {
        var result = {};
        chmonos.parse_str((await chmonos.params(filemanage)).toString(), result);
        return result;
    };
}
