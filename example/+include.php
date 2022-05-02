<?php

use ryunosuke\chmonos\Condition\AbstractCondition;
use ryunosuke\chmonos\Form;
use ryunosuke\chmonos\Input;
use function ryunosuke\chmonos\var_pretty;

$loader = require __DIR__ . '/../vendor/autoload.php';
$loader->addPsr4('ryunosuke\\chmonos\\CustomCondition\\', __DIR__ . '/CustomCondition');

AbstractCondition::setNamespace(['ryunosuke\\chmonos\\CustomCondition' => __DIR__ . '/CustomCondition']);

// サンプル兼テストなのでスーパーリロードで強制出力
AbstractCondition::outputJavascript(__DIR__, ($_SERVER['HTTP_CACHE_CONTROL'] ?? '') === 'no-cache');

Input::setDefaultRule([
    'wrapper' => 'wrapper',
    'grouper' => 'grouper',
    //'event'   => ['change.norequire'],
]);

session_start();

/**
 * condition.php と ajax.php で共用する Ajax インスタンス
 */
$ajax = new ryunosuke\chmonos\Condition\Ajax('ajax.php', ['ajax1', 'ajax2'], function ($self, $params) {
    $sum = $params['ajax1'] + $params['ajax2'];
    if ($sum != $self) {
        return "合計値が一致しません（$sum であるべき）";
    }
    return null;
});

/**
 * フォームの値をよしなに設定する関数
 *
 * @param Form $form
 * @param string $id
 * @return bool
 */
function resetForm(Form $form, $id)
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (($_POST['formid'] ?? '') === $id) {
            $posts = $_POST;
            $values = $posts;
            if (!$form->validate($values)) {
                http_response_code(422);
            }
            echo var_pretty(['posts' => $posts, 'values' => $values], null, true);
            return true;
        }
    }
    return false;
}
