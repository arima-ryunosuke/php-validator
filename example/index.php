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
    <script type="text/javascript" src="../script/validator-error.js"></script>
    <script type="text/javascript" src="./validator.js"></script>
    <script>
        $(function () {
            $('section>h2').each(function () {
                var $this = $(this);
                var a = $('<a></a>').attr('href', '#' + $this.attr('id')).text($this.text());
                $('nav>ul').append($('<li></li>').append(a));
            });
            $('.js-enable-switcher').on('change', function () {
                var form = this.closest('form.validatable_form');
                if (this.checked) {
                    form.chmonos.validationDisabled = false;
                    form.chmonos.validate();
                }
                else {
                    form.chmonos.validationDisabled = true;
                    form.chmonos.clearErrors();
                }
            });
            $('#all_clear').on('click', function () {
                $('form.validatable_form').each(function () {
                    this.chmonos.clearErrors();
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
