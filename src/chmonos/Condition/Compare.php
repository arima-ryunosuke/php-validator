<?php
namespace ryunosuke\chmonos\Condition;

use function ryunosuke\chmonos\date_modulate;

/**
 * 項目比較バリデータ
 *
 * 「確認のためもう一度入力してください」とか「開始時間＞終了時間になっています」とか。
 *
 * - operator: string
 *   - 比較方法（'==' '>=' などの演算子）
 *   - ==, ===, !=, !==, <=, <, >=, > が指定可能
 * - operand: mixed
 *   - 比較対象フィールド名
 *   - direct が true ならフィールドではなく直値を指定できる
 * - filter: string-callable
 *   - 値フィルタ（比較前に通される処理）
 *   - 例えば strtotime などを渡せば日付文字列の大小比較が可能になる
 * - offset: int|null
 *   - 比較する際のオフセット値（ゲタ履かせ）
 * - direct: bool
 *   - true にするとフィールドではなく直値との比較になる
 */
class Compare extends AbstractCondition implements Interfaces\Propagation
{
    public const INVALID      = 'compareInvalid';
    public const EQUAL        = 'compareEqual';
    public const NOT_EQUAL    = 'compareNotEqual';
    public const LESS_THAN    = 'compareLessThan';
    public const GREATER_THAN = 'compareGreaterThan';
    public const SIMILAR      = 'compareSimilar';

    protected static $messageTemplates = [
        self::INVALID      => 'Invalid value given',
        self::EQUAL        => '「${$resolveTitle(_operand)}」と同じ値を入力してください',
        self::NOT_EQUAL    => '「${$resolveTitle(_operand)}」と異なる値を入力してください',
        self::LESS_THAN    => '「${$resolveTitle(_operand)}」より小さい値を入力してください',
        self::GREATER_THAN => '「${$resolveTitle(_operand)}」より大きい値を入力してください',
    ];

    protected $_operator;
    protected $_operand;
    protected $_filter;
    protected $_offset;
    protected $_direct;

    public function __construct($operator, $operand, $filter = '', $offset = null, $direct = false)
    {
        $this->_operator = $operator;
        $this->_operand = $operand;
        $this->_filter = $filter;
        $this->_offset = $offset;
        $this->_direct = $direct;

        parent::__construct();
    }

    public function getFields()
    {
        return $this->_direct ? [] : [$this->_operand];
    }

    public function getPropagation()
    {
        return $this->getFields();
    }

    public static function validate($value, $fields, $params, $consts, $error, $context)
    {
        $field1 = $value;
        $field2 = $params['direct'] ? $params['operand'] : $fields[$params['operand']];

        if (strlen($field2 ?? '') === 0) {
            return;
        }

        if (strlen($params['filter'])) {
            $field1 = ($context['chmonos'][$params['filter']])($field1);
            $field2 = ($context['chmonos'][$params['filter']])($field2);
        }

        if ($params['offset']) {
            $field1 = $params['offset'] + $field1 - $field2;
            $field2 = 0;
        }

        if ($params['operator'] === '==' && $field1 != $field2) {
            return $error($consts['EQUAL'], []);
        }
        if ($params['operator'] === '===' && $field1 !== $field2) {
            return $error($consts['EQUAL'], []);
        }
        if ($params['operator'] === '!=' && $field1 == $field2) {
            return $error($consts['NOT_EQUAL'], []);
        }
        if ($params['operator'] === '!==' && $field1 === $field2) {
            return $error($consts['NOT_EQUAL'], []);
        }
        if ($params['operator'] === '<' && $field1 >= $field2) {
            return $error($consts['LESS_THAN'], []);
        }
        if ($params['operator'] === '<=' && $field1 > $field2) {
            return $error($consts['LESS_THAN'], []);
        }
        if ($params['operator'] === '>' && $field1 <= $field2) {
            return $error($consts['GREATER_THAN'], []);
        }
        if ($params['operator'] === '>=' && $field1 < $field2) {
            return $error($consts['GREATER_THAN'], []);
        }
    }

    public function getFixture($value, $fields)
    {
        $operand = $this->_direct ? $this->_operand : $fields[$this->_operand] ?? null;

        if (!strlen($operand ?? '')) {
            return $value;
        }

        // 等価系はその値を入れてしまえばよい
        if (in_array($this->_operator, ['===', '=='], true)) {
            return $operand;
        }

        // 比較は推移律のある数値か日時が圧倒的に多いので日時だけ特別扱いする
        if (in_array($this->_operator, ['>', '>=', '<', '<='], true)) {
            $plusminus = function ($value, $delta) {
                if (is_numeric($value)) {
                    return $value + $delta;
                }

                try {
                    return date_modulate($value, $delta);
                }
                catch (\Exception $t) {
                    // through
                }

                return null;
            };
            if (strpos($this->_operator, '>') !== false) {
                return $plusminus($operand, +rand(10, 24));
            }
            if (strpos($this->_operator, '<') !== false) {
                return $plusminus($operand, -rand(10, 24));
            }
        }

        // 否定系は算出できない
        return $value;
    }
}
