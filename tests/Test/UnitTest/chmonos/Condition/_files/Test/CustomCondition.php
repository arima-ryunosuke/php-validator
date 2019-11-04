<?php
namespace custom\Condition;

class CustomCondition extends \ryunosuke\chmonos\Condition\AbstractCondition
{
    const INVALID = 'customInvalid';

    protected static $messageTemplates = [
        self::INVALID => "custom is invalid."
    ];

    public static function validate($value, $fields, $params, $consts, $error, $context)
    {
        if (json_last_error()) {
            $error($consts['INVALID']);
        }
    }
}
