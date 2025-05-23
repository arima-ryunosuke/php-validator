<?php

use ryunosuke\chmonos\Form;

$dynamic_rule = [
    'parent_mail'     => [
        'title'     => 'メールアドレス',
        'condition' => [
            'Requires'     => null,
            'EmailAddress' => null
        ],
        'default'   => 'mail@example.com',
    ],
    'require_address' => [
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
            'RequiresChild' => [
                [
                    'title'  => ['all', ['title1', 'title2']],
                    'unique' => ['any', ['99', '98']],
                ]
            ],
            'UniqueChild'   => [['title', 'checkbox'], true],
            'ArrayLength'   => [1, 3]
        ],
        'inputs'    => [
            'title'          => [
                'title'     => 'メールアドレス',
                'condition' => [
                    'Requires' => '/require_address', // "/" を付けると親を辿れる
                    'Compare'  => ['!=', '/parent_mail'], // Compare も同じ
                ],
                'default'   => '',
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
                'title'   => '',
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

<style>
    .html_form .chmonos-output {
        .chmonos-output-row:after {
            content: "、";
        }

        .chmonos-output-row:nth-last-child(1 of :not(:has(>dt:empty))):after,
        .chmonos-output-row:nth-last-child(1 of :not(:has(>dd:empty))):after {
            content: "";
        }

        .chmonos-output-row {
            display: inline;
        }

        .chmonos-output-row:has(>dt:empty),
        .chmonos-output-row:has(>dd:empty) {
            display: none;
        }

        dt,
        dd {
            display: inline;
        }

        dt:after {
            content: "：";
        }
    }
</style>

<section>
    <h3>template</h3>
    <?php
    $template_form = new Form($dynamic_rule);
    $template_form->setValues([
        'rows' => [
            [
                'title'    => 'foo',
                'checkbox' => [2],
                'unique'   => 1,
            ],
        ],
    ]);
    resetForm($template_form, 'template_form');
    ?>
    <?= $template_form->form(['id' => 'template_form', 'method' => 'post', 'class' => 'html_form']) ?>
    <input type="hidden" name="formid" value="template_form">
    <?= $template_form->label('parent_mail') ?>
    <?= $template_form->input('parent_mail') ?>
    <span data-vnode="Chmonos.Utils.htmlTemplateTag">${(this.parent_mail ?? '')}</span>
    <?= $template_form->input('require_address') ?>

    <style>
        .template-item {
            display: grid;
            grid-template-columns: max-content 320px max-content;

            dt {
                grid-column: 1;
            }
            dd {
                grid-column: 2;
            }
            pre {
                grid-column: 3;
                grid-row-start: 1;
                grid-row-end: 6;
            }
        }
    </style>
    <br>
    <input class="append_row1 btn btn-success" type="button" value="追加">
    <?= $template_form->input('rows') ?>
    <div class="template-holder">
        <!-- $template_form->template('hoge') すると script.hoge-template なタグが生成される -->
        <?= $template_form->template('rows') ?>
        <dl class="template-item">
            <pre data-vnode="Chmonos.Utils.htmlTemplateTag">${JSON.stringify(this, null, 2)}</pre>
            <dt style="grid-row: 1"><?= $template_form->label('title') ?></dt>
            <dd style="grid-row: 1"><?= $template_form->input('title') ?></dd>
            <dt style="grid-row: 2">選択肢</dt>
            <dd style="grid-row: 2">
                <?= $template_form->input('checkbox', ['type' => 'checkbox']) ?>
                <span data-vnode="Chmonos.Utils.htmlTemplateTag" data-bool="${!!(this.checkbox ?? []).length}">${(this.checkbox ?? []).length}個</span><br>
                <?= $template_form->input('multiple', ['type' => 'select']) ?>
            </dd>
            <dt style="grid-row: 3">兄弟内の重複禁止</dt>
            <dd style="grid-row: 3"><?= $template_form->input('unique_require') ?> <?= $template_form->input('unique') ?></dd>
            <dt style="grid-row: 4">ファイル要素</dt>
            <dd style="grid-row: 4">
                <div class="vfile-dropzone">
                    ドロップエリア
                    <?= $template_form->input('array_file') ?>
                </div>
            </dd>
            <dd style="grid-row: 5">
                <input class="delete_row1 btn btn-danger" type="button" value="削除">
                <input class="insert_row1 btn btn-danger" type="button" value="挿入">
                <input class="reset_row1 btn btn-danger" type="button" value="リセット">
            </dd>
        </dl>
        <?= $template_form->template() ?>
    </div>

    <input type="button" class="btn btn-info object-button" value="object">
    <input type="button" class="btn btn-info html-button" value="html">
    <input type="submit" id="template_form_submit" class="btn btn-primary" value="post">
    <label class='btn btn-warning'>
        <input type='checkbox' class="js-enable-switcher" checked>
        js チェック有効
    </label>
    <div class="output-html"></div>
    <?= $template_form->form() ?>

    <script type="module">
        var chmonos = document.getElementById('template_form').chmonos;
        // 画面構築時の初期生成開始時に spawnBegin イベントが呼ばれる
        $$('[data-vtemplate-name=rows]').on('spawnBegin', function (e) {
            console.log(e);
        });
        // 画面構築時の初期生成完了時に spawnEnd イベントが呼ばれる
        $$('[data-vtemplate-name=rows]').on('spawnEnd', function (e) {
            console.log(e);
        });
        // ノード生成時に spawn イベントが呼ばれる
        $$('[data-vtemplate-name=rows]').on('spawn', function (e) {
            console.log('ノード追加', e.detail.node);
            var t = e.detail.node.querySelector('[data-vinput-class="rows/title"]');
            var span = document.createElement('span');
            span.textContent = t.value;
            t.parentNode.insertBefore(span, t.nextElementSibling);
        });
        // ノード削除時に cull  イベントが呼ばれる
        $$('[data-vtemplate-name=rows]').on('cull', function (e) {
            console.log('ノード削除', e.detail.node);
        });
        // 追加ボタン
        $$('.append_row1').on('click', function (e) {
            chmonos.spawn('rows', function (node) {
                this.parentNode.appendChild(node);
            }, {title: Math.round(Math.random() * 10), checkbox: [2, 3]});
        });
        // 挿入ボタン
        $$('#template_form').on('click', function (e) {
            if (e.target.matches('.insert_row1')) {
                chmonos.respawn('rows', function (node, self) {
                    self.after(node);
                }, {}, e.target.closest('dl'));
            }
        });
        // リセットボタン
        $$('#template_form').on('click', function (e) {
            if (e.target.matches('.reset_row1')) {
                chmonos.rebirth(e.target.closest('dl'), {title: Math.round(Math.random() * 10), checkbox: [2, 3]});
            }
        });
        // 削除ボタン
        $$('#template_form').on('click', function (e) {
            if (e.target.matches('.delete_row1')) {
                // cull を呼ぶとそのノードが削除される
                // cull を使わず単純にノード削除でも特に問題はない
                chmonos.cull('rows', e.target.closest('dl'));
            }
        });
    </script>
</section>

<section>
    <h3>context</h3>
    <?php
    $context_form = new Form($dynamic_rule);
    resetForm($context_form, 'context_form');
    ?>
    <?= $context_form->form(['id' => 'context_form', 'method' => 'post', 'class' => 'html_form']) ?>
    <input type="hidden" name="formid" value="context_form">
    <?= $context_form->label('parent_mail') ?>
    <?= $context_form->input('parent_mail') ?>
    <?= $context_form->input('require_address') ?>

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
                <td>
                    <div class="vfile-dropzone">
                        ドロップエリア
                        <?= $context_form->input('array_file') ?>
                    </div>
                </td>
                <td>
                    <input class="delete_row2 btn btn-danger" type="button" value="削除">
                </td>
            </tr>
            <?= $context_form->context() ?>
        </template>
        <?php foreach (range(1, 2) as $index): ?>
            <?= $context_form->context('rows', $index) ?>
            <tr data-vinputs-name="rows" data-vinput-index="<?= $index ?>">
                <th><?= $context_form->label('title', ['value' => "title$index"]) ?></th>
                <td><?= $context_form->input('title', ['value' => "title$index"]) ?></td>
                <th>選択肢</th>
                <td><?= $context_form->input('checkbox', ['type' => 'checkbox']) ?><?= $context_form->input('multiple', ['type' => 'select']) ?></td>
                <th>兄弟内の重複禁止</th>
                <td><?= $context_form->input('unique_require') ?> <?= $context_form->input('unique') ?></td>
                <th>ファイル要素</th>
                <td>
                    <div class="vfile-dropzone">
                        ドロップエリア
                        <?= $context_form->input('array_file') ?>
                    </div>
                </td>
                <td>
                    <input class="delete_row2 btn btn-danger" type="button" value="削除">
                </td>
            </tr>
            <?= $context_form->context() ?>
        <?php endforeach ?>
        </tbody>
    </table>

    <input type="button" class="btn btn-info object-button" value="object">
    <input type="button" class="btn btn-info html-button" value="html">
    <input type="submit" id="context_form_submit" class="btn btn-primary" value="post">
    <label class='btn btn-warning'>
        <input type='checkbox' class="js-enable-switcher" checked>
        js チェック有効
    </label>
    <div class="output-html"></div>
    <?= $context_form->form() ?>

    <script type="module">
        var chmonos = document.getElementById('context_form').chmonos;
        // 追加ボタン
        $$('.append_row2').on('click', function (e) {
            var node = chmonos.birth($$('#rows-template'), {title: Math.round(Math.random() * 10)});
            $$('#context-table>tbody').appendChild(node);
        });
        // 削除ボタン
        $$('#context_form').on('click', function (e) {
            if (e.target.matches('.delete_row2')) {
                // cull を呼ぶとそのノードが削除される
                // cull を使わず単純にノード削除でも特に問題はない
                chmonos.cull('rows', e.target.closest('tr'));
            }
        });
    </script>
</section>

<section>
    <h3>vue.js</h3>
    <div id="application">
    <?php
    $vuejs_form = new Form($dynamic_rule, [
        'vuejs' => 'vuejs_chmonos',
    ]);
    resetForm($vuejs_form, 'vuejs_form');
    ?>
    <?= $vuejs_form->form(['id' => 'vuejs_form', 'method' => 'post', 'class' => 'html_form']) ?>
        <input type="hidden" name="formid" value="vuejs_form">
        <?= $vuejs_form->label('parent_mail') ?>
        <?= $vuejs_form->input('parent_mail', [
            'v-model.modifier' => 'trim',
        ]) ?>
        <?= $vuejs_form->input('require_address') ?>

        <br>
        <input class="append_row3 btn btn-success" v-on:click="append" type="button" value="追加">
        <?= $vuejs_form->input('rows', ['data-vinput-selector' => '"#vuejs-table"']) ?>
        <table id="vuejs-table" class="table">
            <tbody>
                <tr class="item" v-for="(row, index) in rows">
                    <?= $vuejs_form->vuefor('rows', 'row', 'index') ?>
                    <th><?= $vuejs_form->label('title') ?></th>
                    <td>
                        <?= $vuejs_form->input('title', ['type' => 'textarea']) ?>
                        文字数<span>{{row.title.length}}</span>
                    </td>
                    <th>選択肢</th>
                    <td><?= $vuejs_form->input('checkbox', ['type' => 'checkbox']) ?><?= $vuejs_form->input('multiple', ['type' => 'select']) ?></td>
                    <th>兄弟内の重複禁止</th>
                    <td><?= $vuejs_form->input('unique_require') ?> <?= $vuejs_form->input('unique') ?></td>
                    <th>ファイル要素</th>
                    <td>
                        <div class="vfile-dropzone">
                            ドロップエリア
                            <?= $vuejs_form->input('array_file') ?>
                        </div>
                    </td>
                    <td>
                        <input class="delete_row3 btn btn-danger" v-on:click="remove(index)" type="button" value="削除">
                    </td>
                    <?= $vuejs_form->vuefor() ?>
                </tr>
            </tbody>
        </table>

        <pre>{{JSON.stringify(this.$data, null, 2)}}</pre>

        <input type="submit" id="vuejs_form_submit" class="btn btn-primary" value="post">
        <input type="button" class="btn btn-info html-button" value="html">
        <label class='btn btn-warning'>
            <input type='checkbox' class="js-enable-switcher" checked>
            js チェック有効
        </label>
        <div class="output-html"></div>
    <?= $vuejs_form->form() ?>
    </div>

    <script type="module">
        const vuejs_chmonos = JSON.parse(document.getElementById('vuejs_chmonos').textContent);
        document.getElementById('vuejs_chmonos').remove();
        const app = Vue.createApp({
            data: function () {
                return vuejs_chmonos.data;
            },
            methods: {
                append: function () {
                    this.rows.push(Object.assign({}, vuejs_chmonos.defaults.rows));
                },
                remove: function (index) {
                    this.rows.splice(index, 1);
                },
            },
            mounted: function () {
                this.$nextTick(function () {
                    const chmonos = new Chmonos($$('#vuejs_form'), vuejs_chmonos);
                    chmonos.initialize();
                });
            },
        }).mount('#application');
    </script>
</section>
