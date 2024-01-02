<?php
namespace ryunosuke\chmonos\Condition;

/**
 * 数値範囲バリデータ
 *
 * - min: int|null
 *   - 数値の最小数
 * - max: int|null
 *   - 数値の最大数
 */
class Range extends AbstractCondition implements Interfaces\ImeMode, Interfaces\Range
{
    public const INVALID        = 'RangeInvalid';
    public const INVALID_MIN    = 'RangeInvalidMin';
    public const INVALID_MAX    = 'RangeInvalidMax';
    public const INVALID_MINMAX = 'RangeInvalidMinMax';

    protected static $messageTemplates = [
        self::INVALID        => 'Invalid value given',
        self::INVALID_MIN    => '%min%以上で入力して下さい',
        self::INVALID_MAX    => '%max%以下で入力して下さい',
        self::INVALID_MINMAX => '%min%以上%max%以下で入力して下さい',
    ];

    protected $_min;
    protected $_max;

    public function __construct($min = null, $max = null)
    {
        $this->_min = $min;
        $this->_max = $max;

        parent::__construct();
    }

    public static function validate($value, $fields, $params, $consts, $error, $context)
    {
        if ((!is_null($params['min']) && !is_null($params['max'])) && !($params['min'] <= $value && $value <= $params['max'])) {
            $error($consts['INVALID_MINMAX']);
        }
        else if ((!is_null($params['min']) && is_null($params['max'])) && ($params['min'] > $value)) {
            $error($consts['INVALID_MIN']);
        }
        else if ((is_null($params['min']) && !is_null($params['max'])) && ($value > $params['max'])) {
            $error($consts['INVALID_MAX']);
        }
    }

    public function getImeMode()
    {
        return Interfaces\ImeMode::DISABLED;
    }

    public function getMin()
    {
        return $this->_min;
    }

    public function getMax()
    {
        return $this->_max;
    }

    public function getStep()
    {
        // step には口出しできない
        return null;
    }

    public function getType()
    {
        // @task InferableType を implement してないので有効になっていない
        // 数値範囲が決まっているからと言って range はやり過ぎ（range は曖昧な数値範囲の入力に使用する）
        // number でもいいがそれは Digits の仕事だろう
        return 'range';
    }

    public function getFixture($value, $fields)
    {
        return (string) $this->fixtureFloat($this->_min ?? -1000, $this->_max ?? 1000);
    }
}
