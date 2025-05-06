<?php

use ryunosuke\chmonos\Condition;
use ryunosuke\chmonos\Form;

$action_rule = [
    'text'       => [
        'title'     => 'text要素',
        'condition' => [
            new Condition\Requires(),
        ],
    ],
    'texts'      => [
        'title'     => 'text要素(複数)',
        'default'   => ['a', 'b', 'c'],
        'delimiter' => "\n",
    ],
    'checkboxes' => [
        'title'     => 'checkbox要素(複数)',
        'options'   => [
            1 => '選択肢1',
            2 => '選択肢2',
            3 => '選択肢3',
        ],
        'default'   => [2],
        'delimiter' => ',',
    ],
    'selects'    => [
        'title'     => 'select要素(複数)',
        'options'   => [
            1 => '選択肢1',
            2 => '選択肢2',
            3 => '選択肢3',
        ],
        'default'   => [1, 3],
        'delimiter' => ',',
    ],
    'children'   => [
        'inputs'  => [
            'checkboxes' => [
                'title'     => 'checkbox要素(複数)',
                'options'   => [
                    1 => '選択肢1',
                    2 => '選択肢2',
                    3 => '選択肢3',
                ],
                'default'   => [1, 3],
                'delimiter' => ',',
            ],
        ],
        'default' => [
            ['checkboxes' => [1]],
            ['checkboxes' => [2, 3]],
        ],
    ],
];
?>

<section>
    <h3>検索フォーム</h3>
    <?php
    $search_form = new Form($action_rule);
    resetForm($search_form, 'search_form');
    ?>
    <?= $search_form->form([
        'id'                    => 'search_form',
        'data-validation-event' => 'click',
    ]) ?>
    <input type="hidden" name="formid" value="search_form">
    <table class="table">
        <tr>
            <th><?= $search_form->label('text') ?></th>
            <td><?= $search_form->input('text') ?></td>
        </tr>
        <tr>
            <th><?= $search_form->label('texts') ?></th>
            <td><?= $search_form->input('texts') ?></td>
        </tr>
        <tr>
            <th><?= $search_form->label('checkboxes') ?></th>
            <td><?= $search_form->input('checkboxes', ['type' => 'checkbox']) ?></td>
        </tr>
        <tr>
            <th><?= $search_form->label('selects') ?></th>
            <td><?= $search_form->input('selects', ['type' => 'select']) ?></td>
        </tr>
        <tr>
            <th><?= $search_form->label('children') ?></th>
            <td>
                <?php foreach (range(0, 1) as $index): ?>
                    <?= $search_form->context('children', $index) ?>
                    <?= $index + 1 ?>:<?= $search_form->input('checkboxes', ['type' => 'checkbox']) ?>
                    <br>
                    <?= $search_form->context() ?>
                <?php endforeach ?>
        </tr>
    </table>
    <input type="button" class="btn btn-info object-button" value="object">
    <input type="submit" id="search_form_submit1" class="btn btn-primary" name="submit1" value="get(normal)" formenctype="application/x-www-form-urlencoded">
    <input type="submit" id="search_form_submit2" class="btn btn-primary" name="submit2" value="get(delimitable)">
    <input type="submit" id="search_form_submit3" class="btn btn-primary" name="submit3" value="get(_blank)" formtarget="_blank">
    <label class='btn btn-warning'>
        <input type='checkbox' class="js-enable-switcher" checked>
        js チェック有効
    </label>
    <?= $search_form->form() ?>
</section>

<section>
    <h3>確認フォーム</h3>
    <?php
    $confirm_form = new Form($action_rule);
    resetForm($confirm_form, 'confirm_form');
    ?>
    <?= $confirm_form->form([
        'id'                    => 'confirm_form',
        'method'                => 'post',
        'data-validation-event' => 'click',
    ]) ?>
    <input type="hidden" name="formid" value="confirm_form">
    <table class="table">
        <tr>
            <th><?= $confirm_form->label('text') ?></th>
            <td><?= $confirm_form->input('text') ?></td>
        </tr>
        <tr>
            <th><?= $confirm_form->label('texts') ?></th>
            <td><?= $confirm_form->input('texts') ?></td>
        </tr>
        <tr>
            <th><?= $confirm_form->label('checkboxes') ?></th>
            <td><?= $confirm_form->input('checkboxes', ['type' => 'checkbox']) ?></td>
        </tr>
        <tr>
            <th><?= $confirm_form->label('selects') ?></th>
            <td><?= $confirm_form->input('selects', ['type' => 'select']) ?></td>
        </tr>
        <tr>
            <th><?= $confirm_form->label('children') ?></th>
            <td>
                <?php foreach (range(0, 1) as $index): ?>
                    <?= $confirm_form->context('children', $index) ?>
                    <?= $index + 1 ?>:<?= $confirm_form->input('checkboxes', ['type' => 'checkbox']) ?>
                    <br>
                    <?= $confirm_form->context() ?>
                <?php endforeach ?>
        </tr>
    </table>
    <input type="button" class="btn btn-info object-button" value="object">
    <input type="submit" id="confirm_form_submit1" class="btn btn-primary" name="submit" value="submit1">
    <input type="submit" id="confirm_form_submit2" class="btn btn-primary" name="submit" value="submit2">
    <label class='btn btn-warning'>
        <input type='checkbox' class="js-enable-switcher" checked>
        js チェック有効
    </label>
    <?= $confirm_form->form() ?>
    <dialog id="confirm-dialog">
        <form method="dialog">
            <button type="submit" value="ok">OK</button>
            <button type="submit" value="">キャンセル</button>
        </form>
    </dialog>

    <script type="module">
        const confirmAsync = async function () {
            return new Promise(function (resolve) {
                const dialog = $$('#confirm-dialog');
                dialog.on('close', function () {
                    resolve(dialog.returnValue);
                }, {once: true});
                dialog.showModal();
            });
        };
        // confirm が同期かどうかで処理が全く異なる
        // - 同期: 単純に NO で preventDefault すれば止まる
        // - 非同期: await 後に preventDefault しても止まらないので共通でいったん止めて YES で再トリガーする
        // 同期の場合はさらに condition に非同期が含まれているかでも挙動が変わる注意
        document.on('click', async function (e) {
            if (e.isTrusted) {
                if (e.target.matches('#confirm_form_submit1')) {
                    if (await e.validationPromise) {
                        if (!confirm('ok?')) {
                            e.preventDefault();
                        }
                    }
                }
                if (e.target.matches('#confirm_form_submit2')) {
                    e.preventDefault();
                    if (await e.validationPromise) {
                        if (await confirmAsync()) {
                            e.target.click();
                        }
                    }
                }
            }
        });
    </script>
</section>
