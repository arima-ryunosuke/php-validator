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
//# sourceMappingURL=array_keys.js.map