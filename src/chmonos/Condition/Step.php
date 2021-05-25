<?php
namespace ryunosuke\chmonos\Condition;

use ryunosuke\chmonos\Condition\Interfaces;

/**
 * 倍数バリデータ
 *
 * 倍数を指定して検証する。
 *
 * - int: int
 *   - 整数部の桁数
 * - dec: int
 *   - 小数部の桁数
 */
class Step extends AbstractCondition implements Interfaces\ImeMode, Interfaces\InferableType, Interfaces\Range
{
    public const INVALID      = 'StepInvalid';
    public const INVALID_STEP = 'StepInvalidInt';

    protected static $messageTemplates = [
        self::INVALID      => 'Invalid value given',
        self::INVALID_STEP => '%step%の倍数で入力してください',
    ];

    protected $_step;

    public function __construct($step)
    {
        if ($step <= 0) {
            throw new \InvalidArgumentException('$step must be positive number.');
        }
        $this->_step = $step;

        parent::__construct();
    }

    public static function validate($value, $fields, $params, $consts, $error, $context)
    {
        $match = [];

        if (!preg_match('#^-?([1-9]\\d*|0)(\\.\\d+)?$#u', $value, $match)) {
            return $error($consts['INVALID']);
        }
        if (abs(round($value / $params['step']) * $params['step'] - $value) > pow(2, -52)) {
            $error($consts['INVALID_STEP']);
        }
    }

    public function getImeMode()
    {
        return Interfaces\ImeMode::DISABLED;
    }

    public function getMin()
    {
        return null;
    }

    public function getMax()
    {
        return null;
    }

    public function getStep()
    {
        return $this->_step;
    }

    public function getType()
    {
        return 'number';
    }
}
