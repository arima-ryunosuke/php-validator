<?php

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
    'client'             => [
        'title'     => 'jsのみ',
        'condition' => [
            'Requires' => null,
        ],
        'checkmode' => ['client' => true, 'server' => false],
    ],
    'server'             => [
        'title'     => 'phpのみ',
        'condition' => [
            'Requires' => null,
        ],
        'checkmode' => ['client' => false, 'server' => true],
    ],
    'ignore_require'             => [
        'title'     => '最終結果に含まれない',
        'condition' => [],
        'options'   => [
            1 => 'チェックを入れると必須',
        ],
        'ignore'    => true,
    ],
    'ignore'             => [
        'title'     => 'チェックを入れると必須',
        'condition' => [
            'Requires' => 'ignore_require',
        ],
    ],
], [
    'tokenName' => 'csrf_token',
]);
resetForm($basic_form, 'basic_form');
?>

<style>
    label.common-checkbox-class, label.common-radio-class {
        background-color: #cde;
    }

    option.common-option-class {
        background-color: #cde;
    }

    label.class-1 {
        color: #f00;
    }

    label.class-2 {
        color: #0f0;
    }

    label.class-3 {
        color: #00f;
    }

    option.class-1 {
        color: #f00;
    }

    option.class-2 {
        color: #0f0;
    }

    option.class-3 {
        color: #00f;
    }
    #toggleInvisible ~ #invisible-wrapper {
        display: none;
    }
    #toggleInvisible:checked ~ #invisible-wrapper {
        display: block;
    }
</style>
<?= $basic_form->form(['id' => 'basic_form'/* ファイル要素を持つ Form は自動で POST,multipart/form-data になる */]) ?>
<input type="hidden" name="formid" value="basic_form">
<table class="table">
    <tr>
        <th>text要素</th>
        <td><?= $basic_form->input('text') ?></td>
    </tr>
    <tr>
        <th>texts要素</th>
        <td><?= $basic_form->input('texts') ?></td>
    </tr>
    <tr>
        <th>textarea要素</th>
        <td><?= $basic_form->input('textarea', ['type' => 'textarea']) ?></td>
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
        <td><?= $basic_form->input('checkboxes-labelclass', ['type' => 'checkbox', 'label_attrs' => ['class' => 'common-checkbox-class class-%s', 'data-value' => '%s']]) ?></td>
    </tr>
    <tr>
        <th>radio要素</th>
        <td><?= $basic_form->input('radio') ?></td>
    </tr>
    <tr>
        <th>radio要素(labelにclass指定)</th>
        <td><?= $basic_form->input('radio-labelclass', ['label_attrs' => ['class' => 'common-radio-class class-%s', 'data-value' => '%s']]) ?></td>
    </tr>
    <tr>
        <th>select要素(optionごとの属性の設定等)</th>
        <td><?= $basic_form->input('select-misc', ['option_attrs' => ['class' => 'common-option-class class-%s', 'data-value' => '%s']]) ?></td>
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
</table>
<input type="submit" id="basic_form_submit" class="btn btn-primary" value="post">
<label class='btn btn-warning'>
    <input type='checkbox' class="js-enable-switcher" checked>
    js チェック有効
</label>
<?= $basic_form->form() ?>
