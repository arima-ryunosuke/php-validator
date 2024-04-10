'use strict';

module.exports = function ctype_print(text) {
  //  discuss at: https://locutus.io/php/ctype_print/
  // original by: Brett Zamir (https://brett-zamir.me)
  //   example 1: ctype_print('AbC!#12')
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

  return text.search(p.locales[p.localeCategories.LC_CTYPE].LC_CTYPE.pr) !== -1;
};
//# sourceMappingURL=ctype_print.js.map