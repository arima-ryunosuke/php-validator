<?php
namespace ryunosuke\chmonos\Condition;

use function ryunosuke\chmonos\arrayize;

/**
 * 子供重複バリデータ
 *
 * ある項目内での重複を検出する。
 *
 * type=arrays 要素に設定される前提。なぜならフラットならば Compare/Unique 等で事足りるから。
 * つまり列の串刺し配列に対して Compare/Unique を適用できるイメージ。
 *
 * $ignore_empty を true にすると串刺し結果が空の場合にスルーされる。
 *
 * ```php
 * // elem1 と elem2 の結合結果が他と重複するときにエラーになる
 * ['elem1', 'elem2'], false
 * ```
 */
class UniqueChild extends AbstractParentCondition
{
    public const INVALID   = 'UniqueChildInvalid';
    public const NO_UNIQUE = 'UniqueChildNoUnique';

    protected static $messageTemplates = [
        self::INVALID   => 'Invalid value given',
        self::NO_UNIQUE => '値が重複しています',
    ];

    protected $_inputs;
    protected $_ignore_empty;

    public function __construct($inputs, $ignore_empty = false)
    {
        $this->_inputs = arrayize($inputs);
        $this->_ignore_empty = $ignore_empty;

        parent::__construct($this->_inputs);
    }

    public static function validate($value, $fields, $params, $consts, $error, $context)
    {
        $rows = array_map($context['function'](function ($row, $children, $context) {
            $row = array_intersect_key($row, $children);
            $cb = fn($v, $context) => implode("\x1f", $context['cast']('array', $v));
            $row = array_map($context['function']($cb, $context), $row);
            return implode("\x1e", $row);
        }, array_flip($params['children']), $context), $value);

        if ($params['ignore_empty']) {
            $rows = array_filter($rows, fn($row) => strlen(trim($row, "\x1e")));
        }

        if (count($rows) !== count(array_unique($rows))) {
            $error($consts['NO_UNIQUE']);
        }
    }

    public function getFixture($value, $fields)
    {
        // 値が重複していたからと言って代替値を提供できるわけではないのでできることはない
        // もっと言うと例えば options が2つの時に3行あったら必ず重複する
        return $value;
    }
}
