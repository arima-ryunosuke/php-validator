<?php

use ryunosuke\chmonos\Condition\Regex;use ryunosuke\chmonos\Form;
use ryunosuke\chmonos\HtmlString;

$other_form = new Form([
    'html-input'          => [
        'title'     => new HtmlString('<strong>STRONG</strong> title'),
        'condition' => [
        ],
        'options'   => [
            1 => '<i>I</i>',
        ],
        'attribute' => [
            'data-hoge' => '<a>A</a>',
        ],
    ],
    'require-mark'        => [
        'condition' => [],
        'options'   => [1 => '必須']
    ],
    'require-depend'      => [
        'title'     => 'チェックを入れると必須',
        'condition' => [
            'Requires' => 'require-mark'
        ]
    ],
    'id'                  => [
        'title' => 'id（id 属性を指定する）',
    ],
    'year-month-day'      => [
        'title'     => '結合値',
        'phantom'   => ['%04d/%02d/%02d', 'year', 'month', 'day'],
        'condition' => [
            'Date' => 'Y/m/d'
        ]
    ],
    'year'                => [
        'condition' => [
            'Digits' => null,
            'Range'  => [1900, 2100]
        ],
        'default' => 2000,
    ],
    'month'               => [
        'condition' => [
            'Digits' => null,
            'Range'  => [1, 12]
        ],
        'default' => 2,
    ],
    'day'                 => [
        'condition' => [
            'Digits' => null,
            'Range'  => [1, 31]
        ],
        'default' => 31,
    ],
    'consent_check'       => [
        'options' => [
            1 => '必須にする'
        ]
    ],
    'consent_memo'        => [
        'title'     => '伝播先',
        'condition' => [
            'Requires' => 'consent_check'
        ],
        'dependent' => [],
    ],
    'flag_trimming_true'  => [
        'title'     => '空白不可',
        'condition' => [
            'Requires' => null
        ],
        'trimming'  => true
    ],
    'flag_trimming_false' => [
        'title'     => '空白可',
        'condition' => [
            'Requires' => null
        ],
        'trimming'  => false
    ],
    'custom_message'      => [
        'title'     => 'カスタムメッセージ',
        'condition' => [
            (new Regex("/^[a-z]*$/"))->setMessageTemplates([
                'regexNotMatch' => 'a～zで入力しろ'
            ]),
        ]
    ],
    'validated'           => [
        'title'     => 'カスタムイベント',
        'condition' => [
            'Requires' => null
        ]
    ],
    'server_only'         => [
        'condition' => []
    ],
    'server_only_ajax'    => [
        'condition' => [
            new ryunosuke\chmonos\Condition\Ajax('', [], function ($value) {
                if ($value !== 'unique') {
                    return 'このメッセージはサーバ側のチェックの結果';
                }
            })
        ]
    ]
]);
if (resetForm($other_form, 'other_form')) {
    // error(要素名, メッセージ) とするとその要素に対してカスタムエラーを発行できる
    $other_form->error('server_only', 'このメッセージはサーバ側のチェックの結果');
}
?>

<?= $other_form->form(['id' => 'other_form', 'method' => 'post']) ?>
<input type="hidden" name="formid" value="other_form">
<input type="hidden" id="cutom-input">
<table class="table">
    <tr>
        <th><?= $other_form->label('html-input') ?></th>
        <td>
            <?= $other_form->input('html-input') ?>
        </td>
    </tr>
    <tr>
        <th><?= $other_form->label('require-depend') ?></th>
        <td>
            <?= $other_form->input('require-mark') ?>
            <?= $other_form->input('require-depend') ?>
        </td>
    </tr>
    <tr>
        <th><?= $other_form->label('id', ['id' => 'this-is-id']) ?></th>
        <td>
            <?= $other_form->input('id', ['for' => 'this-is-id']) ?>
        </td>
    </tr>
    <tr>
        <th>Phantom（結合値が検証に使用される）</th>
        <td>
            <?= $other_form->input('year') ?>年
            <?= $other_form->input('month') ?>月
            <?= $other_form->input('day') ?>日
            <?= $other_form->input('year-month-day') ?>
        </td>
    </tr>
    <tr>
        <th>Propagate（イベントが伝播しない）</th>
        <td>
            <?= $other_form->input('consent_check', ['type' => 'checkbox']) ?>
            <?= $other_form->input('consent_memo') ?>
        </td>
    </tr>
    <tr>
        <th>trimming：trim されるので空白不可 | されないので空白可</th>
        <td>
            <?= $other_form->input('flag_trimming_true') ?>
            <?= $other_form->input('flag_trimming_false') ?>
        </td>
    </tr>
    <tr>
        <th>カスタムメッセージ（Regex Condition なのに「a～zで入力しろ」と言われる）</th>
        <td>
            <?= $other_form->input('custom_message') ?>
        </td>
    </tr>
    <tr>
        <th>バリデーションのカスタムイベント</th>
        <td>
            <?= $other_form->input('validated') ?>
        </td>
    </tr>
    <tr>
        <th>サーバ側でしかできないチェック（重複チェックとか）</th>
        <td>
            <?= $other_form->input('server_only') ?>
        </td>
    </tr>
    <tr>
        <th>サーバ側でしかできないチェック（Ajax を利用する。簡易ならコレで十分）</th>
        <td>
            <?= $other_form->input('server_only_ajax', ['value' => 'hoge']) ?>
        </td>
    </tr>
</table>
<input type="button" class="btn btn-info object-button" value="object">
<input type="submit" id="other_form_submit" class="btn btn-primary" value="post">
<input type="submit" class="btn btn-primary" value="submit ボタンの属性が活きます" formaction="?action=hoge" formmethod="post" formtarget="_blank">
<label class='btn btn-warning'>
    <input type='checkbox' class="js-enable-switcher" checked>
    js チェック有効
</label>
<?= $other_form->form() ?>

<script type="module">
    var $validated = $$('[data-vinput-id=validated]');
    $validated.on('input', function (e) {
        console.log(e)
    });
    // validated で検証完了イベントがフックできる。
    // （イベント順にもよるが） stopPropagation すればエラーをキャンセルできる
    $validated.on('validated', function (e) {
        console.log(e);
        e.stopPropagation();
        alert('フックされたバリデーションイベント：エラー有り\n' + JSON.stringify(e.detail.errorTypes));
    });
    $$('#other_form').chmonos.addCustomValidation(function () {
        var answer = prompt('これはカスタムバリデーションの before イベントです');
        if (answer === null) {
            return false;
        }
        $$('#cutom-input').value = answer;
    }, 'before');
    $$('#other_form').chmonos.addCustomValidation(function () {
        if ($$('#cutom-input').value !== 'hoge') {
            alert('これはカスタムバリデーションの before イベントです。ダイアログで "hoge" と入力してください');
            return false;
        }
    }, 'after');
    $$('#other_form').on('submit', function () {
        alert('これはフォーム自体に bind されたイベントです');
    });
</script>
