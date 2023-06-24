/**
 * base64_decode
 */
module.exports = function base64_decode(string, strict) {
    if (strict === undefined) {
        strict = false;
    }

    try {
        return atob(string);
    }
    catch (e) {
        return false;
    }
};
