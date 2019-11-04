<?php
namespace ryunosuke\chmonos\Condition\Interfaces;

/**
 * maxlength 属性が付加されるインターフェース
 *
 * type="text" などで Condition オブジェクトによって maxlength が制限されるクラスに実装される。
 */
interface MaxLength
{
    public function getMaxLength();
}
