<?php

use ryunosuke\chmonos\Form;

$dynamic_rule = [
    'parent-mail'     => [
        'title'     => 'メールアドレス',
        'condition' => [
            'Requires'     => null,
            'EmailAddress' => null
        ],
        'default'   => 'mail@example.com',
    ],
    'require-address' => [
        'title'     => '',
        'condition' => [],
        'options'   => [
            '1' => '配下アドレス必須'
        ],
        'default'   => ''
    ],
    'rows'            => [
        'title'     => '行',
        'default'   => [],
        'condition' => [
            'ArrayLength' => [1, 3]
        ],
        'inputs'    => [
            'title'          => [
                'title'     => 'メールアドレス',
                'condition' => [
                    'Requires' => '/require-address', // "/" を付けると親を辿れる
                    'Compare'  => ['!=', '/parent-mail'], // Compare も同じ
                ]
            ],
            'checkbox'       => [
                'title'     => 'ただの選択肢',
                'condition' => [
                    'ArrayLength' => [2, null]
                ],
                'options'   => [
                    1 => '選択肢1',
                    2 => '選択肢2',
                    3 => '選択肢3',
                ],
                'default'   => [1],
            ],
            'multiple'       => [
                'title'   => '複数選択肢',
                'options' => [
                    1 => '選択肢1',
                    2 => '選択肢2',
                    3 => '選択肢3',
                ],
                'default' => [1, 3],
            ],
            'unique_require' => [
                'options' => [
                    1 => '必須'
                ]
            ],
            'unique'         => [
                'title'     => '一意フィールド',
                'default'   => 99, // 追加時のデフォルト値としても使われる
                'condition' => [
                    'Requires' => 'unique_require',
                    'Unique'   => false
                ],
                'propagate' => [],
            ],
            'array_file'     => [
                'title'     => '追加ファイル',
                'condition' => [
                    'Requires' => null,
                    'FileType' => [
                        [
                            'JPG' => ['jpg', 'jpeg']
                        ]
                    ],
                ]
            ],
        ]
    ]
];
?>

<section>
    <h3>template</h3>
    <?php
    $template_form = new Form($dynamic_rule);
    resetForm($template_form, 'template_form');
    ?>
    <?= $template_form->form(['id' => 'template_form', 'method' => 'post']) ?>
    <input type="hidden" name="formid" value="template_form">
    <?= $template_form->label('parent-mail') ?>
    <?= $template_form->input('parent-mail') ?>
    <?= $template_form->input('require-address') ?>

    <br>
    <input class="append_row1 btn btn-success" type="button" value="追加">
    <?= $template_form->input('rows') ?>
    <table class="table">
        <!-- $template_form->template('hoge') すると script.hoge-template なタグが生成される -->
        <?= $template_form->template('rows') ?>
        <tr>
            <th><?= $template_form->label('title') ?></th>
            <td><?= $template_form->input('title') ?></td>
            <th>選択肢</th>
            <td><?= $template_form->input('checkbox', ['type' => 'checkbox']) ?><?= $template_form->input('multiple', ['type' => 'select']) ?></td>
            <th>兄弟内の重複禁止</th>
            <td><?= $template_form->input('unique_require') ?> <?= $template_form->input('unique') ?></td>
            <th>ファイル要素</th>
            <td><?= $template_form->input('array_file') ?></td>
            <td>
                <input class="delete_row1 btn btn-danger" type="button" value="削除">
            </td>
        </tr>
        <?= $template_form->template() ?>
    </table>

    <script type="text/javascript">
        (function () {
            var chmonos = document.getElementById('template_form').chmonos;
            // ノード生成時に spawn イベントが呼ばれる
            $('[data-vtemplate-name=rows]').on('spawn', function (e) {
                console.log('ノード追加', e.detail.node);
                var t = $(e.detail.node).find('[data-vinput-class="rows/title"]');
                t.after($('<span>' + t.val() + '</span>'));
            });
            // ノード削除時に cull  イベントが呼ばれる
            $('[data-vtemplate-name=rows]').on('cull', function (e) {
                console.log('ノード削除', e.detail.node);
            });
            // 追加ボタン
            $(document).on('click', '.append_row1', function () {
                chmonos.spawn('rows', function (node) {
                    $(this.parentNode).append(node);
                }, {title: Math.round(Math.random() * 10), multiple: [2, 3]});
            });
            // 削除ボタン
            $(document).on('click', '.delete_row1', function () {
                // cull を呼ぶとそのノードが削除される
                // cull を使わず単純にノード削除でも特に問題はない
                chmonos.cull('rows', this.closest('tr'));
            });
        })();
    </script>
    <input type="submit" id="template_form_submit" class="btn btn-primary" value="post">
    <label class='btn btn-warning'>
        <input type='checkbox' class="js-enable-switcher" checked>
        js チェック有効
    </label>
    <?= $template_form->form() ?>
