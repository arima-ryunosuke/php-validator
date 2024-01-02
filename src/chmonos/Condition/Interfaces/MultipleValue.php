<?php
namespace ryunosuke\chmonos\Condition\Interfaces;

/**
 * delimiter 引数を受け付けて複数値が許容されるインターフェース
 *
 * Uri,Hostname など text 内に,区切りで複数値を入力するようなクラスに実装される。
 */
interface MultipleValue
{
    public function getDelimiter();
}
