<?php
namespace ryunosuke\chmonos\Condition;

/**
 * 値包含バリデータ
 *
 * - haystack: array
 *   - 包含配列
 *   - ['a' => 'A', 'b' => 'B'] を与えると a,b を含まないときに「A,B を含めてください」というエラーになる
 */
class ArrayInclusion extends AbstractCondition
{
    public const INVALID       = 'InvalidArrayInclusion';
    public const NOT_INCLUSION = 'notArrayInclusion';

    protected static $messageTemplates = [
        self::INVALID       => 'Invalid value given',
        self::NOT_INCLUSION => '${implode(", ", contains)}を含めてください',
    ];

    protected $_haystack;

    public function __construct(array $haystack)
    {
        $this->_haystack = $haystack;

        parent::__construct();
    }

    public function isArrayableValidation()
    {
        return true;
    }

    public static function validate($value, $fields, $params, $consts, $error, $context)
    {
        $diff = array_diff_key($params['haystack'], array_flip($value));
        if (count($diff)) {
            $error($consts['NOT_INCLUSION'], [
                ['contains', array_values($diff)],
            ]);
        }
    }

    public function getFixture($value, $fields)
    {
        $flipped = array_flip((array) $value);
        foreach ($this->_haystack as $v => $s) {
            if (!isset($flipped[$v])) {
                $value[] = $v;
            }
        }
        return $value;
    }
}
