<?php
namespace ryunosuke\chmonos\Condition\Interfaces;

/**
 * イベントが伝播されるようなインターフェース
 *
 * ほぼ内部向けであり、これを理解しようとして使用するのは推奨しない。
 */
interface Propagation
{
    public function getPropagation();
}
