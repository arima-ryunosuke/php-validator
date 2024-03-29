/**
 * mb_strwidth
 *
 * unicode 環境のみ。
 */
module.exports = function mb_strwidth(str) {
    // https://www.php.net/manual/ja/function.mb-strwidth.php
    var fullwidth_points = [
        [0x1100, 0x115F],
        [0x11A3, 0x11A7],
        [0x11FA, 0x11FF],
        [0x2329, 0x232A],
        [0x2E80, 0x2E99],
        [0x2E9B, 0x2EF3],
        [0x2F00, 0x2FD5],
        [0x2FF0, 0x2FFB],
        [0x3000, 0x303E],
        [0x3041, 0x3096],
        [0x3099, 0x30FF],
        [0x3105, 0x312D],
        [0x3131, 0x318E],
        [0x3190, 0x31BA],
        [0x31C0, 0x31E3],
        [0x31F0, 0x321E],
        [0x3220, 0x3247],
        [0x3250, 0x32FE],
        [0x3300, 0x4DBF],
        [0x4E00, 0xA48C],
        [0xA490, 0xA4C6],
        [0xA960, 0xA97C],
        [0xAC00, 0xD7A3],
        [0xD7B0, 0xD7C6],
        [0xD7CB, 0xD7FB],
        [0xF900, 0xFAFF],
        [0xFE10, 0xFE19],
        [0xFE30, 0xFE52],
        [0xFE54, 0xFE66],
        [0xFE68, 0xFE6B],
        [0xFF01, 0xFF60],
        [0xFFE0, 0xFFE6],
        [0x1B000, 0x1B001],
        [0x1F200, 0x1F202],
        [0x1F210, 0x1F23A],
        [0x1F240, 0x1F248],
        [0x1F250, 0x1F251],
        [0x20000, 0x2FFFD],
        [0x30000, 0x3FFFD],
    ];

    var str_width = 0;
    for (var i = 0; i < str.length; i++) {
        var char_code = str.charCodeAt(i);
        if (0xD800 <= char_code && char_code <= 0xDBFF) {
            char_code = ((char_code - 0xD800) * 0x400) + (str.charCodeAt(++i) - 0xDC00) + 0x10000;
        }

        str_width++;
        for (var n = 0; n < fullwidth_points.length; n++) {
            if (fullwidth_points[n][0] <= char_code && char_code <= fullwidth_points[n][1]) {
                str_width++;
                break;
            }
        }
    }

    return str_width;
};
