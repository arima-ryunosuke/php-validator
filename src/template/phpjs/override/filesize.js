/**
 * filesize
 *
 * ローカルファイルへのアクセスは出来ないので、file を引数に取る。
 */
module.exports = function filesize(file) {
    if (!file) {
        return;
    }
    return file.size;
};