</section>

<section>
    <h3>context</h3>
    <?php
    $context_form = new Form($dynamic_rule);
    resetForm($context_form, 'context_form');
    ?>
    <?= $context_form->form(['id' => 'context_form', 'method' => 'post']) ?>
    <input type="hidden" name="formid" value="context_form">
    <?= $context_form->label('parent-mail') ?>
    <?= $context_form->input('parent-mail') ?>
    <?= $context_form->input('require-address') ?>

    <br>
    <input class="append_row2 btn btn-success" type="button" value="追加">
    <?= $context_form->input('rows') ?>
    <table id="context-table" class="table">
        <tbody>
        <template id="rows-template" data-vtemplate-name="rows">
            <?= $context_form->context('rows') ?>
            <tr>
                <th><?= $context_form->label('title') ?></th>
                <td><?= $context_form->input('title') ?></td>
                <th>選択肢</th>
                <td><?= $context_form->input('checkbox', ['type' => 'checkbox']) ?><?= $context_form->input('multiple', ['type' => 'select']) ?></td>
                <th>兄弟内の重複禁止</th>
                <td><?= $context_form->input('unique_require') ?> <?= $context_form->input('unique') ?></td>
                <th>ファイル要素</th>
                <td><?= $context_form->input('array_file') ?></td>
                <td>
                    <input class="delete_row2 btn btn-danger" type="button" value="削除">
                </td>
            </tr>
            <?= $context_form->context() ?>
        </template>
        <?php foreach (range(1, 2) as $index): ?>
            <?= $context_form->context('rows', $index) ?>
            <tr>
                <th><?= $context_form->label('title', ['value' => "title$index"]) ?></th>
                <td><?= $context_form->input('title', ['value' => "title$index"]) ?></td>
                <th>選択肢</th>
                <td><?= $context_form->input('checkbox', ['type' => 'checkbox']) ?><?= $context_form->input('multiple', ['type' => 'select']) ?></td>
                <th>兄弟内の重複禁止</th>
                <td><?= $context_form->input('unique_require') ?> <?= $context_form->input('unique') ?></td>
                <th>ファイル要素</th>
                <td><?= $context_form->input('array_file') ?></td>
                <td>
                    <input class="delete_row2 btn btn-danger" type="button" value="削除">
                </td>
            </tr>
            <?= $context_form->context() ?>
        <?php endforeach ?>
        </tbody>
    </table>

    <script type="text/javascript">
        (function () {
            var chmonos = document.getElementById('context_form').chmonos;
            // 追加ボタン
            $(document).on('click', '.append_row2', function () {
                var node = chmonos.birth(document.querySelector('#rows-template'), {title: Math.round(Math.random() * 10)});
                $('#context-table').append(node);
            });
            // 削除ボタン
            $(document).on('click', '.delete_row2', function () {
                // cull を呼ぶとそのノードが削除される
                // cull を使わず単純にノード削除でも特に問題はない
                chmonos.cull('rows', this.closest('tr'));
            });
        })();
    </script>
    <input type="submit" id="context_form_submit" class="btn btn-primary" value="post">
    <label class='btn btn-warning'>
        <input type='checkbox' class="js-enable-switcher" checked>
        js チェック有効
    </label>
    <?= $context_form->form() ?>
</section>
