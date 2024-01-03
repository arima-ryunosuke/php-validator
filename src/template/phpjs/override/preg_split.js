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
