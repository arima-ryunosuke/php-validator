<?php

use function ryunosuke\chmonos\callable_code;

require_once __DIR__ . '/+include.php' ?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <title>Validator テスト</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jasmine/5.4.0/jasmine.css"/>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jasmine/5.4.0/jasmine.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jasmine/5.4.0/jasmine-html.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jasmine/5.4.0/boot0.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jasmine/5.4.0/boot1.js"></script>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.4.1/css/bootstrap.min.css" rel="stylesheet">
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/vue/3.5.13/vue.global.min.js"></script>
    <script type="text/javascript" src="./validator.js"></script>
</head>
<body>

<section style="display: none">
    <?php include __DIR__ . '/section/dynamic.php' ?>
</section>

<script>
    const $ = document.querySelectorAll.bind(document);
    const $$ = document.querySelector.bind(document);
    Node.prototype.on = function (type, listener) {
        this.addEventListener(type, listener);
        return this;
    };
    NodeList.prototype.on = function (type, listener) {
        this.forEach(function (node) {
            node.on(type, listener);
        });
    };
    document.addEventListener('DOMContentLoaded', function () {
        var context_form = document.getElementById('context_form');
        var chmonos = context_form.chmonos;

        beforeEach(function () {
            jasmine.addMatchers({
                toEqualLoosely: function (util, customEqualityTesters) {
                    function is_primitive(value) {
                        if (value === null) {
                            return true;
                        }
                        switch (typeof value) {
                            default:
                                return false;
                            case 'symbol':
                            case 'boolean':
                            case 'number':
                            case 'bigint':
                            case 'string':
                                return true;
                        }
                    }

                    function replacer(sort) {
                        return function inner(key, value) {
                            if (is_primitive(value)) {
                                return '' + value;
                            }
                            if (value instanceof Map) {
                                var object = {};
                                value.forEach(function (e, k) { object[k] = e; });
                                return JSON.parse(JSON.stringify(object, inner));
                            }
                            if (value instanceof Element) {
                                return value.name;
                            }
                            if (value instanceof NodeList) {
                                return Array.from(value, function (e) { return e.name; });
                            }
                            if (sort && typeof value === "object" && !(value instanceof Array || value === null)) {
                                return Object.keys(value).sort().reduce(function (v, k) {
                                    v[k] = value[k];
                                    return v;
                                }, {});
                            }
                            return value;
                        };
                    }

                    function diff(text1, text2) {
                        var lines1 = text1.split("\n");
                        var lines2 = text2.split("\n");
                        var texts1 = lines1.slice();
                        var texts2 = lines2.slice();

                        lines1.forEach(function (line) {
                            texts2[texts2.indexOf(line)] = null;
                        });
                        lines2.forEach(function (line) {
                            texts1[texts1.indexOf(line)] = null;
                        });

                        var diff = [];
                        for (var i1 = 0, i2 = 0; i1 < texts1.length || i2 < texts2.length; i1++, i2++) {
                            for (; i2 < texts2.length; i2++) {
                                if (texts2[i2] == null) {
                                    break;
                                }
                                diff.push('-' + texts2[i2].replace(/^ /, '') + ' (missing)');
                            }
                            for (; i1 < texts1.length; i1++) {
                                if (texts1[i1] == null) {
                                    break;
                                }
                                diff.push('+' + texts1[i1].replace(/^ /, '') + ' (needless)');
                            }
                            if (texts1[i1] === null) {
                                diff.push(lines1[i1]);
                            }
                        }
                        return diff.join("\n");
                    }

                    return {
                        compare: function (actual, expected) {
                            return {
                                pass: JSON.stringify(actual, replacer(true)) === JSON.stringify(expected, replacer(true)),
                                message: function () {
                                    var actualJson = JSON.stringify(actual, replacer(false), 2);
                                    var expectedJson = JSON.stringify(expected, replacer(false), 2);
                                    if (is_primitive(actual) && is_primitive(expected)) {
                                        return "actual and expected are different.\n"
                                            + "actual is " + actual + "\n"
                                            + "expected is " + expected + "\n";
                                    }
                                    if (is_primitive(actual) && !is_primitive(expected)) {
                                        return "actual and expected are different.\n"
                                            + "actual is " + actual + "\n"
                                            + "expected is " + expectedJson + "\n";
                                    }
                                    if (!is_primitive(actual) && is_primitive(expected)) {
                                        return "actual and expected are different.\n"
                                            + "actual is " + actualJson + "\n"
                                            + "expected is " + expected + "\n";
                                    }
                                    return "actual and expected are different.\n"
                                        + diff(actualJson.replace(/,$/gm, ''), expectedJson.replace(/,$/gm, '')) + "\n"
                                        + "actual is " + actualJson + "\n"
                                        + "expected is " + expectedJson + "\n";
                                }
                            };
                        }
                    };
                }
            });
        });

        describe('chmonos.Utils', function () {
            it('htmlTemplateTag', function () {
                expect(Chmonos.Utils.htmlTemplateTag``).toEqual('');
                expect(Chmonos.Utils.htmlTemplateTag`a<b>bold</b>z`).toEqual('a<b>bold</b>z');
                expect(Chmonos.Utils.htmlTemplateTag`a${'<b>bold</b>'}z`).toEqual('a&lt;b&gt;bold&lt;/b&gt;z');
                expect(Chmonos.Utils.htmlTemplateTag`<span hidden="${false}">${'<b>bold</b>'}</span>`).toEqual(`<span>&lt;b&gt;bold&lt;/b&gt;</span>`);
                expect(Chmonos.Utils.htmlTemplateTag`<span hidden='${false}'>${'<b>bold</b>'}</span>`).toEqual(`<span>&lt;b&gt;bold&lt;/b&gt;</span>`);
                expect(Chmonos.Utils.htmlTemplateTag`<span hidden=${false}>${'<b>bold</b>'}</span>`).toEqual(`<span>&lt;b&gt;bold&lt;/b&gt;</span>`);
                expect(Chmonos.Utils.htmlTemplateTag`<span hidden="${true}">${'<b>bold</b>'}</span>`).toEqual(`<span hidden="">&lt;b&gt;bold&lt;/b&gt;</span>`);
            });
        });

        describe('chmonos.context', function () {
            it('function', function () {
                var func = chmonos.context.function(function (a, b, c) {
                    return Array.from(arguments);
                }, 1, 2, 3);
                expect(func()).toEqual([1, 2, 3]);
                expect(func()).toEqual([1, 2, 3]);
                expect(func(0)).toEqual([0, 1, 2, 3]);
                expect(func(-1, 0)).toEqual([-1, 0, 1, 2, 3]);
            });

            it('foreach', function () {
                var actual = [];
                chmonos.context.foreach(['a', 'b'], function (key, value, use1, use2, use3) {
                    actual.push([key, value, use1, use2, use3]);
                }, 1, 2, 3);
                chmonos.context.foreach({a: 'A', b: 'B'}, function (key, value, use1, use2, use3) {
                    actual.push([key, value, use1, use2, use3]);
                }, 4, 5, 6);
                expect(actual).toEqualLoosely([
                    [0, 'a', 1, 2, 3],
                    [1, 'b', 1, 2, 3],
                    ['a', 'A', 4, 5, 6],
                    ['b', 'B', 4, 5, 6],
                ]);
            });

            it('cast', function () {
                expect(chmonos.context.cast('array', 'a')).toEqual(['a']);
                expect(chmonos.context.cast('array', 'a')).toEqual(['a']);
                expect(chmonos.context.cast('array', ['a'])).toEqual(['a']);
                expect(chmonos.context.cast('array', {a: 'A'})).toEqual({a: 'A'});
                expect(chmonos.context.cast('array', null)).toEqual([]);
                expect(chmonos.context.cast('array', [])).toEqual([]);
            });

            it('str_concat', function () {
                expect(chmonos.context.str_concat(1, 2, 3)).toEqual('123');
                expect(chmonos.context.str_concat('a', 'b', 'c')).toEqual('abc');
            });
        });

        describe('chmonos.function', function () {
            <?php
            $expect = function ($callable, $matcher = 'toEqual') {
                [, $code] = callable_code($callable);
                $actual = json_encode($callable());
                return "expect($code).$matcher($actual)";
            };
            ?>
            with (chmonos) {
                it('array_map', function () {
                    expect(array_map((v) => v + 1, [1, 2, 3])).toEqual([2, 3, 4]);
                    expect(array_map((v) => v + 1, {1: 1, 2: 2, 3: 3})).toEqual({1: 2, 2: 3, 3: 4});
                    expect(array_map((v) => v + 1, {a: 1, b: 2, c: 3})).toEqual({a: 2, b: 3, c: 4});

                    expect(array_map((v1, v2) => v1 + v2, {a: 1, b: 2, c: 3}, {x: 7, y: 8, z: 9})).toEqual([8, 10, 12]);
                    expect(array_map((v1, v2) => [v1, v2], {a: 1, b: 2}, {x: 7, y: 8, z: 9})).toEqual([[1, 7], [2, 8], [null, 9]]);
                    expect(array_map((v1, v2) => [v1, v2], {a: 1, b: 2, c: 3}, {x: 7, y: 8})).toEqual([[1, 7], [2, 8], [3, null]]);
                });

                it('array_reduce', function () {
                    expect(array_reduce([1, 2, 3], (c, v) => c + v, 0)).toEqual(6);
                    expect(array_reduce([1, 2, 3], (c, v) => c + v, 100)).toEqual(106);
                    expect(array_reduce([1, 2, 3], (c, v) => c + v, '')).toEqual('123');
                    expect(array_reduce([1, 2, 3], (c, v) => c + v, 'x')).toEqual('x123');
                });

                it('ini_parse_quantity', function () {
                    expect(ini_parse_quantity('')).toEqual(0);
                    expect(ini_parse_quantity(100)).toEqual(100);
                    expect(ini_parse_quantity('1K')).toEqual(1 * 1024);
                    expect(ini_parse_quantity('2M')).toEqual(2 * 1024 * 1024);
                    expect(ini_parse_quantity('3G')).toEqual(3 * 1024 * 1024 * 1024);
                });

                it('strlen', function () {
                    expect(strlen('')).toEqual(0);
                    expect(strlen('abc')).toEqual(3);
                    expect(strlen('あいう')).toEqual(9);
                    expect(strlen('aあ👨👨‍👩‍👧‍👦')).toEqual(33);
                });

                it('mb_str_split', function () {
                    expect(mb_str_split('')).toEqual([]);
                    expect(mb_str_split('abc')).toEqual(['a', 'b', 'c']);
                    expect(mb_str_split('あいう')).toEqual(['あ', 'い', 'う']);
                    expect(mb_str_split('aあ👨👨‍👩‍👧‍👦')).toEqual(['a', 'あ', '👨', '👨', '‍', '👩', '‍', '👧', '‍', '👦']);
                });

                it('preg_match', function () {
                    var matches = [];
                    expect(preg_match('#^\\d\\d\\d$#', 123)).toEqual(1);
                    expect(preg_match('#^\nN$#', '\nN')).toEqual(1);
                    expect(preg_match('#^a\\nz$#', 'a\nz')).toEqual(1);
                    expect(preg_match('/^[a-z]+$/', 'hoge')).toEqual(1);
                    expect(preg_match('/^[a-z]+$/', 'hoge123')).toEqual(0);
                    expect(preg_match('/^([a-z]+)(\\d+)/', 'hoge123X', matches)).toEqual(1);
                    expect(matches).toEqual(['hoge123', 'hoge', '123']);
                    expect(preg_match('/^([a-z]+)(\\d+)?/', 'hogeX', matches, PREG_UNMATCHED_AS_NULL)).toEqual(1);
                    expect(matches).toEqual(['hoge', 'hoge', null]);
                });

                it('preg_split', function () {
                    <?= $expect(fn() => preg_split('/0/', 10203)) ?>;
                    <?= $expect(fn() => preg_split('/,/', 'a,b,c')) ?>;
                    <?= $expect(fn() => preg_split('/,/', ',a,b,c,')) ?>;
                    <?= $expect(fn() => preg_split('/,/', ',a,b,c,', 3)) ?>;
                    <?= $expect(fn() => preg_split('/,/', ',,a,,b,,c,,', -1, PREG_SPLIT_NO_EMPTY)) ?>;
                    <?= $expect(fn() => preg_split('/,/', ',,a,,b,,c,,', 1, PREG_SPLIT_NO_EMPTY)) ?>;
                    <?= $expect(fn() => preg_split('/,/', ',,a,,b,,c,,', 2, PREG_SPLIT_NO_EMPTY)) ?>;
                    <?= $expect(fn() => preg_split('/,/', ',,a,,b,,c,,', 3, PREG_SPLIT_NO_EMPTY)) ?>;
                    <?= $expect(fn() => preg_split('/,|-/', ',h-o--g,-,e-')) ?>;
                    <?= $expect(fn() => preg_split('/,|-/', ',h-o--g,-,e-', 4)) ?>;
                });

                it('fnmatch', function () {
                    expect(fnmatch('image/png', 'image/png')).toEqual(true);
                    expect(fnmatch('image/png', 'image/gif')).toEqual(false);
                    expect(fnmatch('image/png', 'text/csv')).toEqual(false);
                    expect(fnmatch('image/*', 'image/png')).toEqual(true);
                    expect(fnmatch('image/*', 'image/gif')).toEqual(true);
                    expect(fnmatch('image/*', 'text/csv')).toEqual(false);
                    expect(fnmatch('*/*', 'image/png')).toEqual(true);
                    expect(fnmatch('*/*', 'image/gif')).toEqual(true);
                    expect(fnmatch('*/*', 'text/csv')).toEqual(true);
                    expect(fnmatch('*', 'text/csv')).toEqual(true);
                });
            }
        });

        describe('chmonos.dom', function () {
            it('sibling', function () {
                expect(chmonos.sibling('rows')).toEqualLoosely({
                    "1": {
                        "rows/title": "rows[1][title]",
                        "rows/checkbox": ["rows[1][checkbox][]", "rows[1][checkbox][]", "rows[1][checkbox][]"],
                        "rows/multiple": "rows[1][multiple][]",
                        "rows/unique_require": "rows[1][unique_require]",
                        "rows/unique": "rows[1][unique]",
                        "rows/array_file": "rows[1][array_file]",
                    },
                    "2": {
                        "rows/title": "rows[2][title]",
                        "rows/checkbox": ["rows[2][checkbox][]", "rows[2][checkbox][]", "rows[2][checkbox][]"],
                        "rows/multiple": "rows[2][multiple][]",
                        "rows/unique_require": "rows[2][unique_require]",
                        "rows/unique": "rows[2][unique]",
                        "rows/array_file": "rows[2][array_file]",
                    },
                });
            });
            it('value_s', function () {
                expect(chmonos.value('parent_mail')).toEqual('mail@example.com');
                expect(chmonos.values()).toEqualLoosely({
                    "/parent_mail": "mail@example.com",
                    "/rows": [
                        {
                            "title": "title1",
                            "checkbox": ["1"],
                            "multiple": ["1", "3"],
                            "unique_require": "1",
                            "unique": "99",
                            "array_file": ""
                        },
                        {
                            "title": "title2",
                            "checkbox": ["1"],
                            "multiple": ["1", "3"],
                            "unique_require": "1",
                            "unique": "99",
                            "array_file": ""
                        }
                    ],
                    "/rows/1/title": "title1",
                    "/rows/1/checkbox": ["1"],
                    "/rows/1/multiple": ["1", "3"],
                    "/rows/1/unique_require": "1",
                    "/rows/1/unique": "99",
                    "/rows/1/array_file": "",
                    "/rows/2/title": "title2",
                    "/rows/2/checkbox": ["1"],
                    "/rows/2/multiple": ["1", "3"],
                    "/rows/2/unique_require": "1",
                    "/rows/2/unique": "99",
                    "/rows/2/array_file": "",
                });
                expect(chmonos.fields('rows/2/title')).toEqualLoosely({
                    "/require_address": "",
                    "/parent_mail": "mail@example.com",
                });
            });
            it('get/set values', function () {
                chmonos.setValues(null, {
                    parent_mail: "mailX@example.com",
                });
                chmonos.setValues(context_form.querySelectorAll('tr')[0], {
                    title: "titleX1",
                    checkbox: ["1"],
                    multiple: ["1", "3"],
                    unique_require: "1",
                    unique: "199",
                    array_file: "",
                });
                chmonos.setValues(context_form.querySelectorAll('tr')[1], {
                    title: "titleX2",
                    checkbox: ["2"],
                    multiple: ["2", "3"],
                    unique_require: "",
                    unique: "299",
                    array_file: "",
                });
                expect(chmonos.getValues(context_form.querySelectorAll('tr')[0])).toEqualLoosely({
                    "title": "titleX1",
                    "checkbox": ["1"],
                    "multiple": ["1", "3"],
                    "unique_require": "1",
                    "unique": "199",
                    "array_file": "",
                });
                expect(chmonos.getValues(context_form.querySelectorAll('tr')[1])).toEqualLoosely({
                    "title": "titleX2",
                    "checkbox": ["2"],
                    "multiple": ["2", "3"],
                    "unique_require": "",
                    "unique": "299",
                    "array_file": "",
                });
                expect(chmonos.getValues(null)).toEqualLoosely({
                    "parent_mail": "mailX@example.com",
                    "require_address": "",
                    "rows": [
                        {
                            "title": "titleX1",
                            "checkbox": ["1"],
                            "multiple": ["1", "3"],
                            "unique_require": "1",
                            "unique": "199",
                            "array_file": "",
                        },
                        {
                            "title": "titleX2",
                            "checkbox": ["2"],
                            "multiple": ["2", "3"],
                            "unique_require": "",
                            "unique": "299",
                            "array_file": "",
                        }
                    ],
                });
            });
        });
    });
</script>

</body>
</html>
