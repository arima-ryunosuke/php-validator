/*
 * This file is auto generated from ryunosuke Validator Library.
 *
 * エラー表示周りには一切関与しない。
 * 入力枠が赤くなるのは css の仕事だし、 validated イベントが発火するので利用側でよしなに設定すれば良い。
 *
 * from:
 * Copyright (c) 2007-2016 Kevin van Zonneveld (http://kvz.io)
 * and Contributors (http://locutus.io/authors)
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy of
 * this software and associated documentation files (the "Software"), to deal in
 * the Software without restriction, including without limitation the rights to
 * use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies
 * of the Software, and to permit persons to whom the Software is furnished to do
 * so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

function Chmonos(form, options) {
    var chmonos = this;

    // locutus が nodejs 仕様なので格納用 module と ダミー require を用意
    var module = {};
    var require = function (path) {
        var parts = path.split('/');
        return chmonos[parts[parts.length - 1]];
    };

    chmonos.phpjs = {};

    /// phpjs のインポート
    /*<?php $echo_constant($jsconsts); ?>*/
    /*<?php $echo_function($jsfuncs); ?>*/

    /// 検証ルールのインポート
    /*<?php $echo_keyvalue('condition', $condition); ?>*/

    /// エラー定数のインポート
    /*<?php $echo_keyvalue('constants', $constants); ?>*/

    /// エラー文言のインポート
    /*<?php $echo_keyvalue('messages', $messages); ?>*/

    /// 初期化（コンストラクション）

    // noinspection JSUnresolvedFunction
    setlocale('LC_CTYPE', 'en_US'); // for ctype_digit()

    /// 内部用

    function isPlainObject(obj) {
        if (typeof (obj) !== 'object' || obj.nodeType || obj === obj.window) {
            return false;
        }
        return !(obj.constructor && !{}.hasOwnProperty.call(obj.constructor.prototype, 'isPrototypeOf'));
    }

    function resolveDepend(input, contains, already) {
        contains.group = contains.group ?? true;
        contains.phantom = contains.phantom ?? true;
        contains.propagate = contains.propagate ?? false;

        already = already ?? new Set();

        var elemName = input.dataset.vinputClass;
        var rule = options.allrules[elemName];

        const add = function (input) {
            if (already.has(input)) {
                return;
            }
            already.add(input);
            resolveDepend(input, contains, already);
        };

        add(input);

        if (contains.group) {
            if (input.type === 'radio' || input.type === 'checkbox') {
                for (const e of form.querySelectorAll("input[name='" + input.name + "'].validatable")) {
                    add(e);
                }
            }
        }
        if (contains.phantom) {
            rule?.phantom?.forEach(function (phantom) {
                for (const e of chmonos.brother(input, phantom)) {
                    add(e);
                }
            });
        }
        if (contains.propagate) {
            Object.entries(rule?.propagate ?? {}).forEach(function ([propagate, mode]) {
                for (const e of chmonos.brother(input, propagate)) {
                    if (['preceding', 'following'].includes(mode)) {
                        const pos = input.compareDocumentPosition(e);
                        if (mode === 'preceding' && !(pos & Node.DOCUMENT_POSITION_PRECEDING)) {
                            continue;
                        }
                        if (mode === 'following' && !(pos & Node.DOCUMENT_POSITION_FOLLOWING)) {
                            continue;
                        }
                    }
                    add(e);
                }
            });
        }

        return already;
    }

    chmonos.functionCache ??= new Map();

    function templateFunction(vars) {
        var entries = Object.entries(vars).filter(([k]) => k.match(/^[$_a-z0-9]+$/i));
        var args = entries.map(e => e[0]);
        var vals = entries.map(e => e[1]);
        var argstring = args.join(',');

        return function (template, tag) {
            try {
                const cachekey = `${tag}@${template}(${argstring})`;
                if (!chmonos.functionCache.has(cachekey)) {
                    chmonos.functionCache.set(cachekey, new Function(...args, 'return ' + (tag ?? '') + '`' + template + '`'));
                }
                return chmonos.functionCache.get(cachekey).call(vars, ...vals);
            }
            catch (e) {
                console.error(e);
            }
        };
    }

    function renderVnode(vnode, values) {
        const F = templateFunction(values);
        try {
            if (vnode.nextElementSibling.matches('[data-vrender]')) {
                vnode.nextElementSibling.remove();
            }

            vnode.insertAdjacentHTML('afterend', F(vnode.outerHTML, vnode.dataset.vnode));
            const rendered = vnode.nextElementSibling;
            delete rendered.dataset.vnode;
            rendered.dataset.vrender = true;

            rendered.dispatchEvent(new CustomEvent('rendered', {
                bubbles: true,
                detail: {
                    vnode: vnode,
                    values: values,
                },
            }));
        }
        catch (e) {
            console.error(e);
        }
    }

    function addError(input, result) {
        const inputs = resolveDepend(input, {
            group: true,
            phantom: true,
            propagate: false,
        });
        var warningTypes = result.warning ?? {};
        var errorTypes = result.error ?? {};
        var isWarning = Object.keys(warningTypes).length;
        var isError = Object.keys(errorTypes).length;

        input.validationWarnings = Object.assign(input.validationWarnings ?? {}, warningTypes);
        input.validationErrors = Object.assign(input.validationErrors ?? {}, errorTypes);
        inputs.forEach(function (input) {
            input.validationWarnings = Object.assign(input.validationWarnings ?? {}, isWarning ? {"": []} : {});
            input.validationErrors = Object.assign(input.validationErrors ?? {}, isError ? {"": []} : {});
        });

        if (isError) {
            return true;
        }
        if (isWarning) {
            return null;
        }
        return false;
    }

    function notifyError(input, okclass) {
        const inputs = resolveDepend(input, {
            group: true,
            phantom: true,
            propagate: true,
        });
        inputs.forEach(function (input) {
            var isWarning = Object.keys(input.validationWarnings ?? {}).length;
            var isError = Object.keys(input.validationErrors ?? {}).length;
            if (isWarning) {
                input.classList.add('validation_warning');
            }
            if (isError) {
                input.classList.add('validation_error');
            }
            if (isWarning || isError) {
                input.classList.remove('validation_ok');
            }
            else {
                input.classList.remove('validation_warning');
                input.classList.remove('validation_error');
                input.classList.remove('validation_ok');
                if (okclass && chmonos.value(input)?.length > 0) {
                    input.classList.add('validation_ok');
                }
            }
        });
        // {condition: {errortype: message}} => {errortype: message} in future scope
        [input.validationWarnings ?? {}, input.validationErrors ?? {}].forEach(function (types) {
            if (!types.hasOwnProperty('toArray')) {
                Object.defineProperty(types, 'toArray', {
                    value: function () {
                        var collectMessage = function (errors) {
                            var message = [];
                            Object.keys(errors).forEach(function (e) {
                                if (typeof (errors[e]) === 'string') {
                                    message.push(errors[e]);
                                }
                                else {
                                    message = message.concat(collectMessage(errors[e]));
                                }
                            });
                            return message;
                        };
                        return collectMessage(this);
                    },
                });
            }
        });
        input.dispatchEvent(new CustomEvent('validated', {
            bubbles: true,
            detail: {
                title: input.dataset.validationTitle ?? null,
                warningTypes: input.validationWarnings ?? {},
                errorTypes: input.validationErrors ?? {},
                phantoms: [...inputs],
            },
        }));
    }

    function validateInputs(inputs, evt) {
        if (chmonos.validationDisabled) {
            return Promise.resolve([]);
        }

        const validation_id = new Date().getTime();
        const promises = [];

        form.validationValues = undefined;

        inputs.forEach(function (input) {
            input.validationWarnings = {};
            input.validationErrors = {};
        });
        inputs.forEach(function (input) {
            // propagate などで同一要素に検証が走ることがあるので一意な ID を持たせて、同一 ID ならスルーするようにする
            var vid = input.validationId;
            if (vid !== undefined && vid === validation_id) {
                return;
            }
            input.validationId = validation_id;

            var elemName = input.dataset.vinputClass;
            var rule = options.allrules[elemName];
            if (rule === undefined) {
                return;
            }

            var phantom = rule['phantom'];
            if (phantom.length) {
                var flag = true;
                var brothers = [];
                for (var i = 1; i < phantom.length; i++) {
                    var inputs = chmonos.brother(input, phantom[i]);
                    var target = chmonos.value(inputs[0]);
                    if (target.length === 0) {
                        flag = false;
                        break;
                    }
                    brothers.push(target);
                }
                // noinspection JSUnresolvedFunction
                input.value = flag ? vsprintf(phantom[0], brothers) : '';
            }

            var fields = chmonos.fields(input);
            chmonos.required(input, fields);

            if (!rule['invisible'] && input.type !== 'hidden' && input.offsetParent === null) {
                return;
            }

            if (input.disabled) {
                return;
            }

            var condition = rule['condition'];
            var value = chmonos.value(input);
            var errorTypes = {warning: {}, error: {}};
            var asyncs = [];
            var keys = Object.keys(condition);
            for (var k = 0; k < keys.length; k++) {
                let cond = condition[keys[k]];
                let cname = cond.cname;
                let level = cond.level;
                var error = function (err, vars) {
                    if (evt.chmonosSubtypes) {
                        if (evt.chmonosSubtypes.includes('noerror')) {
                            return;
                        }
                        if (evt.chmonosSubtypes.includes('norequire') && cname === 'Requires' && input !== evt.target && evt.target.tagName !== 'FORM') {
                            return;
                        }
                    }

                    if (err === true || err === undefined) {
                        if (input.validationErrors && input.validationErrors[cname]) {
                            errorTypes[level][cname] = input.validationErrors[cname];
                        }
                    }
                    else if (err === null) {
                        delete errorTypes[level][cname];
                    }
                    else if (err instanceof Promise) {
                        asyncs.push(err);
                    }
                    else if (isPlainObject(err)) {
                        if (errorTypes[level][cname] === undefined) {
                            errorTypes[level][cname] = {};
                        }
                        Object.keys(err).forEach(function (mk) {
                            errorTypes[level][cname][mk] = err[mk];
                        });
                    }
                    else {
                        if (err === false) {
                            err = chmonos.constants[cname]['INVALID'];
                        }

                        var ret;
                        if (cond['message'][err] !== undefined) {
                            ret = cond['message'][err];
                        }
                        else if (chmonos.messages[cname][err] !== undefined) {
                            ret = chmonos.messages[cname][err];
                        }
                        else {
                            ret = err;
                        }
                        if (errorTypes[level][cname] === undefined) {
                            errorTypes[level][cname] = {};
                        }

                        const values = Object.assign({
                            "$resolveTitle": function (member, target) {
                                target = target ?? input;
                                return chmonos.brother(target, member)[0]?.dataset?.validationTitle ?? member;
                            },
                            "$resolveLabel": function (value, target) {
                                target = target ?? input;
                                if (target.tagName === 'SELECT') {
                                    return target.querySelector('option[value="' + value + '"]')?.label ?? '';
                                }
                                else {
                                    return form.querySelector('input[name="' + target.name + '"][value="' + value + '"]')?.labels[0]?.textContent ?? '';
                                }
                            },
                            'value': value,
                        }, chmonos.phpjs, Object.fromEntries(vars ?? []), Object.fromEntries(Object.entries(cond['param']).map(kv => ['_' + kv[0], kv[1]])));

                        let message = templateFunction(values)(ret);
                        if (errorTypes[level][cname][err] != null) {
                            message = errorTypes[level][cname][err] + '\n' + message;
                        }
                        errorTypes[level][cname][err] = message;
                    }
                };
                // 値が空の場合は Requires しか検証しない（空かどうかの制御を他の condition に任せたくない）
                // value は必ず length を持つように制御してるので「空・未入力」の判定は length === 0 で OK
                if (value.length > 0 || cname === 'Requires') {
                    var values = cond['arrayable'] ? [value] : chmonos.context.cast('array', value);
                    Object.keys(values).forEach(function (v) {
                        try {
                            asyncs.push(chmonos.condition[cname](input, values[v], fields, cond['param'], chmonos.constants[cname], error, chmonos.context, evt));
                        }
                        catch (e) {
                            error(chmonos.constants[cname]['INVALID']);
                            console.error(e);
                        }
                    });
                }
            }

            // ラジオボタンやチェックボックスなどはこれ以上無駄なので検証 ID を放り込んでおく
            if (input.type === 'radio' || input.type === 'checkbox') {
                form.querySelectorAll("input[name='" + input.name + "'].validatable").forEach(function (e) {
                    e.validationId = validation_id;
                });
            }

            promises.push(new Promise(function (resolve) {
                Promise.all(asyncs).then(function () {
                    resolve(addError(input, errorTypes));
                });
            }));
        });

        return Promise.all(promises).then(function (results) {
            inputs.forEach(function (input) {
                notifyError(input, true);
            });
            return results;
        });
    }

    /// 外部用

    chmonos.context = {
        lang: 'javascript',
        chmonos: chmonos,
        "function": function (callback) {
            var arg = Array.from(arguments).slice(1);
            return function () {
                return callback.apply(null, Array.from(arguments).concat(arg));
            };
        },
        "foreach": function (array, callback) {
            var arg = Array.from(arguments);
            var keys = Object.keys(array);
            for (var i = 0; i < keys.length; i++) {
                arg[0] = keys[i];
                arg[1] = array[keys[i]];
                if (callback.apply(null, arg) === false) {
                    return false;
                }
            }
            return true;
        },
        "cast": function (type, value) {
            // 現状は array/object のみ実装
            if (type === 'array') {
                if (value === null) {
                    return [];
                }
                if (value instanceof Array || isPlainObject(value)) {
                    return value;
                }
                return [value];
            }
            if (type === 'object') {
                if (value === null) {
                    return {};
                }
                if (value instanceof Array || isPlainObject(value)) {
                    return value;
                }
                return {scalar: value};
            }
            throw "invalid cast type";
        },
        "str_concat": function () {
            return Array.from(arguments).join('');
        },
    };

    chmonos.valuesMap ??= new WeakMap();
    chmonos.customValidation = {
        before: [],
        after: [],
        warning: [],
    };

    chmonos.initialize = function (values) {
        // js レンダリング
        Object.keys(values || {}).forEach(function (tname) {
            var template = form.querySelector('[data-vtemplate-name="' + tname + '"]');
            if (template) {
                var curValues = values[tname] || {};
                var eventArgs = {
                    detail: {
                        values: curValues,
                    },
                };
                template.dispatchEvent(new CustomEvent('spawnBegin', eventArgs));
                Object.keys(curValues).forEach(function (index) {
                    chmonos.spawn(template, null, curValues[index], index);
                });
                template.dispatchEvent(new CustomEvent('spawnEnd', eventArgs));
            }
        });
        // form 全体（inputs 要素は spawn がやってくれているので除外）
        form.querySelectorAll('[data-vnode]:not([data-vinputs-name] [data-vnode])').forEach(function (vnode) {
            renderVnode(vnode, values);
        });

        // 超簡易リアクティブ（一括 trigger 等もあるだろうので setTimeout で逃がす）
        const render_timers = new WeakMap();
        form.addEventListener('input', function (e) {
            if (e.target.matches('.validatable')) {
                const parent = e.target.closest('[data-vinputs-name]') ?? form;
                clearTimeout(render_timers.get(parent));
                render_timers.set(parent, setTimeout(function () {
                    const selector = parent === form ? '[data-vnode]:not([data-vinputs-name] [data-vnode])' : '[data-vnode]';
                    const vnodes = parent.querySelectorAll(selector);
                    if (vnodes.length) {
                        const values = chmonos.getValues(parent, parent === form);
                        vnodes.forEach(function (vnode) {
                            renderVnode(vnode, values);
                        });
                    }
                }, 10));
            }
        });

        // サーバー側の結果を表示
        if (Object.keys(options.errors).length) {
            chmonos.setErrors(options.errors);
        }

        // 必須マーク
        form.querySelectorAll('.validatable:is(input, textarea, select)').forEach(function (input) {
            if (!input.disabled) {
                chmonos.required(input);
            }
        });

        // イベントをバインド
        var handler = async function (e) {
            // keyup における Tab はすでに項目が遷移している
            if (e.type === 'keyup' && e.keyCode === 9) {
                return;
            }
            var elemName = e.target.dataset.vinputClass;
            if (options.allrules[elemName] === undefined) {
                return;
            }
            for (var i = 0; i < options.allrules[elemName]['event'].length; i++) {
                var eventName = options.allrules[elemName]['event'][i];
                var parts = eventName.split('.');
                if (e.type === parts[0]) {
                    e.chmonosSubtypes = parts.slice(1);
                    const inputs = resolveDepend(e.target, {
                        group: true,
                        phantom: true,
                        propagate: true,
                    });
                    form.dispatchEvent(new CustomEvent('validation-start', {
                        bubbles: true,
                        detail: {
                            inputs: inputs,
                        },
                    }));
                    await validateInputs(inputs, e);
                    form.dispatchEvent(new CustomEvent('validation-end', {
                        bubbles: true,
                        detail: {
                            inputs: inputs,
                        },
                    }));
                    break;
                }
            }
        };
        // ありそうなイベントを全て listen して呼び出し時に要素単位でチェックする。この候補は割と気軽に追加して良い
        ['change', 'keyup', 'keydown', 'input', 'click', 'focusin', 'focusout'].forEach(function (event) {
            form.addEventListener(event, handler);
        });
        // ファイルドロップイベントも組み込みで実装する
        form.addEventListener('dragenter', function (e) {
            if (e.target.matches('.vfile-dropzone')) {
                e.preventDefault();

                e.target.classList.add('vfile-dragging');
            }
        });
        form.addEventListener('dragover', function (e) {
            if (e.target.matches('.vfile-dropzone')) {
                e.preventDefault();

                const file = e.target.querySelector('input[type=file]') ?? document.getElementById(e.target.getAttribute('for'));
                if (!file || file.multiple || e.dataTransfer.items.length <= 1) {
                    e.dataTransfer.dropEffect = e.target.dataset.dropEffect ?? 'copy';
                }
                else {
                    e.dataTransfer.dropEffect = 'none';
                }
            }
        });
        form.addEventListener('dragleave', function (e) {
            if (e.target.matches('.vfile-dropzone')) {
                e.preventDefault();

                e.target.classList.remove('vfile-dragging');
            }
        });
        form.addEventListener('drop', function (e) {
            if (e.target.matches('.vfile-dropzone')) {
                e.preventDefault();

                e.target.classList.remove('vfile-dragging');

                e.target.dispatchEvent(new CustomEvent('filedrop', {
                    bubbles: true,
                    detail: {
                        files: e.dataTransfer.files,
                    },
                }));

                const file = e.target.querySelector('input[type=file]') ?? document.getElementById(e.target.getAttribute('for'));
                if (file && (file.multiple || e.dataTransfer.items.length <= 1)) {
                    file.files = e.dataTransfer.files;
                    file.dispatchEvent(new Event('change', {bubbles: true}));
                }
            }
        });

        // サブミット時にバリデーション（button-click の方に寄せたいが互換性のため data 属性でタイミングを分ける）
        const isValid = async function (e) {
            return await chmonos.validate(e) ?? await (async function () {
                for (const f of chmonos.customValidation.warning) {
                    if ((await f.call(form)) === false) {
                        return false;
                    }
                }
                return true;
            })();
        };
        const navigate = function (e) {
            var array = (e.submitter?.getAttribute('formenctype') ?? '').includes('array=delimitable');
            if (!array && !e.submitter?.hasAttribute('formenctype')) {
                array = (form.getAttribute('enctype') ?? '').includes('array=delimitable');
            }
            if (array && (e.submitter?.formMethod || form.method) === 'get') {
                e.preventDefault();
                var target = e.submitter?.formTarget || form.target;
                if (target) {
                    window.open(chmonos.url(e.submitter), target);
                }
                else {
                    location.href = chmonos.url(e.submitter);
                }
                return true;
            }
        };
        const submit = async function (e, listener) {
            e.preventDefault();

            if (!await isValid(e)) {
                return false;
            }
            if (navigate(e)) {
                return false;
            }

            var submittingEvent = new CustomEvent('submitting', {
                bubbles: true,
                cancelable: true,
                detail: {
                    submitter: e.submitter ?? null,
                },
            });
            var submittedEvent = new CustomEvent('submitted', {
                bubbles: true,
                detail: {
                    submitter: e.submitter ?? null,
                },
            });
            setTimeout(function () {
                // @see https://developer.mozilla.org/ja/docs/Web/API/HTMLFormElement/submit
                form.removeEventListener('submit', listener);
                if (form.dispatchEvent(submittingEvent)) {
                    form.requestSubmit(e.submitter ?? null);
                    form.dispatchEvent(submittedEvent);
                }
                form.addEventListener('submit', listener);
            }, 0);
        };
        if (form.dataset.validationEvent === 'click') {
            // window の capture フェーズで可能な限り最速にしてキャンセルされないようにする
            window.addEventListener('click', function click(e) {
                if (e.isTrusted && e.target.form === form && e.target.matches('button[type=submit], input[type=submit], input[type=image]')) {
                    e.validationPromise = new Promise(async function (resolve, reject) {
                        try {
                            const valid = await isValid(e);
                            // 一つでも非同期を含むと e.preventDefault は効かないので注意
                            // その意味ではここで呼ぶ必然性はないが、非同期を含む方が稀で、呼ばないと呼び元で強制しなければならなくなる
                            if (!valid) {
                                e.preventDefault();
                            }
                            resolve(valid);
                        }
                        catch (e) {
                            reject(e);
                        }
                    });
                }
            }, {capture: true});

            // ↑で検証済みなので submit では検証不要（submitter が居る場合）
            form.addEventListener('submit', async function submitHandler(e) {
                if (e.submitter) {
                    navigate(e);
                    return;
                }
                return await submit(e, submitHandler);
            });
        }
        else {
            form.addEventListener('submit', async function submitHandler(e) {
                return await submit(e, submitHandler);
            });
        }
    };

    chmonos.addCustomValidation = function (validation, timing) {
        timing = timing || 'after';
        chmonos.customValidation[timing].push(validation);
    };

    chmonos.validate = async function (evt, selector, inputs) {
        form.validationValues = undefined;
        evt = evt || new CustomEvent('vatidation');

        inputs ??= form.querySelectorAll('.validatable:is(input, textarea, select)');
        if (selector) {
            inputs = Array.from(inputs).filter((e) => e.matches(selector));
        }

        for (const f of chmonos.customValidation.before) {
            if ((await f.call(form)) === false) {
                return false;
            }
        }

        form.dispatchEvent(new CustomEvent('validation-start', {
            bubbles: true,
            detail: {
                inputs: inputs,
            },
        }));
        const results = await validateInputs(inputs, evt);
        form.dispatchEvent(new CustomEvent('validation-end', {
            bubbles: true,
            detail: {
                inputs: inputs,
            },
        }));

        for (const f of chmonos.customValidation.after) {
            if ((await f.call(form)) === false) {
                return false;
            }
        }

        if (results.includes(true)) {
            return false;
        }
        if (results.includes(null)) {
            return null;
        }
        return true;
    };

    chmonos.getValues = function (fragment, notrows) {
        fragment ??= form;
        notrows ??= false;
        var values = {};
        fragment.querySelectorAll('.validatable:is(input, textarea, select):enabled').forEach(function (e) {
            // chmonos.value が行全体を返すので form の場合は不要
            if (fragment === form && e.dataset.vinputIndex) {
                return;
            }
            if (notrows && e.matches('[type=dummy]')) {
                return;
            }
            var name = e.dataset.vinputName;
            if (name === undefined) {
                return;
            }
            var value = chmonos.value(e);
            if (value === undefined) {
                return;
            }

            values[name] = value;
        });
        return values;
    };

    chmonos.setValues = function (fragment, values) {
        fragment ??= form;
        Object.keys(values).forEach(function (key) {
            var index = 0;
            if (values[key] !== null) {
                fragment.querySelectorAll('.validatable:is([data-vinput-name="' + key + '"])').forEach(function (e) {
                    if (e.type === 'file') {
                        return;
                    }
                    if (e.type === 'checkbox' || e.type === 'radio') {
                        var vv = (values[key] instanceof Array ? values[key] : [values[key]]).map(function (x) {return '' + (+x);});
                        e.checked = vv.indexOf(e.value) >= 0;
                    }
                    else if (e.type === 'select-multiple') {
                        var vv = (values[key] instanceof Array ? values[key] : [values[key]]).map(function (x) {return '' + x;});
                        e.querySelectorAll('option').forEach(function (o) {
                            o.selected = vv.indexOf(o.value) >= 0;
                        });
                    }
                    else {
                        if (values[key] instanceof Array) {
                            e.value = values[key][index++] || '';
                        }
                        else {
                            e.value = values[key];
                        }
                    }
                });
            }
        });
    };

    chmonos.setErrors = function (emessages) {
        var errorTypes = {};
        // {input: {condition: {errortype: message}}} => {input: {errortype: message}} in future scope
        var flatize = function (current, parent, child, grand) {
            Object.keys(current).forEach(function (key) {
                var value = current[key];
                if (typeof (value) === 'string') {
                    if (errorTypes[child] === undefined) {
                        errorTypes[child] = {};
                    }
                    if (errorTypes[child][grand] === undefined) {
                        errorTypes[child][grand] = {};
                    }
                    errorTypes[child][grand][key] = value;
                }
                else {
                    flatize(value, (parent ? parent + '/' : '') + key, parent, key);
                }
            });
        };
        flatize(emessages, '', '', '');

        const inputs = form.querySelectorAll('.validatable:is(input, textarea, select)');
        inputs.forEach(function (input) {
            addError(input, {error: errorTypes[input.dataset.vinputId] || {}});
        });
        form.dispatchEvent(new CustomEvent('validation-start', {
            bubbles: true,
            detail: {
                inputs: inputs,
            },
        }));
        inputs.forEach(function (input) {
            notifyError(input);
        });
        form.dispatchEvent(new CustomEvent('validation-end', {
            bubbles: true,
            detail: {
                inputs: inputs,
            },
        }));
    };

    chmonos.clearErrors = function () {
        const inputs = form.querySelectorAll('.validatable:is(input, textarea, select)');
        inputs.forEach(function (input) {
            input.validationWarnings = {};
            input.validationErrors = {};
            input.classList.remove('validation_warning');
            input.classList.remove('validation_error');
        });
        inputs.forEach(function (input) {
            notifyError(input);
        });
    };

    /**
     * Array 要素を新しく生成して返す
     *
     * @param template string|template|script
     * @param values 初期値
     * @param index 追加するインデックス
     */
    chmonos.birth = function (template, values, index) {
        var rootTag;
        var fragment;
        if ('content' in template) {
            fragment = template.content.cloneNode(true);
            rootTag = fragment.firstElementChild.tagName;
        }
        else {
            var parent;
            fragment = document.createDocumentFragment();
            if (typeof (template) === 'string') {
                parent = document.createElement('div');
                parent.innerHTML = template;
                rootTag = template.match(/<([a-z0-9_-]+)/i)[1];
            }
            else {
                parent = document.createElement(template.parentNode.tagName);
                parent.innerHTML = template.textContent;
                rootTag = template.textContent.match(/<([a-z0-9_-]+)/i)[1];
            }
            while (parent.firstChild) {
                fragment.appendChild(parent.firstChild);
            }
        }

        if (template.dataset) {
            var template_name = template.dataset.vtemplateName;
            if (template_name && index === undefined) {
                index = Array.from(form.querySelectorAll('[data-vinputs-name]')).reduce(function (result, current) {
                    return Math.min(result, +current.dataset.vinputIndex);
                }, 0) - 1;
            }
        }

        function resetIndex(node, name, index) {
            var newval = (node.getAttribute(name) || '').replace(/__index/, index);
            if (newval) {
                node.setAttribute(name, newval);
            }
        }

        Array.from(fragment.querySelectorAll('[for*=__index]')).forEach(function (e) {
            resetIndex(e, 'for', index);
            resetIndex(e, 'data-vlabel-id', index);
            resetIndex(e, 'data-vlabel-index', index);
            resetIndex(e, 'data-vlabel-parent', index);
        });
        Array.from(fragment.querySelectorAll('[name*=__index]')).forEach(function (e) {
            resetIndex(e, 'id', index);
            resetIndex(e, 'name', index);
            resetIndex(e, 'data-vinput-id', index);
            resetIndex(e, 'data-vinput-index', index);
            resetIndex(e, 'data-vinput-parent', index);
        });
        Array.from(fragment.querySelectorAll('[data-vinput-wrapper],[data-vinput-group]')).forEach(function (e) {
            resetIndex(e, 'data-vinput-wrapper', index);
            resetIndex(e, 'data-vinput-group', index);
        });
        if (values) {
            chmonos.setValues(fragment, values);
        }
        Array.from(fragment.querySelectorAll('.validatable:is(input, textarea, select)')).forEach(function (e) {
            if (!e.disabled) {
                chmonos.required(e, undefined, fragment);
            }
        });

        var node = fragment.querySelector(rootTag);
        node.dataset.vinputsName = template.dataset.vtemplateName;
        node.dataset.vinputIndex = index;
        chmonos.valuesMap.set(node, values ?? {});

        if (values) {
            values = Object.assign(chmonos.getValues(fragment), values);
            node.querySelectorAll('[data-vnode]').forEach(function (vnode) {
                renderVnode(vnode, values);
            });
        }

        return node;
    };

    /**
     * 子要素を生み出す（新規追加）
     *
     * @param template Array 要素名か
     * @param callback 追加処理
     * @param values 初期値
     * @param index 追加するインデックス
     */
    chmonos.spawn = function (template, callback, values, index) {
        if (typeof (template) === 'string') {
            template = form.querySelector('[data-vtemplate-name="' + template + '"]');
        }

        var node = chmonos.birth(template, values, index);

        template.dispatchEvent(new CustomEvent('spawn', {
            detail: {
                node: node,
                index: index ?? +node.dataset.vinputIndex,
                values: values ?? {},
            },
        }));

        callback = callback || function (node) {this.parentNode.appendChild(node);};
        callback.call(template, node);
        return node;
    };

    /**
     * 子要素を生み出す（ベース NODE 指定）
     *
     * @param template Array 要素名か
     * @param callback 追加処理
     * @param values 初期値
     * @param baseNode ベース NODE
     */
    chmonos.respawn = function (template, callback, values, baseNode) {
        var node = chmonos.spawn(template, () => null, Object.assign({}, chmonos.valuesMap.get(baseNode) ?? {}, chmonos.getValues(baseNode), values));
        callback = callback || function (node, base) {base.after(node);};
        callback.call(template, node, baseNode);
        return node;
    };

    /**
     * 子要素を再設定する
     *
     * @param baseNode ベース NODE
     * @param values 初期値
     */
    chmonos.rebirth = function (baseNode, values) {
        chmonos.setValues(baseNode, values);

        baseNode.querySelectorAll('[data-vnode]').forEach(function (vnode) {
            renderVnode(vnode, values);
        });
    };

    /**
     * spawn で生み出したノードを削除する
     *
     * @param template テンプレート script
     * @param node 削除する子要素
     * @param callback 削除処理
     */
    chmonos.cull = function (template, node, callback) {
        if (typeof (template) === 'string') {
            template = form.querySelector('[data-vtemplate-name="' + template + '"]');
        }

        template.dispatchEvent(new CustomEvent('cull', {
            detail: {
                node: node,
            },
        }));

        callback = callback || function (node) {this.parentNode.removeChild(node);};
        callback.call(template, node);
        return node;
    };

    /**
     * Array 要素の兄弟要素を返す
     *
     * @param id input-id のプレフィックス
     */
    chmonos.sibling = function (id) {
        var siblings = new Map();
        form.querySelectorAll('[data-vinput-class^="' + id + '/"].validatable').forEach(function (e) {
            var index = e.dataset.vinputIndex;
            var klass = e.dataset.vinputClass;
            var elems = siblings.get(index) || {};
            if (elems[klass] === undefined) {
                elems[klass] = e;
            }
            else {
                if (!(elems[klass] instanceof Array)) {
                    elems[klass] = [elems[klass]];
                }
                elems[klass].push(e);
            }
            siblings.set(index, elems);
        });
        return siblings;
    };

    /**
     * 自身の兄弟要素を返す
     *
     * @todo 改修に次ぐ改修でカオスになってるので要修正
     *
     * @param input 自身
     * @param target 兄弟 class 名
     */
    chmonos.brother = function (input, target) {
        if (typeof (input) === 'string') {
            input = form.querySelector('[data-vinput-id="' + input + '"]');
        }
        if (target.charAt(0) === '/') {
            return form.querySelectorAll('[data-vinput-class="' + target.substring(1) + '"].validatable');
        }
        var index = input.dataset.vinputIndex;
        var klass = input.dataset.vinputClass;
        var parent = klass.substring(0, klass.indexOf('/') + 1);
        if (index === '') {
            return form.querySelectorAll('[data-vinput-class="' + parent + target + '"].validatable');
        }
        else {
            return form.querySelectorAll('[data-vinput-id="' + parent + index + '/' + target + '"].validatable');
        }
    };

    /**
     * 要素の必須状態を設定する
     *
     * @param input 自身
     * @param fields 依存フィールド
     * @param holder input の所持者
     */
    chmonos.required = function (input, fields, holder) {
        holder = holder || form;
        if (typeof (input) === 'string') {
            input = holder.querySelector('[data-vinput-id="' + input + '"]');
        }
        var elemName = input.dataset.vinputClass;
        var condition = options.allrules[elemName]['condition'];
        var needless = options.allrules[elemName]['needless'];
        var rkey = Object.keys(condition).find(function (key) {
            return condition[key].cname === 'Requires';
        });
        if (rkey) {
            fields = fields || chmonos.fields(input);
            var label = holder.querySelector('[data-vlabel-id="' + input.dataset.vinputId + '"]') || document.createElement('span');
            input.setAttribute('data-vlevel', condition[rkey]['level']);
            label.setAttribute('data-vlevel', condition[rkey]['level']);
            input.classList.remove('required');
            label.classList.remove('required');
            Object.entries(needless).forEach(([attrname, attrvalue]) => input.setAttribute(attrname, attrvalue));
            chmonos.condition['Requires'](input, '', fields, condition[rkey]['param'], chmonos.constants['Requires'], function () {
                input.classList.add('required');
                label.classList.add('required');
                Object.keys(needless).forEach(attrname => input.removeAttribute(attrname));
            }, chmonos.context);
        }
    };

    /**
     * 要素の依存値を返す
     *
     * @param input 依存値が欲しい要素
     */
    chmonos.fields = function (input) {
        if (typeof (input) === 'string') {
            input = form.querySelector('[data-vinput-id="' + input + '"]');
        }
        var elemName = input.dataset.vinputClass;
        var condition = options.allrules[elemName]['condition'];
        var fields = [];
        Object.keys(condition).forEach(function (key) {
            fields.push.apply(fields, condition[key]['fields']);
        });
        if (!fields.length) {
            return {};
        }
        var depends = {};
        var values = chmonos.values();
        for (var i = 0; i < fields.length; i++) {
            depends[fields[i]] = values[fields[i]] !== undefined ? values[fields[i]] : '';
        }
        form.querySelectorAll('[data-vinput-index="' + input.dataset.vinputIndex + '"].validatable').forEach(function (e) {
            var klass = e.dataset.vinputClass;
            if (klass === undefined) {
                return;
            }
            var name = klass.substring(klass.indexOf('/') + 1);
            if (fields.indexOf(name) === -1) {
                return;
            }
            var value = chmonos.value(e);
            if (value === undefined) {
                return;
            }
            depends[name] = value;
        });
        return depends;
    };

    /**
     * 要素の値を返す
     *
     * @param input 値が欲しい要素
     */
    chmonos.value = function (input) {
        if (typeof (input) === 'string') {
            input = form.querySelector('[data-vinput-id="' + input + '"]');
        }
        var elemName = input.dataset.vinputClass;
        if (elemName == null) {
            return undefined;
        }
        var type = input.type;
        if (type === 'file') {
            if (input.multiple) {
                return Array.from(input.files);
            }
            if (input.files[0]) {
                // noinspection JSUndefinedPropertyAssignment
                input.files[0].length = 1;
                return input.files[0];
            }
            return '';
        }
        if (type === 'checkbox') {
            if (!input.name.match(/\[]$/)) {
                return input.checked ? input.value : '';
            }
            return Array.from(form.querySelectorAll('[name="' + input.name + '"].validatable:checked'), function (e) {
                return e.value;
            });
        }
        if (type === 'radio') {
            var checked = form.querySelector('[name="' + input.name + '"].validatable:checked');
            return checked ? checked.value : '';
        }
        if (type === 'select-multiple') {
            return Array.from(input.options).filter(function (e) {
                return e.selected;
            }).map(function (e) {
                return e.value;
            });
        }

        if (input.getAttribute('type') === 'dummy') {
            var dummy = [];
            chmonos.sibling(input.dataset.vinputId).forEach(function (v, k) {
                var inputs = {};
                Object.keys(v).forEach(function (k) {
                    var vs = chmonos.context.cast('array', v[k]);
                    inputs[k.substring(k.indexOf('/') + 1)] = chmonos.value(vs[0]);
                });
                dummy.push(inputs);
            });
            return dummy;
        }

        const getValue = function (e) {
            var value = e.value;
            if (options.allrules[elemName]['trimming']) {
                value = value.trim();
            }
            // number/date 用の特別処理
            if (value.length === 0 && e.validity.badInput && Number.isNaN(e.valueAsNumber)) {
                value = 'bad'; // 数としても日付として不正なら何でもいい（ただし maxlength に引っかかるので短めが良い）
            }
            return value;
        };

        if (input.name.match(/\[]$/)) {
            return Array.from(form.querySelectorAll('[name="' + input.name + '"].validatable:enabled'), function (e) {
                return getValue(e);
            });
        }

        return getValue(input);
    };

    /**
     * フォームの値を返す
     */
    chmonos.values = function () {
        if (form.validationValues) {
            return form.validationValues;
        }

        var values = {};
        form.querySelectorAll('.validatable:is(input, textarea, select):enabled').forEach(function (e) {
            var id = e.dataset.vinputId;
            if (id === undefined || values['/' + id] !== undefined) {
                return;
            }
            var value = chmonos.value(e);
            if (value === undefined) {
                return;
            }
            if (['checkbox', 'radio'].indexOf(e.type) >= 0 && !e.checked) {
                return;
            }
            values['/' + id] = value;
        });

        return form.validationValues = values;
    };

    /**
     * フォームデータを返す
     */
    chmonos.data = function () {
        return new FormData(form);
    };

    /**
     * パラメータを返す
     */
    chmonos.params = async function (filemanage) {
        filemanage = (function (filemanage) {
            if (filemanage === 'string') {
                return async file => file.text();
            }
            if (filemanage === 'binary') {
                return async file => [...new Uint8Array(await file.arrayBuffer())].map(c => String.fromCharCode(c)).join('');
            }
            if (filemanage === 'base64') {
                return async file => btoa([...new Uint8Array(await file.arrayBuffer())].map(c => String.fromCharCode(c)).join(''));
            }
            return filemanage;
        })(filemanage ?? 'base64');

        var params = new URLSearchParams();
        for (var [name, value] of this.data().entries()) {
            if (value instanceof File) {
                params.append(name, await filemanage(value));
            }
            else {
                params.append(name, value);
            }
        }
        return params;
    };

    /**
     * フォームの値を URL で返す
     *
     * @param submitter
     */
    chmonos.url = function (submitter) {
        var url = new URL(submitter?.formAction || form.action);
        url.search = ''; // form の action もパラメータが無視される

        var arrays = {};
        for (var [k, v] of chmonos.data().entries()) {
            var matches = k.match(/(.+?)(\[.+\]\[(.+)\])?\[\]$/) ?? [];
            var elemName = (matches[1] ?? '') + (matches[3] ? '/' + (matches[3] ?? '') : '');
            if (matches.length && options.allrules[elemName]?.delimiter) {
                var name = (matches[1] ?? '') + (matches[2] ?? '') + (matches[3] ?? '');
                arrays[name] = arrays[name] ?? [];
                arrays[name].push(v);
            }
            else {
                url.searchParams.append(k, v);
            }
        }
        for (var [k, vv] of Object.entries(arrays)) {
            url.searchParams.append(k, vv.join(options.allrules[k]?.delimiter));
        }
        if (submitter && submitter.name) {
            // type=image の場合は？ -> 対応しない
            url.searchParams.append(submitter.name, submitter.value);
        }

        return url;
    };

    /**
     * フォームの値をオブジェクトで返す
     *
     * @param filemanage file 要素をどう扱うか？ string|binary|base64
     */
    chmonos.object = async function (filemanage) {
        var result = {};
        chmonos.parse_str((await chmonos.params(filemanage)).toString(), result);
        return result;
    };

    /**
     * フォームの値を html で返す
     *
     * 装飾は一切しない。class は当てるので利用側で好きにすればよい。
     *
     * @param filemanage file 要素をどう扱うか？ filename|object
     * @param delimiter string 値の区切り文字
     */
    chmonos.html = async function (filemanage, delimiter) {
        delimiter = delimiter ?? ',';
        filemanage = (function (filemanage) {
            if (filemanage === 'filename') {
                return file => Array.from(file.files, file => file.name).join(delimiter);
            }
            if (filemanage === 'object') {
                return async file => (await Promise.all(Array.from(file.files, file => new Promise(function (resolve, reject) {
                    var reader = new FileReader();
                    reader.readAsDataURL(file);
                    reader.addEventListener('load', () => resolve(`<object data="${reader.result}"></object>`));
                    reader.addEventListener('error', () => reject(reader.error));
                })))).join('');
            }
            return filemanage;
        })(filemanage ?? 'object');

        var E = Chmonos.Utils.htmlEscape;
        var V = async function (inputs) {
            var result = [];
            for (var input of inputs) {
                var type = input.type;
                if (type === undefined) {
                    //
                }
                else if (type === 'file') {
                    result.push(await filemanage(input));
                }
                else if (type === 'checkbox' || type === 'radio') {
                    if (input.checked) {
                        result.push(...Array.from(input.labels).map(label => E(label.textContent)));
                    }
                }
                else if (type === 'select-one' || type === 'select-multiple') {
                    result.push(...Array.from(input.options).filter(e => e.selected).map(e => E(e.textContent)));
                }
                else {
                    result.push(E(input.value));
                }
            }
            return result;
        };

        var inputs = {};
        form.querySelectorAll('.validatable:is(input, textarea, select):enabled').forEach(function (e) {
            var klass = e.dataset.vinputClass ?? '';
            if (e.matches('[type="dummy"]')) {
                inputs[klass] = inputs[klass] ?? {};
                inputs[klass][''] = [e];
            }
            else if (klass.includes('/')) {
                var index = e.dataset.vinputIndex ?? '';
                var kindex = 'k' + index; // 順序維持のためプレフィックスを付ける
                var [parent, local] = klass.split('/');
                inputs[parent] = inputs[parent] ?? {};
                inputs[parent][kindex] = inputs[parent][kindex] ?? {};
                inputs[parent][kindex][local] = Array.from(form.querySelectorAll(`[data-vinput-class="${klass}"][data-vinput-index="${index}"]`));
            }
            else {
                inputs[klass] = Array.from(form.querySelectorAll(`[data-vinput-class="${klass}"]`));
            }
        });

        var dldtdd = async function (inputs, klass) {
            var result = [];
            for (var input of Object.values(inputs)) {
                if (Array.isArray(input)) {
                    var target = input;
                    var ids = [...new Set(input.map(e => e.dataset.vinputId ?? ''))].join('|');
                    var values = (await V(input)).filter(v => v.length);
                    var delimiter2 = delimiter;
                }
                else {
                    var target = input[''] ?? [];
                    delete input[''];
                    var ids = [...new Set(target.map(e => e.dataset.vinputId ?? ''))].join('|');
                    var values = await Promise.all(Object.entries(input).map(([k, children]) => dldtdd(children, ids + '/' + k.substring(1))));
                    var delimiter2 = '';
                }
                var title = [...new Set(target.map(e => e.dataset.validationTitle ?? ''))].join('|');
                // ラベルを優先する（validationTitle は固定的だが label は html 上で指定されていることもありそっちの方が精度が高い）
                // …がタイトルのないチェックボックスは単一で存在しがち（「同意する」とか）なので特別扱い
                if (title.length === 0 && target.length === 1 && target[0].matches('[type=checkbox]')) {
                    title = Array.from(target[0].labels, (label) => label.textContent).join('|');
                    values = [target[0].checked ? "✓" : ""];
                }
                else {
                    // checkbox,radio の label は「項目のラベル」ではないことが多い
                    var label = [...new Set(target.filter(e => !e.matches('[type=checkbox],[type=radio]')).map(e => Array.from(e.labels).map(l => l.textContent).join('|')))].join('|');
                    title = label || title;
                }
                result.push(`<div class="chmonos-output-row" data-voutput-id="${E(ids)}"><dt>${E(title)}</dt><dd>${values.join(delimiter2)}</dd></div>`);
            }
            return `<dl class="chmonos-output" data-voutput-class="${E(klass)}">${result.join('')}</dl>`;
        };

        return await dldtdd(inputs, "");
    };
}

Chmonos.Utils = {
    htmlEscape: function (string) {
        return ('' + string).replace(/[&'`"<>]/g, function (match) {
            return {
                '&': '&amp;',
                "'": '&#x27;',
                '`': '&#x60;',
                '"': '&quot;',
                '<': '&lt;',
                '>': '&gt;',
            }[match];
        });
    },
    htmlTemplateTag: function (strings, ...values) {
        strings = [...strings];
        return values.reduce(function (result, value, index) {
            if (value === true) {
                // 論理属性は 空 or 属性名 を指定するのが正しい仕様（大抵は受け入れられるがそのままにしておく理由もない）
                value = '';
            }
            else if (value === false) {
                const matches = strings[index].match(/\s+[-_0-9a-z:.]+\s*=\s*(["']?)$/i);
                if (matches && (matches[1] === '' || strings[index + 1].substring(0, 1) === matches[1])) {
                    strings[index + 0] = strings[index + 0].substring(0, matches.index);
                    strings[index + 1] = strings[index + 1].substring(matches[1] ? 1 : 0);
                    value = '';
                }
            }
            return result + strings[index] + Chmonos.Utils.htmlEscape(value);
        }, '') + strings.at(-1);
    },
};
