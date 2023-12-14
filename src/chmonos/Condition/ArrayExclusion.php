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
    public const INVALID           = 'ArrayExclusionInvalid';
    public const INVALID_INCLUSION = 'ArrayExclusionInclusion';

    protected static $messageTemplates = [
        self::INVALID           => 'Invalid value given',
        self::INVALID_INCLUSION => '%message%は同時選択できません',
    ];

    protected $_set;
    protected $_message;

    public function __construct($set, $message = null)
    {
        $this->_set = $set;
        $this->_message = $message ?? implode(',', $set);

        parent::__construct();
    }

    public function isArrayableValidation()
    {
        return true;
    }

    public static function validate($value, $fields, $params, $consts, $error, $context)
    {
        if (count(array_intersect_key(array_flip($value), $params['set'])) > 1) {
            $error($consts['INVALID_INCLUSION']);
        }
    }
}
