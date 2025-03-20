/**
 * fnmatch
 *
 * flags は一部のみ対応（そもそも実装自体も手抜きでまともには動かない）。
 */
module.exports = function fnmatch(pattern, filename, flags) {
    let regexPattern = pattern
        .replace(/\./g, "\\.")
        .replace(/\*/g, ".*")
        .replace(/\?/g, ".")
        .replace(/\[!([^\]]+)\]/g, "[^$1]")
        .replace(/\[([^\]]+)\]/g, "[$1]")
    ;

    if (flags & FNM_PATHNAME) {
        regexPattern = regexPattern.replace(/\\*/g, "[^/]*").replace(/\\?/g, "[^/]");
    }

    if (flags & FNM_PERIOD) {
        if (filename.startsWith(".") && !pattern.startsWith(".")) {
            return false;
        }
    }

    const regex = new RegExp("^" + regexPattern + "$");
    return regex.test(filename);
};
