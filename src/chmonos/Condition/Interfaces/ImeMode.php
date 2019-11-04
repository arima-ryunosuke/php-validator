<?php
namespace ryunosuke\chmonos\Condition\Interfaces;

/**
 * ime-mode 属性が付加されるインターフェース
 *
 * type="text" などで Condition オブジェクトによって ime-mode が制限されるクラスに実装される。
 */
interface ImeMode
{
    const AUTO     = 1;
    const ACTIVE   = 2;
    const INACTIVE = 3;
    const DISABLED = 4;

    public function getImeMode();
}
