'use strict';

module.exports = function arsort(inputArr, sortFlags) {
  //  discuss at: https://locutus.io/php/arsort/
  // original by: Brett Zamir (https://brett-zamir.me)
  // improved by: Brett Zamir (https://brett-zamir.me)
  // improved by: Theriault (https://github.com/Theriault)
  //      note 1: SORT_STRING (as well as natsort and natcasesort) might also be
  //      note 1: integrated into all of these functions by adapting the code at
  //      note 1: https://sourcefrog.net/projects/natsort/natcompare.js
  //      note 1: The examples are correct, this is a new way
  //      note 1: Credits to: https://javascript.internet.com/math-related/bubble-sort.html
  //      note 1: This function deviates from PHP in returning a copy of the array instead
  //      note 1: of acting by reference and returning true; this was necessary because
  //      note 1: IE does not allow deleting and re-adding of properties without caching
  //      note 1: of property position; you can set the ini of "locutus.sortByReference" to true to
  //      note 1: get the PHP behavior, but use this only if you are in an environment
  //      note 1: such as Firefox extensions where for-in iteration order is fixed and true
  //      note 1: property deletion is supported. Note that we intend to implement the PHP
  //      note 1: behavior by default if IE ever does allow it; only gives shallow copy since
  //      note 1: is by reference in PHP anyways
  //      note 1: Since JS objects' keys are always strings, and (the
  //      note 1: default) SORT_REGULAR flag distinguishes by key type,
  //      note 1: if the content is a numeric string, we treat the
  //      note 1: "original type" as numeric.
  //   example 1: var $data = {d: 'lemon', a: 'orange', b: 'banana', c: 'apple'}
  //   example 1: arsort($data)
  //   example 1: var $result = $data
  //   returns 1: {a: 'orange', d: 'lemon', b: 'banana', c: 'apple'}
  //   example 2: ini_set('locutus.sortByReference', true)
  //   example 2: var $data = {d: 'lemon', a: 'orange', b: 'banana', c: 'apple'}
  //   example 2: arsort($data)
  //   example 2: var $result = $data
  //   returns 2: {a: 'orange', d: 'lemon', b: 'banana', c: 'apple'}
  //        test: skip-1

  var i18lgd = require('../i18n/i18n_loc_get_default');
  var strnatcmp = require('../strings/strnatcmp');
  var valArr = [];
  var valArrLen = 0;
  var k = void 0;
  var i = void 0;
  var sorter = void 0;
  var sortByReference = false;
  var populateArr = {};

  var $global = typeof window !== 'undefined' ? window : global;
  $global.$locutus = $global.$locutus || {};
  var $locutus = $global.$locutus;
  $locutus.php = $locutus.php || {};
  $locutus.php.locales = $locutus.php.locales || {};

  switch (sortFlags) {
    case 'SORT_STRING':
      // compare items as strings
      sorter = function sorter(a, b) {
        return strnatcmp(b, a);
      };
      break;
    case 'SORT_LOCALE_STRING':
      // compare items as strings, based on the current locale
      // (set with i18n_loc_set_default() as of PHP6)
      var loc = i18lgd();
      sorter = $locutus.php.locales[loc].sorting;
      break;
    case 'SORT_NUMERIC':
      // compare items numerically
      sorter = function sorter(a, b) {
        return a - b;
      };
      break;
    case 'SORT_REGULAR':
      // compare items normally (don't change types)
      break;
    default:
      sorter = function sorter(b, a) {
        var aFloat = parseFloat(a);
        var bFloat = parseFloat(b);
        var aNumeric = aFloat + '' === a;
        var bNumeric = bFloat + '' === b;

        if (aNumeric && bNumeric) {
          return aFloat > bFloat ? 1 : aFloat < bFloat ? -1 : 0;
        } else if (aNumeric && !bNumeric) {
          return 1;
        } else if (!aNumeric && bNumeric) {
          return -1;
        }

        return a > b ? 1 : a < b ? -1 : 0;
      };
      break;
  }

  var iniVal = (typeof require !== 'undefined' ? require('../info/ini_get')('locutus.sortByReference') : undefined) || 'on';
  sortByReference = iniVal === 'on';

  // Get key and value arrays
  for (k in inputArr) {
    if (inputArr.hasOwnProperty(k)) {
      valArr.push([k, inputArr[k]]);
      if (sortByReference) {
        delete inputArr[k];
      }
    }
  }
  valArr.sort(function (a, b) {
    return sorter(a[1], b[1]);
  });

  // Repopulate the old array
  for (i = 0, valArrLen = valArr.length; i < valArrLen; i++) {
    populateArr[valArr[i][0]] = valArr[i][1];
    if (sortByReference) {
      inputArr[valArr[i][0]] = valArr[i][1];
    }
  }

  return sortByReference || populateArr;
};
//# sourceMappingURL=arsort.js.map