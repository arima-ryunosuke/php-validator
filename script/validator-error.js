/**
 * エラー表示用処理
 *
 * 基本的に validated イベントを listen することだけが唯一の仕事で、ここでは toast 表示にしている。
 */
(function () {
    // エラー
    document.addEventListener('validated', function (e) {
        toast.call(e.target, {
            warning: e.detail.warningTypes,
            error: e.detail.errorTypes,
            phantoms: e.detail.phantoms,
        });
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

    // 実質同じ要素だが、個別要素で飛んでくることがあるので Map でまとめる
    var chmonosMessages = new Map();
    var chmonosMessageTimerId = null;

    function toast(result) {
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
        var scrollAndBlink = function (input, phantoms, type) {
            var blinker = new IntersectionObserver(function (entries, observer) {
                entries.forEach(function (entry) {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('validatable_blink_' + type);
                        entry.target.addEventListener('animationend', function (e) {
                            e.target.classList.remove('validatable_blink_' + type);
                        }, {
                            once: true,
                        });
                        observer.unobserve(entry.target);
                    }
                });
            }, {
                threshold: 1.0,
            });
            var blinkee = [input].concat(phantoms).map(function (e) {
                while (e !== null && e.offsetParent === null) {
                    e = e.parentElement;
                }
                if (e !== null && e.getAttribute('type') === 'dummy') {
                    if (e.dataset.vinputSelector) {
                        e = e.closest('form')?.querySelector(e.dataset.vinputSelector);
                    }
                    else {
                        e = e.closest('form')?.querySelector(`[data-vtemplate-name="${e.dataset.vinputClass}"]`)?.parentNode;
                    }
                }
                return e;
            }).filter(e => e !== null);
            blinkee.forEach(e => blinker.observe(e));

            blinkee[0]?.scrollIntoView({
                behavior: "smooth",
                block: "center",
            });
        };

        // group 単位で toast を共用する（radio や checkbox でその分表示されても嬉しくない）
        var title = this.dataset.validationTitle;
        var input = this.closest('[data-vinput-group]') || this;

        var messages = chmonosMessages.get(input) ?? {};
        for (const type of ['error', 'warning']) {
            messages.title = title;
            messages[type] = (messages[type] ?? []).concat(result[type].toArray ? result[type].toArray() : result[type]);
        }
        chmonosMessages.set(input, messages);

        clearTimeout(chmonosMessageTimerId);
        chmonosMessageTimerId = setTimeout(function () {
            for (const [input, messages] of chmonosMessages) {
                for (const type of ['error', 'warning']) {
                    let TOAST_NAME = 'vinput-toast-' + type;
                    if (messages[type].length) {
                        var toast = input[TOAST_NAME] || showToast({
                            type: type,
                            title: messages.title || '',
                            message: "",
                            onClick: function (e) {
                                scrollAndBlink(input, result.phantoms, type);
                                return false;
                            },
                            onHidden: function (e) {
                                delete input[TOAST_NAME];
                            },
                        });
                        toast.querySelector('.toast-message').innerHTML = [...new Set(messages[type])].join('\n');
                        toast.style.order = Array.prototype.indexOf.call(document.querySelectorAll('.validation_warning, .validation_error'), this) + 1;
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
            }
            chmonosMessages.clear();
        });
    }
})();
