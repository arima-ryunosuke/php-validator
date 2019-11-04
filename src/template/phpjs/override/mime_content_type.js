/**
 * mime_content_type
 *
 * ローカルファイルへのアクセスは出来ないので、file を引数に取る。
 */
module.exports = function mime_content_type(file) {
    if (!file) {
        return;
    }
    return file.type;
};
