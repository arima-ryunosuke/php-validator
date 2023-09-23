<?php
namespace ryunosuke\chmonos\Condition;

use ryunosuke\chmonos\Context;

/**
 * 検証条件抽象クラス
 *
 * このクラスを継承して細かな条件を定義していく。
 * protected 以上のアンダースコア付きフィールドは検証パラメータとしてクライアントサイドに流れるので注意すること。
 */
abstract class AbstractParentCondition extends AbstractCondition implements Interfaces\Propagation, Interfaces\Initialize
{
    protected $_name;
    protected $_children;

    public function __construct(array $children)
    {
        $this->_children = $children;

        parent::__construct();
    }

    public function initialize(?Context $root, ?Context $context, $parent, $name)
    {
        $this->_name = $name;
    }

    public function getPropagation()
    {
        return array_map(fn($v) => "/$this->_name/$v", $this->_children);
    }

    public function isArrayableValidation()
    {
        return true;
    }
}
