/**
 * grapheme_strlen
 */
module.exports = function grapheme_strlen(string) {
    const segmenter = new Intl.Segmenter("ja-JP", {granularity: "grapheme"});
    const segments = segmenter.segment(string);
    return Array.from(segments).length;
};
