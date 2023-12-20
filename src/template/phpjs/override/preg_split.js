/**
 * preg_split
 *
 * flags は未対応。
 * PREG_SPLIT_NO_EMPTY は対応できなくもないが、他に合わせると文字列指定になりフラグとしての汎用性がなくなるし、結局 trim することが多いので実装するアドバンテージが薄い
 */
module.exports = function preg_split(pattern, subject, limit, flags) {
    // 引数4つ以上は未対応
    if (arguments.length >= 4) {
        throw 'arguments is too long.';
    }
    limit = limit ?? 0;

    // 表現とフラグをセパレート
    var meta = pattern.charAt(0);
    var exp = new RegExp(meta + '(.*)' + meta + '([im]*)');
    var eaf = pattern.match(exp);

    // マッチング
    var regexp = new RegExp(eaf[1], eaf[2] + 'gd');
    var match = subject.matchAll(regexp);

    var current = 0;
    var result = [];
    for (var m of match) {
        var part = subject.substring(current, m.indices[0][0]);
        // @todo flags(PREG_SPLIT_NO_EMPTY)
        if (part.length >= 0) {
            result.push(part);
        }

        if (limit > 0 && result.length >= limit) {
            result[result.length - 1] += subject.substring(m.indices[0][0]);
            return result;
        }

        current = m.indices[0][1];
    }

    result.push(subject.substring(current));
    return result;
};
