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
