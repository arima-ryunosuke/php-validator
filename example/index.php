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

        .vfile-dragging {
            background: pink;
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
    <script defer src="<?= $appendmtime('./validator.js') ?>"></script>
    <script defer src="<?= $appendmtime('../script/validator-error.js') ?>"></script>
    <script type="module">
        window.$ = document.querySelectorAll.bind(document);
        window.$$ = document.querySelector.bind(document);
        Window.prototype.var_dump = function () {
            console.log(...arguments);
        };
        Node.prototype.on = function (type, listener) {
            this.addEventListener(type, listener);
            return this;
        };
        NodeList.prototype.on = function (type, listener) {
            this.forEach(function (node) {
                node.on(type, listener);
            });
            return this;
        };
        File.prototype.toDataURL = function () {
            return new Promise((resolve, reject) => {
                const reader = new FileReader();
                reader.onload = () => resolve(reader.result);
                reader.onerror = () => reject(reader.error);
                reader.readAsDataURL(this);
            });
        };
        $('section>h2').forEach(function (e) {
            var a = document.createElement('a');
            a.setAttribute('href', '#' + e.id);
            a.textContent = e.textContent;
            var li = document.createElement('li');
            li.appendChild(a);
            $$('nav>ul').appendChild(li);
        });
        $('form.validatable_form').on('click', async function (e) {
            if (e.target.matches('.object-button')) {
                console.log(await this.chmonos.object('string'));
            }
            if (e.target.matches('.html-button')) {
                this.querySelector('.output-html').innerHTML = await this.chmonos.html();
            }
        }).on('change', async function (e) {
            if (e.target.matches('.js-enable-switcher')) {
                if (e.target.checked) {
                    this.chmonos.validationDisabled = false;
                    this.chmonos.validate();
                }
                else {
                    this.chmonos.validationDisabled = true;
                    this.chmonos.clearErrors();
                }
            }
        });
        $$('#all_clear').on('click', function (e) {
            $('form.validatable_form').forEach(function (e) {
                e.chmonos.clearErrors();
            });
            return false;
        });
    </script>
</head>
<body>

<div id="manipulator">
    <input id="dummy-focus" type="text">
    <a href="./">reload</a>
    <a href="javascript:void(0)" id="all_clear">all clear</a>
    <a href="./testing.php?random=false">jstest</a>
</div>

<h1>Validator サンプル</h1>

<nav>
    <ul></ul>
</nav>

<section id="action-section">
    <h2 id="action">GET フォーム</h2>
    <?php include __DIR__ . '/section/action.php' ?>
</section>

<section id="basic-section">
    <h2 id="basic">基本要素サンプル</h2>
    <?php include __DIR__ . '/section/basic.php' ?>
</section>

<section id="condition-section">
    <h2 id="condition">各種条件サンプル</h2>
    <?php include __DIR__ . '/section/condition.php' ?>
</section>

<section id="dynamic-section">
    <h2 id="dynamic">動的追加サンプル</h2>
    <?php include __DIR__ . '/section/dynamic.php' ?>
</section>

<section id="other-section">
    <h2 id="other">その他の細々としたサンプル</h2>
    <?php include __DIR__ . '/section/other.php' ?>
</section>

</body>
</html>
