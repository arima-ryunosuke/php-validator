<?php
namespace ryunosuke\chmonos\Condition\Interfaces;

/**
 * min/max/step 属性が付加されるインターフェース
 *
 * type="text" などで Condition オブジェクトによって min/max/step が制限されるクラスに実装される。
 */
interface Range
{
    public function getMin();

    public function getMax();

    public function getStep();
}
