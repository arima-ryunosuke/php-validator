/**
 * array_map
 *
 * locutus の array_map は連想配列に対応していないので自前定義。
 * （php の array_map は挙動が謎過ぎるので完全模倣しなくてもいいのだけど…しばしば登場するので）。
 */
module.exports = function array_map(callback, ...arrays) {
    if (callback == null) {
        throw 'array_map() callback must not be null';
    }
    if (arrays.length === 0) {
        throw 'array_map() expects at least 2 arguments, 1 given';
    }

    if (arrays.length === 1) {
        var result = arrays[0] instanceof Array ? [] : {};
        for (var [k, v] of Object.entries(arrays[0])) {
            result[k] = callback(v);
        }
        return result;
    }
    else {
        var arrayed = arrays.map(array => Object.values(array));
        var max = Math.max(...arrayed.map(array => array.length));
        var result = [];
        for (var i = 0; i < max; i++) {
            result.push(callback(...arrayed.map(array => array[i] ?? null)));
        }
        return result;
    }
};
