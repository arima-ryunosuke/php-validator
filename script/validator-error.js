/**
 * エラー表示用処理
 *
 * 基本的に validated イベントを listen することだけが唯一の仕事で、ここでは toast 表示にしている。
 */
(function () {
    // エラー
    document.addEventListener('validated', function (e) {
        toast.call(e.target, e.detail.errorTypes, 'error');
    });

    // 警告
    document.addEventListener('mild-validated', function (e) {
        toast.call(e.target, e.detail.errorTypes, 'warning');
    });

    // DOM が消え去ったときに toast も消えるように監視する
    setInterval(function () {
        var container = document.querySelector('#chmonos-toast-container');
        if (container) {
            Array.prototype.forEach.call(container.children, function (e) {
                var vinput = e.chmonos_vinput;
                if (vinput && vinput.closest('body') === null) {
                    e.remove();
                }
            });
        }
    }, 111);

    function toast(errors, type) {
        var showToast = function (options) {
            var container = document.getElementById('chmonos-toast-container');
            if (!container) {
                document.body.insertAdjacentHTML('beforeend', '<div id="chmonos-toast-container"></div>');
                container = document.getElementById('chmonos-toast-container');
            }

            container.insertAdjacentHTML('beforeend',
                '<div class="validation_message" data-toast-type="' + options.type + '" aria-live="assertive">' +
                '    <button type="button" class="toast-close-button" role="button">&times;</button>' +
                '    <div class="toast-title">' + options.title + '</div>' +
                '    <div class="toast-message">' + options.message + '</div>' +
                '</div>');
            var result = container.lastElementChild;
            result.addEventListener('click', function (e) {
                if (e.target.classList.contains('toast-close-button')) {
                    if (options.onHidden.call(e.target, e) !== false) {
                        e.target.closest('.validation_message').remove();
                    }
                }
                else {
                    if (options.onClick.call(e.target, e) !== false) {
                        // stub
                    }
                }
            });
            return result;
        };
        // 指定要素までスクロールして目立たせる
        var scrollAndBlink = function (input) {
            while (input !== null && input.offsetParent === null) {
                input = input.parentElement;
            }
            if (!input) {
                return;
            }

            (new IntersectionObserver(function (entries, observer) {
                var entry = entries[0];
                if (entry.isIntersecting) {
                    entry.target.classList.add('validatable_blink');
                    entry.target.addEventListener('animationend', function (e) {
                        e.target.classList.remove('validatable_blink');
                    }, {
                        once: true,
                    });
                    observer.unobserve(entry.target);
                }
            }, {
                threshold: 1.0,
            })).observe(input);

            input.scrollIntoView({
                behavior: "smooth",
                block: "center",
            });
        };

        // group 単位で toast を共用する（radio や checkbox でその分表示されても嬉しくない）
        var title = this.dataset.validationTitle;
        var input = this.closest('[data-vinput-group]') || this;

        var TOAST_NAME = 'vinput-toast-' + type;
        var message = errors.toArray ? errors.toArray() : errors;
        if (message.length) {
            var toast = input[TOAST_NAME] || showToast({
                type: type,
                title: title || '',
                message: message.join('\n'),
                onClick: function (e) {
                    scrollAndBlink(input);
                    return false;
                },
                onHidden: function (e) {
                    delete input[TOAST_NAME];
                },
            });
            toast.style.order = Array.prototype.indexOf.call(document.querySelectorAll('.validation_warn, .validation_error'), this) + 1;
            toast.chmonos_vinput = input;
            input[TOAST_NAME] = toast;
        }
        else {
            var toast = input[TOAST_NAME];
            if (toast) {
                toast.remove();
                delete input[TOAST_NAME];
            }
        }
    }
})();
