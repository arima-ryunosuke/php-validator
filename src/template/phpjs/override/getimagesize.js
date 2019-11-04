/**
 * getimagesize
 *
 * 同期は無理なので Promise を返す。
 */
module.exports = function getimagesize(file) {
    if (!file) {
        return;
    }

    return new Promise(function (resolve) {
        try {
            var url = URL.createObjectURL(file);
            var img = new Image();
            img.addEventListener('load', function () {
                resolve([img.width, img.height]);
                URL.revokeObjectURL(img.src);
            });
            img.addEventListener('error', function () {
                resolve(false);
                URL.revokeObjectURL(img.src);
            });
            img.src = url;
        }
        catch (e) {
            console.log(e);
            resolve(false);
        }
    });
};
