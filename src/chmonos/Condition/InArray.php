<?php
namespace ryunosuke\chmonos\Condition;

/**
 * 配列要素バリデータ
 *
 * - haystack: array
 *   - 比較配列
 * - strict: bool|null
 *   - 厳密フラグ
 *   - in_array の第3引数と同じ挙動だが、特殊な値として null が指定可能
 *     - null が与えられると「文字列化してから厳密比較」という動作になる
 */
class InArray extends AbstractCondition
{
    public const INVALID      = 'InvalidInArray';
    public const NOT_IN_ARRAY = 'notInArray';

    protected static $messageTemplates = [
        self::INVALID      => 'Invalid value given',
        self::NOT_IN_ARRAY => '選択値が不正です',
    ];

    protected $_haystack;
    protected $_strict;

    public function __construct($haystack, $strict = null)
    {
        if ($strict === null) {
            $haystack = array_flip($haystack);
        }

        $this->_haystack = $haystack;
        $this->_strict = $strict;

        parent::__construct();
    }

    public static function validate($value, $fields, $params, $consts, $error, $context)
    {
        if ($params['strict'] === null) {
            if (!isset($params['haystack'][$value])) {
                $error($consts['NOT_IN_ARRAY']);
            }
        }
        else {
            if (!in_array($value, $params['haystack'], $params['strict'])) {
                $error($consts['NOT_IN_ARRAY']);
            }
        }
    }
}
