<?php
namespace ryunosuke\chmonos\Condition\Interfaces;

/**
 * input type がほぼ自動的に定まるようなクラスに実装されるインターフェース
 *
 * 例えば Digits なら type=number と推測されるし、Uri なら type=url と推測できるクラスに実装される。
 */
interface InferableType
{
    public function getType();
}
