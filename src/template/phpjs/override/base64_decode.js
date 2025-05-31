/**
 * base64_decode
 */
module.exports = function base64_decode(string, strict) {
    if (strict === undefined) {
        strict = false;
    }

    try {
        return new TextDecoder().decode(Uint8Array.from(atob(string), (m) => m.codePointAt(0)));
    }
    catch (e) {
        return false;
    }
};
