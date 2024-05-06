/**
 * strlen
 */
module.exports = function strlen(string) {
    const encoder = new TextEncoder();
    return encoder.encode(string).length;
};
