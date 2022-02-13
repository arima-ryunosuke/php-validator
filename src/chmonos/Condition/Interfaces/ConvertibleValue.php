<?php
namespace ryunosuke\chmonos\Condition\Interfaces;

/**
 * 値の変換ができるようなクラスに実装されるインターフェース
 *
 * 例えば Date に DateTime が来たら format で文字列化できるし、Decimal なら独自の小数変換をかませられる。
 */
interface ConvertibleValue
{
    public function getValue($value);
}
