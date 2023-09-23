<?php
namespace ryunosuke\chmonos\Condition;

use ryunosuke\chmonos\Context;
use function ryunosuke\chmonos\array_kmap;
use function ryunosuke\chmonos\arrayize;

/**
 * 子供重複バリデータ
 *
 * ある項目内での重複を検出する。
 *
 * type=arrays 要素に設定される前提。なぜならフラットならば Compare/Unique 等で事足りるから。
 * つまり列の串刺し配列に対して Compare/Unique を適用できるイメージ。
 *
 * ```php
 * // elem1 と elem2 の結合結果が他と重複するときにエラーになる
 * ['elem1', 'elem2'],
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

    public function __construct($inputs)
    {
        $this->_inputs = arrayize($inputs);

        parent::__construct($this->_inputs);
    }

    public static function validate($value, $fields, $params, $consts, $error, $context)
    {
        $rows = array_kmap($value, $context['function'](function ($row, $k, $n, $children, $context) {
            $row = array_intersect_key($row, $children);
            $row = array_kmap($row, $context['function'](function ($v, $k, $n, $context) {
                return implode("\x1f", $context['cast']('array', $v));
            }, $context));
            return implode("\x1e", $row);
        }, array_flip($params['children']), $context));

        if (count($rows) !== count(array_unique($rows))) {
            $error($consts['NO_UNIQUE']);
        }
    }
}
