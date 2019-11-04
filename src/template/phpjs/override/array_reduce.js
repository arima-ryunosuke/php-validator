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
