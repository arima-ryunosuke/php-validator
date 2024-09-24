/**
 * エラー表示用処理
 *
 * 基本的に validated イベントを listen することだけが唯一の仕事で、ここでは toast 表示にしている。
 */
(function () {
    // 実質同じ要素だが、個別要素で飛んでくることがあるので Map でまとめる
    var chmonosMessages = new Map();
    var chmonosMessageTimerId = null;
    var chmonosFormElements = new WeakMap();
    var alternateInput = function (input) {
        for (const [selector, fn] of Object.entries(Chmonos.alternativeInput)) {
            if (input?.matches(selector)) {
                input = fn(input) ?? input;
            }
        }
        return input;
    };

    document.addEventListener('validation-start', function (e) {
        e.target.querySelectorAll('.validatable, [data-vinput-group]').forEach(function (e, i) {
            chmonosFormElements.set(alternateInput(e), i + 1);
        });
    });
    document.addEventListener('validated', function (e) {
        toast.call(e.target, {
            title: e.detail.title,
            warning: e.detail.warningTypes,
            error: e.detail.errorTypes,
            phantoms: e.detail.phantoms,
        });
    });

    // DOM が消え去ったときに toast も消えるように監視する
    setInterval(function () {
        var toasts = document.querySelectorAll('.chmonos-toast-container>.validation_message');
        toasts.forEach(function (toast) {
            var vinput = toast.chmonos_vinput;
            if (vinput && vinput.closest('body') === null) {
                toast.remove();
            }
        });
    }, 111);

    function toast(result) {
        var showToast = function (options) {
            var container = options.container.getElementsByClassName('chmonos-toast-container')[0];
            if (!container) {
                options.container.insertAdjacentHTML('beforeend', '<div class="chmonos-toast-container"></div>');
                container = options.container.getElementsByClassName('chmonos-toast-container')[0];
            }

            if (options.container.dataset.maxToastCount) {
                if (container.children.length >= options.container.dataset.maxToastCount) {
                    options.container.classList.add('chmonos-many-invalid');
                    return undefined;
                }
                else {
                    options.container.classList.remove('chmonos-many-invalid');
                }
            }

            container.insertAdjacentHTML('beforeend',
                '<div class="validation_message" data-toast-type="' + options.type + '" aria-live="assertive">' +
                '    <button type="button" class="toast-close-button" role="button">&times;</button>' +
                '    <div class="toast-title"></div>' +
                '    <div class="toast-message"></div>' +
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
            var blinkee = [input].concat(phantoms).map(function (e) {
                while (e !== null && e.offsetParent === null) {
                    e = e.parentElement;
                }
                if (e === null || !e.classList.contains('validation_' + type)) {
                    return null;
                }

                e = alternateInput(e);
                if (e === input && e.getAttribute('type') === 'dummy') {
                    if (e.dataset.vinputSelector) {
                        e = e.closest('form')?.querySelector(e.dataset.vinputSelector);
                    }
                    else {
                        e = e.closest('form')?.querySelector(`[data-vtemplate-name="${e.dataset.vinputClass}"]`)?.parentNode;
                    }
                }
                return e;
            }).filter(e => e !== null);

            blinkee = [...new Set(blinkee)];
            if (blinkee[0]) {
                var blinker = new IntersectionObserver(function (entries, observer) {
                    entries.forEach(function (entry) {
                        if (entry.isIntersecting) {
                            for (const target of blinkee) {
                                target.classList.add('validatable_blink_' + type);
                                target.addEventListener('animationend', function (e) {
                                    e.target.classList.remove('validatable_blink_' + type);
                                }, {
                                    once: true,
                                });
                            }
                            observer.unobserve(entry.target);
                        }
                    });
                }, {
                    threshold: 1.0,
                });
                blinker.observe(blinkee[0]);
                blinkee[0].scrollIntoView({
                    behavior: "smooth",
                    block: "center",
                    inline: "center",
                });
            }
        };

        var input = alternateInput(this);
        var messages = chmonosMessages.get(input) ?? {};
        for (const type of ['error', 'warning']) {
            messages.title = result.title;
            messages.phantoms = (messages.phantoms ?? []).concat(result.phantoms);
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
                            container: input.closest('form'),
                            type: type,
                            onClick: function (e) {
                                scrollAndBlink(input, [...new Set(messages.phantoms)], type);
                                return false;
                            },
                            onHidden: function (e) {
                                delete input[TOAST_NAME];
                            },
                        });
                        if (toast) {
                            toast.querySelector('.toast-title').textContent = messages.title ?? '';
                            toast.querySelector('.toast-message').textContent = [...new Set(messages[type])].join('\n');
                            toast.style.order = chmonosFormElements.get(input) ?? 0;
                            toast.chmonos_vinput = input;
                            input[TOAST_NAME] = toast;
                        }
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

    // グローバル設定
    Chmonos.notifyValidation = toast;
    Chmonos.alternativeInput = {
        "*": e => e.closest('[data-vinput-group]') ?? e,
        // "example-selector": e => e.closest('.selector-wrapper'),
    };
})();
