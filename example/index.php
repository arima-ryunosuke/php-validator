<?php require_once __DIR__ . '/+include.php' ?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no">
    <title>Validator サンプル</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.4.1/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../script/validator-error.css"/>
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
            top: 0;
            left: 0;
            padding: 3px;
        }
    </style>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script type="text/javascript" src="../script/polyfill.js"></script>
    <script type="text/javascript" src="./validator.js"></script>
    <script type="text/javascript" src="../script/validator-error.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('section>h2').forEach(function (e) {
                var a = document.createElement('a');
                a.setAttribute('href', '#' + e.id);
                a.textContent = e.textContent;
                var li = document.createElement('li');
                li.appendChild(a);
                document.querySelector('nav>ul').appendChild(li);
            });
            document.querySelector('.js-enable-switcher').addEventListener('change', function (e) {
                var form = e.closest('form.validatable_form');
                if (e.checked) {
                    form.chmonos.validationDisabled = false;
                    form.chmonos.validate();
                }
                else {
                    form.chmonos.validationDisabled = true;
                    form.chmonos.clearErrors();
                }
            });
            document.querySelector('#all_clear').addEventListener('click', function (e) {
                document.querySelectorAll('form.validatable_form').forEach(function (e) {
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
