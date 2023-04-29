<?php
require_once __DIR__ . '/+include.php';
$appendmtime = function ($filename) {
    $fullname = __DIR__ . '/' . $filename;
    $mtime = '';
    if (file_exists($fullname)) {
        $mtime = '?v=' . filemtime($fullname);
    }
    return $filename . $mtime;
};
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no">
    <title>Validator サンプル</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.4.1/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= $appendmtime('../script/validator-error.css') ?>"/>
    <style>
        body {
            font-size: 16px;
            padding: 16px;
        }

        h1 {
            background: #eee;
        }

        h2 {
            background: #ddd;
        }

        label {
            font-weight: normal;
        }

        input[type=number] {
            width: 80px;
            text-align: right;
        }

        .wrapper {
            white-space: nowrap;
        }

        .table > tbody > tr > td, .table > tbody > tr > th, .table > tfoot > tr > td, .table > tfoot > tr > th, .table > thead > tr > td, .table > thead > tr > th {
            vertical-align: middle;
        }

        #dummy-focus {
            opacity: 0;
            width: 1px;
            height: 1px;
        }

        #manipulator {
            position: fixed;
            background: #ddd;
            bottom: 0;
            right: 0;
            padding: 3px;
            z-index: 9999999;
        }
    </style>
    <script type="text/javascript" src="https://unpkg.com/vue@3.2.47/dist/vue.global.js"></script>
    <script type="text/javascript" src="<?= $appendmtime('./validator.js') ?>"></script>
    <script type="text/javascript" src="<?= $appendmtime('../script/validator-error.js') ?>"></script>
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
            $('section>h2').forEach(function (e) {
                var a = document.createElement('a');
                a.setAttribute('href', '#' + e.id);
                a.textContent = e.textContent;
                var li = document.createElement('li');
                li.appendChild(a);
                $$('nav>ul').appendChild(li);
            });
            $('.object-button').on('click', async function (e) {
                var form = e.target.closest('form.validatable_form');
                console.log(await form.chmonos.object('string'));
            });
            $('.js-enable-switcher').on('change', function (e) {
                var form = e.target.closest('form.validatable_form');
                if (e.target.checked) {
                    form.chmonos.validationDisabled = false;
                    form.chmonos.validate();
                }
                else {
                    form.chmonos.validationDisabled = true;
                    form.chmonos.clearErrors();
                }
            });
            $$('#all_clear').on('click', function (e) {
                $('form.validatable_form').forEach(function (e) {
                    e.chmonos.clearErrors();
                });
                return false;
            });
        });
    </script>
</head>
<body>

<div id="manipulator">
    <input id="dummy-focus" type="text">
    <a href="./">reload</a>
    <a href="javascript:void(0)" id="all_clear">all clear</a>
    <a href="./testing.php">jstest</a>
</div>

<h1>Validator サンプル</h1>

<nav>
    <ul></ul>
</nav>

<section>
    <h2 id="basic">基本要素サンプル</h2>
    <?php include __DIR__ . '/section/basic.php' ?>
</section>

<section>
    <h2 id="condition">各種条件サンプル</h2>
    <?php include __DIR__ . '/section/condition.php' ?>
</section>

<section>
    <h2 id="dynamic">動的追加サンプル</h2>
    <?php include __DIR__ . '/section/dynamic.php' ?>
</section>

<section>
    <h2 id="other">その他の細々としたサンプル</h2>
    <?php include __DIR__ . '/section/other.php' ?>
</section>

</body>
</html>
