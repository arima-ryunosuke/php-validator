<?php

use ryunosuke\chmonos\Condition;
use ryunosuke\chmonos\UploadedFile;

$basic_form = new \ryunosuke\chmonos\Form([
    'text'                  => [
        'default' => "<script>alert('XSS!!');</script>",
    ],
    'texts'                 => [
        'default' => ['a', 'b', 'c'],
    ],
    'textarea'              => [
        'title'   => 'textarea要素',
        'default' => "</textarea><script>alert('XSS!!');</script>"
    ],
    'datetime'              => [
        'default' => time(),
        'condition' => [
            'Date' => 'Y-m-d\TH:i:s'
        ],
    ],
    'datalist'              => [
        'title'    => 'date要素（datalist 付き）',
        'datalist' => [
            '2014-12-24' => 'クリスマス前日',
            '2014-12-25' => 'クリスマス当日',
            '2014-12-26' => 'クリスマス後日'
        ],
    ],
    'checkbox'              => [
        'title'   => 'checkbox要素',
        'options' => [
            1 => '選択肢1'
        ]
    ],
    'checkboxes'            => [
        'title'   => 'checkbox要素(複数)',
        'options' => [
            1 => '選択肢1',
            2 => '選択肢2',
            3 => '選択肢3'
        ],
        'default' => [],
    ],
    'checkboxes-html'       => [
        'title'   => 'checkbox要素(複数でformat・separator指定)',
        'options' => [
            1 => '選択肢1',
            2 => '選択肢2',
            3 => '選択肢3'
        ],
        'default' => [],
    ],
    'checkboxes-labelclass' => [
        'title'   => 'checkbox要素(複数でlabelにclass指定)',
        'options' => [
            1 => '選択肢1',
            2 => '選択肢2',
            3 => '選択肢3'
        ],
        'default' => [],
    ],
    'radio'                 => [
        'title'   => 'radio要素',
        'options' => [
            1 => '選択肢1',
            2 => '選択肢2',
            3 => '選択肢3'
        ]
    ],
    'radio-labelclass'      => [
        'title'   => 'radio要素(labelにclass指定)',
        'options' => [
            1 => '選択肢1',
            2 => '選択肢2',
            3 => '選択肢3'
        ]
    ],
    'select-misc'           => [
        'title'   => 'select要素(色々混ざってる)',
        'options' => [
            1       => '選択肢1',
            2       => '選択肢2',
            3       => '選択肢3',
            'グループA' => [
                4 => 'Aの選択肢1',
                5 => 'Aの選択肢2'
            ],
            'グループB' => [
                6 => 'Bの選択肢1',
                7 => 'Bの選択肢2'
            ]
        ]
    ],
    'file'                  => [
        'title'     => 'ファイルアップロード',
        'condition' => [
            'FileSize' => [1024 * 200]
        ]
    ],
    'files'                 => [
        'title'     => 'ファイルアップロード',
        'condition' => [
            'FileSize' => [1024 * 200]
        ],
        'default'   => ['', '', ''],
    ],
    'invisible'             => [
        'title'     => '不可視要素',
        'condition' => [
            'Requires' => null,
        ],
        'invisible' => true,
    ],
    'client'                => [
        'title'     => 'jsのみ',
        'condition' => [
            'Requires' => null,
        ],
        'checkmode' => ['client' => true, 'server' => false],
    ],
    'server'                => [
        'title'     => 'phpのみ',
        'condition' => [
            'Requires' => null,
        ],
        'checkmode' => ['client' => false, 'server' => true],
    ],
    'ignore_require'        => [
        'title'     => '最終結果に含まれない',
        'condition' => [],
        'options'   => [
            1 => 'チェックを入れると必須',
        ],
        'ignore'    => true,
    ],
    'ignore'                => [
        'title'     => 'チェックを入れると必須',
        'condition' => [
            'Requires' => 'ignore_require',
        ],
    ],
    'warning'                => [
        'title'     => '必須警告のみ',
        'condition' => [
            (new Condition\Requires())->setValidationLevel('warning')->setMessageTemplate('入力した方がよいです', Condition\Requires::INVALID_TEXT),
        ],
    ],
], [
    'tokenName' => 'csrf_token',
    'fileClass' => UploadedFile::class,
]);
resetForm($basic_form, 'basic_form');
?>

<style>
    [data-vinput-wrapper="checkboxes-labelclass[]"] label {
        background-color: #cde;
    }

    [data-vinput-wrapper="checkboxes-labelclass[]"][data-value="1"] label {
        color: #f00;
    }

    [data-vinput-wrapper="checkboxes-labelclass[]"][data-value="2"] label {
        color: #0f0;
    }

    [data-vinput-wrapper="checkboxes-labelclass[]"][data-value="3"] label {
        color: #00f;
    }

    [data-vinput-wrapper="radio-labelclass"] label {
        background-color: #cde;
    }

    [data-vinput-wrapper="radio-labelclass"][data-value="1"] label {
        color: #f00;
    }

    [data-vinput-wrapper="radio-labelclass"][data-value="2"] label {
        color: #0f0;
    }

    [data-vinput-wrapper="radio-labelclass"][data-value="3"] label {
        color: #00f;
    }

    [data-vinput-wrapper="select-misc"] option {
        background-color: #cde;
    }

    [data-vinput-wrapper="select-misc"] option[value="1"] {
        color: #f00;
    }

    [data-vinput-wrapper="select-misc"] option[value="2"] {
        color: #0f0;
    }

    [data-vinput-wrapper="select-misc"] option[value="3"] {
        color: #00f;
    }

    #toggleInvisible ~ #invisible-wrapper {
        display: none;
    }

    #toggleInvisible:checked ~ #invisible-wrapper {
        display: block;
    }

    #basic_form .chmonos-output {
        display: grid;
        grid-template-columns: max-content auto;
        overflow-x: auto;
        gap: 4px 3px;

        .chmonos-output-row {
            display: contents;
        }

        .chmonos-output-row:has(>dt:empty),
        .chmonos-output-row:has(>dd:empty) {
            display: none;
        }

        dt:after {
            content: "：";
        }
    }
