<?php
namespace ryunosuke\chmonos\Condition;

/**
 * 排他バリデータ
 *
 * - set: array
 *   - 指定セット同士で同時選択が不可になる
 */
class ArrayExclusion extends AbstractCondition
{
    public const INVALID_INCLUSION = 'ArrayExclusionInclusion';

    protected static $messageTemplates = [
        self::INVALID_INCLUSION => '${implode(",", _set)}は同時選択できません',
    ];

    protected $_set;

    public function __construct($set)
    {
        $this->_set = $set;

        parent::__construct();
    }

    public function isArrayableValidation()
    {
        return true;
    }

    public static function validate($value, $fields, $params, $consts, $error, $context)
    {
        if (count(array_intersect_key(array_flip($value), $params['set'])) > 1) {
            $error($consts['INVALID_INCLUSION'], []);
        }
    }

    public function getFixture($value, $fields)
    {
        return array_flip(array_diff_key(array_flip($value), $this->_set));
    }
}
