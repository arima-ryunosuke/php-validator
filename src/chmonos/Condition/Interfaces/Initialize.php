<?php
namespace ryunosuke\chmonos\Condition\Interfaces;

use ryunosuke\chmonos\Context;

/**
 * 初期化の必要があるインターフェース
 *
 * 親 Context の initialize 時に自身の initialize もコールされるようになる。
 */
interface Initialize
{
    public function initialize(?Context $root, ?Context $context, $parent, $name);
}