</style>
<?= $basic_form->form(['id' => 'basic_form'/* ファイル要素を持つ Form は自動で POST,multipart/form-data になる */]) ?>
<input type="hidden" name="formid" value="basic_form">
<table class="table">
    <tr>
        <th><?= $basic_form->label('text', ['label' => 'text要素']) ?></th>
        <td><?= $basic_form->input('text') ?></td>
    </tr>
    <tr>
        <th><?= $basic_form->label('texts', ['label' => 'texts要素']) ?></th>
        <td><?= $basic_form->input('texts') ?></td>
    </tr>
    <tr>
        <th>textarea要素</th>
        <td><?= $basic_form->input('textarea', ['type' => 'textarea']) ?></td>
    </tr>
    <tr>
        <th><?= $basic_form->label('datetime', ['label' => 'datetime要素']) ?></th>
        <td><?= $basic_form->input('datetime', [
            'format' => 'yyyyMMdd日',
            ]) ?></td>
    </tr>
    <tr>
        <th>date要素（datalist 付き）</th>
        <td><?= $basic_form->input('datalist', ['type' => 'date']) ?></td>
    </tr>
    <tr>
        <th>checkbox要素</th>
        <td><?= $basic_form->input('checkbox') ?></td>
    </tr>
    <tr>
        <th>checkbox要素(複数。[]が勝手に付く)</th>
        <td><?= $basic_form->input('checkboxes', ['type' => 'checkbox']) ?></td>
    </tr>
    <tr>
        <th>checkbox要素(複数でformat・separator指定)</th>
        <td><?= $basic_form->input('checkboxes-html', ['type' => 'checkbox', 'format' => "a%sb", 'separator' => " and "]) ?></td>
    </tr>
    <tr>
        <th>checkbox要素(複数でlabelにclass指定)</th>
        <td><?= $basic_form->input('checkboxes-labelclass', ['type' => 'checkbox']) ?></td>
    </tr>
    <tr>
        <th>radio要素</th>
        <td><?= $basic_form->input('radio') ?></td>
    </tr>
    <tr>
        <th>radio要素(labelにclass指定)</th>
        <td><?= $basic_form->input('radio-labelclass') ?></td>
    </tr>
    <tr>
        <th>select要素(optionごとの属性の設定等)</th>
        <td><?= $basic_form->input('select-misc') ?></td>
    </tr>
    <tr>
        <th>file要素</th>
        <td><?= $basic_form->input('file') ?></td>
    </tr>
    <tr>
        <th>file要素(複数)</th>
        <td><?= $basic_form->input('files') ?></td>
    </tr>
    <tr>
        <th>不可視要素</th>
        <td>
            ここに不可視要素があります：
            <input type="checkbox" id="toggleInvisible" name="toggleInvisible" value="1"><label for="toggleInvisible">表示する</label>
            <div id="invisible-wrapper"><?= $basic_form->input('invisible') ?></div>
        </td>
    </tr>
    <tr>
        <th>jsのみ</th>
        <td><?= $basic_form->input('client') ?></td>
    </tr>
    <tr>
        <th>phpのみ</th>
        <td><?= $basic_form->input('server') ?></td>
    </tr>
    <tr>
        <th>最終結果に含まれない</th>
        <td>
            <?= $basic_form->input('ignore_require') ?>
            <?= $basic_form->input('ignore') ?>
        </td>
    </tr>
    <tr>
        <th>警告表示</th>
        <td>
            <?= $basic_form->input('warning') ?>
        </td>
    </tr>
</table>
<input type="button" class="btn btn-info object-button" value="object">
<input type="button" class="btn btn-info html-button" value="html">
<input type="submit" id="basic_form_submit" class="btn btn-primary" value="post">
<label class='btn btn-warning'>
    <input type='checkbox' class="js-enable-switcher" checked>
    js チェック有効
</label>
<div class="output-html"></div>
<?= $basic_form->form() ?>

<dialog id="dialog">
    <form method="dialog">
        <p>警告があります。保存しますか？</p>
        <button value="yes">はい</button>
        <button value="no">いいえ</button>
    </form>
</dialog>

<script type="module">
    $$('#basic_form').chmonos.addCustomValidation(function () {
        return new Promise(function (resolve, reject) {
            var dialog = $$('#dialog');
            dialog.addEventListener('close', function (e) {
                resolve(e.target.returnValue === 'yes');
            }, {
                once: true,
            });
            dialog.returnValue = null;
            dialog.showModal();
        });
    }, 'warning');
</script>
