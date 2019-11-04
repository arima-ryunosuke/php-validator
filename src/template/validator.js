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

    /// phpjs のインポート
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

    function core_validate(input, validation_id, e) {
        var result = [];

        if (chmonos.validationDisabled) {
            return result;
        }

        // propagate などで同一要素に検証が走ることがあるので一意な ID を持たせて、同一 ID ならスルーするようにする
        var vid = input.validationId;
        if (vid !== undefined && vid === validation_id) {
            return result;
        }
        input.validationId = validation_id;

        if (input.disabled) {
            return result;
        }

        var elemName = input.dataset.vinputClass;
        var rule = options.allrules[elemName];
        if (rule === undefined) {
            return result;
        }

        if (!rule['invisible'] && input.type !== 'hidden' && input.offsetParent === null) {
            fireError(input, {}, true);
            return result;
        }

        var phantom = rule['phantom'];
        if (phantom.length) {
            var flag = true;
            var brothers = [];
            for (var i = 1; i < phantom.length; i++) {
                var target = chmonos.value(chmonos.brother(input, phantom[i])[0]);
                if (target.length === 0) {
                    flag = false;
                    break;
                }
                brothers.push(target);
            }
            // noinspection JSUnresolvedFunction
            input.value = flag ? vsprintf(phantom[0], brothers) : '';
        }

        var condition = rule['condition'];
        var value = chmonos.value(input);
        var fields = chmonos.fields(input);
        var errorTypes = {};
        var asyncs = [];
        var keys = Object.keys(condition);
        for (var k = 0; k < keys.length; k++) {
            let cond = condition[keys[k]];
            let cname = cond.cname;
            var error = function (e) {
                if (e === undefined) {
                    if (input.validationErrors && input.validationErrors[cname]) {
                        errorTypes[cname] = input.validationErrors[cname];
                    }
                }
                else if (e === null) {
                    delete errorTypes[cname];
                }
                else if (e instanceof Promise) {
                    asyncs.push(e);
                }
                else if (isPlainObject(e)) {
                    if (errorTypes[cname] === undefined) {
                        errorTypes[cname] = {};
                    }
                    Object.keys(e).forEach(function (mk) {
                        errorTypes[cname][mk] = e[mk];
                    });
                }
                else {
                    var ret;
                    if (cond['message'][e] !== undefined) {
                        ret = cond['message'][e];
                    }
                    else if (chmonos.messages[cname][e] !== undefined) {
                        ret = chmonos.messages[cname][e];
                    }
                    else {
                        ret = e;
                    }
                    if (errorTypes[cname] === undefined) {
                        errorTypes[cname] = {};
                    }
                    errorTypes[cname][e] = ret.replace(/%(.+?)%/g, function (p0, p1) {
                        if (cond['param'][p1] !== undefined) {
                            return cond['param'][p1];
                        }
                        return p0;
                    });
                }
            };
            // 値が空の場合は Requires しか検証しない（空かどうかの制御を他の condition に任せたくない）
            // value は必ず length を持つように制御してるので「空・未入力」の判定は length === 0 で OK
            if (value.length > 0 || cname === 'Requires') {
                var values = cond['arrayable'] ? [value] : chmonos.context.cast('array', value);
                Object.keys(values).forEach(function (v) {
                    chmonos.condition[cname](input, values[v], fields, cond['param'], chmonos.constants[cname], error, chmonos.context, e);
                });
            }
        }

        chmonos.required(input, fields);

        result.push(new Promise(function (resolve) {
            Promise.all(asyncs).then(function () {
                resolve(fireError(input, errorTypes, value.length > 0));
            });
        }));

        // ラジオボタンやチェックボックスなどはこれ以上無駄なので検証 ID を放り込んでおく
        if (input.type === 'radio' || input.type === 'checkbox') {
            form.querySelectorAll("input[name='" + input.name + "'].validatable").forEach(function (e) {
                e.validationId = validation_id;
            });
        }

        // 伝播先へ伝播
        rule['propagate'].forEach(function (propagate) {
            chmonos.brother(input, propagate).forEach(function (elm) {
                result.push.apply(result, core_validate(elm, validation_id, e));
            });
        });

        return result;
    }

    function isPlainObject(obj) {
        if (typeof (obj) !== 'object' || obj.nodeType || obj === obj.window) {
            return false;
        }
        return !(obj.constructor && !{}.hasOwnProperty.call(obj.constructor.prototype, 'isPrototypeOf'));
    }

    function fireError(input, errorTypes, okclass) {
        var isError = !!Object.keys(errorTypes).length;
        if (input.type === 'radio' || input.type === 'checkbox') {
            input = form.querySelectorAll("input[name='" + input.name + "'].validatable");
        }
        else {
            input = [input];
        }
        input.forEach(function (e) {
            if (isError) {
                e.classList.add('validation_error');
                e.classList.remove('validation_ok');
                e.validationErrors = errorTypes;
            }
            else {
                e.classList.remove('validation_error');
                e.classList.remove('validation_ok');
                if (okclass) {
                    e.classList.add('validation_ok');
                }
                e.validationErrors = undefined;
            }
            e.dispatchEvent(new CustomEvent('validated', {detail: {errorTypes: errorTypes}, bubbles: true}));
        });
        return isError;
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
            // 現状は array のみ実装
            if (type === 'array') {
                if (value === null) {
                    return [];
                }
                if (value instanceof Array || isPlainObject(value)) {
                    return value;
                }
                return [value];
            }
            throw "invalid cast type";
        },
        "str_concat": function () {
            return Array.from(arguments).join('');
        },
    };

    chmonos.customValidation = {
        before: [],
        after: [],
    };

    chmonos.initialize = function (values) {
        // js レンダリング
        Object.keys(values || {}).forEach(function (tname) {
            var template = form.querySelector('[data-vtemplate-name="' + tname + '"]');
            Object.keys(values[tname] || {}).forEach(function (index) {
                chmonos.spawn(template, null, values[tname][index], index);
            });
        });

        // サーバー側の結果を表示
        chmonos.setErrors(options.errors);

        // 必須マーク
        form.querySelectorAll('.validatable:enabled').forEach(function (input) {
            chmonos.required(input);
        });

        // イベントをバインド
        var handler = function (e) {
            var elemName = e.target.dataset.vinputClass;
            if (options.allrules[elemName] === undefined) {
                return;
            }
            if (options.allrules[elemName]['event'].indexOf(e.type) === -1) {
                return;
            }
            // keyup における Tab はすでに項目が遷移している
            if (e.type === 'keyup' && e.keyCode === 9) {
                return;
            }
            form.validationValues = undefined;
            core_validate(e.target, new Date().getTime(), e);
        };
        // ありそうなイベントを全て listen して呼び出し時に要素単位でチェックする。この候補は割と気軽に追加して良い
        ['change', 'keyup', 'keydown', 'input', 'click', 'focusin', 'focusout'].forEach(function (event) {
            form.addEventListener(event, handler);
        });

        // サブミット時にバリデーション
        form.addEventListener('submit', function submit(e) {
            try {
                chmonos.validate(e).then(function (result) {
                    if (result.indexOf(true) === -1) {
                        var button = document.activeElement;
                        var submitter = ['input[type="submit"]', 'input[type="image"]', 'button[type="submit"]', 'button:not([type])'];
                        if (options.alternativeSubmit && button && button.matches(submitter.join(','))) {
                            setTimeout(function () {
                                form.removeEventListener('submit', submit);
                                button.click();
                                form.addEventListener('submit', submit);
                            }, 0);
                        }
                        else {
                            // @see https://developer.mozilla.org/ja/docs/Web/API/HTMLFormElement/submit
                            // 発火するしないは規定されていないらしいので念の為に付け外す
                            form.removeEventListener('submit', submit);
                            form.submit();
                            form.addEventListener('submit', submit);
                        }
                    }
                });
            }
            catch (ex) {
                console.error(ex);
            }
            e.preventDefault();
            e.stopPropagation();
            return false;
        });
    };

    chmonos.addCustomValidation = function (validation, timing) {
        timing = timing || 'after';
        chmonos.customValidation[timing].push(validation);
    };

    chmonos.validate = function (evt) {
        form.validationValues = undefined;
        evt = evt || new CustomEvent('vatidation');
        var validation_id = new Date().getTime();

        var promises = [];
        if (chmonos.customValidation.before.some(function (f) { return f.call(form, promises) === false })) {
            promises.push(true);
            return Promise.all(promises);
        }
        form.querySelectorAll('.validatable').forEach(function (e) {
            promises.push.apply(promises, core_validate(e, validation_id, evt));
        });
        if (chmonos.customValidation.after.some(function (f) { return f.call(form, promises) === false })) {
            promises.push(true);
            return Promise.all(promises);
        }
        return Promise.all(promises);
    };

    chmonos.setErrors = function (emessages) {
        var errorTypes = {};
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

        form.querySelectorAll('.validatable').forEach(function (input) {
            fireError(input, errorTypes[input.dataset.vinputId] || {}, false);
        });
    };

    chmonos.clearErrors = function () {
        form.querySelectorAll('.validatable').forEach(function (input) {
            fireError(input, {}, false);
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
        var parent;
        var fragment;
        if ('content' in template) {
            fragment = template.content.cloneNode(true);
        }
        else {
            fragment = document.createDocumentFragment();
            if (typeof (template) === 'string') {
                parent = document.createElement('div');
                parent.innerHTML = template;
            }
            else {
                parent = document.createElement(template.parentNode.tagName);
                parent.innerHTML = template.textContent;
            }
            while (parent.firstChild) {
                fragment.appendChild(parent.firstChild);
            }
        }

        if (template.dataset) {
            var template_name = template.dataset.vtemplateName;
            if (template_name && index === undefined) {
                index = -(chmonos.sibling(template_name).size + 1);
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
        });
        Array.from(fragment.querySelectorAll('[name*=__index]')).forEach(function (e) {
            resetIndex(e, 'id', index);
            resetIndex(e, 'name', index);
            resetIndex(e, 'data-vinput-id', index);
            resetIndex(e, 'data-vinput-index', index);
            e.disabled = false;
        });
        Array.from(fragment.querySelectorAll('.validatable:enabled')).forEach(function (e) {
            chmonos.required(e, undefined, fragment);
        });
        if (values) {
            Object.keys(values).forEach(function (key) {
                var index = 0;
                if (values[key] !== null) {
                    fragment.querySelectorAll('[data-vinput-id$="/' + key + '"].validatable').forEach(function (e) {
                        if (e.type === 'file') {
                            return;
                        }
                        if (e.type === 'checkbox' || e.type === 'radio') {
                            var vv = (values[key] instanceof Array ? values[key] : [values[key]]).map(function (x) {return '' + x});
                            e.checked = vv.indexOf(e.value) >= 0;
                        }
                        else if (e.type === 'select-multiple') {
                            var vv = (values[key] instanceof Array ? values[key] : [values[key]]).map(function (x) {return '' + x});
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
        }

        if (parent === undefined) {
            return fragment.firstElementChild;
        }
        return fragment.firstElementChild.firstElementChild;
    };

    /**
     * 子要素を生み出す
     *
     * @param template Array 要素名か
     * @param callback 追加処理
     * @param values 初期値
     * @param index 追加するインデックス
     */
    chmonos.spawn = function (template, callback, values, index) {
        if (typeof (template) === 'string') {
            template = form.querySelector('[data-vtemplate-name="' + template + '"]')
        }

        var node = chmonos.birth(template, values, index);
        template.dispatchEvent(new CustomEvent('spawn', {
            detail: {
                node: node,
                index: index,
                values: values,
            },
        }));

        callback = callback || function (node) {this.parentNode.appendChild(node)};
        callback.call(template, node);
        return node;
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
            template = form.querySelector('[data-vtemplate-name="' + template + '"]')
        }

        template.dispatchEvent(new CustomEvent('cull', {
            detail: {
                node: node,
            },
        }));

        callback = callback || function (node) {this.parentNode.removeChild(node)};
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
        var rkey = Object.keys(condition).find(function (key) {
            return condition[key].cname === 'Requires';
        });
        if (rkey) {
            fields = fields || chmonos.fields(input);
            var label = holder.querySelector('[data-vlabel-id="' + input.dataset.vinputId + '"]') || document.createElement('span');
            input.classList.remove('required');
            label.classList.remove('required');
            chmonos.condition['Requires'](input, '', fields, condition[rkey]['param'], chmonos.constants['Requires'], function () {
                input.classList.add('required');
                label.classList.add('required');
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

        if (input.name.match(/\[]$/)) {
            return Array.from(form.querySelectorAll('[name="' + input.name + '"].validatable:enabled'), function (e) {
                return e.value;
            });
        }

        var val = input.value;
        if (options.allrules[elemName]['trimming']) {
            val = val.trim();
        }
        return val;
    };

    /**
     * フォームの値を返す
     */
    chmonos.values = function () {
        if (form.validationValues) {
            return form.validationValues;
        }

        var values = {};
        form.querySelectorAll('.validatable:enabled').forEach(function (e) {
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
}
