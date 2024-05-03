<?php

$action_form = new \ryunosuke\chmonos\Form([
    'text'       => [
        'title'   => 'text要素',
        'default' => "X",
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
            3 => '選択肢3'
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
                    3 => '選択肢3'
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
]);
resetForm($action_form, 'action_form');
?>

<?= $action_form->form(['id' => 'action_form']) ?>
<input type="hidden" name="formid" value="action_form">
<table class="table">
    <tr>
        <th><?= $action_form->label('text') ?></th>
        <td><?= $action_form->input('text') ?></td>
    </tr>
    <tr>
        <th><?= $action_form->label('texts') ?></th>
        <td><?= $action_form->input('texts') ?></td>
    </tr>
    <tr>
        <th><?= $action_form->label('checkboxes') ?></th>
        <td><?= $action_form->input('checkboxes', ['type' => 'checkbox']) ?></td>
    </tr>
    <tr>
        <th><?= $action_form->label('selects') ?></th>
        <td><?= $action_form->input('selects', ['type' => 'select']) ?></td>
    </tr>
    <tr>
        <th><?= $action_form->label('children') ?></th>
        <td>
            <?php foreach (range(0, 1) as $index): ?>
                <?= $action_form->context('children', $index) ?>
                <?= $index + 1 ?>:<?= $action_form->input('checkboxes', ['type' => 'checkbox']) ?>
                <br>
                <?= $action_form->context() ?>
            <?php endforeach ?>
    </tr>
</table>
<input type="button" class="btn btn-info object-button" value="object">
<input type="submit" id="action_form_submit1" class="btn btn-primary" name="submit1" value="get(normal)" formenctype="application/x-www-form-urlencoded">
<input type="submit" id="action_form_submit2" class="btn btn-primary" name="submit2" value="get(delimitable)">
<input type="submit" id="action_form_submit3" class="btn btn-primary" name="submit3" value="get(_blank)" formtarget="_blank">
<label class='btn btn-warning'>
    <input type='checkbox' class="js-enable-switcher" checked>
    js チェック有効
</label>
<?= $action_form->form() ?>
