<?php

namespace ryunosuke\chmonos;

use ryunosuke\chmonos\Condition\AbstractCondition;
use ryunosuke\Functions\Transporter;

$DIR = __DIR__;

require_once "$DIR/../vendor/autoload.php";

// 使用されているものだけ吐き出す（「プレーン・テキストとしてマーク」するとよい）
file_put_contents("$DIR/../src/functions.php", Transporter::exportNamespace(__NAMESPACE__, false, "$DIR/../src/chmonos"));
// ただし、開発中に不便なので tests にすべて吐き出す
file_put_contents("$DIR/../tests/functions.php", Transporter::exportNamespace(__NAMESPACE__));

// js のビルド
AbstractCondition::outputJavascript(__DIR__ . '/../script', true);
