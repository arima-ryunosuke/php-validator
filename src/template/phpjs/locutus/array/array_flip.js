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
//# sourceMappingURL=array_flip.js.map