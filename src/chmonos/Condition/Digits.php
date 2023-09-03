<?php
namespace ryunosuke\chmonos\Condition;

/**
 * 整数バリデータ
 */
class Digits extends AbstractCondition implements Interfaces\ImeMode, Interfaces\InferableType
{
    public const INVALID    = 'notDigits';
    public const NOT_DIGITS = 'digitsInvalid';

    protected static $messageTemplates = [
        self::INVALID    => 'Invalid value given',
        self::NOT_DIGITS => '整数を入力してください',
    ];

    public function __construct()
    {
        parent::__construct();
    }

    public static function validate($value, $fields, $params, $consts, $error, $context)
    {
        if (!ctype_digit(ltrim($value, '-+'))) {
            $error($consts['NOT_DIGITS']);
        }
    }

    public function getImeMode()
    {
        return Interfaces\ImeMode::DISABLED;
    }

    public function getType()
    {
        return 'number';
    }
}
