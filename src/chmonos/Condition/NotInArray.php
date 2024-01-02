<?php
namespace ryunosuke\chmonos\Condition;

/**
 * 配列の要素バリデータ（否定）
 *
 * - haystack: array
 *   - 比較配列
 * - strict: bool|null
 *   - 厳密フラグ
 *   - in_array の第3引数と同じ挙動だが、特殊な値として null が指定可能
 *     - null が与えられると「文字列化してから厳密比較」という動作になる
 */
class NotInArray extends AbstractCondition
{
    public const INVALID        = 'InvalidNotInArray';
    public const VALUE_IN_ARRAY = 'valueInArray';

    protected static $messageTemplates = [
        self::INVALID        => 'Invalid value given',
        self::VALUE_IN_ARRAY => '選択値が不正です',
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

    public function getHaystack()
    {
        if ($this->_strict === null) {
            return $this->_haystack;
        }
        else {
            return array_flip($this->_haystack);
        }
    }

    public static function validate($value, $fields, $params, $consts, $error, $context)
    {
        if ($params['strict'] === null) {
            if (isset($params['haystack'][$value])) {
                $error($consts['VALUE_IN_ARRAY']);
            }
        }
        else {
            if (in_array($value, $params['haystack'], $params['strict'])) {
                $error($consts['VALUE_IN_ARRAY']);
            }
        }
    }

    public function getFixture($value, $fields)
    {
        $haystack = $this->_haystack;
        if ($this->_strict !== null) {
            $haystack = array_flip($haystack);
        }
        unset($haystack['']);
        if (isset($haystack[$value])) {
            $value = null;
        }
        return $value;
    }
}
