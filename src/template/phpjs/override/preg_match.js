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
