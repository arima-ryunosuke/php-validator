/**
 * array_kmap
 */
module.exports = function array_kmap(array, callback) {
    var n = 0;
    var result = array instanceof Array ? [] : {};
    for (var [k, v] of Object.entries(array)) {
        result[k] = callback(v, k, n++);
    }
    return result;
};
