<?php
namespace ryunosuke\chmonos\Condition;

/**
 * 配列の数バリデータ
 *
 * type=arrays での使用を想定しているが、複数 checkbox や複数 select でも使える。
 *
 * - min: int|null
 *   - 配列の最小数
 * - max: int|null
 *   - 配列の最大数
 */
class ArrayLength extends AbstractCondition
{
    public const INVALID   = 'ArrayLengthInvalidLength';
    public const TOO_SHORT = 'ArrayLengthInvalidMin';
    public const TOO_LONG  = 'ArrayLengthInvalidMax';
    public const SHORTLONG = 'ArrayLengthInvalidMinMax';

    protected static $messageTemplates = [
        self::INVALID   => 'Invalid value given',
        self::TOO_SHORT => '%min%件以上は入力してください',
        self::TOO_LONG  => '%max%件以下で入力して下さい',
        self::SHORTLONG => '%min%件～%max%件を入力して下さい',
    ];

    protected $_min;
    protected $_max;

    public function __construct($min = null, $max = null)
    {
        $this->_min = $min;
        $this->_max = $max;

        parent::__construct();
    }

    public function isArrayableValidation()
    {
        return true;
    }

    public static function validate($value, $fields, $params, $consts, $error, $context)
    {
        $length = count($value);

        if (!is_null($params['max']) && !is_null($params['min']) && ($length > $params['max'] || $length < $params['min'])) {
            $error($consts['SHORTLONG']);
        }
        else if (is_null($params['max']) && !is_null($params['min']) && $length < $params['min']) {
            $error($consts['TOO_SHORT']);
        }
        else if (is_null($params['min']) && !is_null($params['max']) && $length > $params['max']) {
            $error($consts['TOO_LONG']);
        }
    }

    public function getFixture($value, $fields)
    {
        $value = array_slice($value, 0, $this->_max ?? PHP_INT_MAX);
        $value = array_pad($value, $this->_min ?? 0, reset($value) ?: 'X');
        return $value;
    }
}
